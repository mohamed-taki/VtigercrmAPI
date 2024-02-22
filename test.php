<?php

// error_reporting(-1);
// ini_set('display_errors', 'On');
    include "WebServiceAPI.php";

    $client = new WebServiceAPI(<URL> . "/webservice.php",<username>,<user_token>);

    $temp = array(
        'lastname' => 'Doe',
        'firstname' => 'John',
        'email' => 'john.doe@example.com',
        'assigned_user_id' => $client->getLoginData()['userId'],
    );
    var_dump($client->createRecord('Contacts',$temp));

    // var_dump($client->getModulesId());

    // var_dump($client->retrieve("Potentials","18"));

?>
