<?php

  function paymentInteraxAPI($account_id, $supplier_name, $amount) {
    // Reference for CURL
    // https://stackoverflow.com/questions/2138527/php-curl-http-post-sample-code

    // set post fields
    $post = [
        'compte' => $account_id,
        'fournisseur' => $supplier_name,
        'montant'   => $amount,
        'banque'   => 'gringotts',
    ];


    $url = 'https://api.interax.ca/factures.json';
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