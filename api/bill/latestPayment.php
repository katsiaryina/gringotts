<?php
  header('Content-Type: application/json');

  include("../../config/session.php");
  include_once '../../config/Database.php';

  // Get Posted data
  $data = json_decode(file_get_contents("php://input"));
  if (!$data) {
    die("Please Submit Data");
  }

  $client_id = $_SESSION['client_id'];
  $supplier_id = $data->supplier_id;

  // Instantiate Database
  $database = new Database();
  $db = $database->connect();

  // Query
  $query = "SELECT b.payment_date, b.amount
            FROM bill_payment AS b
            JOIN account AS a ON (a.account_id = b.account_id)
            WHERE a.client_id = ?
            AND b.supplier_id = ?
            ORDER BY b.payment_date DESC
            LIMIT 1";

  // Prepare Statement
  $stmt = $db->prepare($query);
  $stmt->bind_param('ss', $client_id, $supplier_id);
  $stmt->execute();

  // Fetch Data
  $result = $stmt->get_result();

  // Fetch Data
  $data = $result->fetch_assoc();
  if (!$data) $data = ["payment_date" => "", "amount" => ""];


  // Cleanup
  $result->free();
  $database->close();

  // echo Data
  echo json_encode($data);

?>