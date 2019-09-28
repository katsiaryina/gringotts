<?php 
  session_start();
  // Check is already logged in
  if (isset($_SESSION['client_id'])) {
    header("Location: ./pages/accueil.php");
  } 

  include_once "./pages/includes/error.php";
  if (isset($_GET['error'])) {
    $error = $_GET['error'];
  } else {
    $error = '';
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Gringotts</title>
    <link rel="icon" type="image/png" sizes="32x32" href="./img/favicon-32x32.png">


    <link rel="stylesheet" href="./css/style.css" />
    <link rel="stylesheet" href="./css/container.css" />
    <link rel="stylesheet" href="./css/backgrounds.css" />
    <link rel="stylesheet" href="./css/buttons.css" />
    <link rel="stylesheet" href="./css/inputs.css" />
    <link rel="stylesheet" href="./css/forms.css" />

    <link rel="stylesheet" href="./css/responsive.css" />

  </head>
  <body>
    <div class="flex-2">
      <aside class="background-coins">
        <div class="overlay-dark"></div>
      </aside>
      <main class="container sign-in dark">
        <h1>Gringotts Bank</h1>
        <form class="vertical-form" action="./config/login.php" method="POST">
          <h4>Bienvenue!</h4>
          <label for="email">Email</label>
          <input
            type="email"
            name="email"
            id="email"
            placeholder="Votre courriel"
            required
          />
          <label for="password">Mot de passe</label>
          <input
            type="password"
            name="password"
            id="password"
            placeholder="Entrer votre mot de passe"
          />
          <button type="submit" class="btn btn-primary" name="login-submit">🔒 Sign In</button>
          <p class="error"><?php errorMessage($error)?></p>
          <a href="#">Oublié votre mot de passe?</a>
        </form>
        <section>
          <h3>Première fois ici?</h3>
          <p>Créer votre compte de banque</p>
          <a href="./pages/register.php" class="btn btn-secondary"
            >Créer mon compte</a
          >
        </section>
      </main>
    </div>
  </body>
</html>
