<?php
// Conexão à Base de Dados - RodeiaFlix
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "rodeiaflix_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão à base de dados: " . $conn->connect_error);
}

// Configurar codificação UTF-8
$conn->set_charset("utf8mb4");

// Função global para extrair o ID do YouTube de qualquer link
if (!function_exists('converterYoutubeEmbed')) {
    function converterYoutubeEmbed($url) {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        return isset($match[1]) ? $match[1] : $url;
    }
}
?>