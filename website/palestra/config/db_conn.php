<?php
    $host='mysql';
    $pass='apppass';
    $user='appuser';
    $db='palestra';
    $charset='utf8mb4';
    $dsn="mysql:host=$host;dbname=$db;charset=$charset";
    $options=[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    try{
        $pdo = new PDO($dsn, $user, $pass, $options);   
    }
    catch(PDOException $e){
        echo "Connessione fallita: ".$e->getMessage();
    }catch(Exception $e){
        echo "Errore: ".$e->getMessage();
    };
?>