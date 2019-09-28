<?php
  // if (!isset($_SESSION) || !isset($_POST)) {
  //   header("Location: ../../index.php");
  //   exit();
  // } 

  function updateBalance($db, $account_id, $amount, $transaction_type) {
    $currentBalance = getBalance($db, $account_id);

    if ($transaction_type == 'withdrawal'){
      $newBalance = $currentBalance - $amount;
    }

    if ($transaction_type == 'deposit') {
      $newBalance = $currentBalance + $amount;
    }

    // SQL
    $query = "UPDATE account 
        SET
        balance = ?
        WHERE account_id = ?
        ";
    
    // Prepare Statement
    $stmt = $db->prepare($query);
    $stmt->bind_param('ss', $newBalance, $account_id);
    $stmt->execute();
    
    // Cleanup
    $stmt->close();
    
  }

  function getBalance($db, $account_id) {
    // echo "getting Balance";
    $query = "SELECT balance FROM account WHERE account_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $account_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $balance = $data['balance'];
    $stmt->close();

    // echo $balance;
    return $balance;

  }

?>