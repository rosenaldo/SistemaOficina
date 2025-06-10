<?php
@session_start();
require_once("verificar_usuario.php");

$pag = "veiculos";
require_once("../conexao.php");
?>


<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">
			<i class="fas fa-car mr-2"></i>Veículos
		</h1>
		<a href="index.php?pag=<?php echo $pag ?>&funcao=novo"
			class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
			<i class="fas fa-plus fa-sm text-white-50"></i> Novo Veículo
		</a>
	</div>

	<!-- Card de Veículos -->
	<div class="card shadow mb-4">
		<div class="card-header py-3 bg-primary text-white">
			<h6 class="m-0 font-weight-bold">Todos os Veículos</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
					<thead class="thead-dark">
						<tr>
							<th>Marca</th>
							<th>Modelo</th>
							<th>Placa</th>
							<th>Cor</th>
							<th>Cliente</th>
							<th>Data</th>
							<th class="text-center">Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$query = $pdo->query("SELECT * FROM veiculos ORDER BY id DESC");
						$res = $query->fetchAll(PDO::FETCH_ASSOC);

						for ($i = 0; $i < @count($res); $i++) {
							$marca = $res[$i]['marca'];
							$modelo = $res[$i]['modelo'];
							$cor = $res[$i]['cor'];
							$data = $res[$i]['data'];
							$placa = $res[$i]['placa'];
							$cliente = $res[$i]['cliente'];
							$id = $res[$i]['id'];
							$km = $res[$i]['km'];
							$ano = $res[$i]['ano'];

							$data = implode('/', array_reverse(explode('-', $data)));

							$query_cat = $pdo->query("SELECT * FROM clientes WHERE cpf = '$cliente'");
							$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
							if (!empty($res_cat)) {
								$nome_cli = $res_cat[0]['nome'];
							} else {
								$nome_cli = 'Não informado';
							}

						?>
							<tr>
								<td><?php echo $marca ?></td>
								<td><?php echo $modelo ?></td>
								<td><?php echo $placa ?></td>
								<td><?php echo $cor ?></td>
								<td><?php echo $nome_cli ?></td>
								<td><?php echo $data ?></td>
								<td class="text-center">
									<div class="btn-group" role="group">
										<a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>"
											class="btn btn-sm btn-primary mr-1" title="Editar"><i class="far fa-edit"></i></a>
										<a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>"
											class="btn btn-sm btn-danger mr-1" title="Excluir"><i class="far fa-trash-alt"></i></a>
										<a href="index.php?pag=<?php echo $pag ?>&funcao=dados&id=<?php echo $id ?>"
											class="btn btn-sm btn-info mr-1" title="Ver Dados"><i class="fas fa-info-circle"></i></a>
										<a href="../rel/rel_os_veiculo.php?id=<?php echo $id ?>" target="_blank"
											class="btn btn-sm btn-success mr-1" title="Relatório de Serviços"><i class="far fa-file-alt"></i></a>
									</div>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal para Cadastro/Edição -->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="modalDadosLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<?php
				if (@$_GET['funcao'] == 'editar') {
					$titulo = "Editar Veículo";
					$id2 = $_GET['id'];

					$query = $pdo->query("SELECT * FROM veiculos WHERE id = '$id2'");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					$marca2 = $res[0]['marca'];
					$modelo2 = $res[0]['modelo'];
					$cor2 = $res[0]['cor'];
					$placa2 = $res[0]['placa'];
					$cliente2 = $res[0]['cliente'];
					$km2 = $res[0]['km'];
					$ano2 = $res[0]['ano'];
				} else {
					$titulo = "Novo Veículo";
				}
				?>
				<h5 class="modal-title" id="modalDadosLabel">
					<i class="fas fa-car mr-2"></i><?php echo $titulo ?>
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form" method="POST">
				<div class="modal-body">
					<div class="form-group">
						<label>Cliente</label>
						<select name="cliente" class="form-control select2" id="cliente" style="width:100%">
							<option value="">Selecione um Cliente</option>
							<?php
							$query = $pdo->query("SELECT * FROM clientes ORDER BY nome ASC");
							$res = $query->fetchAll(PDO::FETCH_ASSOC);

							for ($i = 0; $i < @count($res); $i++) {
								$nome_reg = $res[$i]['nome'];
								$cpf_reg = $res[$i]['cpf'];
							?>
								<option <?php if (@$cliente2 == $cpf_reg) { ?> selected <?php } ?> value="<?php echo $cpf_reg ?>">
									<?php echo $nome_reg ?> - <?php echo $cpf_reg ?>
								</option>
							<?php } ?>
						</select>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Marca</label>
								<input value="<?php echo @$marca2 ?>" type="text" class="form-control" id="marca" name="marca" placeholder="Marca">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Modelo</label>
								<input value="<?php echo @$modelo2 ?>" type="text" class="form-control" id="modelo" name="modelo" placeholder="Modelo">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Cor</label>
								<input value="<?php echo @$cor2 ?>" type="text" class="form-control" id="cor" name="cor" placeholder="Cor">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Placa</label>
								<input value="<?php echo @$placa2 ?>" type="text" class="form-control" id="placa" name="placa" placeholder="Placa">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Ano</label>
								<input value="<?php echo @$ano2 ?>" type="text" class="form-control" id="ano" name="ano" placeholder="Ano">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>KM</label>
								<input value="<?php echo @$km2 ?>" type="text" class="form-control" id="km" name="km" placeholder="KM Rodada">
							</div>
						</div>
					</div>

					<div id="mensagem" class="mt-3"></div>
				</div>
				<div class="modal-footer">
					<input value="<?php echo @$_GET['id'] ?>" type="hidden" name="txtid2" id="txtid2">
					<input value="<?php echo @$placa2 ?>" type="hidden" name="antigo" id="antigo">

					<button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					<button type="submit" id="btn-salvar" class="btn btn-primary">
						<i class="fas fa-save"></i> Salvar
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="modal-deletar" tabindex="-1" role="dialog" aria-labelledby="modal-deletarLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-danger text-white">
				<h5 class="modal-title" id="modal-deletarLabel">Excluir Veículo</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Tem certeza que deseja excluir este veículo permanentemente?</p>
				<div id="mensagem_excluir" class="alert"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancelar-excluir">Cancelar</button>
				<form method="post">
					<input type="hidden" id="id" name="id" value="<?php echo @$_GET['id'] ?>" required>
					<button type="button" id="btn-deletar" class="btn btn-danger">Confirmar Exclusão</button>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Modal de Detalhes do Veículo -->
<div class="modal fade" id="modal-dados" tabindex="-1" role="dialog" aria-labelledby="modal-dadosLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-info text-white">
				<h5 class="modal-title" id="modal-dadosLabel">Detalhes do Veículo</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php
				if (@$_GET['funcao'] == 'dados') {
					$id2 = $_GET['id'];

					$query = $pdo->query("SELECT * FROM veiculos WHERE id = '$id2'");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					$marca3 = $res[0]['marca'];
					$modelo3 = $res[0]['modelo'];
					$cor3 = $res[0]['cor'];
					$placa3 = $res[0]['placa'];
					$cliente3 = $res[0]['cliente'];
					$km3 = $res[0]['km'];
					$ano3 = $res[0]['ano'];
					$data3 = $res[0]['data'];

					$data3 = implode('/', array_reverse(explode('-', $data3)));

					$query_cat = $pdo->query("SELECT * FROM clientes WHERE cpf = '$cliente3'");
					$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
					$nome_cli2 = $res_cat[0]['nome'];
				}
				?>
				<div class="row mb-3">
					<div class="col-md-6">
						<strong>Cliente:</strong> <?php echo $nome_cli2 ?>
					</div>
					<div class="col-md-6">
						<strong>Placa:</strong> <?php echo $placa3 ?>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-md-6">
						<strong>Marca:</strong> <?php echo $marca3 ?>
					</div>
					<div class="col-md-6">
						<strong>Modelo:</strong> <?php echo $modelo3 ?>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-md-6">
						<strong>Cor:</strong> <?php echo $cor3 ?>
					</div>
					<div class="col-md-6">
						<strong>Ano:</strong> <?php echo $ano3 ?>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-md-6">
						<strong>KM:</strong> <?php echo $km3 ?>
					</div>
					<div class="col-md-6">
						<strong>Data de Cadastro:</strong> <?php echo $data3 ?>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>

<?php
// Abre modais conforme a função chamada
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "novo") {
	echo "<script>$('#modalDados').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "editar") {
	echo "<script>$('#modalDados').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "excluir") {
	echo "<script>$('#modal-deletar').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "dados") {
	echo "<script>$('#modal-dados').modal('show');</script>";
}
?>

