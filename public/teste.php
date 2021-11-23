<?php

$usuario = 'root';
$senha = '';
$pasta = 'origo';
$endereco = 'localhost';

// Conecta-se ao banco de dados MySQL
$conexaoComBanco = new mysqli($endereco, $usuario, $senha, $pasta);

$conexaoComBanco->set_charset("utf8mb4");

// Caso algo tenha dado errado, exibe uma mensagem de erro
if (mysqli_connect_errno()) {
    trigger_error(mysqli_connect_error());
}

// Executa uma consulta que retorna todos os produtos
$sql = "SELECT `nome`, `valor`, frete FROM `produtos`";
$query = $conexaoComBanco->query($sql);

echo '<link rel="stylesheet" type="text/css" href="app.css">';
echo '<ul>';

$valorTotal = 0;
while ($produto = $query->fetch_array()) {
    echo '<li>';
    echo 'Nome: <span class="tetereere">' . $produto['nome'] . '</span><br>';
    echo 'Valor: ' . $produto['valor'] . '<br>';
    echo '</li>';

    $valorTotal = $valorTotal+$produto['valor'];
}
echo '</ul>';

echo "Valor total dos produtos (php):".$valorTotal.'<br>';



// Executa uma consulta que retorna todos os produtos
$sql = "SELECT sum(valor) FROM produtos";
$query = $conexaoComBanco->query($sql);

$valorTotalDoBanco = $query->fetch_row()[0];

echo "Valor total dos produtos (banco):".$valorTotalDoBanco;