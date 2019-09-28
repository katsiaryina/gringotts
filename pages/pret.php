<?php
  include("../config/session.php");
  include('./includes/header.php');
  include('./includes/sidenav.php');
?>

<main>
  <h2>Prêt hypothécaire</h2>
  <form class="vertical-form">
    <label for="amount">Montant du prêt</label>
    <input type="text" name="amount" id="amount" />

    <label for="rate">Taux annuel</label>
    <input type="text" name="rate" id="rate" />

    <label for="duration">Durée d'amortissmeent</label>
    <select name="duration" id="duration">
      <option value="1">1 an</option>
      <option value="5">5 ans</option>
      <option value="10">10 ans</option>
      <option value="25">25 ans</option>
    </select>

    <div id="output"></div>
    <button id="submit" type="submit" class="btn btn-primary">Calculer</button>
  </form>
</main>

<script>
  document.getElementById("submit").addEventListener("click", calculate);

  function calculate(e) {
    e.preventDefault();
    var url = "http://api.interax.ca/interet.json";
    var xhr = new XMLHttpRequest();

    xhr.open("POST", url, true);
    xhr.onload = function() {
      if (this.status == 200) {
        console.log(this.responseText);
        var result = JSON.parse(this.responseText);
        console.log(result);
        var html =
          " <p><strong>Vous devez payer " +
          result.payment +
          "$ par mois.</strong></p>";
        document.querySelector("#output").innerHTML = html;
      }
    };

    // var data = "amount=200000&duration=120&rate=3";
    var amount = document.getElementById("amount").value;
    var rate = document.getElementById("rate").value;
    var duration = document.getElementById("duration").value * 12;
    var data = `amount=${amount}&duration=${duration}&rate=${rate}`;
    // var data  = 'amount='+amount+'&duration='+duration+'&rate='+rate;
    // console.log(data);

    output = document.getElementById;

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(data);
  }
</script>
