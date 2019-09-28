<?php
  include("../config/session.php");
  include('./includes/header.php');
  include('./includes/sidenav.php');
?>

<main>
  <h2>Sommaire</h2>
  <section id="sommaire" class="flex-row">
    <!-- javascript will generate this -->
  </section>

  <h2>Mes transactions</h2>
  <section id="my-transactions" class="grid-table">
    <!-- generated with ajax -->
  </section>
</main>

<script>
  getAccountInfo('../api/account/readCredit.php');
  getAccountInfo('../api/account/readCheque.php');
  getTransactions();

  function getAccountInfo(url){
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.onload = function() {
      var data = JSON.parse(this.response);
      var html = `
        <div class="card">
          <div id="chequing-account">
            <p>${data.description}</p>
            </h4>${data.balance}$</h4>
          </div>
          <h3><a href="./comptes.php">></a></h3>
        </div>
      `;
      
      var div = document.getElementById('sommaire');
      div.insertAdjacentHTML('beforeend', html);
    }
    xhr.send();
  }

  function getTransactions() {
    var url = '../api/transaction/all.php';
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.onload = function() {
      var data = JSON.parse(this.response);
      console.log(data);
      var html = '';
      for (var item of data) {
        var amount;
        if (item.transaction_type === 'deposit') {
          amount = `<span style="color:var(--primary);">+$${item.amount}</span>`;
        } else {
          amount = '$' + item.amount;
        }
        html += `
          <h4>${item.supplier}</h4>
          <p>${item.transaction_date.split(' ')[0]}</p>
          <p class="description">${item.description}</p>
          <h4>${amount}</h4>
          <a href="./comptes.php">></a>
        `
      }

      var div = document.getElementById('my-transactions');
      div.insertAdjacentHTML('beforeend', html);
    }
    xhr.send();
  }


</script>
<?php include('./includes/footer.php'); ?>
