<?php 
require_once("../../conexao.php"); 
@session_start();

$id = $_GET['id'];

require_once("data_formatada.php"); 


//DADOS DO ORÇAMENTO
$query_orc = $pdo->query("SELECT * FROM orcamentos where id = '$id' ");
$res_orc = $query_orc->fetchAll(PDO::FETCH_ASSOC);
$cpf_cliente = $res_orc[0]['cliente'];
$veiculo = $res_orc[0]['veiculo'];
$descricao = $res_orc[0]['descricao'];
$obs = $res_orc[0]['obs'];
$valor_orc = $res_orc[0]['valor'];
$mecanico = $res_orc[0]['mecanico'];
$data_orc = $res_orc[0]['data'];
$data_entrega = $res_orc[0]['data_entrega'];
$servico = $res_orc[0]['servico'];
$garantia = $res_orc[0]['garantia'];

$data_entrega = implode('/', array_reverse(explode('-', $data_entrega)));
$valor_orc_f = number_format($valor_orc, 2, ',', '.');

$query_mec = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico' ");
$res_mec = $query_mec->fetchAll(PDO::FETCH_ASSOC);
$nome_mecanico = 'Marcos';


$query_mec = $pdo->query("SELECT * FROM servicos where id = '$servico' ");
$res_mec = $query_mec->fetchAll(PDO::FETCH_ASSOC);
$nome_servico = !empty($res_mec[0]['nome']) ? $res_mec[0]['nome'] : null;


$query_cli = $pdo->query("SELECT * FROM clientes where cpf = '$cpf_cliente' ");
$res_cli = $query_cli->fetchAll(PDO::FETCH_ASSOC);
$nome_cli = $res_cli[0]['nome'];
$telefone_cli = $res_cli[0]['telefone'];
$endereco_cli = $res_cli[0]['endereco'];
$email_cli = $res_cli[0]['email'];


$query_vei = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
$res_vei = $query_vei->fetchAll(PDO::FETCH_ASSOC);
$marca = $res_vei[0]['marca'] . ' - ' .$res_vei[0]['modelo'];
$placa = $res_vei[0]['placa'];
$cor = $res_vei[0]['cor'];
$ano = $res_vei[0]['ano'];
$km = $res_vei[0]['km'];

?>

<!DOCTYPE html>
<html>
<head>
	<title>Relatório de Orçamento</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<style>

		@page {
			margin: 0px;

		}

		.footer {
			margin-top:20px;
			width:100%;
			background-color: #ebebeb;
			padding:10px;
		}

		.cabecalho {    
			background-color: #ebebeb;
			padding:10px;
			margin-bottom:30px;
			width:100%;
			height:100px;
		}

		.titulo{
			margin:0;
			font-size:28px;
			font-family:Arial, Helvetica, sans-serif;
			color:#6e6d6d;

		}

		.subtitulo{
			margin:0;
			font-size:17px;
			font-family:Arial, Helvetica, sans-serif;
		}

		.areaTotais{
			border : 0.5px solid #bcbcbc;
			padding: 15px;
			border-radius: 5px;
			margin-right:25px;
			margin-left:25px;
			position:absolute;
			right:20;
		}

		.areaTotal{
			border : 0.5px solid #bcbcbc;
			padding: 15px;
			border-radius: 5px;
			margin-right:25px;
			margin-left:25px;
			background-color: #f9f9f9;
			margin-top:2px;
		}

		.pgto{
			margin:1px;
		}

		.fonte13{
			font-size:13px;
		}

		.esquerda{
			display:inline;
			width:50%;
			float:left;
		}

		.direita{
			display:inline;
			width:50%;
			float:right;
		}

		.table{
			padding:15px;
			font-family:Verdana, sans-serif;
			margin-top:20px;
		}

		.texto-tabela{
			font-size:12px;
		}


		.esquerda_float{

			margin-bottom:10px;
			float:left;
			display:inline;
		}


		.titulos{
			margin-top:10px;
		}

		.image{
			margin-top:-10px;
		}

		.margem-direita{
			margin-right: 80px;
		}

		hr{
			margin:8px;
			padding:1px;
		}


	</style>

