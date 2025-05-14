<?php
@session_start();
require_once("../../conexao.php");

$pagina = 'cadastrar_manutencao';
$descricao = $_POST['nome_mec'];
$id = @$_POST['txtid2'];

// Verificar se o tipo PCM já existe
$query = $pdo->prepare("SELECT * FROM tipo_pcm WHERE descricao = :descricao");
$query->bindValue(":descricao", $descricao);
$query->execute();

if($query->rowCount() > 0 && $id == ''){
    echo "Este tipo PCM já está cadastrado!";
    exit();
}

if($id == "" || $id == 0){
    $query = $pdo->prepare("INSERT INTO tipo_pcm SET descricao = :descricao");
}else{
    $query = $pdo->prepare("UPDATE tipo_pcm SET descricao = :descricao WHERE id = '$id'");
}

$query->bindValue(":descricao", $descricao);
$query->execute();

echo "Salvo com Sucesso!";
?>