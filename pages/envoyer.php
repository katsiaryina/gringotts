<?php
  include("../config/session.php");
  include('./includes/header.php');
  include('./includes/sidenav.php');
?>

<main>
  <h2>Faire un transfert INTERAX</h2>
  <form action="../api/interax/send.php" method="POST" class="vertical-form">
    <label for="select">Destinataire</label>
    <input type="text" name="email" id="email" placeholder="email@mail.com"/>

    <label for="amount">Montant à envoyer</label>
    <input type="text" name="amount" id="amount"/>

    <label for="select">Choisir un compte</label>
    <select name="account" id="select-account">
      <!-- javascript generates this -->
    </select>

    <label for="select">Question de sécurité</label>
    <input type="text" name="question" id="question"/>

    <label for="select">Réponse</label>
    <input type="text" name="answer" id="answer"/>

    <button type="submit" class="btn btn-primary">Envoyer</button>
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
