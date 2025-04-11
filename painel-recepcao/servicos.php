<?php 
@session_start();
require_once("verificar_usuario.php");

$pag = "servicos";
require_once("../conexao.php"); 

$funcao = @$_GET['funcao'];

?>

<!-- DataTales Example -->
<div class="card shadow mb-4">

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Cliente</th>
						<th>Mecânico</th>
						<th>Valor Serviço</th>
						<th>Serviço</th>
						<th>Veículo</th>
						<th>Data Entrega</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>

					<?php 
					$total_da_os = 0;
					$query = $pdo->query("SELECT * FROM os order by concluido asc, data_entrega asc ");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					
					for ($i=0; $i < @count($res); $i++) { 
						foreach ($res[$i] as $key => $value) {
						}
						$cliente = $res[$i]['cliente'];
						$veiculo = $res[$i]['veiculo'];
						$descricao = $res[$i]['descricao'];
						$valor = $res[$i]['valor'];
						$valor_mao_obra = $res[$i]['valor_mao_obra'];
						
						$data = $res[$i]['data'];
						$data_entrega = $res[$i]['data_entrega'];
						$concluido = $res[$i]['concluido'];
						$mecanico = $res[$i]['mecanico'];
						$tipo = $res[$i]['tipo'];
						$id = $res[$i]['id'];
						$id_orc = $res[$i]['id_orc'];

						$data = implode('/', array_reverse(explode('-', $data)));
						$data_entrega = implode('/', array_reverse(explode('-', $data_entrega)));
						$valorF = number_format($valor, 2, ',', '.');
						$valor_mao_obraF = number_format($valor_mao_obra, 2, ',', '.');



						$query_s = $pdo->query("SELECT * FROM orc_serv where orcamento = '$id_orc' ");
						$res_s = $query_s->fetchAll(PDO::FETCH_ASSOC);
						if(@count($res_s) > 0){
						for ($i2=0; $i2 < @count($res_s); $i2++) { 
							foreach ($res_s[$i2] as $key => $value) {
							}
							$serv = $res_s[$i2]['servico'];

							$query_ser = $pdo->query("SELECT * FROM servicos where id = '$serv' ");
							$res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
							$nome_ser = $res_ser[0]['nome'];
							$valor_ser = $res_ser[0]['valor'];
							$id_ser = $res_ser[0]['id'];

						}
						}

						$total_da_os_F = number_format($valor, 2, ',', '.');



						$query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_cli = $res_cat[0]['nome'];
						$email_cli = $res_cat[0]['email'];


						$query_cat = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_mec = $res_cat[0]['nome'];
						

						$query_cat = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$modelo = $res_cat[0]['modelo'];
						$marca = $res_cat[0]['marca'];


						
						$query_cat = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_mecanico = $res_cat[0]['nome'];


						if($concluido == 'Sim'){
							$cor_pago = 'text-success';
						}else{
							$cor_pago = 'text-danger';
						}

						?>

						<tr>
							<td><i class='fas fa-square mr-1 <?php echo $cor_pago ?>'></i>
								<?php echo $nome_cli ?></td>
							<td><?php echo $nome_mec ?></td>
							<td>R$ <?php echo $total_da_os_F ?></td>
							<td><?php echo $descricao ?></td>
							<td><?php echo $marca .' '.$modelo ?></td>
							<td><?php echo $data_entrega ?></td>
							
							<td>
								
									<a href="../painel-mecanico/rel/rel_os.php?id=<?php echo $id ?>" target="_blank" class='text-info mr-1' title='Imprimir OS'><i class='far fa-file-alt'></i></a>

							</td>
						</tr>
					<?php } ?>





				</tbody>
			</table>
		</div>
	</div>
</div>





<script type="text/javascript">
	$(document).ready(function () {


		var funcao = "<?=$funcao?>";

		if(funcao.trim() === 'editar'){
			$('#btn-buscar').click();
		}else{
			$('#div-veiculo').text('Busque pelo CPF ao Lado');
		}


		$('#dataTable').dataTable({
			"ordering": false
		})

		$('#dataTable2').dataTable({
			"ordering": false
		})

	});
</script>



