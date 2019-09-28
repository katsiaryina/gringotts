<?php
  session_start();

  if (!isset($_POST['register-submit'])) {
    header('Location: ../index.php');
  }
  // Check if already logged in
  if (isset($_SESSION['client_id'])) {
    header("Location: ../pages/accueil.php");
  } 

  require_once "./Database.php";

  // Get data
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $email = $_POST['email'];
  $password1 = sha1($_POST['password1']);
  $password2 = sha1($_POST['password2']);

  //  check for empty fields
  if (
    empty($firstName) || 
    empty($lastName) ||
    empty($email) || 
    empty($password1) || 
    empty($password2)
  ) {
    header ("Location: ../pages/register.php?error=emptyfields");
    exit();
  }

  // Make sure passwords match
  if ( $password1 != $password2) {
    header ("Location: ../pages/register.php?error=pwdmatch");
    exit();
  }

  // Create Database Instance
  $database = new Database();
  $conn = $database->connect();

  // Check if email exists in Database
  $sql = "SELECT * FROM client WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows >= 1) {
    $database->close();
    header ("Location: ../pages/register.php?error=emailtaken");
    exit();
  }

  // Save new user to database
  $sql = "INSERT INTO client 
    (first_name, last_name, email, pwd) 
    VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ssss', $firstName, $lastName, $email, $password1);
  $stmt->execute();

  // Get client Id, first name and email for this new user
  $sql = "SELECT client_id, first_name, email FROM client WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $email);
  $stmt->execute();

  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $client_id = $data["client_id"];
  $first_name = $data["first_name"];
  $email = $data["email"];
  var_dump($client_id);

  // Create some accounts
  $sql = "INSERT INTO `account` 
    (`account_id`, `account_type`, `description`,`account_number`, `balance`, `client_id`) 
    VALUES 
    (NULL, 'cheques', 'Compte de cheques', '0000-1 99-888-01', '11000.32', ?),
    (NULL, 'credit', 'Mastercard', '**** **** **** 1234', '-1133.65', ?),
    (NULL, 'investment', 'Investments banque', '0000-1 2345455', '2234.23', ?);";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sss', $client_id , $client_id , $client_id);
  $stmt->execute();

  // Get the chequing account id
  $sql = "SELECT account_id FROM account 
          WHERE client_id = ?
          AND account_type = 'cheques';";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $client_id);
  $stmt->execute();

  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $account_id = $data["account_id"];

  // Create some Bills
  $sql = "INSERT INTO `bill_supplier` (`supplier_id`, `supplier_name`, `reference_number`, `account_id`) 
          VALUES 
            (NULL, 'Mastercard BMO', '**** **** **** 1234', ?),
            (NULL, 'Videotron S.E.N.C.', '123 1234', ?),
            (NULL, 'Bell Canada Inc.', 'aa1234', ?),
            (NULL, 'Visa Scotia', 'sco123', ?),
            (NULL, 'Visa Desjardins', 'desj699', ?);";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sssss', $account_id , $account_id , $account_id, $account_id, $account_id);
  $stmt->execute();

  // Close DB
  $stmt->close();
  $database->close();

  // User was successfully created.
  // Start sessin and login
  $_SESSION['client_id'] = $client_id;
  $_SESSION['first_name'] = $first_name;
  $_SESSION['email'] = $email;
  header ("Location: ../pages/accueil.php");

?>
