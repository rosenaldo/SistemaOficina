<?php 
@session_start();
if(@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'admin'){
	echo "<script language='javascript'> window.location='../index.php' </script>";
}

$pag = "plano_manutencao";
require_once("../conexao.php"); 

$funcao = @$_GET['funcao'];
$varios_serv = '';
?>
<div class="row mt-4 mb-4">
    <a type="button" class="btn-secondary btn-sm ml-3 d-none d-md-block"
        href="index.php?pag=<?php echo $pag ?>&funcao=novo">Novo PCM</a>
    <a type="button" class="btn-primary btn-sm ml-3 d-block d-sm-none"
        href="index.php?pag=<?php echo $pag ?>&funcao=novo">+</a>

</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Veículo</th>
                        <th>Mecânico</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php 

					$query = $pdo->query("SELECT * FROM pcm  order by id desc ");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					
					for ($i=0; $i < @count($res); $i++) { 
						foreach ($res[$i] as $key => $value) {
						}
						$cliente = $res[$i]['cliente'];
						$veiculo = $res[$i]['veiculo'];
						$observacao = $res[$i]['observacao'];
						$servico = $res[$i]['servico'];
						$data = $res[$i]['data'];
						$mecanico = $res[$i]['mecanico'];
						$id = $res[$i]['id'];

						$data = implode('/', array_reverse(explode('-', $data)));


						$query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_cli = $res_cat[0]['nome'];
						$email_cli = $res_cat[0]['email'];

						$query_cat = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$modelo = $res_cat[0]['modelo'];
						$marca = $res_cat[0]['marca'];

						$query_cat = $pdo->query("SELECT * FROM pcm_preventiva where pcm = '$id' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						if(@count($res_cat) == 0){
							$nome_serv = "Não Lançado!";
							$varios_serv = 'Não';
						}else if(@count($res_cat) == 1){
							$serv = $res_cat[0]['servico'];
							$varios_serv = 'Não';

						$query_ser = $pdo->query("SELECT * FROM tipo_pcm where id = '$serv' ");
						$res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
						$nome_serv = $res_ser[0]['descricao'];

						}else if(@count($res_cat) > 1){
							$nome_serv = @count($res_cat) . ' Serviços';
							$varios_serv = 'Sim';
						}
						

						$query_cat = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico' ");
						$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
						$nome_mecanico = $res_cat[0]['nome'];

						?>

                    <tr>
                        <td><?php echo $nome_cli ?></td>
                        <td><?php echo $marca .' '.$modelo ?></td>

                        <td><?php echo $nome_mecanico ?></td>

                        <td>
                            <a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>"
                                class='text-primary mr-1' title='Editar Dados'><i class='far fa-edit'></i></a>
                            <a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>"
                                class='text-danger mr-1' title='Excluir Registro'><i class='far fa-trash-alt'></i></a>

                            <a href="index.php?pag=<?php echo $pag ?>&funcao=preventiva&id=<?php echo $id ?>"
                                class='text-secondary mr-1' title='Manutenção Preventiva'><i
                                    class='fas fa-check-circle'></i></a>

                            <a href="index.php?pag=<?php echo $pag ?>&funcao=corretiva&id=<?php echo $id ?>"
                                class='text-secondary mr-1' title='Manutenção Corretiva'><i
                                    class='fas fa-wrench'></i></a>

                            <a href="index.php?pag=<?php echo $pag ?>&funcao=preditiva&id=<?php echo $id ?>"
                                class='text-secondary mr-1' title='Manutenção Preditiva'><i
                                    class='fas fa-search'></i></a>

                            <a href="rel/rel_pcm.php?id=<?php echo $id ?>&email=<?php echo $email_cli ?>"
                                target="_blank" class='text-info mr-1' title='Imprimir Planejamento'><i
                                    class='far fa-file-alt'></i></a>

                        </td>
                    </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <?php 
				if (@$_GET['funcao'] == 'editar') {
					$titulo = "Editar Registro";
					$id2 = $_GET['id'];

					$query = $pdo->query("SELECT * FROM pcm where id = '$id2' ");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					$cliente2 = $res[0]['cliente'];
					$veiculo2 = $res[0]['veiculo'];
					$descricao2 = $res[0]['descricao'];
					$servico2 = $res[0]['servico'];
					$data2 = $res[0]['data'];
					$mecanico2 = $res[0]['mecanico'];

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

                    <div class="row">
                        <div class="col-md-4 d-none">
                            <div class="form-group">
                                <label>CPF Cliente</label>
                                <div class="row">
                                    <div class="col-sm-9">
                                        <input value="<?php echo @$cliente2 ?>" type="text" class="form-control"
                                            id="cpf" name="cliente" placeholder="CPF do Cliente">
                                    </div>
                                    <div class="col-sm-3">
                                        <a href="" name="btn-buscar" id="btn-buscar"
                                            class="btn btn-primary text-light"><i class="fas fa-search"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Cliente</label>
                                <select name="cli" class="form-control sel2" id="cli" style="width:100%">
                                    <option value="">Selecione um Cliente</option>
                                    <?php 

									$query = $pdo->query("SELECT * FROM clientes order by nome asc ");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									
									for ($i=0; $i < @count($res); $i++) { 
										foreach ($res[$i] as $key => $value) {
										}
										$nome_reg = $res[$i]['nome'];
										$id_reg = $res[$i]['cpf'];
										?>
                                    <option <?php if(@$cliente2 == $id_reg){ ?> selected <?php } ?>
                                        value="<?php echo $id_reg ?>"><?php echo $nome_reg ?> - <?php echo $id_reg ?>
                                    </option>
                                    <?php } ?>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Veículo</label>
                                <div id="div-veiculo">

                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label>Observações do Veículo</label>
                        <textarea type="text" class="form-control" id="descricao"
                            name="descricao"><?php echo @$obs2 ?></textarea>
                    </div>

                    <small>
                        <div id="mensagem">

                        </div>
                    </small>

                </div>

                <div class="modal-footer">

                    <input value="<?php echo @$_GET['id'] ?>" type="hidden" name="txtid2" id="txtid2">
                    <input value="<?php echo @$placa2 ?>" type="hidden" name="antigo" id="antigo">

                    <button type="button" id="btn-fechar" class="btn btn-secondary"
                        data-dismiss="modal">Cancelar</button>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    id="btn-cancelar-excluir">Cancelar</button>
                <form method="post">

                    <input type="hidden" id="id" name="id" value="<?php echo @$_GET['id'] ?>" required>

                    <button type="button" id="btn-deletar" name="btn-deletar" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-preventiva" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Selecionar Planejamento de Manutenção - <a
                        href="index.php?pag=<?php echo $pag ?>&funcao=detalhesServ&id=<?php echo $_GET['id'] ?>"
                        class="text-dark">Preventiva</a></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="card shadow mb-4">

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php 

									$query = $pdo->query("SELECT * FROM tipo_pcm order by descricao asc ");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									
									for ($i=0; $i < @count($res); $i++) { 
										foreach ($res[$i] as $key => $value) {
										}
										$nome = $res[$i]['descricao'];
										$id_serv = $res[$i]['id'];

										?>

                                    <tr>
                                        <td><?php echo $nome ?></td>
                                        <td>
                                            <a href="index.php?pag=<?php echo $pag ?>&funcao=servicos&funcao2=adicionarServ&id_serv=<?php echo $id_serv ?>&id=<?php echo @$_GET['id'] ?>"
                                                class='text-success mr-1' title='Selecionar Planejamento'><i
                                                    class='fas fa-check'></i></a>

                                        </td>
                                    </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<?php 
$id_orc = $_GET['id'];
$tem_preventiva = false;

$query = $pdo->query("SELECT * FROM pcm_preventiva WHERE pcm = '$id_orc'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if (count($res) > 0) {
	$tem_preventiva = true;
?>

<h6 class="text-primary mt-3">PCM Preventiva</h6>

<?php
for ($i = 0; $i < count($res); $i++) { 
	$serv = $res[$i]['servico'];

	$query_pro = $pdo->query("SELECT * FROM tipo_pcm WHERE id = '$serv'");
	$res_pro = $query_pro->fetchAll(PDO::FETCH_ASSOC);
	$nome_prod = $res_pro[0]['descricao'];
	$id_prd = $res_pro[0]['id'];

	$id_pcm_preventiva = $res[$i]['id']; // ID da tabela pcm_preventiva

	// Buscar observação apenas pelo ID da pcm_preventiva
	$valor_observacao = '';
	try {
		$query_obs = $pdo->prepare("SELECT observacao FROM pcm_preventiva WHERE id = :id");
		$query_obs->execute([
			':id' => $id_pcm_preventiva
		]);
		$res_obs = $query_obs->fetch(PDO::FETCH_ASSOC);
		if ($res_obs) {
			$valor_observacao = htmlspecialchars($res_obs['observacao']);
		}
	} catch (Exception $e) {
		// Log de erro se necessário
	}
?>

<div class="mb-2">
    <span><small><i><?php echo $nome_prod ?></i></small></span>

    <!-- Campo de entrada de observação jose-->
    <input type="text" name="obs_preventiva_<?php echo $id_prd ?>" id="obs_preventiva_<?php echo $id_prd ?>"
        class="form-control form-control-sm d-inline-block obs-preventiva" style="width: 200px;"
        placeholder="Ex: 5 litros" data-tipo-pcm="preventiva" data-id="<?php echo $id_prd ?>"
        data-id-pcm="<?php echo $id_orc ?>" value="<?php echo $valor_observacao ?>"
        onblur="enviarPreventiva(<?php echo $id_prd ?>, <?php echo $id_orc ?>)">

    <!-- Botão de exclusão -->
    <a
        href="index.php?pag=<?php echo $pag ?>&funcao=servicos&funcao2=adicionarServ2&id_serv=<?php echo $id_prd ?>&id=<?php echo @$_GET['id'] ?>&funcao3=excluirServ">
        <i class='far fa-trash-alt ml-1 text-danger'></i>
    </a>

    <!-- Separador -->
    <br><span class="text-secondary">---------------------------------------------------------------</span>
</div>
<?php } ?>
<?php } ?>

<div class="modal" id="modal-corretiva" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Selecionar Planejamento de Manutenção - <a
                        href="index.php?pag=<?php echo $pag ?>&funcao=detalhesServ&id=<?php echo $_GET['id'] ?>"
                        class="text-dark">Corretiva</a></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="card shadow mb-4">

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php 

									$query = $pdo->query("SELECT * FROM tipo_pcm order by descricao asc ");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									
									for ($i=0; $i < @count($res); $i++) { 
										foreach ($res[$i] as $key => $value) {
										}
										$nome = $res[$i]['descricao'];
										$id_serv = $res[$i]['id'];

										?>

                                    <tr>
                                        <td><?php echo $nome ?></td>

                                        <td>
                                            <a href="index.php?pag=<?php echo $pag ?>&funcao=servicos&funcao2=adicionarServ2&id_serv=<?php echo $id_serv ?>&id=<?php echo @$_GET['id'] ?>"
                                                class='text-success mr-1' title='Selecionar Planejamento'><i
                                                    class='fas fa-check'></i></a>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>



<?php 
$id_orc = $_GET['id'];
$tem_corretiva = false;

$query = $pdo->query("SELECT * FROM pcm_corretiva WHERE pcm = '$id_orc'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if (count($res) > 0) {
	$tem_corretiva = true;
?>

<h6 class="text-primary mt-3">PCM Corretiva</h6>

<?php
for ($i = 0; $i < count($res); $i++) { 
	$serv = $res[$i]['servico'];

	$query_pro = $pdo->query("SELECT * FROM tipo_pcm WHERE id = '$serv'");
	$res_pro = $query_pro->fetchAll(PDO::FETCH_ASSOC);
	$nome_prod = $res_pro[0]['descricao'];
	$id_prd = $res_pro[0]['id'];

	$id_pcm_corretiva = $res[$i]['id']; // ID da tabela pcm_preventiva

	// Buscar observação apenas pelo ID da pcm_preventiva
	$valor_observacao = '';
	try {
		$query_obs = $pdo->prepare("SELECT observacao FROM pcm_corretiva WHERE id = :id");
		$query_obs->execute([
			':id' => $id_pcm_corretiva
		]);
		$res_obs = $query_obs->fetch(PDO::FETCH_ASSOC);
		if ($res_obs) {
			$valor_observacao = htmlspecialchars($res_obs['observacao']);
		}
	} catch (Exception $e) {
		// Log de erro se necessário
	}
?>

<div class="mb-2">
    <span><small><i><?php echo $nome_prod ?></i></small></span>

    <!-- Campo de entrada para observações rosenaldo-->
    <input type="text" name="obs_corretiva_<?php echo $id_prd ?>" id="obs_corretiva_<?php echo $id_prd ?>"
        class="form-control form-control-sm d-inline-block obs-corretiva" style="width: 200px;"
        placeholder="Ex: 5 litros" data-tipo-pcm="corretiva" data-id="<?php echo $id_prd ?>"
        data-id-pcm="<?php echo $id_orc ?>" value="<?php echo $valor_observacao ?>"
        onblur="enviarCorretiva(<?php echo $id_prd ?>, <?php echo $id_orc ?>)">

    <!-- Botão de exclusão -->
    <a
        href="index.php?pag=<?php echo $pag ?>&funcao=servicos&funcao2=adicionarServ2&id_serv=<?php echo $id_prd ?>&id=<?php echo @$_GET['id'] ?>&funcao3=excluirServ2">
        <i class='far fa-trash-alt ml-1 text-danger'></i>
    </a>

    <!-- Separador -->
    <br><span class="text-secondary">---------------------------------------------------------------</span>
</div>
<?php } ?>
<?php } ?>

<div class="modal" id="modal-preditiva" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Selecionar Planejamento de Manutenção - <a
                        href="index.php?pag=<?php echo $pag ?>&funcao=detalhesServ&id=<?php echo $_GET['id'] ?>"
                        class="text-dark">Preditiva</a></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="card shadow mb-4">

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php 

									$query = $pdo->query("SELECT * FROM tipo_pcm order by descricao asc ");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									
									for ($i=0; $i < @count($res); $i++) { 
										foreach ($res[$i] as $key => $value) {
										}
										$nome = $res[$i]['descricao'];
										$id_serv = $res[$i]['id'];

										?>

                                    <tr>
                                        <td><?php echo $nome ?></td>

                                        <td>
                                            <a href="index.php?pag=<?php echo $pag ?>&funcao=servicos&funcao2=adicionarServ3&id_serv=<?php echo $id_serv ?>&id=<?php echo @$_GET['id'] ?>"
                                                class='text-success mr-1' title='Selecionar Planejamento'><i
                                                    class='fas fa-check'></i></a>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>



            </div>

        </div>
    </div>
</div>


<?php 
$id_orc = $_GET['id'];
$tem_preditiva = false;

$query = $pdo->query("SELECT * FROM pcm_preditiva WHERE pcm = '$id_orc'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if (count($res) > 0) {
	$tem_preditiva = true;
?>

<h6 class="text-primary mt-3">PCM Preditiva</h6>

<?php
	for ($i = 0; $i < count($res); $i++) { 
        $serv = $res[$i]['servico'];
    
        $query_pro = $pdo->query("SELECT * FROM tipo_pcm WHERE id = '$serv'");
        $res_pro = $query_pro->fetchAll(PDO::FETCH_ASSOC);
        $nome_prod = $res_pro[0]['descricao'];
        $id_prd = $res_pro[0]['id'];
    
        $id_pcm_preditiva = $res[$i]['id']; 
    
        
        $valor_observacao = '';
        try {
            $query_obs = $pdo->prepare("SELECT observacao FROM pcm_preditiva WHERE id = :id");
            $query_obs->execute([
                ':id' => $id_pcm_preditiva
            ]);
            $res_obs = $query_obs->fetch(PDO::FETCH_ASSOC);
            if ($res_obs) {
                $valor_observacao = htmlspecialchars($res_obs['observacao']);
            }
        } catch (Exception $e) {
            // Log de erro se necessário
        }
	?>
<div class="mb-2">
    <span><small><i><?php echo $nome_prod ?></i></small></span>

    <!-- Campo de entrada para observações -->
    <input type="text" name="obs_preditiva_<?php echo $id_prd ?>" id="obs_preditiva_<?php echo $id_prd ?>"
        class="form-control form-control-sm d-inline-block obs-preditiva" style="width: 200px;"
        placeholder="Ex: 5 litros" data-tipo-pcm="preditiva" data-id="<?php echo $id_prd ?>"
        data-id-pcm="<?php echo $id_orc ?>" value="<?php echo $valor_observacao ?>"
        onblur="enviarPreditiva(<?php echo $id_prd ?>, <?php echo $id_orc ?>)">

    <!-- Botão de exclusão -->
    <a
        href="index.php?pag=<?php echo $pag ?>&funcao=servicos&funcao2=adicionarServ2&id_serv=<?php echo $id_prd ?>&id=<?php echo @$_GET['id'] ?>&funcao3=excluirServ3">
        <i class='far fa-trash-alt ml-1 text-danger'></i>
    </a>

    <!-- Separador -->
    <br><span class="text-secondary">---------------------------------------------------------------</span>
</div>
<?php } ?>
<?php } ?>


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

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "produtos") {
	echo "<script>$('#modal-produtos').modal('show');</script>";
}


if (@$_GET["funcao"] != null && @$_GET["funcao"] == "preventiva") {
	echo "<script>$('#modal-preventiva').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "corretiva") {
	echo "<script>$('#modal-corretiva').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "preditiva") {
	echo "<script>$('#modal-preditiva').modal('show');</script>";
}


if (@$_GET["funcao2"] != null && @$_GET["funcao2"] == "adicionarServ") {
    $id_orc = $_GET['id'];
    $id_serv = $_GET['id_serv'];
    $obs = isset($_GET["obs"]) ? $_GET["obs"] : '';

    if (!isset($_GET["funcao3"])) {
        $query = $pdo->prepare("SELECT COUNT(*) FROM pcm_preventiva WHERE pcm = :pcm AND servico = :servico");
        $query->execute([
            ':pcm' => $id_orc,
            ':servico' => $id_serv
        ]);
        $existe = $query->fetchColumn();

        if ($existe == 0) {
            $stmt = $pdo->prepare("INSERT INTO pcm_preventiva (pcm, servico, observacao) VALUES (:pcm, :servico, :obs)");
            $stmt->execute([
                ':pcm' => $id_orc,
                ':servico' => $id_serv,
                ':obs' => $obs
            ]);

            $update = $pdo->prepare("UPDATE pcm SET servico = :servico WHERE id = :id");
            $update->execute([
                ':servico' => $id_serv,
                ':id' => $id_orc
            ]);
        } else {
            // Atualiza observação se já existir
            $updateObs = $pdo->prepare("UPDATE pcm_preventiva SET observacao = :obs WHERE pcm = :pcm AND servico = :servico");
            $updateObs->execute([
                ':obs' => $obs,
                ':pcm' => $id_orc,
                ':servico' => $id_serv
            ]);

            // echo "<script>alert('Serviço já existia. Observação atualizada.');</script>";
        }
    }

    echo "<script>window.location='index.php?pag=$pag&id=$id_orc&funcao=detalhesServ';</script>";
}


if (@$_GET["funcao2"] != null && @$_GET["funcao2"] == "adicionarServ2") {
    $id_orc = $_GET['id'];
    $id_serv = $_GET['id_serv'];
    $obs = isset($_GET["obs"]) ? $_GET["obs"] : '';

    if (!isset($_GET["funcao3"])) {
        $query = $pdo->prepare("SELECT COUNT(*) FROM pcm_corretiva WHERE pcm = :pcm AND servico = :servico");
        $query->execute([
            ':pcm' => $id_orc,
            ':servico' => $id_serv
        ]);
        $existe = $query->fetchColumn();

        if ($existe == 0) {
            // Inserir com observação
            $stmt = $pdo->prepare("INSERT INTO pcm_corretiva (pcm, servico, observacao) VALUES (:pcm, :servico, :obs)");
            $stmt->execute([
                ':pcm' => $id_orc,
                ':servico' => $id_serv,
                ':obs' => $obs
            ]);

            $update = $pdo->prepare("UPDATE pcm SET servico = :servico WHERE id = :id");
            $update->execute([
                ':servico' => $id_serv,
                ':id' => $id_orc
            ]);
        } else { 
            // Atualiza observação se já existir
            $updateObs = $pdo->prepare("UPDATE pcm_corretiva SET observacao = :obs WHERE pcm = :pcm AND servico = :servico");
            $updateObs->execute([
                ':obs' => $obs,
                ':pcm' => $id_orc,
                ':servico' => $id_serv
            ]);
        }
    }

    echo "<script>window.location='index.php?pag=$pag&id=$id_orc&funcao=detalhesServ2';</script>";
}

if (@$_GET["funcao2"] != null && @$_GET["funcao2"] == "adicionarServ3") {
    $id_orc = $_GET['id'];
    $id_serv = $_GET['id_serv'];
    $obs = isset($_GET["obs"]) ? $_GET["obs"] : '';

    if (!isset($_GET["funcao3"])) {
        $query = $pdo->prepare("SELECT COUNT(*) FROM pcm_preditiva WHERE pcm = :pcm AND servico = :servico");
        $query->execute([
            ':pcm' => $id_orc,
            ':servico' => $id_serv
        ]);
        $existe = $query->fetchColumn();

        if ($existe == 0) {
            // Inserir com observação
            $stmt = $pdo->prepare("INSERT INTO pcm_preditiva (pcm, servico, observacao) VALUES (:pcm, :servico, :obs)");
            $stmt->execute([
                ':pcm' => $id_orc,
                ':servico' => $id_serv,
                ':obs' => $obs
            ]);

            $update = $pdo->prepare("UPDATE pcm SET servico = :servico WHERE id = :id");
            $update->execute([
                ':servico' => $id_serv,
                ':id' => $id_orc
            ]);
        } else {
            // Atualiza observação se já existir
            $updateObs = $pdo->prepare("UPDATE pcm_preditiva SET observacao = :obs WHERE pcm = :pcm AND servico = :servico");
            $updateObs->execute([
                ':obs' => $obs,
                ':pcm' => $id_orc,
                ':servico' => $id_serv
            ]);
        }
    }

    echo "<script>window.location='index.php?pag=$pag&id=$id_orc&funcao=detalhesServ3';</script>";
}


if (@$_GET["funcao"] != null && @$_GET["funcao"] == "detalhes") {
	echo "<script>$('#modal-detalhes').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "detalhesServ") {
	echo "<script>$('#modal-detalhesServ').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "detalhesServ2") {
	echo "<script>$('#modal-detalhesServ2').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "detalhesServ3") {
	echo "<script>$('#modal-detalhesServ3').modal('show');</script>";
}



if (@$_GET["funcao3"] != null && @$_GET["funcao3"] == "excluir") {
	$id_orc = $_GET['id'];
	$id_prod = $_GET['id_prod'];
	$pdo->query("DELETE FROM orc_prod WHERE orcamento = '$id_orc' AND produto = '$id_prod'");
}


if (@$_GET["funcao3"] != null && @$_GET["funcao3"] == "excluirServ") {
	$id_orc = $_GET['id'];
	$id_serv = $_GET['id_serv'];
	$pdo->query("DELETE FROM pcm_preventiva WHERE pcm = '$id_orc' AND servico = '$id_serv'");
}

if (@$_GET["funcao3"] != null && @$_GET["funcao3"] == "excluirServ2") {
	$id_orc = $_GET['id'];
	$id_serv = $_GET['id_serv'];
	$pdo->query("DELETE FROM pcm_corretiva WHERE pcm = '$id_orc' AND servico = '$id_serv'");
}

if (@$_GET["funcao3"] != null && @$_GET["funcao3"] == "excluirServ3") {
	$id_orc = $_GET['id'];
	$id_serv = $_GET['id_serv'];
	$pdo->query("DELETE FROM pcm_preditiva WHERE pcm = '$id_orc' AND servico = '$id_serv'");
}

?>

<script>
function enviarPreventiva(idServ, idOrc) {
    const input = document.getElementById('obs_preventiva_' + idServ);
    const obs = input.value;
    const tipo = input.dataset.tipoPcm;
    const pag = "<?php echo $pag ?>";

    const xhr = new XMLHttpRequest();
    xhr.open('GET',
        `index.php?pag=${pag}&funcao=servicos&funcao2=adicionarServ&id_serv=${idServ}&id=${idOrc}&obs=${encodeURIComponent(obs)}`,
        true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            console.log("Preventiva enviada com sucesso");
        } else {
            console.error("Erro:", xhr.status);
        }
    };

    xhr.send();
}
</script>


<script>
function enviarCorretiva(idServ, idOrc) {
    const input = document.getElementById('obs_corretiva_' + idServ);
    const obs = input.value;
    const tipo = input.dataset.tipoPcm;
    const pag = "<?php echo $pag ?>";

    const xhr = new XMLHttpRequest();
    xhr.open('GET',
        `index.php?pag=${pag}&funcao=servicos&funcao2=adicionarServ2&id_serv=${idServ}&id=${idOrc}&obs=${encodeURIComponent(obs)}`,
        true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            console.log("Corretiva enviada com sucesso");
        } else {
            console.error("Erro:", xhr.status);
        }
    };

    xhr.send();
}
</script>

<script>
function enviarPreditiva(idServ, idOrc) {
    const input = document.getElementById('obs_preditiva_' + idServ);
    const obs = input.value;
    const tipo = input.dataset.tipoPcm;
    const pag = "<?php echo $pag ?>";

    const xhr = new XMLHttpRequest();
    xhr.open('GET',
        `index.php?pag=${pag}&funcao=servicos&funcao2=adicionarServ3&id_serv=${idServ}&id=${idOrc}&obs=${encodeURIComponent(obs)}`,
        true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            console.log("Preditiva enviada com sucesso");
        } else {
            console.error("Erro:", xhr.status);
        }
    };

    xhr.send();
}
</script>


<!--AJAX PARA INSERÇÃO E EDIÇÃO DOS DADOS COM OU SEM IMAGEM -->
<script type="text/javascript">
$("#form").submit(function() {
    var pag = "<?=$pag?>";
    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: pag + "/inserir.php",
        type: 'POST',
        data: formData,

        success: function(mensagem) {
            $('#mensagem').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso!") {
                //$('#nome').val('');
                $('#btn-fechar').click();
                window.location = "index.php?pag=" + pag;
            } else {
                $('#mensagem').addClass('text-danger')
            }
            $('#mensagem').text(mensagem)
        },

        cache: false,
        contentType: false,
        processData: false,
        xhr: function() { // Custom XMLHttpRequest
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
                myXhr.upload.addEventListener('progress', function() {
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
$(document).ready(function() {
    var pag = "<?=$pag?>";
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
                $('#mensagem_excluir').text(mensagem)

            },

        })
    })
})
</script>

