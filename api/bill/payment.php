<?php
  include_once("../../config/session.php");
  include_once '../../config/Database.php';
  include("../account/updateBalance.php");
  include("../transaction/create.php");
  include("./paymentInteraxAPI.php");

  if (!isset($_POST)){
    header("Location: ../../pages/factures.php");
    exit();
  }

  $client_id = $_SESSION['client_id'];

  $supplier_name = $_POST['supplier'];
  $date = $_POST['date'] . date(" h:i:s");
  $amount = $_POST['amount'];

  // Instantiate Database
  $database = new Database();
  $db = $database->connect();

  // Get the supplier_id and account_id
  $query = "SELECT a.account_id, b.supplier_id
    FROM bill_supplier AS b 
    JOIN account AS a ON (a.account_id = b.account_id) 
    WHERE a.client_id = ? 
    AND b.supplier_name = ?";
    
  $stmt = $db->prepare($query);
  $stmt->bind_param('ss', $client_id, $supplier_name);
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $result->free();

  $supplier_id = $data['supplier_id'];
  $account_id = $data['account_id'];


  // AJAX request to INTERAX API
  $response = paymentInteraxAPI($account_id, $supplier_name, $amount);
  $code = $response->code;
  if ($code == 200) {
    $interaxId = $response->id;
  } else {
    $interaxId = 'interaxAPIerror';
  }
  var_dump($response);
  var_dump($interaxId);



  // INSERT INTO BILL PAYMENTS
  $query = "INSERT INTO bill_payment 
                (bill_id, amount, payment_date, interax_id, supplier_id, account_id) 
            VALUES (
                NULL, 
                ?, -- amount
                ?,  -- payment_date
                ?, -- interax_id
                ?, -- supplier_id
                ?) -- account_id"; 

  $stmt = $db->prepare($query);
  $stmt->bind_param('sssss', $amount, $date, $interaxId, $supplier_id, $account_id);
  $stmt->execute();



  // INSERT INTO TRANSACTIONS
  createTransaction($db, $date, $amount, 'withdrawal', $supplier_name, $account_id);

  // UPDATE BALANCE
  updateBalance($db, $account_id, $amount, 'withdrawal');

  // Cleanup
  $database->close();

  // Return to factures page
  header("Location: ../../pages/factures.php");
  exit();

?>