<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/menu.css">
</head>
<body>
    <nav class="navbar">
        <ul class="nav-list">
            <!-- <li><a href="index.php">Início</a></li> -->
            <li><a href="cadastro.php">Cadastro</a></li>
            <li><a href="listar.php">Listar</a></li>
            <div class="login-link">
                <?php
                if (isset($_SESSION["user_id"])) {
                    echo '<li><a href="logout.php">Logout</a></li>';
                } else {
                    echo '<li><a href="login.php">Login</a></li>';
                }
                ?>
            </div>
        </ul>
    </nav>
</body>
</html>