<?php

  function sendInteraxAPI($nom, $email, $question, $answer, $amount) {
    // Reference for CURL
    // https://stackoverflow.com/questions/2138527/php-curl-http-post-sample-code

    // set post fields
    $post = [
        'nom' => $nom,
        'email' => $email,
        'question'   => $question,
        'password'   => $answer,
        'montant'   => $amount,
        'tel'   => '450-444-4444'
    ];

    $url = 'http://api.interax.ca/interax.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    // execute!
    $response = curl_exec($ch);

    // close the connection, release resources used
    curl_close($ch);

    // handle response
    $data = json_decode($response)->Reponse;

    return $data;

  }


?>