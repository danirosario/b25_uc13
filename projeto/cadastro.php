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
        // Se encontrou alguma linha, o e-mail já existe
        $_SESSION['mensagem'] = "Erro: E-mail já cadastrado!";
        $stmt_check->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    $stmt_check->close();

    // INSERIR OS DADOS NO BANCO

    $stmt = $conn->prepare("INSERT INTO clientes (nome, email, telefone, nascimento, data_cadastro) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssis", $nome, $email, $telefone, $data_nascimento);

    if ($stmt->execute()) {

        $_SESSION['mensagem'] = "Cliente cadastrado com sucesso!"; // Armazena a mensagem de sucesso na sessão para ser exibida após o redirecionamento
        header("Location: " . $_SERVER['PHP_SELF']);               // Redireciona o usuário para a mesma página, limpando os dados do formulário (evita reenvio com F5)
        exit();
    } else {
        $_SESSION['mensagem'] = "Erro ao cadastrar: " . $stmt->error; // Armazena a mensagem de erro na sessão, concatenando com o erro retornado pelo banco
        header("Location: " . $_SERVER['PHP_SELF']);                  // Redireciona o usuário de volta para a mesma página
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
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