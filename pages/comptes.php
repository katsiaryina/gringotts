<?php
  include("../config/session.php");
  include('./includes/header.php');
  include('./includes/sidenav.php');
?>

<main>
  <section id="my-accounts" class="grid-table"> 
    <!-- javsacript will generate this -->
  </section>

  <section id="my-accounts-summary">
    <!-- javascript will generate this -->
  </section>

</main>

<script>

  // Get the Account Data
  var url = '../api/account/all.php'
  var xhr = new XMLHttpRequest();
  xhr.open('GET', url, true);
  xhr.onload = function() {
    var data = JSON.parse(this.response);
    myAccountsTable('my-accounts', data);
    summaryTable('my-accounts-summary', data)
  }
  xhr.send(); 

  // MES COMPTES DE BANQUE
  function myAccountsTable(selector, data) {
    // COMPTE CHEQUES
    var html = '<h2>Mes comptes de banque</h2>';
    for (var account of data) {
      if (account.account_type === 'cheques') {
        html += `<p>${account.description}</p>
        <p>${account.account_number}</p>
        <h4><a href="./accueil.php">${account.balance}$ ></a></h4>`;
      }
    }

    // cartes de crédits
    html += '<h2>Mes cartes de crédits</h2>';
    for (var account of data) {
      if (account.account_type === 'credit') {
        html += `<p>${account.description}</p>
        <p>${account.account_number}</p>
        <h4><a href="./accueil.php">${account.balance}$ ></a></h4>`;
      }
    }

    // Investments
    html += '<h2>Mes investissements</h2>';
    for (var account of data) {
      if (account.account_type === 'investment') {
        html += `<p>${account.description}</p>
        <p>${account.account_number}</p>
        <h4><a href="./accueil.php">${account.balance}$ ></a></h4>`;
      }
    }

    var myAccounts = document.getElementById(selector);
    myAccounts.innerHTML = html;
  }

  function getTotal(data, account_type) {
    var total = 0;
    for (var item of data) {
      if (item.account_type === account_type) {
        total += Number(item.balance);
      }
    }
    console.log(total);
    return Math.round(total * 100) / 100;
  }

  function summaryTable(selector, data) {
    var totalCheque = getTotal(data, 'cheques');
    var totalCredit = getTotal(data, 'credit');
    var totalInvestissement = getTotal(data, 'investment');
    var totalActifs = Math.round((totalCheque + totalInvestissement) * 100) / 100;

    var html = `
      <h1>Sommaire de mes comptes</h1>
      <table>
        <thead>
          <tr>
            <th>Produits</th>
            <th>Actifs</th>
            <th>Passifs</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Comptes de banque</td>
            <td>${totalCheque}$</td>
            <td>0.00$</td>
          </tr>
          <tr>
            <td>Carte de crédits</td>
            <td>0.00$</td>
            <td>${totalCredit}$</td>
          </tr>
          <tr>
            <td>Investissements</td>
            <td>${totalInvestissement}$</td>
            <td>0.00$</td>
          </tr>
          <tr>
            <td><strong> Total (CAD) </strong></td>
            <td><strong>${totalActifs}$</strong></td>
            <td><strong>${totalCredit}$</strong></td>
          </tr>
        </tbody>
      </table>`;

      var mySummary = document.getElementById(selector);
      mySummary.innerHTML = html;
  }


</script>
<?php include('./includes/footer.php'); ?>