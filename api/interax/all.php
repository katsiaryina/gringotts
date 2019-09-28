<?php
  header('Content-Type: application/json');

  include("../../config/session.php");
  include_once '../../config/Database.php';

  $client_id = $_SESSION['client_id'];

  // Instantiate Database
  $database = new Database();
  $db = $database->connect();

  // Query
  $query = "SELECT it.transfer_id, 
              c2.email AS from_email,
              it.to_user_email,
              it.security_question,
              it.answer,
              it.amount
            FROM interax_transfer AS it
            JOIN client AS c ON (c.email = it.to_user_email)
            JOIN account AS a ON (it.from_account = a.account_id)
            JOIN client AS c2 ON (a.client_id = c2.client_id)
            WHERE c.client_id = ?
            AND it.accepted = 0 
            ORDER BY it.sent_at ASC";

  // Prepare Statement
  $stmt = $db->prepare($query);
  $stmt->bind_param('s', $client_id);
  $stmt->execute();

  // Fetch Data
  $result = $stmt->get_result();

  // Fetch Data
  $data = [];
  while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
  }

  // Cleanup
  $result->free();
  $database->close();

  // echo Data
  echo json_encode($data);

?>