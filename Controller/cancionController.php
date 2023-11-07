<?php
if (!empty($_POST['song-title'])) {
    $playlist_id = $_GET['pl-id'];
    $title = $_POST['song-title'];
    $duration = $_POST['song-mins'] + $_POST['song-secs'] * 0.01;
    $author = $_POST['song-author'];
    $id_creator = $_SESSION['user']->getId();
    if ($_FILES['song-mp3']) {
        $mp3 = "assets/music/" . $title . ".mp3";
        $destino = $_SERVER['DOCUMENT_ROOT'] . "/musica/" . $mp3;
        move_uploaded_file($_FILES["song-mp3"]["tmp_name"], $destino);
    } else {
        $mp3 = "Todavia no disponible";
    }
    CancionRepository::createSong($playlist_id, $title, $duration, $author, $id_creator, $mp3);
    header('location: index.php?c=cancion&pl-id=' . $_GET['pl-id']);
}

if (!empty($_GET['song-search'])) {
    $searchContent = $_GET['song-search'];
    $songsSearched = CancionRepository::searchSongs($searchContent);
}

if (!empty($_GET['add-song'])) {
    CancionRepository::addSongToPlaylist($_GET['pl-id'], $_GET['add-song']);
}

if (!empty($_GET['pl-id'])) {
    $songs = CancionRepository::getAllSongs();
    include('View/cancionView.phtml');
    die;
}
