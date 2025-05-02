<?php 
require_once("../../conexao.php"); 

$id = $_POST['id'];


if($total_produtos == 0){
	$pdo->query("DELETE FROM tipo_pcm WHERE id = '$id'");
	echo 'Excluído com Sucesso!';
}else{
	echo '';
}




?>