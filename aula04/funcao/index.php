<?php

include("funcao.php");

primeiraFuncao();

echo "<hr>";

//função somar dois números
echo "resultado da soma: " . somar(10, 10);

echo "<br>";

//função média de 3 números
echo "media: " . media(10, 15, 20);

?>
<!-- 
add um numero via formulario e verificar se o número digitado é par ou impar 
-->
<hr>
<form method="GET" action="">
    <label>Digite um numero: </label>
    <input type="number" name="numero">
    <button type="submit">Verificar</button>
</form>

<?php
// Verifica se o formulário foi enviado 
if (isset($_GET["numero"])) {
    $numeroDigitado = $_GET["numero"];
    echo "O número digitado é: " . parOuImpar($numeroDigitado);
}
?>