<!-- Scripts JavaScript -->
<script type="text/javascript">
	// Formulário principal
	$("#form").submit(function() {
		var pag = "<?= $pag ?>";
		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: pag + "/inserir.php",
			type: 'POST',
			data: formData,
			success: function(mensagem) {
				$('#mensagem').removeClass()
				if (mensagem.trim() == "Salvo com Sucesso!") {
					$('#btn-fechar').click();
					window.location = "index.php?pag=" + pag;
				} else {
					$('#mensagem').addClass('alert alert-danger')
				}
				$('#mensagem').text(mensagem)
			},
			cache: false,
			contentType: false,
			processData: false,
			xhr: function() {
				var myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) {
					myXhr.upload.addEventListener('progress', function() {
						// Barra de progresso pode ser adicionada aqui
					}, false);
				}
				return myXhr;
			}
		});
	});

	// Exclusão de veículo
	$(document).ready(function() {
		var pag = "<?= $pag ?>";
		$('#btn-deletar').click(function(event) {
			event.preventDefault();
			$.ajax({
				url: pag + "/excluir.php",
				method: "post",
				data: $('form').serialize(),
				dataType: "text",
				success: function(mensagem) {
					if (mensagem.trim() === 'Excluído com Sucesso!') {
						$('#btn-cancelar-excluir').click();
						window.location = "index.php?pag=" + pag;
					}
					$('#mensagem_excluir').html('<div class="alert alert-' + (mensagem
							.trim() === 'Excluído com Sucesso!' ? 'success' : 'danger') + '">' +
						mensagem + '</div>');
				},
			})
		})
	});

	// Configurações da tabela
	$(document).ready(function() {
		$('#dataTable').dataTable({
			"ordering": false,
			"language": {
				"url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
			}
		});
	});

	// Select2
	$(document).ready(function() {
		$('.select2').select2({
			placeholder: 'Selecione um Cliente',
			width: '100%'
		});
	});
