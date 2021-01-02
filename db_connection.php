<?php
    session_start();
    session_regenerate_id(true);

    $host = "ec2-52-201-55-4.compute-1.amazonaws.com";
    $user = "tsksabxokdawqm";
    $password = "03f55d605a04fa4d25ee18b978c211953141532f2196333a6a7ed0e4862d0744";
    $dbname = "d4h2ac7l9anakb";

    // change the information according to your database
    $dsn = "pgsql:host=" . $host . ";port=" . $port .";dbname=" . $dbname . ";user=" . $user . ";password=" . $password . ";";
    $db_connection = mysqli_connect("$host","$user","$password","$dbname");

    // CHECK DATABASE CONNECTION
    if(mysqli_connect_errno()){
        echo "Connection Failed".mysqli_connect_error();
        exit;
}