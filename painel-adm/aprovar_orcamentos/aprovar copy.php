<?php 
require_once("../../conexao.php"); 

$id = $_POST['id'];

//BUSCAR O VALOR DO ORÇAMENTO
$query_orc = $pdo->query("SELECT * FROM orcamentos where id = '$id' ");
$res_orc = $query_orc->fetchAll(PDO::FETCH_ASSOC);
$valor_orc = $res_orc[0]['valor'];
$cliente = $res_orc[0]['cliente'];
$mecanico = $res_orc[0]['mecanico'];
$data_entrega = $res_orc[0]['data_entrega'];
$servico = $res_orc[0]['servico'];
$veiculo = $res_orc[0]['veiculo'];
$garantia = $res_orc[0]['garantia'];
$obs = $res_orc[0]['obs'];


$query_cat = $pdo->query("SELECT * FROM orc_serv where orcamento = '$id' ");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
if(@count($res_cat) == 0){
	$nome_serv = "Não Lançado!";

}else if(@count($res_cat) == 1){
	$serv = $res_cat[0]['servico'];
	

	$query_ser = $pdo->query("SELECT * FROM servicos where id = '$serv' ");
	$res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
	$nome_servico = $res_ser[0]['nome'];

}else if(@count($res_cat) > 1){
	$nome_servico = @count($res_cat) . ' Serviços';
	
}


$total_prod = 0;

$query = $pdo->query("SELECT * FROM orc_prod where orcamento = '$id' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if(@count($res) == 0){
	$total_pagar = $valor_orc;
}else{

	for ($i=0; $i < @count($res); $i++) { 
		foreach ($res[$i] as $key => $value) {
		}
		$prod = $res[$i]['produto'];

		$query_pro = $pdo->query("SELECT * FROM produtos where id = '$prod' ");
		$res_pro = $query_pro->fetchAll(PDO::FETCH_ASSOC);
		$valor_prod = $res_pro[0]['valor_venda'];
		$estoque = $res_pro[0]['estoque'] - 1;

		$total_prod = $valor_prod + $total_prod;
		$total_pagar = $total_prod + $valor_orc;




	//ABATER DO ESTOQUE E LANÇAR NA VENDA
		$pdo->query("UPDATE produtos SET estoque = '$estoque' where id = '$prod' ");

		$pdo->query("INSERT INTO vendas SET produto = '$prod', valor = '$valor_prod', funcionario = '$mecanico', data = curDate(), id_orc = '$id' ");



	}
}


$total_ser = 0;
$query = $pdo->query("SELECT * FROM orc_serv where orcamento = '$id' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0)
for ($i=0; $i < @count($res); $i++) { 
	foreach ($res[$i] as $key => $value) {
	}
	$serv = $res[$i]['servico'];

	$query_ser = $pdo->query("SELECT * FROM servicos where id = '$serv' ");
	$res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
	$valor_ser = $res_ser[0]['valor'];
	$total_ser = $valor_ser + $total_ser;
	

}

$total_pagar += $total_ser;

//INSERIR NA TABELA DE CONTAS A RECEBER
$pdo->query("INSERT INTO contas_receber SET descricao = 'Orçamento', valor = '$total_pagar', adiantamento = '0', mecanico = '$mecanico', cliente = '$cliente', data = curDate(), pago = 'Não', id_servico = '$id' ");

//INSERIR NA TABELA DE OS
$nome_servico = !empty($nome_servico) ? $nome_servico : null;

$pdo->query("INSERT INTO os SET descricao = '$nome_servico', valor = '$total_pagar', mecanico = '$mecanico', cliente = '$cliente', data_entrega = '$data_entrega', concluido = 'Não', valor_mao_obra = '$valor_orc', data = curDate(), veiculo = '$veiculo', garantia = '$garantia', obs = '$obs', tipo = 'Orçamento', id_orc = '$id' ");


$pdo->query("UPDATE orcamentos SET status = 'Aprovado' WHERE id = '$id'");

	//ENTRADA DO VEÍCULO
$query = $pdo->query("SELECT * FROM controles where veiculo = '$veiculo' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg == 0){
	$pdo->query("INSERT INTO controles SET veiculo = '$veiculo', mecanico = '$mecanico', data = curDate(), descricao = '$nome_servico' ");
}





//CONCLUIR STATUS DO ORÇAMENTO

$id_os = $_POST['id'];

$query = $pdo->query("SELECT * FROM os where id = '$id' ");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$mecanico = $res[0]['mecanico'];
	$tipo = $res[0]['tipo'];
	$id = $res[0]['id_orc'];
	$veiculo = $res[0]['veiculo'];
	

	if($tipo == 'Orçamento'){
		$pdo->query("UPDATE orcamentos SET status = 'Concluído' WHERE id = '$id'");
	}
	
	$pdo->query("UPDATE os SET concluido = 'Sim' WHERE id = '$id_os'");
	
	
	//LANÇAR NA TABELA DE RETORNOS
	$query = $pdo->query("SELECT * FROM retornos where veiculo = '$veiculo' ");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		if(@count($res) == 0){
			$pdo->query("INSERT INTO retornos SET veiculo = '$veiculo', data_serv = curDate(), data_contato = curDate()");
		}else{
			$pdo->query("UPDATE retornos SET data_serv = curDate(), data_contato = curDate() WHERE veiculo = '$veiculo'");
		}




echo 'Aprovado com Sucesso!';

?>