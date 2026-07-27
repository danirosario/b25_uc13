<?php 
require_once('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    //PREPARA A CONSULTA NO BANCO
    $stmt = $conn->prepare("SELECT id, nome, email, senha FROM clientes WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $senha_criptografada = $row["senha"];

        if (password_verify($senha, $senha_criptografada)) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["user_nome"] = $row["nome"];

            header("Location: listar.php");
            exit();
        } else {
            //senha incorreta
            $erro = "Email ou senha inválidos.";
        }
    } else {
        //email não encontrado
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
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <form action="" method="POST">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required>
        <input type="submit" value="Entrar">
    </form>
</body>
</html>