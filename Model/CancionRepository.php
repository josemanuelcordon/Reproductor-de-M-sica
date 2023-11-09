<?php
class CancionRepository
{
    public static function getSongsInPlaylist($playlist_id)
    {
        $bd = Conectar::conexion();
        $q = "SELECT * FROM canciones WHERE id IN (SELECT id_song FROM song_in_playlist WHERE id_playlist=" . $playlist_id . ")";
        $result = $bd->query($q);
        $songs = [];
        while ($row = $result->fetch_assoc()) {
            $songs[] = new CancionClass($row);
        }
        return $songs;
    }

    public static function createSong($playlist_id, $title, $duration, $author, $id_creator, $mp3)
    {
        $bd = Conectar::conexion();
        $q = "INSERT INTO canciones VALUES (NULL, '" . $title . "', " . $duration . ", '" . $author . "', " . $id_creator . ", '" . $mp3 . "')";
        $bd->query($q);
        $id_song = $bd->insert_id;
        $q = "INSERT INTO song_in_playlist VALUES (" . $playlist_id . ", " . $id_song . ")";
        $bd->query($q);
    }

    public static function searchSongs($searchContent)
    {
        $bd = Conectar::conexion();
        $q = "SELECT * FROM canciones WHERE author LIKE '%" . $searchContent . "%' OR title LIKE '%" . $searchContent . "%'";
        $result = $bd->query($q);
        $songs = [];
        while ($row = $result->fetch_assoc()) {
            $song = new CancionClass($row);
            $songs[] = $song;
        }
        return $songs;
    }

    public static function getAllSongs()
    {
        $bd = Conectar::conexion();
        $q = "SELECT * FROM canciones";
        $result = $bd->query($q);
        $songs = [];
        while ($row = $result->fetch_assoc()) {
            $songs[] = new CancionClass($row);
        }
        return $songs;
    }

    public static function getLimitedSongs($index, $limit)
    {
        $bd = Conectar::conexion();
        $q = "SELECT * FROM canciones LIMIT " . $index . ", " . $limit;
        $result = $bd->query($q);
        $songs = [];
        while ($datos = $result->fetch_assoc()) {
            $songs[] = new CancionClass($datos);
        }
        return $songs;
    }

    public static function getLimitedSongsFiltered($filter, $index, $limit)
    {
        $bd = Conectar::conexion();
        $q = "SELECT * FROM canciones WHERE title LIKE '%" . $filter . "%' OR author LIKE '%" . $filter . "%' LIMIT " . $index . ", " . $limit;
        $result = $bd->query($q);
        $songs = [];
        while ($datos = $result->fetch_assoc()) {
            $songs[] = new CancionClass($datos);
        }
        return $songs;
    }

    public static function addSongToPlaylist($playlist_id, $song_id)
    {
        $bd = Conectar::conexion();
        $q = "INSERT INTO song_in_playlist VALUES (" . $playlist_id . "," . $song_id . ")";
        $bd->query($q);
    }

    public static function deleteSongFromPlayList($playlist_id, $song_id)
    {
        $bd = Conectar::conexion();
        $q = "DELETE FROM song_in_playlist WHERE id_playlist=" . $playlist_id . " AND id_song=" . $song_id;
        $bd->query($q);
    }

    public static function getNumberLinks($filter)
    {
        $bd = Conectar::conexion();
        $q = "SELECT COUNT(*) FROM canciones WHERE title LIKE '%" . $filter . "%' OR author LIKE '%" . $filter . "%'";
        $result = $bd->query($q);
        $datos = $result->fetch_array();
        return $datos[0];
    }

    public static function getSongById($song_id)
    {
        $bd = Conectar::conexion();
        $q = "SELECT * FROM canciones WHERE id=" . $song_id;
        $result = $bd->query($q);
        $datos = $result->fetch_assoc();
        return new CancionClass($datos);
    }

    public static function updateSong($id, $title, $author, $mp3)
    {
        $bd = Conectar::conexion();
        $q = "UPDATE canciones SET title ='" . $title . "', author='" . $author . "', mp3='" . $mp3 . "' WHERE id=" . $id;
        $bd->query($q);
    }
}
