<?php
  session_start();

  if (!isset($_SESSION['client_id'])) {
    header("Location: ../index.php");
    exit();
  } 

?>