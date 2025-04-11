<?php 
@session_start();
require_once("verificar_usuario.php");

$pag = "veiculos";
require_once("../conexao.php"); 



?>

<div class="row mt-4 mb-4">
	<a type="button" class="btn-secondary btn-sm ml-3 d-none d-md-block" href="index.php?pag=<?php echo $pag ?>&funcao=novo">Novo Veículo</a>
	<a type="button" class="btn-primary btn-sm ml-3 d-block d-sm-none" href="index.php?pag=<?php echo $pag ?>&funcao=novo">+</a>

</div>



<!-- DataTales Example -->
<div class="card shadow mb-4">

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Marca</th>
						<th>Modelo</th>
						<th>Placa</th>
						<th>Cor</th>
						<th>Cliente</th>
						<th>Data</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>

					<?php 

					$query = $pdo->query("SELECT * FROM veiculos order by id desc ");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					
					for ($i=0; $i < @count($res); $i++) { 
						foreach ($res[$i] as $key => $value) {
						}
						$marca = $res[$i]['marca'];
						$modelo = $res[$i]['modelo'];
						$cor = $res[$i]['cor'];
						$data = $res[$i]['data'];
						$placa = $res[$i]['placa'];
						$cliente = $res[$i]['cliente'];
						$id = $res[$i]['id'];

						$data = implode('/', array_reverse(explode('-', $data)));


						$query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_cli = $res_cat[0]['nome'];

						?>

						<tr>
							<td><?php echo $marca ?></td>
							<td><?php echo $modelo ?></td>
							<td><?php echo $placa ?></td>
							<td><?php echo $cor ?></td>
							<td><?php echo $nome_cli ?></td>
							<td><?php echo $data ?></td>

							<td>
								<a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>" class='text-primary mr-1' title='Editar Dados'><i class='far fa-edit'></i></a>
								<a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>" class='text-danger mr-1' title='Excluir Registro'><i class='far fa-trash-alt'></i></a>
								<a href="index.php?pag=<?php echo $pag ?>&funcao=dados&id=<?php echo $id ?>" class='text-info mr-1' title='Ver Dados do Veículo'><i class='fas fa-info-circle'></i></a>

								<a href="../rel/rel_os_veiculo.php?id=<?php echo $id ?>" target="_blank" class='text-success mr-1' title='Relatório Serviços Veículo'><i class='far fa-envelope'></i></a>

								<?php 
								$query2 = $pdo->query("SELECT * FROM os where veiculo = '$id' order by id asc");
					$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
					if(@count($res2) > 0){
								 ?>

								<a href="../rel/rel_ult_serv.php?id=<?php echo $id ?>" target="_blank" class='text-primary mr-1' title='Relatório Último Serviço'><i class='far fa-envelope-open'></i></a>

							<?php } ?>


							</td>
						</tr>
					<?php } ?>





				</tbody>
			</table>
		</div>
	</div>
</div>





<!-- Modal -->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<?php 
				if (@$_GET['funcao'] == 'editar') {
					$titulo = "Editar Registro";
					$id2 = $_GET['id'];

					$query = $pdo->query("SELECT * FROM veiculos where id = '$id2' ");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					$marca2 = $res[0]['marca'];
					$modelo2 = $res[0]['modelo'];
					$cor2 = $res[0]['cor'];
					$placa2 = $res[0]['placa'];
					$cliente2 = $res[0]['cliente'];
					$km2 = $res[0]['km'];
					$ano2 = $res[0]['ano'];



				} else {
					$titulo = "Inserir Registro";

				}


				?>

				<h5 class="modal-title" id="exampleModalLabel"><?php echo $titulo ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form" method="POST">
				<div class="modal-body">

					<div class="form-group">
								<label >Cliente</label>
								<select name="cliente" class="form-control sel2" id="cliente" style="width:100%">

									<?php 

									$query = $pdo->query("SELECT * FROM clientes order by nome asc ");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);

									for ($i=0; $i < @count($res); $i++) { 
										foreach ($res[$i] as $key => $value) {
										}
										$nome_reg = $res[$i]['nome'];
										$cpf_reg = $res[$i]['cpf'];
										?>									
										<option <?php if(@$cliente2 == $cpf_reg){ ?> selected <?php } ?> value="<?php echo $cpf_reg ?>"><?php echo $nome_reg ?> - <?php echo $cpf_reg ?></option>
									<?php } ?>
									
								</select>
							</div>
						

					<div class="row">

						<div class="col-md-6">
							<div class="form-group">
								<label >Marca</label>
								<input value="<?php echo @$marca2 ?>" type="text" class="form-control" id="marca" name="marca" placeholder="Marca">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label >Modelo</label>
								<input value="<?php echo @$modelo2 ?>" type="text" class="form-control" id="modelo" name="modelo" placeholder="Modelo">
							</div>
						</div>


						

					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label >Cor</label>
								<input value="<?php echo @$cor2 ?>" type="text" class="form-control" id="cor" name="cor" placeholder="Cor">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label >Placa</label>
								<input value="<?php echo @$placa2 ?>" type="text" class="form-control" id="placa" name="placa" placeholder="Placa">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label >Ano</label>
								<input value="<?php echo @$ano2 ?>" type="text" class="form-control" id="ano" name="ano" placeholder="Ano">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label >KM</label>
								<input value="<?php echo @$km2 ?>" type="text" class="form-control" id="km" name="km" placeholder="KM Rodada">
							</div>

						</div>
					</div>
					

					<small>
						<div id="mensagem">

						</div>
					</small> 

				</div>



				<div class="modal-footer">



					<input value="<?php echo @$_GET['id'] ?>" type="hidden" name="txtid2" id="txtid2">
					<input value="<?php echo @$placa2 ?>" type="hidden" name="antigo" id="antigo">
					

					<button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					<button type="submit" name="btn-salvar" id="btn-salvar" class="btn btn-primary">Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>






