<?php 
require_once("../../conexao.php"); 

$id = $_POST['id'];

$pdo->query("DELETE FROM pcm WHERE id = '$id'");

$pdo->query("DELETE FROM pcm_preventiva WHERE pcm = '$id'");

$pdo->query("DELETE FROM pcm_preditiva WHERE  pcm = '$id'");

$pdo->query("DELETE FROM pcm_corretiva WHERE  pcm = '$id'");



echo 'Excluído com Sucesso!';

?>