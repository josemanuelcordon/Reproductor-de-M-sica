<?php
class CancionClass
{
    private $id;
    private $title;
    private $author;
    private $duration;
    private $mp3;
    private $creator;
    public function __construct($datos)
    {
        $this->id = $datos["id"];
        $this->title = $datos["title"];
        $this->author = $datos['author'];
        $this->duration = $datos['duration'];
        $this->mp3 = $datos['mp3'];
        $this->creator = UsuarioRepository::getUserWhoCreatedSong($this->id);
    }

    public function getTitle()
    {
        return $this->title;
    }
    public function getAuthor()
    {
        return $this->author;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function getMmp3()
    {
        return $this->mp3;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDurationFormatted()
    {
        $float = $this->duration;
        $minutos = floor($float);
        $segundos = ($float - floor($float)) * 100;
        $result = $minutos . ':' . (($segundos < 10) ? '0' . $segundos : $segundos);
        return $result;
    }

    public function getJSON()
    {
        $datos['id'] = $this->id;
        $datos['title'] = $this->title;
        $datos['author'] = $this->author;
        $datos['duration'] = $this->duration;
        $datos['mp3'] = $this->mp3;
        $datos['creator'] = $this->creator;
        return json_encode($datos);
    }
}
