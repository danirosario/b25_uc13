<?php
$nome  = isset($_GET["nome"])  ? $_GET["nome"]  : "Não preenchido";
$idade = isset($_GET["idade"]) ? $_GET["idade"] : "Não preenchida";
$email = isset($_GET["email"]) ? $_GET["email"] : "Não preenchido";

echo "Nome: $nome <br> Idade: $idade <br> Email: $email.";
?>