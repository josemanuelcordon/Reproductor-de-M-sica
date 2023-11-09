<?php
if (!empty($_POST['pl-name'])) {
    $pl_name = $_POST['pl-name'];
    if (!empty($_FILES['pl-img']['name'])) {
        $ruta_relativa = "assets/img/" . $pl_name . ".jpg";
        $destino = $_SERVER['DOCUMENT_ROOT'] . "/listaReproduccion/" . $ruta_relativa;
        move_uploaded_file($_FILES["pl-img"]["tmp_name"], $destino);
        ListaReproduccionRepository::createPlaylist($pl_name, $_SESSION['user']->getId(), $ruta_relativa);
    } else {
        ListaReproduccionRepository::createPlaylist($pl_name, $_SESSION['user']->getId());
    }
}

if (!empty($_GET['clone'])) {
    $playListToClon = ListaReproduccionRepository::getPlayListById($_GET['clone']);
    $id_user = $_SESSION['user']->getId();
    ListaReproduccionRepository::clonePlayList($playListToClon, $_SESSION['user']->getId());
    header('location: index.php');
}

if (!empty($_GET['fetch-prueba'])) {
    $playlist_desired = ListaReproduccionRepository::getPlayListById($_GET['fetch-prueba']);
    echo $playlist_desired->getJSON();
    die;
}

if (isset($_GET['fav'])) {
    if ($_GET['fav']) {
        ListaReproduccionRepository::makePlayListFavourite($_SESSION['user']->getId(), $_GET['show-pl']);
    } else {
        ListaReproduccionRepository::unfavPlaylist($_SESSION['user']->getId(), $_GET['show-pl']);
    }
    header('location: index.php?c=listaReproduccion&show-pl=' . $_GET['show-pl']);
}

if (isset($_GET['search'])) {
    $searchedPlaylists = ListaReproduccionRepository::searchPlaylist($_GET['search']);
}

if (!empty($_GET['search-pl'])) {
    $playlists = ListaReproduccionRepository::getAllPlayLists();
    include('View/playListSearchView.phtml');
    die;
}

if (!empty($_GET['pl'])) {
    include('View/createPlaylistView.phtml');
    die;
}

if (!empty($_GET['show-pl'])) {
    $playlist_selected = ListaReproduccionRepository::getPlayListById($_GET['show-pl']);
    include('View/playListView.phtml');
    die;
}
