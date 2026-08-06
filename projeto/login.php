<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('conexao.php');

$erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $stmt = $conn->prepare("SELECT id, nome, email, senha, nivel FROM clientes WHERE email = ?");

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $senha_criptografada = $row["senha"];

        if (password_verify($senha, $senha_criptografada)) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["user_nome"] = $row["nome"];
            $_SESSION["nivel"] = $row["nivel"];

            header("Location: listar.php");
            exit();

        } else {
            $erro = "Email ou senha inválidos.";
        }
    } else {
        $erro = "Email ou senha inválidos.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/form.css">
    <title>Login</title>
</head>

<body>
    <main class="main-content">
        <div class="container-form">
            <h2>Login</h2>

            <?php if (!empty($erro)): ?>
                <p style="color: red; text-align: center;"><?php echo $erro; ?></p>
            <?php endif; ?>

            <form action="" method="POST">
                <label>E-mail: </label>
                <input type="email" name="email" required>
                
                <label>Senha: </label>
                <input type="password" name="senha" required>

                <button class="btn-cadastro" type="submit">Entrar</button>
            </form>
        </div>
    </main>
</body>

</html>