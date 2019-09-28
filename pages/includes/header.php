<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Gringotts</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../img/favicon-32x32.png">

    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/backgrounds.css" />
    <link rel="stylesheet" href="../css/navbar.css" />

    <link rel="stylesheet" href="../css/container.css" />
    <link rel="stylesheet" href="../css/buttons.css" />
    <link rel="stylesheet" href="../css/inputs.css" />
    <link rel="stylesheet" href="../css/forms.css" />
    <link rel="stylesheet" href="../css/tables.css" />

    <link rel="stylesheet" href="../css/responsive.css" />

  </head>
  <body>
    <div class="layout">
      <header>
        <h3 class="logo"><a href="../index.php">Gringotts</a></h3>

        <div>
          <h2>Bonsoir <?php echo $_SESSION['first_name']; ?>!</h2>
        </div>
        <nav class="topnav">
          <ul>
            <li><a href="#" class="btn btn-secondary">Mon Profil</a></li>
            <li><a href="#" class="btn btn-secondary">Notifications</a></li>
            <li><a href="../config/logout.php" class="btn btn-primary">Logout</a></li>
          </ul>
        </nav>
      </header>

