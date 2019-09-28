<?php
  session_start();

  // Check is already logged in
  if (isset($_SESSION['client_id'])) {
    header("Location: ./accueil.php");
    exit();
  } 

  include_once "./includes/error.php";
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
    <link rel="icon" type="image/png" sizes="32x32" href="../img/favicon-32x32.png">

    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/container.css" />
    <link rel="stylesheet" href="../css/backgrounds.css" />
    <link rel="stylesheet" href="../css/buttons.css" />
    <link rel="stylesheet" href="../css/inputs.css" />
    <link rel="stylesheet" href="../css/forms.css" />

    <link rel="stylesheet" href="../css/responsive.css" />

  </head>
  <body>
    <div class="flex-2">
      <aside class="background-cents">
        <div class="overlay-dark"></div>
      </aside>
      <main class="container sign-in dark">
        <h1>Enregistrez-vous à la Gringotts Bank!</h1>
        <form class="vertical-form" action="../config/signup.php" method="POST">

          <label for="email">Email</label>
          <input
            type="email"
            name="email"
            id="email"
            placeholder="Entrer votre email"
            required
          />

          <label for="firstName">Prénom</label>
          <input
            type="text"
            name="firstName"
            id="firstName"
            placeholder="Ex. Bob"
            required
          />

          <label for="lastName">Nom de Famille</label>
          <input
            type="text"
            name="lastName"
            id="lastName"
            placeholder="Ex. Marley"
            required
          />

          <label for="password">Mot de passe</label>
          <input
            type="password"
            name="password1"
            id="password1"
            placeholder="Entrer un mot de passe"
          />

          <label for="password2">Confirmer votre mot de passe</label>
          <input
            type="password"
            name="password2"
            id="password2"
            placeholder="Confirmer votre mot de passe"
          />
          <p class="error"><?php errorMessage($error)?></p>
          <button type="submit" class="btn btn-primary" name="register-submit">S'enregister!</button>
          <a href="../index.php">Déjà un membre? Connectez-vous ici.</a>
        </form>

      </main>
    </div>
  </body>
</html>
