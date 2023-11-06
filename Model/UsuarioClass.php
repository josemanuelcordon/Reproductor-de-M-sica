<?php
class UsuarioClass
{
    private $id;
    private $name;
    private $rol;
    public function __construct($datos)
    {
        $this->id = $datos['id'];
        $this->name = $datos['name'];
        $this->rol = $datos['rol'];
    }

    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getRol()
    {
        return $this->rol;
    }
    public function getFavoritePlaylist()
    {
        return ListaReproduccionRepository::getFavouritePlaylists($this->id);
    }

    public function getPlayListCreated()
    {
        return ListaReproduccionRepository::getCreatedPlaylists($this->id);
    }

    public function isFavourite($id_playlist)
    {
        foreach ($this->getFavoritePlaylist() as $fav) {
            if ($fav->getId() == $id_playlist) {
                return true;
            }
        }
        return false;
    }
}
