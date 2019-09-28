<?php
    // Error Messages
  function errorMessage($error) {
    if($error == "emptyfields") {
      echo 'Svp remplir tous les champs';
    }
    if ($error == "wrongpwd") {
      echo 'Mot de passe ou courriel invalide!';
    } 
    if($error == "emailtaken") {
      echo 'Le email est déjà utilisé';
    }
    if ($error == "pwdmatch") {
      echo 'Les mots de passe ne sont pas identiques';
    } 
    if ($error == "wrong-answer") {
      echo 'SVP entrer la bonne réponse';
    } 
  }

?>