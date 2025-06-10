<?php
require_once("../../conexao.php");
@session_start();

$id = $_GET['id'];
require_once("data_formatada.php");

// DADOS DA ORDEM DE SERVIÇO
$query_orc = $pdo->query("SELECT * FROM os WHERE id = '$id'");
$res_orc = $query_orc->fetchAll(PDO::FETCH_ASSOC);
$cpf_cliente = $res_orc[0]['cliente'];
$veiculo = $res_orc[0]['veiculo'];
$descricao = $res_orc[0]['descricao'];
$obs = $res_orc[0]['obs'];
$valor_orc = $res_orc[0]['valor_mao_obra'];
$valor = $res_orc[0]['valor'];
$mecanico = $res_orc[0]['mecanico'];
$data_orc = $res_orc[0]['data'];
$data_entrega = $res_orc[0]['data_entrega'];
$tipo = $res_orc[0]['tipo'];
$id_orc = $res_orc[0]['id_orc'];
$concluido = $res_orc[0]['concluido'];
$garantia = $res_orc[0]['garantia'];

// Formatação de valores
$valor_orc_f = number_format($valor_orc, 2, ',', '.');
$valor_f = number_format($valor, 2, ',', '.');

// DADOS DO MECÂNICO
$query_mec = $pdo->query("SELECT * FROM mecanicos WHERE cpf = '$mecanico'");
$res_mec = $query_mec->fetchAll(PDO::FETCH_ASSOC);
$nome_mecanico = !empty($res_mec[0]['nome']) ? $res_mec[0]['nome'] : 'Não informado';

// DADOS DO CLIENTE
$query_cli = $pdo->query("SELECT * FROM clientes WHERE cpf = '$cpf_cliente'");
$res_cli = $query_cli->fetchAll(PDO::FETCH_ASSOC);

$nome_cli = !empty($res_cli[0]['nome']) ? $res_cli[0]['nome'] : 'Não informado';
$telefone_cli = !empty($res_cli[0]['telefone']) ? $res_cli[0]['telefone'] : 'Não informado';
$endereco_cli = !empty($res_cli[0]['endereco']) ? $res_cli[0]['endereco'] : 'Não informado';
$email_cli = !empty($res_cli[0]['email']) ? $res_cli[0]['email'] : 'Não informado';

// DADOS DO VEÍCULO
$query_vei = $pdo->query("SELECT * FROM veiculos WHERE id = '$veiculo'");
$res_vei = $query_vei->fetchAll(PDO::FETCH_ASSOC);

$marca_modelo = 'Não informado';
$placa = 'Não informado';
$cor = 'Não informado';
$ano = 'Não informado';
$km = 'Não informado';

if (!empty($res_vei[0])) {
	$marca = !empty($res_vei[0]['marca']) ? $res_vei[0]['marca'] : 'Não informado';
	$modelo = !empty($res_vei[0]['modelo']) ? $res_vei[0]['modelo'] : 'Não informado';
	$marca_modelo = $marca . ($marca != 'Não informado' && $modelo != 'Não informado' ? ' - ' : '') . $modelo;

	$placa = !empty($res_vei[0]['placa']) ? $res_vei[0]['placa'] : 'Não informado';
	$cor = !empty($res_vei[0]['cor']) ? $res_vei[0]['cor'] : 'Não informado';
	$ano = !empty($res_vei[0]['ano']) ? $res_vei[0]['ano'] : 'Não informado';
	$km = !empty($res_vei[0]['km']) ? $res_vei[0]['km'] : 'Não informado';
}



