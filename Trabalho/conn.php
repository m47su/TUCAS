<?php
    try{
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=tucas;port=3307','root','');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("SET CHARACTER SET utf8");

    }catch(PDOException $error){
        echo 'Error '.$error->getMessage();
    }
?>