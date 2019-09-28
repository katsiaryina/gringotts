<?php
  include("../config/session.php");
  include('./includes/header.php');
  include('./includes/sidenav.php');
  include('../api/interax/getTransferInfo.php');
  include_once('../config/Database.php');

  // Instantiate Database
  $database = new Database();
  $db = $database->connect();

  if (!isset($_POST) OR !isset($_POST['transfer_id']) ){
    header("Location: ../../pages/recevoir.php");
    exit();
  }
  
  // Get posted data
  $transfer_id = $_POST['transfer_id'];
  $from_email = $_POST['from_email'];

  // Get the interax transfer information
  $data = getTransferInfo($db, $transfer_id);
  $security_question = $data['security_question'];
  $amount = $data['amount'];

  $database->close();

  
?>

<main>

  <form action="../api/interax/receiveOne.php" method="POST" class="vertical-form">
    <h3>Accepter le transfert interax de <?php echo $from_email ?>?</h3>
    <p>Montant: <strong><?php echo $amount ?></strong></p>
    <input type="text" name="transfer_id" value="<?php echo $transfer_id ?>" hidden>


    <label for="select">Choisir un compte</label>
    <select name="account" id="select-account">
      <!-- Javascript will generate the accounts -->
    </select>

    <label for="select">Question de sécurité</label>
    <input type="text" name="question" id="question" value="<?php echo $security_question ?>?" disabled/>

    <label for="select">Réponse</label>
    <input type="text" name="answer" id="answer"/>

    <button type="submit" class="btn btn-primary">Recevoir</button>
  </form>
</main>

<script>
  getAccounts();

  function getAccounts() {
    var url = '../api/account/all.php'
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.onload = function() {
      var data = JSON.parse(this.response);
      console.log(data);
      // Create the row select options
      var html = '';
      for (var item of data) {
        if (item.account_type === 'cheques') {
          html += `
            <option value="${item.description}">${item.description}</option>
          `
        }
      }
      var div = document.getElementById('select-account');
      div.insertAdjacentHTML('beforeend', html);

    }
    xhr.send();
  }
  </script>

<?php include('./includes/footer.php'); ?>