<!--AJAX PARA EXCLUSÃO DOS DADOS -->
<script type="text/javascript">
$(document).ready(function() {

    $('#btn-buscar').click(function(event) {
        event.preventDefault();

        var pag = "<?=$pag?>";
        var funcao = "<?=$funcao?>";

        if (funcao.trim() === 'editar') {
            var veiculo = "<?=$veiculo2?>";
            var cpf = "<?=$cliente2?>";
        } else {
            var veiculo = "";
            var cpf = document.getElementById('cpf').value;
        }

        $.ajax({
            url: pag + "/buscar-veiculo.php",
            method: "post",
            data: {
                cpf,
                veiculo
            },
            dataType: "html",
            success: function(result) {

                $('#div-veiculo').html(result);

            },

        })
    })
})
</script>

<script type="text/javascript">
$(document).ready(function() {


    var funcao = "<?=$funcao?>";

    if (funcao.trim() === 'editar') {
        $('#btn-buscar').click();
    } else {
        $('#div-veiculo').text('Busque pelo Cliente ao Lado');
    }


    $('#dataTable').dataTable({
        "ordering": false
    })

    $('#dataTable2').dataTable({
        "ordering": false
    })

});
</script>


<script type="text/javascript">
$('#cli').on('change', function(e) {
    var cpf = $(this).val();
    $('#cpf').val(cpf)
    $('#btn-buscar').click();
});
</script>




<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('.sel2').select2({
        placeholder: 'Selecione um Cliente',
        //dropdownParent: $('#modal-processo')
    });
});
</script>

<style type="text/css">
.select2-selection__rendered {
    line-height: 36px !important;
    font-size: 16px !important;
    color: #666666 !important;
}

.select2-selection {
    height: 36px !important;
    font-size: 16px !important;
    color: #666666 !important;
}
</style>