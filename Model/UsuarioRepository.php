<?php
class UsuarioRepository
{
    public static function getUserWhoCreatedSong($id)
    {
        $bd = Conectar::conexion();
        $q = "SELECT * FROM usuarios WHERE id IN (SELECT creator FROM canciones WHERE id =" . $id . ")";
        $result = $bd->query($q);
        return new UsuarioClass($result->fetch_assoc());
    }

    public static function getUserWhoCreatedPlaylist($playlist_id)
    {
        $bd = Conectar::conexion();
        $q = "SELECT * FROM usuarios WHERE id IN (SELECT creator FROM playlists WHERE id=" . $playlist_id . ")";
        $result = $bd->query($q);
        $datos = $result->fetch_assoc();
        return new UsuarioClass($datos);
    }

    public static function login($username, $password)
    {
        $bd = Conectar::conexion();
        $q = "SELECT * FROM usuarios WHERE name='" . $username . "' AND password='" . md5($password) . "'";
        $result = $bd->query($q);
        if ($result->num_rows > 0) {
            return new UsuarioClass($result->fetch_assoc());
        } else {
            return NULL;
        }
    }

    public static function register($username, $password)
    {
        $bd = Conectar::conexion();
        $q = "SELECT * FROM usuarios WHERE name='" . $username . "'";
        $result = $bd->query($q);
        if ($result->num_rows == 0) {
            $q = "INSERT INTO usuarios VALUES (NULL, '" . $username . "', '" . md5($password) . "', 1)";
            return ($bd->query($q));
        } else {
            return NULL;
        }
    }
}
