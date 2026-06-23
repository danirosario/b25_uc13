<?php
//variaveis

$nome = "dani";
$altura = "1.65";
$peso = "53";

$imc = $peso / ($altura * $altura);

echo "Nome: $nome <br>";
echo "Peso: $peso <br>";
echo "Altura: $altura <br>";
echo "IMC: $imc";
echo "<br><br>";

//condicional if e else

if ($imc >= 40.0) {
    echo "Obesidade Grave";
} 

elseif ($imc >= 30.0) {
    echo "Obesidade";
} 

elseif ($imc >= 25.0) {
    echo "Sobrepeso";
} 

elseif ($imc >= 18.5) {
    echo "Peso Normal";
} 

else {
    echo "Baixo peso";
}

?>