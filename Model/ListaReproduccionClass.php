<?php
class ListaReproduccionClass
{
    private $id;
    private $name;
    private $songs;
    private $creator;
    private $img;

    public function __construct($datos)
    {
        $this->id = $datos["id"];
        $this->name = $datos["name"];
        $this->songs = CancionRepository::getSongsInPlaylist($this->id);
        $this->creator = UsuarioRepository::getUserWhoCreatedPlaylist($this->id);
        $this->img = $datos['img'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSongs()
    {
        return $this->songs;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    /*    public function getDuration()
    {
        $duracion = 0;
        foreach ($this->songs as $song) {
            $duracion += $song->getDuration();
        }
        return $duracion;
    } */

    public function getImg()
    {
        return $this->img;
    }

    public function getDurationFormatted()
    {
        $float = 0;
        foreach ($this->songs as $song) {
            $float += $song->getDuration();
        }
        $segundos = ($float - floor($float)) * 100;
        $minutos = floor($float);
        if ($segundos >= 60) {
            $segundos -= 60;
            $minutos += 1;
        }
        $result = $minutos . ':' . (($segundos < 10) ? '0' . $segundos : $segundos);
        return $result;
    }

    public function getJSON()
    {
        $datos['id'] = $this->id;
        $datos['name'] = $this->name;
        foreach ($this->songs as $song) {
            $datos['songs'][] = $song->getJSON();
        }
        $datos['creator'] = $this->creator->getName();
        $datos['img'] = $this->img;
        $datos['favourite'] = ListaReproduccionRepository::timesFavourite($this->id);
        $datos['clonned'] = ListaReproduccionRepository::timesClonned($this->id);
        return json_encode($datos);
    }
}
