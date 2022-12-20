<?php

// error_reporting(-1);
// ini_set('display_errors', 'On');
    include "WebServiceAPI.php";

    $client = new WebServiceAPI("http://localhost/vtigercrm/webservice.php",'admin',"HMsoXX5BmigD1YmI");

    $temp = array(
        'lastname' => 'New module name test',
        'firstname' => 'John',
        'email' => 'john.doe@example.com',
        'assigned_user_id' => $client->getLoginData()['userId'],
    );
    var_dump($client->createRecord('Contacts',$temp));

    // var_dump($client->getModulesId());

    // var_dump($client->retrieve("Potentials","18"));

?>