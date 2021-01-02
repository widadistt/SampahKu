<?php
    session_start();
    session_regenerate_id(true);

    $host = "sql12.freemysqlhosting.net";
    $user = "sql12384697";
    $password = "wqLDnFyuNG";
    $dbname = "sql12384697";

    // change the information according to your database
    $db_connection = mysqli_connect("$host","$user","$password","$dbname");

    // $db = parse_url(getenv("postgres://tsksabxokdawqm:03f55d605a04fa4d25ee18b978c211953141532f2196333a6a7ed0e4862d0744@ec2-52-201-55-4.compute-1.amazonaws.com:5432/d4h2ac7l9anakb"));
    // $db["path"] = ltrim($db["path"], "/");

    // CHECK DATABASE CONNECTION
    if(mysqli_connect_errno()){
        echo "Connection Failed".mysqli_connect_error();
        exit;
    }