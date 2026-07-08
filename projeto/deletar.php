<?php
session_start();
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

    // Executar o comando SQL e validar o resultado
    if ($stmt->execute() === TRUE) {
        // Define a mensagem de sucesso que será lida pelo listar.php
        $_SESSION['mostrar_popup'] = "Deletado com sucesso!";
    } else {
        // Define a mensagem de erro detalhada caso algo dê errado no banco
        $_SESSION['mostrar_popup'] = "ERRO AO DELETAR: " . addslashes($stmt->error);
    }

    // Fecha o statement para liberar memória
    $stmt->close();

    // Redireciona de volta para a página de listagem
    header("Location: listar.php");
    exit();

} else {
    // Se tentarem acessar o arquivo diretamente via URL (GET), redireciona imediatamente
    header("Location: listar.php");
    exit();
}