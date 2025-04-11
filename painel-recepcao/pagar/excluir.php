<?php 
require_once("../../conexao.php"); 

$id = $_POST['id'];

//BUSCAR A IMAGEM PARA EXCLUIR DA PASTA
$query_con = $pdo->query("SELECT * FROM contas_pagar WHERE id = '$id'");
$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
$imagem = $res_con[0]['imagem'];
if($imagem != 'sem-foto.jpg'){
	@unlink('../../img/contas/'.$imagem);
}

$query = $pdo->query("SELECT * FROM compras where id_conta = '$id' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_compra = @$res[0]['id'];


$pdo->query("DELETE FROM compras WHERE id = '$id_compra'");
$pdo->query("DELETE FROM contas_pagar WHERE id = '$id'");

echo 'Excluído com Sucesso!';

?>