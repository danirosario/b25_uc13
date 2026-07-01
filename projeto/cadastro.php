<?php
session_start();

require_once("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $data_nascimento = $_POST["data_nascimento"];

    // VERIFICAR SE OS DADOS JA EXISTEM
    $stmt_check = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result(); // Armazena o resultado para poder contar as linhas

    if ($stmt_check->num_rows > 0) {
        $_SESSION['mensagem'] = "<span class='msg-erro'>Erro: E-mail já cadastrado!</span>";
        $stmt_check->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    $stmt_check->close();

    // INSERIR OS DADOS NO BANCO

    $stmt = $conn->prepare("INSERT INTO clientes (nome, email, telefone, nascimento, data_cadastro) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssis", $nome, $email, $telefone, $data_nascimento);

    if ($stmt->execute()) {
        // Envelopado na classe de sucesso
        $_SESSION['mensagem'] = "<span class='msg-sucesso'>Cliente cadastrado com sucesso!</span>";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Envelopado na classe de erro concatenando o erro do banco
        $_SESSION['mensagem'] = "<span class='msg-erro'>Erro ao cadastrar: " . $stmt->error . "</span>";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cadastro.css">
    <title>Cadastro</title>
</head>

<body>
    <div class="container-form">
        <h2>Cadastro</h2>

        <!-- Exibe a mensagem de sucesso ou erro se ela existir na sessão -->
        <?php
        if (isset($_SESSION['mensagem'])) {
            echo "<p><strong>" . $_SESSION['mensagem'] . "</strong></p>";
            unset($_SESSION['mensagem']); // Limpa a mensagem para não exibir novamente no próximo F5
        }
        ?>

        <form action="" method="POST">
            <label>Nome: </label>
            <input type="text" name="nome" required>
            <label>E-mail: </label>
            <input type="email" name="email" required>
            <label>Telefone: </label>
            <input type="number" name="telefone" required>
            <label>Data de Nascimento: </label>
            <input type="date" name="data_nascimento" required>
            <button type="submit">Cadastrar</button>
        </form>
    </div>
</body>

</html>