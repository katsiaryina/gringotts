<?php
  include("../config/session.php");
  include('./includes/header.php');
  include('./includes/sidenav.php');

  include_once "./includes/error.php";

  if (isset($_GET['error'])) {
    $error = $_GET['error'];
  } else {
    $error = '';
  }
?>

<main>
  <h2>Recevoir un transfert INTERAX</h2>
  <p class="error"><?php errorMessage($error)?></p>
  <div id="transfers-output"></div>
</main>


<script>
  getInteraxTransfers();
  
  function getInteraxTransfers() {
    var url = '../api/interax/all.php';
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.onload = function() {
      var data = JSON.parse(this.response);
      console.log(data);
      // Create the row select options
      var html = ``;
      for (var item of data) {
          html += `
            <form action="../pages/recevoirUnTransfert.php" method="POST" class="horizontal-form">
              <input type="text" name="transfer_id" value="${item.transfer_id}" hidden>
              <input type="text" name="from_email" value="${item.from_email}" hidden>

              <p>Vous avez reçus <strong>$${item.amount}</strong> de ${item.from_email}</p>
              <button type="submit" class="btn btn-primary">Accepter</button>
            </form>
          `
      }
  
      var div = document.getElementById('transfers-output');
      div.insertAdjacentHTML('beforeend', html);
    }

    xhr.send();
  }



 
</script>
<?php include('./includes/footer.php'); ?>
