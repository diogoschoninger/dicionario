<?php
  require_once "../../config.php";
  session_destroy();
  header("Location: " . BASE_URL . "pages/session/login.php");
  exit();
?>
