<?php
// Desenvolvido por Marco Rodeia
session_start();
session_destroy();
header("Location: index.php");
exit;
?>