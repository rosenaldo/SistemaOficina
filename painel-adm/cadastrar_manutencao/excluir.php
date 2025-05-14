<?php
@session_start();
require_once("../../conexao.php");

$id = @$_POST['id'];

// Verificar se o tipo PCM está sendo usado em alguma manutenção
$query = $pdo->query("SELECT * FROM pcm WHERE servico = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if($total_reg > 0){
    echo 'Este tipo PCM não pode ser excluído, pois está vinculado a uma ou mais manutenções!';
    exit();
}

$pdo->query("DELETE FROM tipo_pcm WHERE id = '$id'");
echo 'Excluído com Sucesso!';
?>