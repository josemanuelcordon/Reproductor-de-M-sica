<?php
if (!empty($_POST['song-title'])) {
    $playlist_id = $_GET['pl-id'];
    $title = $_POST['song-title'];
    $duration = $_POST['song-mins'] + $_POST['song-secs'] * 0.01;
    $author = $_POST['song-author'];
    $id_creator = $_SESSION['user']->getId();
    if ($_FILES['song-mp3']) {
        $mp3 = "assets/music/" . $title . ".mp3";
        move_uploaded_file($_FILES["song-mp3"]["tmp_name"], $mp3);
    } else {
        $mp3 = "Todavia no disponible";
    }
    CancionRepository::createSong($playlist_id, $title, $duration, $author, $id_creator, $mp3);
    header('location: index.php?c=cancion&pl-id=' . $_GET['pl-id']);
}

if (!empty($_POST['song-title-edit'])) {
    $id = intval($_POST['song-id']);
    $title = $_POST['song-title-edit'];
    $author = $_POST['song-author-edit'];
    $mp3 = "assets/music/" . $title . ".mp3";
    move_uploaded_file($_FILES["song-mp3-edit"]["tmp_name"], $mp3);
    CancionRepository::updateSong($id, $title, $author, $mp3);
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

if (!empty($_GET['adm-song'])) {
    $LIMIT = 6;
    $offset = ($_GET['index'] - 1) * $LIMIT;
    if (!empty($_POST['search-song'])) {
        $filter = $_POST['search-song'];
        $songsLimited = CancionRepository::getLimitedSongsFiltered($filter, $offset, $LIMIT);
    } else {
        $filter = '';
        $songsLimited = CancionRepository::getLimitedSongs($offset, $LIMIT);
    }
    $nLinks = ceil(CancionRepository::getNumberLinks($filter) / 6);
    include('View/adminCancionView.phtml');
    die;
}

if (!empty($_GET['edit-s'])) {
    $song = CancionRepository::getSongById($_GET['edit-s']);
    include('View/editCancionView.phtml');
    die;
}

if (!empty($_GET['dl-pl'])) {
    CancionRepository::deleteSongFromPlayList($_GET['dl-pl'], $_GET['dl-song']);
    echo true;
    die;
}
