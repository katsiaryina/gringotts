<?php
  include("../config/session.php");
  include('./includes/header.php');
  include('./includes/sidenav.php');
?>

<main>
  <div class="factures-header">
    <h2>Mes Factures</h2>
    <button class="btn btn-secondary">Ajouter une facture</button>
  </div>

  <section id="factures" class="grid-table">
    <!-- titles -->
    <h4>Facture</h4>
    <h4>Numéro de Référence</h4>
    <h4>Dernier paiment</h4>
    <h4></h4>

    <!-- generated by javscript -->
  </section>
</main>

<script>
  getSuppliers();

  function getSuppliers() {
    var url = '../api/bill/all.php'
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.onload = function() {
      var data = JSON.parse(this.response);
      
      // Create the row
      var html = '';
      for (var item of data) {
        html += `
          <h4>${item.supplier_name}</h4>
          <p>${item.reference_number}</p>
          <div id="${item.supplier_id}">
          </div>
          <a href="./payer.php">></a>
        `
      }

      var div = document.getElementById('factures');
      div.insertAdjacentHTML('beforeend', html);

      // Add the lastest Transaction
      for (var item of data) {
        getLatestPayment(item.supplier_id);
      }
    }
    xhr.send();
  }

  function getLatestPayment(supplier_id) {
    var url = '../api/bill/latestPayment.php';
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url);
    xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xhr.onload = function() {
      var data = JSON.parse(this.response);
      console.log(data);
      var html2 = "";
      if (data.payment_date !== "") {
        html2 =  `
          <small>${data.payment_date.split(" ")[0]}</small>
          <small><strong>${data.amount}$</strong></small>
        `
      }
      var div2 = document.getElementById(supplier_id);
      div2.innerHTML = html2;
    }
    var body = {
      "supplier_id" : supplier_id
    }
    xhr.send(JSON.stringify(body));
  }
</script>
<?php include('./includes/footer.php'); ?>
