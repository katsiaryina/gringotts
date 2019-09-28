<?php
  header('Content-Type: application/json');

  include("../../config/session.php");
  include_once '../../config/Database.php';

  $client_id = $_SESSION['client_id'];

  // Instantiate Database
  $database = new Database();
  $db = $database->connect();

  // Query
  $query = "SELECT t.supplier, t.transaction_date, a.description, t.amount, t.transaction_type
            FROM transaction AS t
            JOIN account AS a ON (a.account_id = t.account_id)
            WHERE a.client_id = ?
            ORDER BY t.transaction_date DESC";

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