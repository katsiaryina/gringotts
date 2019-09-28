<?php
  // if (!isset($_SESSION['client_id'])) {
  //   header("Location: ../../index.php");
  //   exit();
  // } 

  function createTransaction($db, $date, $amount, $transaction_type, $supplier_name, $account_id) {
    $query = "INSERT INTO transaction 
      (transaction_id, transaction_date, amount, transaction_type, supplier, account_id) 
      VALUES (
          NULL, 
          ?, -- date 
          ?, -- amount 
          ?, -- transaction_type (withdrawal or deposit)
          ?, -- supplier name
          ?) -- acount_id
    ";

    $stmt = $db->prepare($query);
    $stmt->bind_param('sssss', $date, $amount, $transaction_type, $supplier_name, $account_id);
    $stmt->execute();

    $stmt->close();

  }

?>