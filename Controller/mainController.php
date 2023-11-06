<?php
//Requerimos los modelos
require_once("Model/CancionClass.php");
require_once("Model/ListaReproduccionClass.php");
require_once("Model/UsuarioClass.php");
require_once("Model/CancionRepository.php");
require_once("Model/ListaReproduccionRepository.php");
require_once("Model/UsuarioRepository.php");

//Iniciamos la sesión
session_start();

$misPlaylist = [];
$favourites = [];

if (!empty($_SESSION['user'])) {
    $misPlaylist = $_SESSION['user']->getPlayListCreated();
    $favourites = ListaReproduccionRepository::getFavouritePlaylists($_SESSION['user']->getId());
}

if (!empty($_GET['c'])) {
    require('Controller/' . $_GET['c'] . 'Controller.php');
}





include("View/mainView.phtml");
