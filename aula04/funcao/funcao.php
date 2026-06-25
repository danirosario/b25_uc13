<?php

//FUNÇÕES EM PHP 
function primeiraFuncao() {
    echo"UC13 - PHP";
}

function somar($num1, $num2) {
    $resultado = $num1 + $num2;
    return $resultado;
}

function media($num1, $num2, $num3) {
    $media = ($num1 + $num2 + $num3) / 3;
    return $media;
}

function parOuImpar($numero) {
    if ($numero % 2 == 0) {
        return "Par";
    }
    else {
        return "Impar";
    }
}