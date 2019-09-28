<?php

  if (!isset($_POST)){
    header("Location: ../../pages/recevoir.php");
    exit();
  }

  function getTransferInfo($db, $transfer_id) {
    // Set variables
    $client_id = $_SESSION['client_id'];
      
    // Get the Interax transfer information
    $query = "SELECT  
                `transfer_id`, `to_user_email`, `from_account`, `security_question`, `answer`, 
                `amount`, `accepted`, `sent_at`, `accepted_at`
            FROM interax_transfer
            WHERE transfer_id = ?;";

    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $transfer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $result->free();


    return $data;
  }


?>