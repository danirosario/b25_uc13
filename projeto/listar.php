<?php
require_once("conexao.php");

$sql = "SELECT * FROM clientes";
$result = $conn->query($sql) or die("Erro ao executar a consulta: " . $conn->error);

$rows = $result->num_rows;

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/listar.css">
    <title>Listar Clientes</title>
</head>

<body>
    <h1>Lista de clientes</h1>
    <p>Clientes cadastrados: <?php echo $rows; ?></p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Data de Nascimento</th>
                <th>Data de Cadastro</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo $row["nome"]; ?></td>
                        <td><?php echo $row["email"]; ?></td>
                        <td><?php echo $row["telefone"]; ?></td>
                        <td><?php echo $row["nascimento"]; ?></td>
                        <td><?php echo $row["data_cadastro"]; ?></td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="6">Nenhum cliente cadastrado.</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</body>

</html>