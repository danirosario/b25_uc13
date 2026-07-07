<?php
require_once("conexao.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
    if ($id <= 0) {
        echo "ID Inválido!";
        exit;
    }

    // PREPARAR O COMANDO SQL PARA DELETAR O CLIENTE
    $stmt = $conn->prepare("DELETE FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $id);

    // EXECUTAR O COMANDO SQL PARA DELETAR O CLIENTE
    if ($stmt->execute() === TRUE) {
        echo "<span>DELETADO COM SUCESSO! </span><br>";
        echo "<a href='listar.php'>VOLTAR PARA A LISTA</a>";
    } else {
        echo "<span>ERRO AO DELETAR: " . $stmt->error . "</span>";
    }
} else {
    header("Location:listar.php");
    exit();
}