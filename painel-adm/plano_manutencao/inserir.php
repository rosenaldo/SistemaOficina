<?php 
require_once("../../conexao.php"); 
@session_start();

$cliente = $_POST['cliente'];
$veiculo = @$_POST['veiculo'];

$descricao = $_POST['descricao'];
$obs = $_POST['descricao'];

$id = $_POST['txtid2'];



//VERIFICAR SE O CLIENTE EXISTE
$query = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res);
	if($total_reg == 0){
		echo 'O Cliente não está cadastrado ou o CPF está incorreto!';
		exit();
}

if($cliente == ""){
	echo 'O CPF do Cliente é Obrigatório!';
	exit();
}

if($veiculo == ""){
	echo 'Você precisa selecionar um Veiculo';
	exit();
}


if($id == ""){
	$res = $pdo->prepare("INSERT INTO pcm SET cliente = :cliente, veiculo = :veiculo, descricao = :descricao, mecanico = '$_SESSION[cpf_usuario]', data = curDate()");	

}else{
	$res = $pdo->prepare("UPDATE pcm SET cliente = :cliente, veiculo = :veiculo, descricao = :descricao, mecanico = '$_SESSION[cpf_usuario]' WHERE id = '$id'");
	
}

$res->bindValue(":cliente", $cliente);
$res->bindValue(":veiculo", $veiculo);
$res->bindValue(":descricao", $descricao);

$res->execute();


echo 'Salvo com Sucesso!';

?>