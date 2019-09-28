<?php
  require_once "./Database.php";

  if (!isset($_POST['login-submit'])) {
    header('Location: ../index.php');
  }

  // Create Database Instance
  $database = new Database();
  $conn = $database->connect();

  // Get data
  $email = $_POST['email'];
  $password = $_POST['password'];
  $success = 'false';


  // login attempts
  function logAttempts($conn, $email, $success) {
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $sql2 = "INSERT into logins (ip_address, email, success) VALUES (?, ?, ?)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('sss', $ip_address, $email, $success);
    $stmt2->execute();
    $stmt2->close();
  }

  //  check for empty email or password
  if (empty($email) || empty($password)) {
    header ("Location: ../index.php?error=emptyfields");
    logAttempts($conn, $email, $success);
    exit();
  }

  // check if the username matches with password in our db
  $sql = "SELECT * FROM client WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  // Verify Password with SHA1
  $pwdCheck = sha1($password) == $row['pwd'];
  if ($pwdCheck == false) {
    header ("Location: ../index.php?error=wrongpwd");
    logAttempts($conn, $email, $success);
    exit();
    
  }
  else if ($pwdCheck == true){
    $success = 'true';
    logAttempts($conn, $email, $success);
    // creating a session
    session_start();
    $_SESSION['client_id'] = $row['client_id'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['first_name'] = $row['first_name'];
    header ("Location: ../pages/accueil.php");
    exit();
  } 

?>