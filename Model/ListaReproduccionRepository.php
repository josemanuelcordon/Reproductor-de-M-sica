<?php
class ListaReproduccionRepository
{
    public static function getFavouritePlaylists($user_id)
    {
        $bd = Conectar::conexion();
        $query = "SELECT * FROM playlists WHERE id IN (SELECT id_playlist FROM favourite_pl WHERE id_usuario=" . $user_id . ")";
        $result = $bd->query($query);
        $playlists = [];
        while ($row = $result->fetch_assoc()) {
            $playlists[] = new ListaReproduccionClass($row);
        }
        return $playlists;
    }

    public static function getCreatedPlaylists($user_id)
    {
        $bd = Conectar::conexion();
        $q = "SELECT * FROM playlists WHERE creator=" . $user_id;
        $result = $bd->query($q);
        $playlists = [];
        while ($row = $result->fetch_assoc()) {
            $playlists[] = new ListaReproduccionClass($row);
        }
        return $playlists;
    }

    public static function createPlaylist($pl_name, $id_user, $img = "assets/img/default/caratula.png")
    {
        $bd = Conectar::conexion();
        $q = "INSERT INTO playlists VALUES (NULL, '" . $pl_name . "', " . $id_user . ", '" . $img . "')";
        $bd->query($q);
    }

    public static function getPlayListById($id_playlist)
    {
        $bd = Conectar::conexion();
        $q = "SELECT * FROM playlists WHERE id=" . $id_playlist;
        $result = $bd->query($q);
        if ($datos = $result->fetch_assoc()) {
            return new ListaReproduccionClass($datos);
        } else {
            return null;
        }
    }



    public static function makePlayListFavourite($user_id, $pl_id)
    {
        $bd = Conectar::conexion();
        $q = "INSERT INTO favourite_pl VALUES ( " . $pl_id . " , " . $user_id . ")";
        $bd->query($q);
    }

    public static function unfavPlaylist($user_id, $pl_id)
    {
        $bd = Conectar::conexion();
        $q = "DELETE FROM favourite_pl WHERE id_playlist=" . $pl_id . " AND id_usuario=" . $user_id;
        $bd->query($q);
    }

    public static function searchPlaylist($title)
    {
        $bd = Conectar::conexion();
        $q = "SELECT * FROM playlists WHERE name LIKE '%" . $title . "%'";
        $result = $bd->query($q);
        $searchedPlaylists = [];
        while ($datos = $result->fetch_assoc()) {
            $searchedPlaylists[] = new ListaReproduccionClass($datos);
        }
        return $searchedPlaylists;
    }

    public static function getAllPlayLists()
    {
        $bd = Conectar::conexion();
        $q = "SELECT * FROM playlists";
        $result = $bd->query($q);
        $playlists = [];
        while ($datos = $result->fetch_assoc()) {
            $playlists[] = new ListaReproduccionClass($datos);
        }
        return $playlists;
    }

    public static function clonePlayList($playlist, $id_user)
    {
        $plName = $playlist->getName();
        $img = $playlist->getImg();
        $bd = Conectar::conexion();
        $q = "INSERT INTO playlists VALUES (NULL, '" . $plName . "', " . $id_user . ", '" . $img . "')";
        $bd->query($q);
        $idClonned = $bd->insert_id;
        foreach ($playlist->getSongs() as $song) {
            $q = "INSERT INTO song_in_playlist VALUES (" . $idClonned . ", " . $song->getId() . ")";
            $bd->query($q);
        }
        $q = "INSERT INTO pl_clonned VALUES (" . $playlist->getId() . ", " . $idClonned . ")";
        $bd->query($q);
    }

    public static function timesClonned($id)
    {
        $bd = Conectar::conexion();
        $q = "SELECT COUNT(*) FROM pl_clonned WHERE id_original=" . $id;
        $result = $bd->query($q);
        $datos = $result->fetch_array();
        return $datos[0];
    }
    public static function timesFavourite($id)
    {
        $bd = Conectar::conexion();
        $q = "SELECT COUNT(*) FROM favourite_pl WHERE id_playlist=" . $id;
        $result = $bd->query($q);
        $datos = $result->fetch_array();
        return $datos[0];
    }
}
