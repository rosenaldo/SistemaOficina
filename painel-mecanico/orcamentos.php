<?php 
@session_start();
if(@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'mecanico'){
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

$pag = "orcamentos";
require_once("../conexao.php"); 

$funcao = @$_GET['funcao'];
$varios_serv = '';
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-invoice-dollar mr-2"></i>Orçamentos
        </h1>
        <a href="index.php?pag=<?php echo $pag ?>&funcao=novo"
            class="d-none d-sm-inline-block btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Novo Orçamento
        </a>
        <a href="index.php?pag=<?php echo $pag ?>&funcao=novo"
            class="d-block d-sm-none btn btn-primary btn-circle">
            <i class="fas fa-plus"></i>
        </a>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Orçamentos em Aberto</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Cliente</th>
                            <th>Veículo</th>
                            <th>Valor</th>
                            <th>Serviço</th>
                            <th>Data</th>
                            <th>Mecânico</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = $pdo->query("SELECT * FROM orcamentos where mecanico = '$_SESSION[cpf_usuario]' and status = 'Aberto' order by id desc ");
                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
                        
                        for ($i=0; $i < @count($res); $i++) { 
                            foreach ($res[$i] as $key => $value) {
                            }
                            $cliente = $res[$i]['cliente'];
                            $veiculo = $res[$i]['veiculo'];
                            $descricao = $res[$i]['descricao'];
                            $valor = $res[$i]['valor'];
                            $servico = $res[$i]['servico'];
                            $data = $res[$i]['data'];
                            $data_entrega = $res[$i]['data_entrega'];
                            $garantia = $res[$i]['garantia'];
                            $mecanico = $res[$i]['mecanico'];
                            $id = $res[$i]['id'];

                            $data = implode('/', array_reverse(explode('-', $data)));
                            $valor = number_format($valor, 2, ',', '.');

                            $query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $nome_cli = $res_cat[0]['nome'];
                            $email_cli = $res_cat[0]['email'];

                            $query_cat = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $modelo = $res_cat[0]['modelo'];
                            $marca = $res_cat[0]['marca'];
                            $placa = $res_cat[0]['placa'];

                            $query_cat = $pdo->query("SELECT * FROM orc_serv where orcamento = '$id' ");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            if(@count($res_cat) == 0){
                                $nome_serv = "Não Lançado!";
                                $varios_serv = 'Não';
                            } else if(@count($res_cat) == 1){
                                $serv = $res_cat[0]['servico'];
                                $varios_serv = 'Não';

                                $query_ser = $pdo->query("SELECT * FROM servicos where id = '$serv' ");
                                $res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
                                $nome_serv = $res_ser[0]['nome'];
                            } else if(@count($res_cat) > 1){
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
                            <td>R$ <?php echo $valor ?></td>
                            <?php if($varios_serv == 'Sim'){ ?>
                                <td>
                                    <a title="Ver Serviços" class="text-primary" href="index.php?pag=<?php echo $pag ?>&funcao=detalhesServ&id=<?php echo $id ?>">
                                    <?php echo $nome_serv ?>
                                    </a>
                                </td>
                            <?php }else{ ?>
                                <td><?php echo $nome_serv ?></td>
                            <?php } ?>
                            <td><?php echo $data ?></td>
                            <td><?php echo $nome_mecanico ?></td>

                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>"
                                        class="btn btn-sm btn-primary mr-1" title="Editar"><i class="far fa-edit"></i></a>
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>"
                                        class="btn btn-sm btn-danger mr-1" title="Excluir"><i class="far fa-trash-alt"></i></a>
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=produtos&id=<?php echo $id ?>"
                                        class="btn btn-sm btn-success mr-1" title="Produtos"><i class="fab fa-product-hunt"></i></a>
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=servicos&id=<?php echo $id ?>"
                                        class="btn btn-sm btn-info mr-1" title="Serviços"><i class="fas fa-tools"></i></a>
                                    <a href="rel/rel_orcamento.php?id=<?php echo $id ?>&email=<?php echo $email_cli ?>"
                                        target="_blank" class="btn btn-sm btn-secondary" title="Imprimir"><i class="far fa-file-alt"></i></a>
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
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="modalDadosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <?php 
                if (@$_GET['funcao'] == 'editar') {
                    $titulo = "Editar Orçamento";
                    $id2 = $_GET['id'];

                    $query = $pdo->query("SELECT * FROM orcamentos where id = '$id2' ");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    $cliente2 = $res[0]['cliente'];
                    $veiculo2 = $res[0]['veiculo'];
                    $descricao2 = $res[0]['descricao'];
                    $valor2 = $res[0]['valor'];
                    $servico2 = $res[0]['servico'];
                    $data2 = $res[0]['data'];
                    $data_entrega2 = $res[0]['data_entrega'];
                    $garantia2 = $res[0]['garantia'];
                    $mecanico2 = $res[0]['mecanico'];
                    $obs2 = $res[0]['obs'];
                } else {
                    $titulo = "Novo Orçamento";
                }
                ?>
                <h5 class="modal-title" id="modalDadosLabel">
                    <i class="fas fa-file-invoice-dollar mr-2"></i><?php echo $titulo ?>
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
                                    <input value="<?php echo @$cliente2 ?>" type="text" class="form-control" id="cpf" name="cliente" placeholder="CPF do Cliente">
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
                                    $query = $pdo->query("SELECT * FROM clientes order by nome asc ");
                                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    for ($i=0; $i < @count($res); $i++) { 
                                        $nome_reg = $res[$i]['nome'];
                                        $id_reg = $res[$i]['cpf'];
                                    ?>
                                    <option <?php if(@$cliente2 == $id_reg){ ?> selected <?php } ?> value="<?php echo $id_reg ?>">
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
                        <label>Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3"><?php echo @$descricao2 ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data da Entrega</label>
                                <input value="<?php echo @$data2 ?>" type="date" class="form-control" id="data_entrega" name="data_entrega">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Garantia (Dias)</label>
                                <input value="<?php echo @$garantia2 ?>" type="text" class="form-control" id="garantia" name="garantia" placeholder="Total de Dias Garantia">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Valor (Mão de Obra)</label>
                                <input value="<?php echo @$valor2 ?>" type="text" class="form-control" id="valor" name="valor" placeholder="Valor da Mão de Obra">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Observações do Veículo</label>
                        <textarea class="form-control" id="obs" name="obs" rows="3"><?php echo @$obs2 ?></textarea>
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
<div class="modal fade" id="modal-deletar" tabindex="-1" role="dialog" aria-labelledby="modal-deletarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modal-deletarLabel">Excluir Registro</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este orçamento permanentemente?</p>
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

<!-- Modal para seleção de produtos -->
<div class="modal fade" id="modal-produtos" tabindex="-1" role="dialog" aria-labelledby="modal-produtosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modal-produtosLabel">
                    Selecionar Produto - 
                    <a href="index.php?pag=<?php echo $pag ?>&funcao=detalhes&id=<?php echo $_GET['id'] ?>" class="text-white">
                        <u>Ver Produtos</u>
                    </a>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable2" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>Nome</th>
                                <th>Valor Venda</th>
                                <th>Estoque</th>
                                <th>Imagem</th>
                                <th width="100px">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query = $pdo->query("SELECT * FROM produtos order by id desc ");
                            $res = $query->fetchAll(PDO::FETCH_ASSOC);

                            for ($i=0; $i < @count($res); $i++) { 
                                $nome = $res[$i]['nome'];
                                $valor_venda = $res[$i]['valor_venda'];
                                $estoque = $res[$i]['estoque'];
                                $imagem = $res[$i]['imagem'];
                                $id_prod = $res[$i]['id'];

                                if($estoque < $nivel_estoque){
                                    $cor = "text-danger";
                                }else{
                                    $cor = "";
                                }

                                $valor_venda = number_format($valor_venda, 2, ',', '.');
                            ?>
                            <tr>
                                <td><?php echo $nome ?></td>
                                <td>R$ <?php echo $valor_venda ?></td>
                                <td><span class="<?php echo $cor ?>"><?php echo $estoque ?></span></td>
                                <td><img src="../img/produtos/<?php echo $imagem ?>" width="35"></td>
                                <td class="text-center">
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=produtos&funcao2=adicionar&id_prod=<?php echo $id_prod ?>&id=<?php echo @$_GET['id'] ?>"
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

<!-- Modal para seleção de serviços -->
<div class="modal fade" id="modal-servicos" tabindex="-1" role="dialog" aria-labelledby="modal-servicosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modal-servicosLabel">
                    Selecionar Serviços - 
                    <a href="index.php?pag=<?php echo $pag ?>&funcao=detalhesServ&id=<?php echo $_GET['id'] ?>" class="text-white">
                        <u>Ver Serviços</u>
                    </a>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable2" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>Nome</th>
                                <th>Valor</th>
                                <th width="100px">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query = $pdo->query("SELECT * FROM servicos order by nome asc ");
                            $res = $query->fetchAll(PDO::FETCH_ASSOC);
                            
                            for ($i=0; $i < @count($res); $i++) { 
                                $nome = $res[$i]['nome'];
                                $id_serv = $res[$i]['id'];
                                $valor = $res[$i]['valor'];
                                $valor = number_format($valor, 2, ',', '.');
                            ?>
                            <tr>
                                <td><?php echo $nome ?></td>
                                <td>R$ <?php echo $valor ?></td>
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

<!-- Modal para detalhes de produtos -->
<div class="modal fade" id="modal-detalhes" tabindex="-1" role="dialog" aria-labelledby="modal-detalhesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modal-detalhesLabel">Produtos Adicionados</h5>
                <a type="button" class="close text-light" href="index.php?pag=<?php echo $pag ?>&funcao=produtos&id=<?php echo $_GET['id'] ?>">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body">
                <?php 
                $id_orc = $_GET['id'];
                $query = $pdo->query("SELECT * FROM orc_prod where orcamento = '$id_orc' ");
                $res = $query->fetchAll(PDO::FETCH_ASSOC);

                $total_prod = 0;
                for ($i=0; $i < @count($res); $i++) { 
                    $prod = $res[$i]['produto'];
                    $query_pro = $pdo->query("SELECT * FROM produtos where id = '$prod' ");
                    $res_pro = $query_pro->fetchAll(PDO::FETCH_ASSOC);
                    $nome_prod = $res_pro[0]['nome'];
                    $valor_prod = $res_pro[0]['valor_venda'];
                    $id_prd = $res_pro[0]['id'];

                    $total_prod = $valor_prod + $total_prod;
                    $valor_prod = number_format($valor_prod, 2, ',', '.');
                ?>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <small><i><?php echo $nome_prod ?> - R$ <?php echo $valor_prod ?></i></small>
                    </div>
                    <div>
                        <a href="index.php?pag=<?php echo $pag ?>&funcao=produtos&funcao2=adicionar&id_prod=<?php echo $id_prd ?>&id=<?php echo @$_GET['id'] ?>&funcao3=excluir" class="text-danger">
                            <i class="far fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
                <hr class="my-1">
                <?php } ?>

                <div id="mensagem_excluir_produto" class="mt-3"></div>
            </div>
            <div class="modal-footer bg-success text-white">
                <h6 class="m-0">Total Produtos: R$ <?php echo number_format($total_prod, 2, ',', '.') ?></h6>
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalhes de serviços -->
<div class="modal fade" id="modal-detalhesServ" tabindex="-1" role="dialog" aria-labelledby="modal-detalhesServLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modal-detalhesServLabel">Serviços Adicionados</h5>
                <a type="button" class="close text-light" href="index.php?pag=<?php echo $pag ?>&funcao=servicos&id=<?php echo $_GET['id'] ?>">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body">
                <?php 
                $id_orc = $_GET['id'];
                $query = $pdo->query("SELECT * FROM orc_serv where orcamento = '$id_orc' ");
                $res = $query->fetchAll(PDO::FETCH_ASSOC);

                $total_serv = 0;
                for ($i=0; $i < @count($res); $i++) { 
                    $serv = $res[$i]['servico'];
                    $query_pro = $pdo->query("SELECT * FROM servicos where id = '$serv' ");
                    $res_pro = $query_pro->fetchAll(PDO::FETCH_ASSOC);
                    $nome_prod = $res_pro[0]['nome'];
                    $valor_prod = $res_pro[0]['valor'];
                    $id_prd = $res_pro[0]['id'];

                    $total_serv = $valor_prod + $total_serv;
                    $valor_prod = number_format($valor_prod, 2, ',', '.');
                ?>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <small><i><?php echo $nome_prod ?> - R$ <?php echo $valor_prod ?></i></small>
                    </div>
                    <div>
                        <a href="index.php?pag=<?php echo $pag ?>&funcao=servicos&funcao2=adicionarServ&id_serv=<?php echo $id_prd ?>&id=<?php echo @$_GET['id'] ?>&funcao3=excluirServ" class="text-danger">
                            <i class="far fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
                <hr class="my-1">
                <?php } ?>

                <div id="mensagem_excluir_produto" class="mt-3"></div>
            </div>
            <div class="modal-footer bg-info text-white">
                <h6 class="m-0">Total Serviços: R$ <?php echo number_format($total_serv, 2, ',', '.') ?></h6>
            </div>
        </div>
    </div>
</div>

<?php 
// Exibir modais conforme a função
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

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "servicos") {
    echo "<script>$('#modal-servicos').modal('show');</script>";
}

if (@$_GET["funcao2"] != null && @$_GET["funcao2"] == "adicionar") {
    $id_orc = $_GET['id'];
    $id_prod = $_GET['id_prod'];

    if (!isset($_GET["funcao3"])) {
        $query = $pdo->prepare("SELECT COUNT(*) FROM orc_prod WHERE orcamento = :orcamento AND produto = :produto");
        $query->execute([
            ':orcamento' => $id_orc,
            ':produto' => $id_prod
        ]);
        $existe = $query->fetchColumn();

        if ($existe == 0) {
            $stmt = $pdo->prepare("INSERT INTO orc_prod (orcamento, produto) VALUES (:orcamento, :produto)");
            $stmt->execute([
                ':orcamento' => $id_orc,
                ':produto' => $id_prod
            ]);
        } else {
            echo "<script>alert('Produto já foi adicionado anteriormente.');</script>";
        }
    }

    echo "<script>window.location='index.php?pag=$pag&id=$id_orc&funcao=detalhes';</script>";
}

if (@$_GET["funcao2"] != null && @$_GET["funcao2"] == "adicionarServ") {
    $id_orc = $_GET['id'];
    $id_serv = $_GET['id_serv'];

    if (!isset($_GET["funcao3"])) {
        $query = $pdo->prepare("SELECT COUNT(*) FROM orc_serv WHERE orcamento = :orcamento AND servico = :servico");
        $query->execute([
            ':orcamento' => $id_orc,
            ':servico' => $id_serv
        ]);
        $existe = $query->fetchColumn();

        if ($existe == 0) {
            $stmt = $pdo->prepare("INSERT INTO orc_serv (orcamento, servico) VALUES (:orcamento, :servico)");
            $stmt->execute([
                ':orcamento' => $id_orc,
                ':servico' => $id_serv
            ]);

            $update = $pdo->prepare("UPDATE orcamentos SET servico = :servico WHERE id = :id");
            $update->execute([
                ':servico' => $id_serv,
                ':id' => $id_orc
            ]);
        } else {
            echo "<script>alert('Serviço já foi adicionado anteriormente.');</script>";
        }
    }

    echo "<script>window.location='index.php?pag=$pag&id=$id_orc&funcao=detalhesServ';</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "detalhes") {
    echo "<script>$('#modal-detalhes').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "detalhesServ") {
    echo "<script>$('#modal-detalhesServ').modal('show');</script>";
}

if (@$_GET["funcao3"] != null && @$_GET["funcao3"] == "excluir") {
    $id_orc = $_GET['id'];
    $id_prod = $_GET['id_prod'];
    $pdo->query("DELETE FROM orc_prod WHERE orcamento = '$id_orc' AND produto = '$id_prod'");
}

if (@$_GET["funcao3"] != null && @$_GET["funcao3"] == "excluirServ") {
    $id_orc = $_GET['id'];
    $id_serv = $_GET['id_serv'];
    $pdo->query("DELETE FROM orc_serv WHERE orcamento = '$id_orc' AND servico = '$id_serv'");
}
?>

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

.card-header {
    border-radius: 0.35rem 0.35rem 0 0 !important;
}

.table th {
    border-top: none;
}

.btn-circle {
    width: 40px;
    height: 40px;
    padding: 6px 0;
    border-radius: 20px;
    text-align: center;
    font-size: 16px;
    line-height: 1.42857;
}

.btn-group-sm > .btn, .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.modal-content {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.modal-footer {
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.03);
}

.bg-primary {
    background-color: #7C3AED !important;
}

.bg-success {
    background-color: #1cc88a !important;
}

.bg-info {
    background-color: #36b9cc !important;
}

.bg-warning {
    background-color: #f6c23e !important;
}

.bg-danger {
    background-color: #e74a3b !important;
}

.bg-secondary {
    background-color: #858796 !important;
}

.bg-dark {
    background-color: #5a5c69 !important;
}

.text-white {
    color: #fff !important;
}

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}
</style>