// Formatação de datas
$data_entrega = implode('/', array_reverse(explode('-', $data_entrega)));
$data = implode('/', array_reverse(explode('-', $data_orc)));
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Ordem de Serviço</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

	<style>
		@page {
			margin: 0;
			size: A4;
		}

		body {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			color: #333;
			line-height: 1.6;
		}

		.header {
			background-color: #f8f9fa;
			padding: 20px 0;
			border-bottom: 2px solid #e9ecef;
			margin-bottom: 30px;
		}

		.logo {
			max-width: 150px;
			height: auto;
		}

		.company-name {
			font-size: 24px;
			font-weight: 700;
			color: #2c3e50;
			margin-bottom: 5px;
		}

		.company-info {
			font-size: 14px;
			color: #7f8c8d;
		}

		.document-title {
			font-size: 22px;
			font-weight: 600;
			color: #2c3e50;
			margin-bottom: 20px;
			text-transform: uppercase;
		}

		.section-title {
			font-size: 16px;
			font-weight: 600;
			color: #2c3e50;
			margin: 20px 0 10px;
			border-bottom: 1px solid #e9ecef;
			padding-bottom: 5px;
		}

		.client-info,
		.vehicle-info {
			margin-bottom: 20px;
		}

		.info-label {
			font-weight: 600;
			color: #7f8c8d;
			display: inline-block;
			width: 120px;
		}

		.info-value {
			color: #2c3e50;
		}

		.service-table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 20px;
		}

		.service-table th {
			background-color: #f8f9fa;
			text-align: left;
			padding: 10px;
			border: 1px solid #dee2e6;
			font-weight: 600;
		}

		.service-table td {
			padding: 10px;
			border: 1px solid #dee2e6;
		}

		.total-box {
			background-color: #f8f9fa;
			padding: 15px;
			border-radius: 5px;
			border: 1px solid #dee2e6;
			margin-top: 20px;
		}

		.footer {
			margin-top: 40px;
			padding: 15px 0;
			border-top: 2px solid #e9ecef;
			text-align: center;
			font-size: 12px;
			color: #7f8c8d;
		}

		.observation {
			background-color: #f8f9fa;
			padding: 10px;
			border-radius: 5px;
			margin: 10px 0;
		}

		.signature-area {
			margin-top: 50px;
			padding-top: 20px;
			border-top: 1px dashed #7f8c8d;
		}

		.signature-line {
			width: 300px;
			border-top: 1px solid #7f8c8d;
			margin: 30px auto 0;
			text-align: center;
			padding-top: 5px;
			font-size: 12px;
		}

		.no-service {
			color: #7f8c8d;
			font-style: italic;
			padding: 10px;
		}

		.status-badge {
			padding: 5px 10px;
			border-radius: 20px;
			font-size: 12px;
			font-weight: 600;
		}

		.status-completed {
			background-color: #d4edda;
			color: #155724;
		}

		.status-pending {
			background-color: #fff3cd;
			color: #856404;
		}

		.total-amount {
			font-size: 18px;
			font-weight: 700;
			color: #2c3e50;
		}
	</style>
</head>

