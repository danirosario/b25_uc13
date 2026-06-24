<?php

include("teste.php");
// require_once("teste.php");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Include</title>
</head>

<body>
    <h2>Testando o include, require e require_once</h2>

    <?php

    echo $numero * 2;

    ?>
</body>

</html>

<!--
O require para a execução do script se o arquivo não for encontrado, gerando um erro fatal. 
O include emite apenas um alerta (warning) e continua executando o código.
-->