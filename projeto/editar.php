<?php
session_start();

require_once("conexao.php");

// VERIFICAR SE O ID FOI PASSADO NA URL
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
if ($id <= 0) {
    $_SESSION['mensagem'] = "<span class='msg-erro'>Erro: ID inválido!</span>";
    header("Location: index.php");
    exit();
}

// VERIFICAR SE RECEBEMOS OS DADOS DO FORMULARIO PELO METODO POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST["id"]);
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $data_nascimento = $_POST["data_nascimento"];

    // PREPARAR O COMANDO SQL PARA ATUALIZAÇÃO DOS DADOS
    $stmt = $conn->prepare("UPDATE clientes SET nome = ?, email = ?, telefone = ?, nascimento = ? WHERE id = ?");

    // VINCULANDO OS PARAMETROS (s = string, i = inteiro)
    $stmt->bind_param("ssisi", $nome, $email, $telefone, $data_nascimento, $id);

    // EXECUTAR O SQL
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "<span class='msg-sucesso'>Edição realizada com sucesso!</span>";
        header("Location: editar.php?id=" . $id);
        exit();
    } else {
        $_SESSION['mensagem'] = "<span class='msg-erro'>Erro ao editar: " . $stmt->error . "</span>";
    }

    // FECHAR A CONEXÃO
    $stmt->close();
}

// BUSCAR OS DADOS DO CLIENTE NO BANCO VIA ID (Executado após o POST para trazer os dados atualizados)
$stmt = $conn->prepare("SELECT nome, email, telefone, nascimento FROM clientes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$cliente = $result->fetch_assoc();

if (!$cliente) {
    echo "Cliente não encontrado.";
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
                <input type="text" name="nome"
                    value="<?php echo htmlspecialchars($cliente['nome'], ENT_QUOTES, 'UTF-8'); ?>" required>

                <label>E-mail: </label>
                <input type="email" name="email"
                    value="<?php echo htmlspecialchars($cliente['email'], ENT_QUOTES, 'UTF-8'); ?>" required>

                <label>Telefone: </label>
                <input type="text" name="telefone"
                    value="<?php echo htmlspecialchars($cliente['telefone'], ENT_QUOTES, 'UTF-8'); ?>" required>

                <label>Data de Nascimento: </label>
                <input type="date" name="data_nascimento"
                    value="<?php echo htmlspecialchars($cliente['nascimento'], ENT_QUOTES, 'UTF-8'); ?>" required>

                <button class="btn-save" type="submit">Salvar</button>
                <button class="btn-cancel" type="button" onclick="window.location.href='listar.php'">Cancelar</button>
            </form>
        </div>
    </main>

</body>

</html>