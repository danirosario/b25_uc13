<?php
session_start();
require_once("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $telefone = $_POST["telefone"];
    $nivel = $_POST["nivel"] ?? 'usuario';
    $data_nascimento = $_POST["data_nascimento"];

    // 1. VALIDAÇÃO DE SEGURANÇA DO RADIO BUTTON (Whitelisting)
    $niveis_permitidos = ['usuario', 'admin'];
    if (!in_array($nivel, $niveis_permitidos)) {
        $_SESSION['mensagem'] = "<span class='msg-erro'>Erro: Nível de acesso inválido!</span>";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    //in_array no PHP serve para verificar se um valor específico existe dentro de um array, 
    // retornando true (verdadeiro) se achar o item ou false (falso) caso contrário.

    // VERIFICAR SE OS DADOS JA EXISTEM     
    $stmt_check = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $_SESSION['mensagem'] = "<span class='msg-erro'>Erro: E-mail já cadastrado!</span>";
        $stmt_check->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    $stmt_check->close();

    // VERIFICAR SE A SENHA É FORTE O SUFICIENTE
    // Regex de validação de senha:
    // ^ -> Início | 
    // (?=.*[A-Z]) -> Mín. 1 maiúscula | 
    // (?=.*\d) -> Mín. 1 número
    // (?=.*[#@$!%*?&]) -> Mín. 1 caractere especial | 
    // [A-Za-z\d#@$!%*?&]{8,} -> Permitidos e Mín. 8 caracteres | 
    // $ -> Fim
    $passwordPattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[#@$!%*?&])[A-Za-z\d#@$!%*?&]{8,}$/";

    //preg_match é uma função da linguagem PHP que procura por um padrão dentro de um texto usando expressões regulares. 
    // Ela serve para verificar se um texto combina com uma regra específica, retornando 1 se encontrar o padrão, 0 se não encontrar, ou false se houver algum erro.

    if (!preg_match($passwordPattern, $senha)) {
        $_SESSION['mensagem'] = "<span class='msg-erro'>A senha deve conter pelo menos 8 caracteres, uma letra maiúscula, um número e um caractere especial.</span>";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Aplica o hash logo após passar na validação da Regex             
        $hashedPassword = password_hash($senha, PASSWORD_DEFAULT);

        // INSERIR OS DADOS NO BANCO (incluindo senha)     
        $stmt = $conn->prepare("INSERT INTO clientes (nome, email, senha, telefone, nascimento, nivel, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssss", $nome, $email, $hashedPassword, $telefone, $data_nascimento, $nivel);

        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "<span class='msg-sucesso'>Cliente cadastrado com sucesso!</span>";
        } else {
            $_SESSION['mensagem'] = "<span class='msg-erro'>Erro ao cadastrar: " . $stmt->error . "</span>";
        }

        $stmt->close();
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
    <link rel="stylesheet" href="css/form.css">
    <title>Cadastro</title>
</head>

<body>
    <header class="container-menu">
        <?php include("menu.php"); ?>
    </header>

    <main class="main-content">
        <div class="container-form">
            <h2>Cadastro</h2>

            <?php
            if (isset($_SESSION['mensagem'])) {
                echo "<p><strong>" . $_SESSION['mensagem'] . "</strong></p>";
                unset($_SESSION['mensagem']);
            }
            ?>

            <form action="" method="POST">
                <label>Nome: </label>
                <input type="text" name="nome" required>
                <label>E-mail: </label>
                <input type="email" name="email" required>
                <label>Senha: </label>
                <input type="password" name="senha" required>
                <label>Telefone: </label>
                <input type="text" name="telefone" required>
                <label>Data de Nascimento: </label>
                <input type="date" name="data_nascimento" required>
                <label>Nível de Acesso: </label>
                <div class="nivel">
                    <div class="opcao">
                        <input type="radio" id="admin" name="nivel" value="admin">Administrador
                    </div>
                    <div class="opcao">
                        <input type="radio" id="userComum" name="nivel" value="usuario" checked>Usuário
                    </div>
                </div>
                <button class="btn-cadastro" type="submit">Cadastrar</button>
            </form>
        </div>
    </main>
</body>

</html>

<!--

senhas cadastradas:
dani - Novasenha123@
ni - niGracinhas123@