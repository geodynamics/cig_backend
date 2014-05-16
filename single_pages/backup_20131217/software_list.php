<?php
  if(!isset($_GET["pkg"])) {
    include 'list.php';
  } else {
    include 'package.php';
  }
?>
