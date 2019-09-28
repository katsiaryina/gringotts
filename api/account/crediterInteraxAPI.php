<?php
  header('Acces-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');


  include('../../config/Database.php');
  include("./updateBalance.php");
  include("../transaction/create.php");

  $success = false;

  // Get submitted Data
  $data = json_decode(file_get_contents("php://input"));
  if (!$data) {
    die("Please Submit Data");
  }

  $account_id = htmlspecialchars(strip_tags($data->compte));
  $transit = htmlspecialchars(strip_tags($data->transit));
  $amount = htmlspecialchars(strip_tags($data->montant));


  // Instantiate Database
  $database = new Database();
  $db = $database->connect();

  // Check if we have a matching account
  $sql = "SELECT account_id FROM account
          WHERE account_id = ?;";
  $stmt = $db->prepare($sql);
  $stmt->bind_param('s', $account_id);
  $stmt->execute();

  $result = $stmt->get_result();
  $data = $result->fetch_assoc();

  // If we have an account, 
  // create a transaction and update the balance
  if ($result->num_rows >= 1) {
    $transaction_type = "deposit";
    $supplier_name = "CREDIT INTERAX";
    $date = date('Y-m-d h:i:s');

    createTransaction($db, $date, $amount, $transaction_type, $supplier_name, $account_id);
    updateBalance($db, $account_id, $amount, $transaction_type);
    $success = true;
  }
  
  $stmt->close();

  // Close Database Connection
  $database->close();

  // Print if success or failure
  if ($success) {
    echo '{ "status": "OK"}';
  } else {
    echo '"status": "FAILURE"';
  };

?>