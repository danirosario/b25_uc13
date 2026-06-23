<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Enviar</title>
</head>

<body>
    <form action="exibir.php" method="GET">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome"> <br><br>

        <label for="idade">Idade:</label>
        <input type="number" id="idade" name="idade"> <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email"> <br><br>

        <button type="submit">Enviar</button>
    </form>
</body>

</html>


<!--
Em PHP, os métodos GET e POST são usados para transferir dados do navegador para o servidor. 
O GET transmite dados abertamente pela URL, sendo ideal para buscas. 
O POST envia dados no corpo da requisição HTTP, sendo indicado para formulários longos, 
arquivos ou dados sensíveis como senhas.
-->