<div class="modal" id="modal-deletar" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Excluir Registro</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<p>Deseja realmente Excluir este Registro?</p>

				<div align="center" id="mensagem_excluir" class="">

				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancelar-excluir">Cancelar</button>
				<form method="post">

					<input type="hidden" id="id"  name="id" value="<?php echo @$_GET['id'] ?>" required>

					<button type="button" id="btn-deletar" name="btn-deletar" class="btn btn-danger">Excluir</button>
				</form>
			</div>
		</div>
	</div>
</div>





<div class="modal" id="modal-veiculo" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Dados do Veículo</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<?php 
				if (@$_GET['funcao'] == 'dados') {
					
					$id2 = $_GET['id'];

					$query = $pdo->query("SELECT * FROM veiculos where id = '$id2' ");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					$marca3 = $res[0]['marca'];
					$modelo3 = $res[0]['modelo'];
					$cor3 = $res[0]['cor'];
					$placa3= $res[0]['placa'];
					$cliente3 = $res[0]['cliente'];
					$km3 = $res[0]['km'];
					$ano3 = $res[0]['ano'];
					$data3 = $res[0]['data'];

					$data3 = implode('/', array_reverse(explode('-', $data3)));

					$query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente3' ");
					$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
					$nome_cli2 = $res_cat[0]['nome'];
					
				} 


				?>
				<span><b>Cliente: </b> <i><?php echo $nome_cli2 ?><br>
					<span><b>Marca: </b> <i><?php echo $marca3 ?></i><span class="ml-4"><b>Modelo: </b> <i><?php echo $modelo3 ?></i><br>

						<span><b>Cor: </b> <i><?php echo $cor3 ?></i> <span class="ml-4"><b>Placa: </b> <i><?php echo $placa3 ?></i><br>


							<span><b>Ano: </b> <i><?php echo $ano3 ?><span class="ml-4"><b>KM: </b> <i><?php echo $km3 ?></i>
								<span class="ml-4"><b>Data: </b> <i><?php echo $data3 ?></i><br>

								</div>

							</div>
						</div>
					</div>





					<?php 

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
						echo "<script>$('#modal-veiculo').modal('show');</script>";
					}

					?>




					<!--AJAX PARA INSERÇÃO E EDIÇÃO DOS DADOS COM OU SEM IMAGEM -->
					<script type="text/javascript">
						$("#form").submit(function () {
							var pag = "<?=$pag?>";
							event.preventDefault();
							var formData = new FormData(this);

							$.ajax({
								url: pag + "/inserir.php",
								type: 'POST',
								data: formData,

								success: function (mensagem) {
									$('#mensagem').removeClass()
									if (mensagem.trim() == "Salvo com Sucesso!") {
                    //$('#nome').val('');
                    $('#btn-fechar').click();
                    window.location = "index.php?pag="+pag;
                } else {
                	$('#mensagem').addClass('text-danger')
                }
                $('#mensagem').text(mensagem)
            },

            cache: false,
            contentType: false,
            processData: false,
            xhr: function () {  // Custom XMLHttpRequest
            	var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
                	myXhr.upload.addEventListener('progress', function () {
                		/* faz alguma coisa durante o progresso do upload */
                	}, false);
                }
                return myXhr;
            }
        });
						});
					</script>





					<!--AJAX PARA EXCLUSÃO DOS DADOS -->
					<script type="text/javascript">
						$(document).ready(function () {
							var pag = "<?=$pag?>";
							$('#btn-deletar').click(function (event) {
								event.preventDefault();
								$.ajax({
									url: pag + "/excluir.php",
									method: "post",
									data: $('form').serialize(),
									dataType: "text",
									success: function (mensagem) {

										if (mensagem.trim() === 'Excluído com Sucesso!') {
											$('#btn-cancelar-excluir').click();
											window.location = "index.php?pag=" + pag;
										}
										$('#mensagem_excluir').text(mensagem)

									},

								})
							})
						})
					</script>



					<!--SCRIPT PARA CARREGAR IMAGEM -->
					<script type="text/javascript">

						function carregarImg() {

							var target = document.getElementById('target');
							var file = document.querySelector("input[type=file]").files[0];
							var reader = new FileReader();

							reader.onloadend = function () {
								target.src = reader.result;
							};

							if (file) {
								reader.readAsDataURL(file);


							} else {
								target.src = "";
							}
						}

					</script>





					<script type="text/javascript">
						$(document).ready(function () {
							$('#dataTable').dataTable({
								"ordering": false
							})

						});
					</script>




<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
	$(document).ready(function() {
    $('.sel2').select2({
    	//dropdownParent: $('#modal-processo')
    });
});
</script>

<style type="text/css">
  .select2-selection__rendered {
    line-height: 36px !important;
    font-size:16px !important;
    color:#666666 !important;
  }

  .select2-selection {
    height: 36px !important;
    font-size:16px !important;
    color:#666666 !important;
  }
</style>  