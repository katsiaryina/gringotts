<?php
  include_once("../../config/session.php");
  include_once '../../config/Database.php';
  include("../account/updateBalance.php");
  include("../transaction/create.php");
  include("./sendInteraxAPI.php");

  if (!isset($_POST)){
    header("Location: ../../pages/envoyer.php");
    exit();
  }

  $client_id = $_SESSION['client_id'];
  $supplier_name = 'Transfert Interax';

  // Get POST data
  $nom = 'Gringotts';
  $email = $_POST['email'];
  $amount = $_POST['amount'];
  $accountDescription = $_POST['account'];
  $question = $_POST['question'];
  $answer = $_POST['answer'];

  // Instantiate Database
  $database = new Database();
  $db = $database->connect();

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

   // AJAX request to INTERAX API
   $response = sendInteraxAPI($nom, $email, $question, $answer, $amount);
   $code = $response->code;
   if ($code == 200) {
     $interaxId = $response->id;
   } else {
     $interaxId = 'interaxAPIerror';
   }
   var_dump($response);
   var_dump($interaxId);

  // INSERT INTO transfer_interax
  $query = "INSERT INTO `interax_transfer` 
            (`transfer_id`, `to_user_email`, `from_account`, `security_question`, `answer`, 
            `amount`, `accepted`, `sent_at`, `accepted_at`, `interax_id`) VALUES (
                NULL, -- transfer id
                ?, -- to_user_email
                ?, -- from_account
                ?, -- security question
                ?, -- answer
                ?, -- amount
                0, -- accepted status (0 = no)
                current_timestamp(), 
                NULL,
                ? -- interaxId
            );";

  $stmt = $db->prepare($query);
  $stmt->bind_param('ssssss', $email, $account_id, $question, $answer, $amount, $interaxId);
  $stmt->execute();  

    // INSERT INTO TRANSACTIONS
  $date = date('Y-m-d h:i:s');
  createTransaction($db, $date, $amount, 'withdrawal', $supplier_name, $account_id);

  // UPDATE BALANCE
  updateBalance($db, $account_id, $amount, 'withdrawal');

  // Cleanup
  $database->close();

  // Return to factures page
  header("Location: ../../pages/accueil.php");
  exit();

?>