<?php
  session_start();

  if (!isset($_SESSION['usuario_id'])) {
    header('Location: PHP/View/login.php');
    exit;
  } else {
    header('Location: PHP/View/index.php');
    exit;
  }

?>