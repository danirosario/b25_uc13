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
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Formata o telefone (aceita 8 ou 9 dígitos + DDD)
                    // 1. Verifica se o campo "telefone" vindo do banco não está vazio ou nulo
                    if (!empty($row["telefone"])) {

                        // 2. Limpeza de segurança: Remove qualquer caractere que NÃO seja um número de 0 a 9.
                        // O sinal '^' dentro dos colchetes significa "negação". Tudo que não for número vira '' (nada).
                        // Se o banco tiver "(11) 98888-7777", vira apenas "11988887777".
                        $telefoneLimpo = preg_replace('/[^0-9]/', '', $row["telefone"]);

                        // 3. Aplicação da máscara usando Expressão Regular (Regex):
                        // /(\d{2})/     -> Grupo 1 ($1): Captura os 2 primeiros dígitos (DDD)
                        // /(\d{4,5})/   -> Grupo 2 ($2): Captura os próximos 4 ou 5 dígitos (Número da frente)
                        // /(\d{4})/     -> Grupo 3 ($3): Captura os últimos 4 dígitos (Fim do telefone)
                        // O segundo parâmetro '($1) $2-$3' organiza esses grupos visualmente.
                        $telefone = preg_replace('/(\d{2})(\d{4,5})(\d{4})/', '($1) $2-$3', $telefoneLimpo);

                    } else {
                        // 4. Caso o campo esteja vazio no banco, define o texto padrão
                        $telefone = 'Não informado';
                    }

                    // Formata a data de nascimento apenas se ela não estiver vazia
                    $dataNascimento = !empty($row["nascimento"])
                        ? (new DateTime($row["nascimento"]))->format('d/m/Y')
                        : 'Não informada';

                    // Formata a data de cadastro com hora apenas se ela não estiver vazia
                    $dataCadastro = !empty($row["data_cadastro"])
                        ? (new DateTime($row["data_cadastro"]))->format('d/m/Y H:i')
                        : 'Não informada';
                    ?>
                    <tr>
                        <td><?php echo $row["id"];     ?></td>
                        <td><?php echo $row["nome"];   ?></td>
                        <td><?php echo $row["email"];  ?></td>
                        <td><?php echo $telefone;      ?></td> 
                        <td><?php echo $dataNascimento;?></td> 
                        <td><?php echo $dataCadastro;  ?></td> 
                        <td>
                            <a href="editar.php?id=<?php echo $row["id"];  ?>" id="editar-link">Editar</a> |
                            <a href="deletar.php?id=<?php echo $row["id"]; ?>" id="deletar-link" 
                                onclick="return confirm('Tem certeza que deseja excluir este cliente?');">Excluir</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="7">Nenhum cliente cadastrado.</td>
                </tr>
                <?php
            }
            ?>
        </tbody>

    </table>
</body>

</html>