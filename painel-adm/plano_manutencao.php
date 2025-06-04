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
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-cogs mr-2"></i>Planejamento e Controle de Manutenção (PCM)
        </h1>
        <a href="index.php?pag=<?php echo $pag ?>&funcao=novo"
            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Novo PCM
        </a>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Planos Cadastrados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Cliente</th>
                            <th>Veículo</th>
                            <th>Placa</th>
                            <th>Mecânico</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = $pdo->query("SELECT * FROM pcm ORDER BY id DESC");
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

                            $query_cat = $pdo->query("SELECT * FROM clientes WHERE cpf = '$cliente'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $nome_cli = $res_cat[0]['nome'];
                            $email_cli = $res_cat[0]['email'];

                            $query_cat = $pdo->query("SELECT * FROM veiculos WHERE id = '$veiculo'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $modelo = $res_cat[0]['modelo'];
                            $marca = $res_cat[0]['marca'];
                            $placa = $res_cat[0]['placa'];

                            $query_cat = $pdo->query("SELECT * FROM pcm_preventiva WHERE pcm = '$id'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            if(@count($res_cat) == 0){
                                $nome_serv = "Não Lançado!";
                                $varios_serv = 'Não';
                            } else if(@count($res_cat) == 1){
                                $serv = $res_cat[0]['servico'];
                                $varios_serv = 'Não';

                                $query_ser = $pdo->query("SELECT * FROM tipo_pcm WHERE id = '$serv'");
                                $res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
                                $nome_serv = $res_ser[0]['descricao'];
                            } else if(@count($res_cat) > 1){
                                $nome_serv = @count($res_cat) . ' Serviços';
                                $varios_serv = 'Sim';
                            }
                            
                            $query_cat = $pdo->query("SELECT * FROM mecanicos WHERE cpf = '$mecanico'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $nome_mecanico = $res_cat[0]['nome'];
                        ?>
                        <tr class="linha-com-link" data-id-orc="<?= $id ?>"
                            data-url="index.php?pag=<?= $pag ?>&funcao=preventiv&id=<?= $id ?>">

                            <td><?php echo $nome_cli ?></td>
                            <td><?php echo $marca .' '.$modelo ?></td>
                            <td><?php echo $placa ?></td>
                            <td><?php echo $nome_mecanico ?></td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>"
                                        class="btn btn-sm btn-primary mr-1" title="Editar"><i
                                            class="far fa-edit"></i></a>
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>"
                                        class="btn btn-sm btn-danger mr-1" title="Excluir"><i
                                            class="far fa-trash-alt"></i></a>
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=preventiva&id=<?php echo $id ?>"
                                        class="btn btn-sm btn-info mr-1" title="Preventiva"><i
                                            class="fas fa-check-circle"></i></a>
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=corretiva&id=<?php echo $id ?>"
                                        class="btn btn-sm btn-warning mr-1" title="Corretiva"><i
                                            class="fas fa-wrench"></i></a>
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=preditiva&id=<?php echo $id ?>"
                                        class="btn btn-sm btn-secondary mr-1" title="Preditiva"><i
                                            class="fas fa-search"></i></a>
                                    <a href="rel/rel_pcm.php?id=<?php echo $id ?>&email=<?php echo $email_cli ?>"
                                        target="_blank" class="btn btn-sm btn-success" title="Imprimir"><i
                                            class="far fa-file-alt"></i></a>
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

<!-- Modal para cadastro/edição -->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="modalDadosLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <?php 
                if (@$_GET['funcao'] == 'editar') {
                    $titulo = "Editar PCM";
                    $id2 = $_GET['id'];

                    $query = $pdo->query("SELECT * FROM pcm WHERE id = '$id2'");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    $cliente2 = $res[0]['cliente'];
                    $veiculo2 = $res[0]['veiculo'];
                    $descricao2 = $res[0]['descricao'];
                    $servico2 = $res[0]['servico'];
                    $data2 = $res[0]['data'];
                    $mecanico2 = $res[0]['mecanico'];
                } else {
                    $titulo = "Novo PCM";
                }
                ?>
                <h5 class="modal-title" id="modalDadosLabel">
                    <i class="fas fa-cogs mr-2"></i><?php echo $titulo ?>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 d-none">
                            <div class="form-group">
                                <label>CPF Cliente</label>
                                <div class="input-group">
                                    <input value="<?php echo @$cliente2 ?>" type="text" class="form-control" id="cpf"
                                        name="cliente" placeholder="CPF do Cliente">
                                    <div class="input-group-append">
                                        <button type="button" name="btn-buscar" id="btn-buscar" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Cliente</label>
                                <select name="cli" class="form-control select2" id="cli" style="width:100%">
                                    <option value="">Selecione um Cliente</option>
                                    <?php 
                                    $query = $pdo->query("SELECT * FROM clientes ORDER BY nome ASC");
                                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    for ($i=0; $i < @count($res); $i++) { 
                                        $nome_reg = $res[$i]['nome'];
                                        $id_reg = $res[$i]['cpf'];
                                    ?>
                                    <option <?php if(@$cliente2 == $id_reg){ ?> selected <?php } ?>
                                        value="<?php echo $id_reg ?>">
                                        <?php echo $nome_reg ?> - <?php echo $id_reg ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Veículo</label>
                                <div id="div-veiculo" class="form-control">
                                    <?php if(@$funcao != 'editar') { ?>
                                    Selecione um cliente primeiro
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Observações do Veículo</label>
                        <textarea class="form-control" id="descricao" name="descricao"
                            rows="3"><?php echo @$obs2 ?></textarea>
                    </div>

                    <div id="mensagem" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <input value="<?php echo @$_GET['id'] ?>" type="hidden" name="txtid2" id="txtid2">
                    <input value="<?php echo @$placa2 ?>" type="hidden" name="antigo" id="antigo">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmação de exclusão -->
<div class="modal fade" id="modal-deletar" tabindex="-1" role="dialog" aria-labelledby="modal-deletarLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modal-deletarLabel">Excluir Registro</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este PCM permanentemente?</p>
                <div id="mensagem_excluir" class="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form method="post">
                    <input type="hidden" id="id" name="id" value="<?php echo @$_GET['id'] ?>" required>
                    <button type="button" id="btn-deletar" class="btn btn-danger">Confirmar Exclusão</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para seleção de manutenção preventiva -->
<div class="modal fade" id="modal-preventiva" tabindex="-1" role="dialog" aria-labelledby="modal-preventivaLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modal-preventivaLabel">Selecionar Manutenção Preventiva</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable2" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>Serviço</th>
                                <th width="120px">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query = $pdo->query("SELECT * FROM tipo_pcm ORDER BY descricao ASC");
                            $res = $query->fetchAll(PDO::FETCH_ASSOC);
                            
                            for ($i=0; $i < @count($res); $i++) { 
                                $nome = $res[$i]['descricao'];
                                $id_serv = $res[$i]['id'];
                            ?>
                            <tr>
                                <td><?php echo $nome ?></td>
                                <td class="text-center">
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=servicos&funcao2=adicionarServ&id_serv=<?php echo $id_serv ?>&id=<?php echo @$_GET['id'] ?>"
                                        class="btn btn-sm btn-success" title="Selecionar">
                                        <i class="fas fa-check"></i>
                                    </a>
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

<!-- Modal para seleção de manutenção corretiva -->
<div class="modal fade" id="modal-corretiva" tabindex="-1" role="dialog" aria-labelledby="modal-corretivaLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modal-corretivaLabel">Selecionar Manutenção Corretiva</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable2" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>Serviço</th>
                                <th width="120px">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query = $pdo->query("SELECT * FROM tipo_pcm ORDER BY descricao ASC");
                            $res = $query->fetchAll(PDO::FETCH_ASSOC);
                            
                            for ($i=0; $i < @count($res); $i++) { 
                                $nome = $res[$i]['descricao'];
                                $id_serv = $res[$i]['id'];
                            ?>
                            <tr>
                                <td><?php echo $nome ?></td>
                                <td class="text-center">
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=servicos&funcao2=adicionarServ2&id_serv=<?php echo $id_serv ?>&id=<?php echo @$_GET['id'] ?>"
                                        class="btn btn-sm btn-success" title="Selecionar">
                                        <i class="fas fa-check"></i>
                                    </a>
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

<!-- Modal para seleção de manutenção preditiva -->
<div class="modal fade" id="modal-preditiva" tabindex="-1" role="dialog" aria-labelledby="modal-preditivaLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="modal-preditivaLabel">Selecionar Manutenção Preditiva</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable2" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>Serviço</th>
                                <th width="120px">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query = $pdo->query("SELECT * FROM tipo_pcm ORDER BY descricao ASC");
                            $res = $query->fetchAll(PDO::FETCH_ASSOC);
                            
                            for ($i=0; $i < @count($res); $i++) { 
                                $nome = $res[$i]['descricao'];
                                $id_serv = $res[$i]['id'];
                            ?>
                            <tr>
                                <td><?php echo $nome ?></td>
                                <td class="text-center">
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=servicos&funcao2=adicionarServ3&id_serv=<?php echo $id_serv ?>&id=<?php echo @$_GET['id'] ?>"
                                        class="btn btn-sm btn-success" title="Selecionar">
                                        <i class="fas fa-check"></i>
                                    </a>
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

<!-- Exibição dos serviços selecionados -->
<?php 
$id_orc = $_GET['id'];

// Preventiva
$query = $pdo->query("SELECT * FROM pcm_preventiva WHERE pcm = '$id_orc'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0) {
?>
<div class="card border-left-info shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-info">Manutenção Preventiva</h6>
    </div>
    <div class="card-body">
        <?php
        for ($i = 0; $i < count($res); $i++) { 
            $serv = $res[$i]['servico'];
            $id_pcm_preventiva = $res[$i]['id'];
            
            $query_pro = $pdo->query("SELECT * FROM tipo_pcm WHERE id = '$serv'");
            $res_pro = $query_pro->fetchAll(PDO::FETCH_ASSOC);
            $nome_prod = $res_pro[0]['descricao'];
            $id_prd = $res_pro[0]['id'];
            
            $valor_observacao = '';
            try {
                $query_obs = $pdo->prepare("SELECT observacao FROM pcm_preventiva WHERE id = :id");
                $query_obs->execute([':id' => $id_pcm_preventiva]);
                $res_obs = $query_obs->fetch(PDO::FETCH_ASSOC);
                if ($res_obs) {
                    $valor_observacao = htmlspecialchars($res_obs['observacao']);
                }
            } catch (Exception $e) {
                // Log de erro se necessário
            }
        ?>
        <div class="mb-3">
            <div class="d-flex align-items-center">
                <span class="font-weight-bold"><?php echo $nome_prod ?></span>
                <div class="ml-3">
                    <input type="text" name="obs_preventiva_<?php echo $id_prd ?>"
                        id="obs_preventiva_<?php echo $id_prd ?>" class="form-control form-control-sm d-inline-block"
                        style="width: 200px;" placeholder="Observações" data-tipo-pcm="preventiva"
                        data-id="<?php echo $id_prd ?>" data-id-pcm="<?php echo $id_orc ?>"
                        value="<?php echo $valor_observacao ?>"
                        onblur="enviarPreventiva(<?php echo $id_prd ?>, <?php echo $id_orc ?>)">
                </div>
                <a href="index.php?pag=<?php echo $pag ?>&funcao=servicos&funcao2=adicionarServ2&id_serv=<?php echo $id_prd ?>&id=<?php echo @$_GET['id'] ?>&funcao3=excluirServ"
                    class="ml-2 text-danger">
                    <i class="far fa-trash-alt"></i>
                </a>
            </div>
            <hr class="mt-2">
        </div>
        <?php } ?>
    </div>
</div>
<?php } ?>

<!-- Corretiva -->
<?php 
$query = $pdo->query("SELECT * FROM pcm_corretiva WHERE pcm = '$id_orc'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0) {
?>
<div class="card border-left-warning shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-warning">Manutenção Corretiva</h6>
    </div>
    <div class="card-body">
        <?php
        for ($i = 0; $i < count($res); $i++) { 
            $serv = $res[$i]['servico'];
            $id_pcm_corretiva = $res[$i]['id'];
            
            $query_pro = $pdo->query("SELECT * FROM tipo_pcm WHERE id = '$serv'");
            $res_pro = $query_pro->fetchAll(PDO::FETCH_ASSOC);
            $nome_prod = $res_pro[0]['descricao'];
            $id_prd = $res_pro[0]['id'];
            
            $valor_observacao = '';
            try {
                $query_obs = $pdo->prepare("SELECT observacao FROM pcm_corretiva WHERE id = :id");
                $query_obs->execute([':id' => $id_pcm_corretiva]);
                $res_obs = $query_obs->fetch(PDO::FETCH_ASSOC);
                if ($res_obs) {
                    $valor_observacao = htmlspecialchars($res_obs['observacao']);
                }
            } catch (Exception $e) {
                // Log de erro se necessário
            }
        ?>
        <div class="mb-3">
            <div class="d-flex align-items-center">
                <span class="font-weight-bold"><?php echo $nome_prod ?></span>
                <div class="ml-3">
                    <input type="text" name="obs_corretiva_<?php echo $id_prd ?>"
                        id="obs_corretiva_<?php echo $id_prd ?>" class="form-control form-control-sm d-inline-block"
                        style="width: 200px;" placeholder="Observações" data-tipo-pcm="corretiva"
                        data-id="<?php echo $id_prd ?>" data-id-pcm="<?php echo $id_orc ?>"
                        value="<?php echo $valor_observacao ?>"
                        onblur="enviarCorretiva(<?php echo $id_prd ?>, <?php echo $id_orc ?>)">
                </div>
                <a href="index.php?pag=<?php echo $pag ?>&funcao=servicos&funcao2=adicionarServ2&id_serv=<?php echo $id_prd ?>&id=<?php echo @$_GET['id'] ?>&funcao3=excluirServ2"
                    class="ml-2 text-danger">
                    <i class="far fa-trash-alt"></i>
                </a>
            </div>
            <hr class="mt-2">
        </div>
        <?php } ?>
    </div>
</div>
<?php } ?>

<!-- Preditiva -->
<?php 
$query = $pdo->query("SELECT * FROM pcm_preditiva WHERE pcm = '$id_orc'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0) {
?>
<div class="card border-left-secondary shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-secondary">Manutenção Preditiva</h6>
    </div>
    <div class="card-body">
        <?php
        for ($i = 0; $i < count($res); $i++) { 
            $serv = $res[$i]['servico'];
            $id_pcm_preditiva = $res[$i]['id'];
            
            $query_pro = $pdo->query("SELECT * FROM tipo_pcm WHERE id = '$serv'");
            $res_pro = $query_pro->fetchAll(PDO::FETCH_ASSOC);
            $nome_prod = $res_pro[0]['descricao'];
            $id_prd = $res_pro[0]['id'];
            
            $valor_observacao = '';
            try {
                $query_obs = $pdo->prepare("SELECT observacao FROM pcm_preditiva WHERE id = :id");
                $query_obs->execute([':id' => $id_pcm_preditiva]);
                $res_obs = $query_obs->fetch(PDO::FETCH_ASSOC);
                if ($res_obs) {
                    $valor_observacao = htmlspecialchars($res_obs['observacao']);
                }
            } catch (Exception $e) {
                // Log de erro se necessário
            }
        ?>
        <div class="mb-3">
            <div class="d-flex align-items-center">
                <span class="font-weight-bold"><?php echo $nome_prod ?></span>
                <div class="ml-3">
                    <input type="text" name="obs_preditiva_<?php echo $id_prd ?>"
                        id="obs_preditiva_<?php echo $id_prd ?>" class="form-control form-control-sm d-inline-block"
                        style="width: 200px;" placeholder="Observações" data-tipo-pcm="preditiva"
                        data-id="<?php echo $id_prd ?>" data-id-pcm="<?php echo $id_orc ?>"
                        value="<?php echo $valor_observacao ?>"
                        onblur="enviarPreditiva(<?php echo $id_prd ?>, <?php echo $id_orc ?>)">
                </div>
                <a href="index.php?pag=<?php echo $pag ?>&funcao=servicos&funcao2=adicionarServ2&id_serv=<?php echo $id_prd ?>&id=<?php echo @$_GET['id'] ?>&funcao3=excluirServ3"
                    class="ml-2 text-danger">
                    <i class="far fa-trash-alt"></i>
                </a>
            </div>
            <hr class="mt-2">
        </div>
        <?php } ?>
    </div>
</div>
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
            $updateObs = $pdo->prepare("UPDATE pcm_preventiva SET observacao = :obs WHERE pcm = :pcm AND servico = :servico");
            $updateObs->execute([
                ':obs' => $obs,
                ':pcm' => $id_orc,
                ':servico' => $id_serv
            ]);
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

if (@$_GET["funcao3"] != null && @$_GET["funcao3"] == "excluirServ") {
    $id_orc = $_GET['id'];
    $id_serv = $_GET['id_serv'];
    $pdo->query("DELETE FROM pcm_preventiva WHERE pcm = '$id_orc' AND servico = '$id_serv'");
    echo "<script>window.location='index.php?pag=$pag&id=$id_orc';</script>";
}

if (@$_GET["funcao3"] != null && @$_GET["funcao3"] == "excluirServ2") {
    $id_orc = $_GET['id'];
    $id_serv = $_GET['id_serv'];
    $pdo->query("DELETE FROM pcm_corretiva WHERE pcm = '$id_orc' AND servico = '$id_serv'");
    echo "<script>window.location='index.php?pag=$pag&id=$id_orc';</script>";
}

if (@$_GET["funcao3"] != null && @$_GET["funcao3"] == "excluirServ3") {
    $id_orc = $_GET['id'];
    $id_serv = $_GET['id_serv'];
    $pdo->query("DELETE FROM pcm_preditiva WHERE pcm = '$id_orc' AND servico = '$id_serv'");
    echo "<script>window.location='index.php?pag=$pag&id=$id_orc';</script>";
}
?>

<script>
function enviarPreventiva(idServ, idOrc) {
    const input = document.getElementById('obs_preventiva_' + idServ);
    const obs = input.value;
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

function enviarCorretiva(idServ, idOrc) {
    const input = document.getElementById('obs_corretiva_' + idServ);
    const obs = input.value;
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

function enviarPreditiva(idServ, idOrc) {
    const input = document.getElementById('obs_preditiva_' + idServ);
    const obs = input.value;
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

<!-- AJAX para inserção e edição dos dados -->
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
</script>

<!-- AJAX para exclusão dos dados -->
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
                $('#mensagem_excluir').html('<div class="alert alert-' + (mensagem
                        .trim() === 'Excluído com Sucesso!' ? 'success' : 'danger') +
                    '">' +
                    mensagem + '</div>');
            },
        })
    })
})
</script>

<!-- AJAX para buscar veículos -->
<script type="text/javascript">
$(document).ready(function() {
    $('#btn-buscar').click(function(event) {
        event.preventDefault();
        var pag = "<?=$pag?>";
        var funcao = "<?=$funcao?>";
        var veiculo = "<?=@$veiculo2?>";
        var cpf = "<?=@$cliente2?>";

        if (funcao.trim() !== 'editar') {
            cpf = $('#cpf').val();
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

    // Dispara automaticamente se for edição
    var funcao = "<?=$funcao?>";
    if (funcao.trim() === 'editar') {
        $('#btn-buscar').click();
    }
})
</script>

<!-- Configurações gerais -->
<script type="text/javascript">
$(document).ready(function() {
    $('#dataTable').dataTable({
        "ordering": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        }
    });

    $('#dataTable2').dataTable({
        "ordering": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        }
    });

    // Seleção de cliente
    $('#cli').on('change', function(e) {
        var cpf = $(this).val();
        $('#cpf').val(cpf);
        $('#btn-buscar').click();
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const linhas = document.querySelectorAll('.linha-com-link');

    // Restaura seleção
    const linhaSelecionada = sessionStorage.getItem('linhaSelecionada');
    if (linhaSelecionada) {
        const linha = document.querySelector(`[data-id-orc="${linhaSelecionada}"]`);
        if (linha) {
            linha.classList.add('tr-selecionada');
        }
        sessionStorage.removeItem('linhaSelecionada');
    }

    // Clique: salva ID e redireciona
    linhas.forEach(function(linha) {
        linha.addEventListener('click', function() {
            const id = linha.getAttribute('data-id-orc');
            const url = linha.getAttribute('data-url');

            if (id && url) {
                sessionStorage.setItem('linhaSelecionada', id);
                window.location.href = url;
            }
        });
    });
});
</script>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
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
    background-color: rgb(220, 181, 238) !important;
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

.obs-preventiva,
.obs-corretiva,
.obs-preditiva {
    transition: border-color 0.3s;
}

.obs-preventiva:focus {
    border-color: #36b9cc;
}

.obs-corretiva:focus {
    border-color: #f6c23e;
}

.obs-preditiva:focus {
    border-color: #858796;
}
</style>