</head>
<body>


	<div class="cabecalho">
		<div class="container">
			<div class="row titulos">
				<div class="col-sm-2 esquerda_float image">	
					<!-- <img src="../../img/logo2.png" width="100px"> -->
				</div>
				<div class="col-sm-10 esquerda_float">	
					<h2 class="titulo"><b><?php echo strtoupper($nome_oficina) ?></b></h2>
					<h6 class="subtitulo"><?php echo $endereco_oficina . ' Tel: '.$telefone_oficina  ?></h6>

				</div>
			</div>
		</div>

	</div>

	<div class="container">

		<div class="row">
			<div class="col-sm-8 esquerda">	
				<big> Orçamento Nº <?php echo $id ?>  </big>
			</div>
			<div class="col-sm-4 direita" align="right">	
				<big> <small> Data: <?php echo $data_hoje; ?></small> </big>
			</div>
		</div>


		<hr>



		<div class="row">
			<div class="col-sm-12">
				<p class="fonte13"> <b> Dados do Cliente </b> </p>
			</div>
		</div>

		<div class="row">
			<div class="esquerda">
				<div class="col-sm-6">
					<p class="fonte13">  Nome: <?php echo $nome_cli; ?> </p>

					<p class="fonte13">  Email: <?php echo $email_cli; ?> </p>

					<p class="fonte13">  Endereço: <?php echo $endereco_cli; ?> </p>
				</div>
				
			</div>

			<div class="direita">
				<div class="col-sm-6">
					<p class="fonte13">  Telefone: <?php echo $telefone_cli; ?> </p>
					<p class="fonte13">  CPF: <?php echo $cpf_cliente; ?> </p>
					<p class="fonte13"> &nbsp;&nbsp;  </p>
				</div>
			</div>
		</div>



		<hr>


		<div class="row">
			<div class="col-sm-12">
				<p class="fonte13"> <b> Dados do Veículo </b> </p>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="esquerda_float margem-direita">
					<p class="fonte13 ">  Marca / Modelo: <?php echo $marca; ?> </p>
				</div>
				<div class="esquerda_float margem-direita">
					<p class="fonte13">  Placa: <?php echo $placa; ?> Cor: <?php echo $cor ?> </p>
				</div>
				<div class="">
					<p class="fonte13">  Ano: <?php echo $ano ?> KM: <?php echo $km ?> </p>

				</div>


			</div>
		</div>

		
				<div class="">
					<p class="fonte13">  Observações: <?php echo $obs; ?> </p>

				</div>

		<hr>



		<div class="row ">
			<div class="col-sm-12">
				<p style="font-size:14px"> <b> Laudo do Mecânico </b> </p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">	
				
					<?php 
						$query_s = $pdo->query("SELECT * FROM orc_serv where orcamento = '$id' ");
						$res_s = $query_s->fetchAll(PDO::FETCH_ASSOC);
						if(@count($res_s) == 0){
							$nome_serv = "Não Lançado!";
							echo '<p style="font-size:13px"> <b> Serviço: </b>'; 
							echo $nome_serv;
							
						}else if(@count($res_s) == 1){
							$serv = $res_s[0]['servico'];
							echo '<p style="font-size:13px"> <b>Tipo de Serviço: </b>'; 
							
						$query_ser = $pdo->query("SELECT * FROM servicos where id = '$serv' ");
						$res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
						$nome_serv = $res_ser[0]['nome'];
						echo $nome_serv;

						}else{
							echo '<p style="font-size:13px"> <b> '.count($res_s).' Serviços: </b>'; 
							
							for ($i=0; $i < @count($res_s); $i++) { 
							foreach ($res_s[$i] as $key => $value) {
							}

							$serv = $res_s[$i]['servico'];
							
						$query_ser = $pdo->query("SELECT * FROM servicos where id = '$serv' ");
						$res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
						$nome_serv = $res_ser[0]['nome'];


						if($i + 1 == @count($res_s)){
							echo $nome_serv;
						}else{
							echo $nome_serv .', ';
						}
						

						}
							
						}
						
					 ?> 

					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php if($garantia > 0){ ?>
						<b>Garantia: </b> <?php echo $garantia ?> Dias <?php } ?> </p>
						<p style="font-size:13px">  <?php echo $descricao; ?>  </p>
					</div>


				</div>





				<?php 
				$total_ser = 0;
				$valor_ser = 0;
				$valor_ser_f = 0;
				$total_ser_f = 0;
				$total_pagar_ser = 0;
				$total_pagar_ser_f = 0;
				$query = $pdo->query("SELECT * FROM orc_serv where orcamento = '$id' ");
				$res = $query->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res) > 0){
					?>
					<small>
					<table class='table' width='100%'  cellspacing='0' cellpadding='3'>
						<tr bgcolor='#f9f9f9' >
							<td> <b>Serviço</b> </td>
							<td> <b>Valor</b> </td>
							
						</tr>
						<?php 


						for ($i=0; $i < @count($res); $i++) { 
							foreach ($res[$i] as $key => $value) {
							}
							$serv = $res[$i]['servico'];

							$query_ser = $pdo->query("SELECT * FROM servicos where id = '$serv' ");
							$res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
							$nome_ser = $res_ser[0]['nome'];
							$valor_ser = $res_ser[0]['valor'];
							$id_ser = $res_ser[0]['id'];

							$total_ser = $valor_ser + $total_ser;
							$total_pagar_ser = $total_ser + $valor_ser;

							$valor_ser_f = number_format($valor_ser, 2, ',', '.');
							$total_ser_f = number_format($total_ser, 2, ',', '.');
							$total_pagar_ser_f = number_format($total_pagar_ser, 2, ',', '.');

							?>

							<tr>
								<td> <?php echo $nome_ser; ?> </td>
								<td>R$ <?php echo $valor_ser_f; ?> </td>
								

							</tr>

						<?php } ?>

					</table>
				</small>
				<?php }else{
					$total_pagar = $valor_ser;
					$total_pagar_ser_f = number_format($total_pagar_ser, 2, ',', '.');

				} ?>

				<hr>




				<?php 
				$total_prod = 0;
				$valor_prod_f = 0;
				$total_prod_f = 0;
				$total_pagar_f = 0;
				$query = $pdo->query("SELECT * FROM orc_prod where orcamento = '$id' ");
				$res = $query->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res) > 0){
					?>
					<small>
					<table class='table' width='100%'  cellspacing='0' cellpadding='3'>
						<tr bgcolor='#f9f9f9' >
							<td> <b>Peça / Produto</b> </td>
							<td> <b>Valor</b> </td>
							<td> <b> Quantidade</b> </td>

						</tr>
						<?php 


						for ($i=0; $i < @count($res); $i++) { 
							foreach ($res[$i] as $key => $value) {
							}
							$prod = $res[$i]['produto'];

							$query_pro = $pdo->query("SELECT * FROM produtos where id = '$prod' ");
							$res_pro = $query_pro->fetchAll(PDO::FETCH_ASSOC);
							$nome_prod = $res_pro[0]['nome'];
							$valor_prod = $res_pro[0]['valor_venda'];
							$id_prd = $res_pro[0]['id'];

							$total_prod = $valor_prod + $total_prod;
							$total_pagar = $total_prod + $valor_orc;

							$valor_prod_f = number_format($valor_prod, 2, ',', '.');
							$total_prod_f = number_format($total_prod, 2, ',', '.');
							$total_pagar_f = number_format($total_pagar, 2, ',', '.');

							?>

							<tr>
								<td> <?php echo $nome_prod; ?> </td>
								<td>R$ <?php echo $valor_prod_f; ?> </td>
								<td> 1 </td>

							</tr>

						<?php } ?>

					</table>
				</small>
				<?php }else{
					$total_pagar = $valor_orc;
					$total_pagar_f = number_format($total_pagar, 2, ',', '.');

				} 

				$total_pgto = $total_prod + $total_ser + $valor_orc;
				$total_pgto_f = number_format($total_pgto, 2, ',', '.');
				?>

				<hr>
				




				<div class="row">
					<div class="col-md-6" style="width:50%; float:left;">	
						<p style="font-size:13px">  <b>Valor Serviços: </b> R$ <?php echo number_format($total_ser, 2, ',', '.') ?> </p>
						<p style="font-size:13px">  <b>Valor Peças / Produtos: </b> R$ <?php echo number_format($total_prod, 2, ',', '.') ?> </p>
						<p style="font-size:13px">  <b>Valor Mão de Obra: </b> R$ <?php echo $valor_orc_f; ?> </p>
						<p style="font-size:13px">  <b>Mecânico: </b> <?php echo $nome_mecanico; ?>  </p>

					</div>
					<div class="col-md-4 areaTotal" align="right" style="width:40%; float:right;">	

						<p class="pgto" style="font-size:16px">  <b>Total a Pagar: </b> R$ <?php echo $total_pgto_f; ?>  </p>
						<p class="pgto" style="font-size:12px">  Previsão de Entrega: <?php echo $data_entrega; ?> <br> 
							<?php if($desconto_orc == 'Sim'){?>
								Desconto de <?php echo $valor_desconto ?>% para pagamento á vista! <?php } ?> </p>
								<p class="pgto" style="font-size:12px">  Orçamento válido até: <?php echo date('d/m/Y', strtotime("+$validade_orcamento_dias days",strtotime($data_orc)));  ?>  </p>
							</div>


						</div>



						<br>


						


					</div>


				<div class="footer">
		<p style="font-size:14px" align="center"><?php echo $rodape_relatorios ?></p> 
	</div>




				</body>
				</html>
