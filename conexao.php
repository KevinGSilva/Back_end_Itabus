<?php
$servername = "localhost";
$database = "itajubus";
$username = "root";
$password = "Fedora-666";

try {
    $conn = new PDO('mysql:host='.$servername.';dbname='.$database.'', $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
  } catch(PDOException $e) {
      echo 'ERROR: ' . $e->getMessage();
  }
?>
