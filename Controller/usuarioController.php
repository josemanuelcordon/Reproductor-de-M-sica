<?php
if (!empty($_POST['username'])) {
    if (!empty($_POST['register'])) {
        $register = UsuarioRepository::register($_POST['username'], $_POST['password']);
        if ($register) {
            $_SESSION['user'] = UsuarioRepository::login($_POST['username'], $_POST['password']);
        }
    } else {
        $_SESSION['user'] = UsuarioRepository::login($_POST['username'], $_POST['password']);
    }
    header('location: index.php');
}

if (!empty($_GET['logout'])) {
    session_destroy();
    session_start();
}
