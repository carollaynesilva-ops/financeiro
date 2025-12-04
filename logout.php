<?php

require_once 'config.php';

//Fazer Logout
session_unset();
session_destroy();


//redireciona para Login
header('Location:login.php');
exit;

?>