<body>
	<div class="container">
		<div class="header">
			<div class="row align-items-center">
				<div class="col-md-2">
					<!-- <img src="../../img/logo2.png" alt="Logo" class="logo"> -->
				</div>
				<div class="col-md-10">
					<div class="company-name"><?php echo strtoupper($nome_oficina) ?></div>
					<div class="company-info">
						<?php echo $endereco_oficina ?> | Tel: <?php echo $telefone_oficina ?>
					</div>
				</div>
			</div>
		</div>

		<div class="row mb-4">
			<div class="col-md-8">
				<h1 class="document-title">ORDEM DE SERVIÇO Nº <?php echo $id ?></h1>
			</div>
			<div class="col-md-4 text-end">
				<div class="text-muted">Data: <?php echo $data ?></div>
				<div class="mt-2">
					<span
						class="status-badge <?php echo $concluido == 'Sim' ? 'status-completed' : 'status-pending' ?>">
						<?php echo $concluido == 'Sim' ? 'CONCLUÍDO' : 'PENDENTE' ?>
					</span>
				</div>
			</div>
		</div>

		<div class="client-info">
			<h5 class="section-title">DADOS DO CLIENTE</h5>
			<div class="row">
				<div class="col-md-6">
					<p><span class="info-label">Nome:</span> <span class="info-value"><?php echo $nome_cli ?></span></p>
					<p><span class="info-label">Email:</span> <span class="info-value"><?php echo $email_cli ?></span>
					</p>
					<p><span class="info-label">Endereço:</span> <span
							class="info-value"><?php echo $endereco_cli ?></span></p>
				</div>
				<div class="col-md-6">
					<p><span class="info-label">Telefone:</span> <span
							class="info-value"><?php echo $telefone_cli ?></span></p>
					<p><span class="info-label">CPF/CNPJ:</span> <span
							class="info-value"><?php echo $cpf_cliente ?></span></p>
				</div>
			</div>
		</div>

		<div class="vehicle-info">
			<h5 class="section-title">DADOS DO VEÍCULO</h5>
			<div class="row">
				<div class="col-md-12">
					<p><span class="info-label">Marca/Modelo:</span> <span
							class="info-value"><?php echo $marca_modelo ?></span></p>
					<p><span class="info-label">Placa:</span> <span class="info-value"><?php echo $placa ?></span>
						<span class="info-label">Cor:</span> <span class="info-value"><?php echo $cor ?></span>
					</p>
					<p><span class="info-label">Ano:</span> <span class="info-value"><?php echo $ano ?></span>
						<span class="info-label">KM:</span> <span class="info-value"><?php echo $km ?></span>
					</p>
				</div>
			</div>
			<div class="observation">
				<p><strong>Observações:</strong> <?php echo $obs ?></p>
			</div>
		</div>

		<!-- SERVIÇOS -->
		<div class="service-section">
			<h5 class="section-title">SERVIÇOS</h5>
			<?php
			$query_s = $pdo->query("SELECT * FROM orc_serv WHERE orcamento = '$id_orc'");
			$res_s = $query_s->fetchAll(PDO::FETCH_ASSOC);

			if (count($res_s) > 0) {
			?>
				<table class="service-table">
					<thead>
						<tr>
							<th width="70%">Descrição</th>
							<th width="30%">Valor</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$total_servicos = 0;
						for ($i = 0; $i < count($res_s); $i++) {
							$serv = $res_s[$i]['servico'];

							$query_ser = $pdo->query("SELECT * FROM servicos WHERE id = '$serv'");
							$res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
							$nome_serv = $res_ser[0]['nome'];
							$valor_serv = $res_ser[0]['valor'];
							$total_servicos += $valor_serv;

							$valor_serv_f = number_format($valor_serv, 2, ',', '.');
						?>
							<tr>
								<td><?php echo $nome_serv ?></td>
								<td>R$ <?php echo $valor_serv_f ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } else { ?>
				<div class="no-service">Nenhum serviço cadastrado</div>
			<?php } ?>
		</div>

		<!-- PEÇAS/PRODUTOS -->
		<?php if ($tipo == 'Orçamento') { ?>
			<div class="service-section">
				<h5 class="section-title">PEÇAS/PRODUTOS</h5>
				<?php
				$query_p = $pdo->query("SELECT * FROM orc_prod WHERE orcamento = '$id_orc'");
				$res_p = $query_p->fetchAll(PDO::FETCH_ASSOC);

				if (count($res_p) > 0) {
				?>
					<table class="service-table">
						<thead>
							<tr>
								<th width="50%">Descrição</th>
								<th width="20%">Valor Unitário</th>
								<th width="10%">Qtd</th>
								<th width="20%">Total</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$total_produtos = 0;
							for ($i = 0; $i < count($res_p); $i++) {
								$prod = $res_p[$i]['produto'];

								$query_prod = $pdo->query("SELECT * FROM produtos WHERE id = '$prod'");
								$res_prod = $query_prod->fetchAll(PDO::FETCH_ASSOC);
								$nome_prod = $res_prod[0]['nome'];
								$valor_prod = $res_prod[0]['valor_venda'];
								$total_produtos += $valor_prod;

								$valor_prod_f = number_format($valor_prod, 2, ',', '.');
							?>
								<tr>
									<td><?php echo $nome_prod ?></td>
									<td>R$ <?php echo $valor_prod_f ?></td>
									<td>1</td>
									<td>R$ <?php echo $valor_prod_f ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				<?php } else { ?>
					<div class="no-service">Nenhum produto cadastrado</div>
				<?php } ?>
			</div>
		<?php } ?>

		<!-- RESUMO FINAL -->
		<div class="row mt-4">
			<div class="col-md-6">
				<div class="mb-3">
					<p><span class="info-label">Mecânico:</span> <span
							class="info-value"><?php echo $nome_mecanico ?></span></p>
				</div>
				<?php if ($garantia > 0) { ?>
					<div class="mb-3">
						<p><span class="info-label">Garantia:</span> <span class="info-value"><?php echo $garantia ?>
								dias</span></p>
					</div>
				<?php } ?>
				<div class="mb-3">
					<p><span class="info-label">Previsão de Entrega:</span> <span
							class="info-value"><?php echo $data_entrega ?></span></p>
				</div>
			</div>
			<div class="col-md-6">
				<div class="total-box">
					<div class="row">
						<div class="col-md-6">
							<p><span class="info-label">Serviços:</span></p>
							<?php if ($tipo == 'Orçamento') { ?>
								<p><span class="info-label">Produtos:</span></p>
							<?php } ?>
							<p><span class="info-label">Mão de Obra:</span></p>
						</div>
						<div class="col-md-6 text-end">
							<p>R$ <?php echo isset($total_servicos) && is_numeric($total_servicos) ? number_format($total_servicos, 2, ',', '.') : '0,00' ?></p>
							<?php if ($tipo == 'Orçamento') { ?>
								<p>R$ <?php echo isset($total_produtos) && is_numeric($total_produtos) ? number_format($total_produtos, 2, ',', '.') : '0,00' ?></p>
							<?php } ?>
							<p>R$ <?php echo $valor_orc_f ?></p>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-6">
							<p class="total-amount">Total:</p>
						</div>
						<div class="col-md-6 text-end">
							<p class="total-amount">
								R$ <?php
									$total_servicos = isset($total_servicos) && is_numeric($total_servicos) ? $total_servicos : 0;
									$total_produtos = isset($total_produtos) && is_numeric($total_produtos) ? $total_produtos : 0;
									$valor_orc = isset($valor_orc) && is_numeric($valor_orc) ? $valor_orc : 0;

									$total_geral = $total_servicos + ($tipo == 'Orçamento' ? $total_produtos : 0) + $valor_orc;

									echo number_format($total_geral, 2, ',', '.');
									?>
							</p>


						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="signature-area">
			<div class="row">
				<div class="col-md-6">
					<p><strong>Responsável Técnico:</strong> <?php echo $nome_mecanico ?></p>
				</div>
				<div class="col-md-6 text-end">
					<div class="signature-line">Assinatura do Cliente</div>
				</div>
			</div>
		</div>

		<div class="footer">
			<?php echo $rodape_relatorios ?>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>