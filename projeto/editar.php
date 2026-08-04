<?php
session_start();

require_once("conexao.php");

// VERIFICAR SE O ID FOI PASSADO NA URL
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
if ($id <= 0) {
    $_SESSION['mensagem'] = "<span class='msg-erro'>Erro: ID inválido!</span>";
    header("Location: listar.php");
    exit();
}

// VERIFICAR SE RECEBEMOS OS DADOS DO FORMULARIO PELO METODO POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $telefone = $_POST["telefone"];
    $nivel = $_POST["nivel"] ?? 'usuario';
    $data_nascimento = $_POST["data_nascimento"];

    // Verifica se o usuário preencheu uma nova senha
    if (!empty($senha)) {
        $passwordPattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[#@$!%*?&])[A-Za-z\d#@$!%*?&]{8,}$/";

        if (!preg_match($passwordPattern, $senha)) {
            $_SESSION['mensagem'] = "<span class='msg-erro'>A senha deve conter pelo menos 8 caracteres, uma letra maiúscula, um número e um caractere especial.</span>";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        $hashedPassword = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE clientes SET nome = ?, email = ?, senha = ?, telefone = ?, nivel = ?, nascimento = ?, ultima_alteracao = NOW() WHERE id = ?");
        $stmt->bind_param("ssssssi", $nome, $email, $hashedPassword, $telefone, $nivel, $data_nascimento, $id);
    } else {
        $stmt = $conn->prepare("UPDATE clientes SET nome = ?, email = ?, telefone = ?, nivel = ?, nascimento = ?, ultima_alteracao = NOW() WHERE id = ?");
        $stmt->bind_param("sssssi", $nome, $email, $telefone, $nivel, $data_nascimento, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "<span class='msg-sucesso'>Cliente atualizado com sucesso!</span>";
    } else {
        $_SESSION['mensagem'] = "<span class='msg-erro'>Erro ao atualizar: " . $stmt->error . "</span>";
    }

    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id);
    exit();
}

// BUSCAR OS DADOS DO CLIENTE NO BANCO VIA ID
$stmt = $conn->prepare("SELECT nome, email, telefone, nivel, nascimento FROM clientes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$cliente = $result->fetch_assoc();

$stmt->close();

if (!$cliente) {
    $_SESSION['mensagem'] = "<span class='msg-erro'>Cliente não encontrado.</span>";
    header("Location: listar.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/form.css">
    <title>Editar</title>
</head>

<body>
    <header class="container-menu">
        <?php include("menu.php"); ?>
    </header>

    <main class="main-content">
        <div class="container-form">
            <h2>Editar Dados</h2>

            <?php
            if (isset($_SESSION['mensagem'])) {
                echo "<p><strong>" . $_SESSION['mensagem'] . "</strong></p>";
                unset($_SESSION['mensagem']);
            }
            ?>

            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <label>Nome: </label>
                <input type="text" name="nome" value="<?php echo htmlspecialchars($cliente['nome']); ?>" required>

                <label>E-mail: </label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($cliente['email']); ?>" required>

                <label>Senha: </label>
                <input type="password" name="senha">

                <label>Telefone: </label>
                <input type="text" name="telefone" value="<?php echo htmlspecialchars($cliente['telefone']); ?>"
                    required>

                <label>Data de Nascimento: </label>
                <input type="date" name="data_nascimento"
                    value="<?php echo htmlspecialchars($cliente['nascimento']); ?>" required>

                <label>Nível de Acesso: </label>
                <div class="nivel">
                    <div class="opcao">
                        <input type="radio" id="admin" name="nivel" value="admin" <?php echo $cliente['nivel'] == "admin" ? 'checked' : ""; ?>>Administrador
                    </div>
                    <div class="opcao">
                        <input type="radio" id="userComum" name="nivel" value="usuario" <?php echo $cliente['nivel'] == "usuario" ? 'checked' : ""; ?>>Usuário
                    </div>
                </div>

                <button class="btn-save" type="submit">Salvar</button>
                <button class="btn-cancel" type="button" onclick="window.location.href='listar.php'">Cancelar</button>
            </form>
        </div>
    </main>

</body>

</html>