<?php
  include("../config/session.php");
  include('./includes/header.php');
  include('./includes/sidenav.php');
?>

<main>
  <h2>Payer une facture</h2>
  <form action="../api/bill/payment.php" method="POST" class="vertical-form">
    <label for="select">Choisir une facture</label>
    <select name="supplier" id="select-facture">
      <!-- javascript generates this -->
    </select>

    <label for="amount">Somme a payer</label>
    <input type="text" name="amount" id="amount" />

    <label for="amount">Date</label>
    <input type="date" name="date" id="date" />

    <button type="submit" class="btn btn-primary">Payer</button>
  </form>
</main>


<script>
  getSuppliers();

  function getSuppliers() {
    var url = '../api/bill/all.php'
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.onload = function() {
      var data = JSON.parse(this.response);
      console.log(data);
      // Create the row select options
      var html = '';
      for (var i in data) {
        html += `
          <option value="${data[i].supplier_name}">${data[i].supplier_name}</option>
        `
      }
      document.getElementById('select-facture').innerHTML = html;
      

    }
    xhr.send();
  }
</script>
<?php include('./includes/footer.php'); ?>