</script>

<!-- Estilos personalizados -->
<style>
	.select2-selection__rendered {
		line-height: 36px !important;
		font-size: 14px !important;
		color: #495057 !important;
	}

	.select2-selection {
		height: 38px !important;
		border: 1px solid #ced4da !important;
	}

	.select2-selection__arrow {
		height: 36px !important;
	}

	.tr-selecionada {
		background-color: #e6f7ff !important;
	}

	.linha-com-link {
		cursor: pointer;
		transition: background-color 0.3s;
	}

	.linha-com-link:hover {
		background-color: #f8f9fa;
	}

	.card-header {
		border-radius: 0.35rem 0.35rem 0 0 !important;
	}

	.table th {
		border-top: none;
	}

	.btn-group .btn {
		padding: 0.25rem 0.5rem;
		font-size: 0.875rem;
	}

	.modal-header {
		border-bottom: none;
	}

	.modal-footer {
		border-top: none;
	}

	.bg-primary {
		background-color: #7C3AED !important;
	}

	.bg-danger {
		background-color: #e74a3b !important;
	}

	.bg-info {
		background-color: #36b9cc !important;
	}

	.text-white {
		color: white !important;
	}

	.btn-sm {
		padding: 0.25rem 0.5rem;
		font-size: 0.875rem;
		line-height: 1.5;
		border-radius: 0.2rem;
	}
</style>