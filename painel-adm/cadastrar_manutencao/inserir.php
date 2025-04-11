<?php 
require_once("../../conexao.php"); 

$nome = $_POST['nome_mec'];

$antigo = $_POST['antigo'];
$id = $_POST['txtid2'];

if($nome == ""){
	echo 'O nome é Obrigatório!';
	exit();
}


//VERIFICAR SE O REGISTRO JÁ EXISTE NO BANCO
if($antigo != $nome){
	$query = $pdo->query("SELECT * FROM tipo_pcm where descricao = '$nome' ");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res);
	if($total_reg > 0){
		echo 'A PCM já está Cadastrada!';
		exit();
	}
}


if($id == ""){
	$res = $pdo->prepare("INSERT INTO tipo_pcm SET descricao = :descricao");	

}else{
	$res = $pdo->prepare("UPDATE tipo_pcm SET descricao = :descricao WHERE id = '$id'");
		
}

$res->bindValue(":descricao", $nome);
$res->execute();


echo 'Salvo com Sucesso!';

?>