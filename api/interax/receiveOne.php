<?php
  include_once("../../config/session.php");
  include_once('../../config/Database.php');
  include("../account/updateBalance.php");
  include("../transaction/create.php");
  include('./getTransferInfo.php');


  if (!isset($_POST)){
    header("Location: ../../pages/recevoir.php");
    exit();
  }

  // SET session variables
  $client_id = $_SESSION['client_id'];

  // Get POST data
  $transfer_id = $_POST['transfer_id'];
  $accountDescription = $_POST['account'];
  $answer = $_POST['answer'];
  $supplier_name = 'Transfert Interax';

  // Instantiate Database
  $database = new Database();
  $db = $database->connect();

  // Get interax transfer information
  $data = getTransferInfo($db, $transfer_id);
  $security_answer = $data['answer'];
  $amount = $data['amount'];

  // Verify questions
  if ($security_answer != $answer) {
    echo "WRONG ANSWER";
    header("Location: ../../pages/recevoir.php?error=wrong-answer");
    exit();
  }

    // Update transfer_interax
  $query = "UPDATE `interax_transfer`
            SET
                accepted = 1,
                accepted_at = current_timestamp()
            WHERE transfer_id = ?;";

  $stmt = $db->prepare($query);
  $stmt->bind_param('s', $transfer_id);
  $stmt->execute();  

  // Get the account_id from the description
  $query = "SELECT account_id
    FROM account
    WHERE client_id = ? 
    AND description = ?";

  $stmt = $db->prepare($query);
  $stmt->bind_param('ss', $client_id, $accountDescription);
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $result->free();

  $account_id = $data['account_id'];

    // INSERT INTO TRANSACTIONS
  $date = date('Y-m-d h:i:s');
  createTransaction($db, $date, $amount, 'deposit', $supplier_name, $account_id);

  // UPDATE BALANCE
  updateBalance($db, $account_id, $amount, 'deposit');

  // Cleanup
  $database->close();

  // Return to accueil page
  header("Location: ../../pages/accueil.php");
  exit();

?>