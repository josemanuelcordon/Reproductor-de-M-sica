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
}
