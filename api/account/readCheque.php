<?php
  header('Content-Type: application/json');

  include("../../config/session.php");
  include_once '../../config/Database.php';

  $client_id = $_SESSION['client_id'];

  // Instantiate Database
  $database = new Database();
  $db = $database->connect();

  // Get Data
  $query = "SELECT description, balance FROM account 
            WHERE client_id = ? AND account_type = 'cheques'
            LIMIT 1";
  
  // Prepare Statement
  $stmt = $db->prepare($query);
  $stmt->bind_param('s', $client_id);
  $stmt->execute();

  // Fetch Data
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();

  // Cleanup
  $stmt->close();

  // Set message for no data found
  if (!$data) $data = ["message" => "No rows found"];

  // Close Database Connection
  $database->close();

  // Print Data
  echo json_encode($data);

?>