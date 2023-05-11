<?php
    include("../conn.php");
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $usuario = $pdo->prepare('SELECT * FROM usuarios where email=:email 
    AND senha=:senha');
    $usuario->execute(array(':email'=>$email,':senha'=>$senha));

    $rowTabela = $usuario->fetchAll();
    
    if (empty($rowTabela)){
        echo "<script>
        alert('Usuario e/ou senha invalidos!!!');
        </script>";
    }else{
       
    $sessao = $rowTabela[0];

    if(!isset($_SESSION)) {
    session_start();
    }
    $_SESSION['id'] = $sessao['id'];
    $_SESSION['login'] = $sessao['login'];

    header("Location: ../tela_inicial/index.html");
    }

?>