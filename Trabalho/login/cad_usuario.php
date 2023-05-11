<?php

require ('../conn.php');

if(isset($_POST['email']) && isset($_POST['senha'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if(empty($email) || empty($senha)){
        echo "Os valores não podem ser vazios";
    }else{
        $cad_prod = $pdo->prepare("INSERT INTO usuarios(email, senha) 
        VALUES(:email, :senha)");
        $cad_prod->execute(array(
            ':email'=> $email,
            ':senha'=> $senha,
        ));

        echo "<script>
        alert('Solicitação feita com sucesso!');
        </script>";
        header("Location: ../login/index.html");
    exit();
    }
} else {
    echo "Campos não preenchidos";
}

?>


