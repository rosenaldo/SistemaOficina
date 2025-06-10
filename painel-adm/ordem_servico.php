<?php 
@session_start();
if(@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'admin'){
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

$pag = "servicos";
require_once("../conexao.php"); 

$funcao = @$_GET['funcao'];
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tools mr-2"></i>Ordens de Serviço
        </h1>
    </div>

    <!-- Card de Ordens de Serviço -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Todas as Ordens de Serviço</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Status</th>
                            <th>Cliente</th>
                            <th>Mecânico</th>
                            <th>Valor Total</th>
                            <th>Serviço</th>
                            <th>Veículo</th>
                            <th>Data Entrega</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_da_os = 0;
                        $query = $pdo->query("SELECT * FROM os ORDER BY concluido ASC, data_entrega ASC");
                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
                        
                        for ($i=0; $i < @count($res); $i++) { 
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

                            // Busca serviços associados
                            $query_s = $pdo->query("SELECT * FROM orc_serv WHERE orcamento = '$id_orc'");
                            $res_s = $query_s->fetchAll(PDO::FETCH_ASSOC);
                            $nome_ser = "Nenhum serviço";
                            if(@count($res_s) > 0){
                                $serv = $res_s[0]['servico'];
                                $query_ser = $pdo->query("SELECT * FROM servicos WHERE id = '$serv'");
                                $res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
                                $nome_ser = $res_ser[0]['nome'];
                            }

                            // Busca cliente
                            $query_cat = $pdo->query("SELECT * FROM clientes WHERE cpf = '$cliente'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $nome_cli = $res_cat[0]['nome'];
                            $email_cli = $res_cat[0]['email'];

                            // Busca mecânico
                            $query_cat = $pdo->query("SELECT * FROM mecanicos WHERE cpf = '$mecanico'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $nome_mec = $res_cat[0]['nome'];

                            // Busca veículo
                            $query_cat = $pdo->query("SELECT * FROM veiculos WHERE id = '$veiculo'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $modelo = $res_cat[0]['modelo'];
                            $marca = $res_cat[0]['marca'];

                            if($concluido == 'Sim'){
                                $status = "<span class='badge badge-success'>Concluído</span>";
                                $cor_pago = 'text-success';
                            } else {
                                $status = "<span class='badge badge-warning'>Pendente</span>";
                                $cor_pago = 'text-danger';
                            }
                        ?>
                        <tr>
                            <td><?php echo $status ?></td>
                            <td><?php echo $nome_cli ?></td>
                            <td><?php echo $nome_mec ?></td>
                            <td>R$ <?php echo $valorF ?></td>
                            <td><?php echo $nome_ser ?></td>
                            <td><?php echo $marca .' '.$modelo ?></td>
                            <td><?php echo $data_entrega ?></td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="../painel-adm/rel/rel_os.php?id=<?php echo $id ?>" target="_blank" class="btn btn-sm btn-info mr-1" title="Imprimir OS">
                                        <i class="far fa-file-alt"></i>
                                    </a>
                                    <?php if($concluido == 'Não') { ?>
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=concluir&id=<?php echo $id ?>" class="btn btn-sm btn-success" title="Concluir OS">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <?php } ?>
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

<!-- Modal de Detalhes -->
<div class="modal fade" id="modal-detalhes" tabindex="-1" role="dialog" aria-labelledby="modal-detalhesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modal-detalhesLabel">
                    <i class="fas fa-info-circle mr-2"></i>Detalhes da Ordem de Serviço
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                if (@$_GET['funcao'] == 'detalhes') {
                    $id2 = $_GET['id'];
                    $query = $pdo->query("SELECT * FROM os WHERE id = '$id2'");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    
                    if(count($res) > 0) {
                        $os = $res[0];
                        $cliente = $os['cliente'];
                        $veiculo = $os['veiculo'];
                        $descricao = $os['descricao'];
                        $valor = $os['valor'];
                        $data = $os['data'];
                        $data_entrega = $os['data_entrega'];
                        $concluido = $os['concluido'];
                        $mecanico = $os['mecanico'];
                        $id_orc = $os['id_orc'];
                        
                        // Busca informações do cliente
                        $query_c = $pdo->query("SELECT * FROM clientes WHERE cpf = '$cliente'");
                        $cli = $query_c->fetch(PDO::FETCH_ASSOC);
                        
                        // Busca informações do veículo
                        $query_v = $pdo->query("SELECT * FROM veiculos WHERE id = '$veiculo'");
                        $veic = $query_v->fetch(PDO::FETCH_ASSOC);
                        
                        // Busca informações do mecânico
                        $query_m = $pdo->query("SELECT * FROM mecanicos WHERE cpf = '$mecanico'");
                        $mec = $query_m->fetch(PDO::FETCH_ASSOC);
                        
                        // Formata valores e datas
                        $valorF = number_format($valor, 2, ',', '.');
                        $dataF = implode('/', array_reverse(explode('-', $data)));
                        $data_entregaF = implode('/', array_reverse(explode('-', $data_entrega)));
                ?>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="font-weight-bold">Informações do Cliente</h5>
                        <p><strong>Nome:</strong> <?php echo $cli['nome'] ?></p>
                        <p><strong>CPF:</strong> <?php echo $cli['cpf'] ?></p>
                        <p><strong>Telefone:</strong> <?php echo $cli['telefone'] ?></p>
                        <p><strong>Email:</strong> <?php echo $cli['email'] ?></p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="font-weight-bold">Informações do Veículo</h5>
                        <p><strong>Marca/Modelo:</strong> <?php echo $veic['marca'] . ' ' . $veic['modelo'] ?></p>
                        <p><strong>Placa:</strong> <?php echo $veic['placa'] ?></p>
                        <p><strong>Ano:</strong> <?php echo $veic['ano'] ?></p>
                        <p><strong>Cor:</strong> <?php echo $veic['cor'] ?></p>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="font-weight-bold">Informações do Serviço</h5>
                        <p><strong>Data Abertura:</strong> <?php echo $dataF ?></p>
                        <p><strong>Data Entrega:</strong> <?php echo $data_entregaF ?></p>
                        <p><strong>Status:</strong> 
                            <?php echo ($concluido == 'Sim') ? '<span class="badge badge-success">Concluído</span>' : '<span class="badge badge-warning">Pendente</span>' ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="font-weight-bold">Responsável</h5>
                        <p><strong>Mecânico:</strong> <?php echo $mec['nome'] ?></p>
                        <p><strong>Valor Total:</strong> R$ <?php echo $valorF ?></p>
                    </div>
                </div>
                
                <div class="mb-3">
                    <h5 class="font-weight-bold">Descrição do Serviço</h5>
                    <p><?php echo $descricao ?></p>
                </div>
                
                <div class="mb-3">
                    <h5 class="font-weight-bold">Produtos Utilizados</h5>
                    <?php 
                    $query_p = $pdo->query("SELECT p.nome, p.valor_venda FROM orc_prod op JOIN produtos p ON op.produto = p.id WHERE op.orcamento = '$id_orc'");
                    $produtos = $query_p->fetchAll(PDO::FETCH_ASSOC);
                    
                    if(count($produtos) > 0) {
                        echo '<ul class="list-group">';
                        foreach($produtos as $prod) {
                            $valor_prod = number_format($prod['valor_venda'], 2, ',', '.');
                            echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                            echo $prod['nome'];
                            echo '<span class="badge badge-primary badge-pill">R$ '.$valor_prod.'</span>';
                            echo '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<p class="text-muted">Nenhum produto registrado.</p>';
                    }
                    ?>
                </div>
                
                <div class="mb-3">
                    <h5 class="font-weight-bold">Serviços Realizados</h5>
                    <?php 
                    $query_s = $pdo->query("SELECT s.nome, s.valor FROM orc_serv os JOIN servicos s ON os.servico = s.id WHERE os.orcamento = '$id_orc'");
                    $servicos = $query_s->fetchAll(PDO::FETCH_ASSOC);
                    
                    if(count($servicos) > 0) {
                        echo '<ul class="list-group">';
                        foreach($servicos as $serv) {
                            $valor_serv = number_format($serv['valor'], 2, ',', '.');
                            echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                            echo $serv['nome'];
                            echo '<span class="badge badge-primary badge-pill">R$ '.$valor_serv.'</span>';
                            echo '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<p class="text-muted">Nenhum serviço registrado.</p>';
                    }
                    ?>
                </div>
                <?php } } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <a href="../painel-adm/rel/rel_os.php?id=<?php echo @$id2 ?>" target="_blank" class="btn btn-primary">
                    <i class="fas fa-print mr-2"></i>Imprimir OS
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Conclusão -->
<div class="modal fade" id="modal-concluir" tabindex="-1" role="dialog" aria-labelledby="modal-concluirLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modal-concluirLabel">Concluir Ordem de Serviço</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja marcar esta ordem de serviço como concluída?</p>
                <div id="mensagem_concluir" class="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancelar-concluir">Cancelar</button>
                <form method="post">
                    <input type="hidden" id="id_concluir" name="id" value="<?php echo @$_GET['id'] ?>" required>
                    <button type="button" id="btn-confirmar-concluir" class="btn btn-success">Confirmar Conclusão</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
// Abre modais conforme a função chamada
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "detalhes") {
    echo "<script>$('#modal-detalhes').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "concluir") {
    echo "<script>$('#modal-concluir').modal('show');</script>";
}
?>

<!-- Scripts JavaScript -->
<script type="text/javascript">
// Conclusão de OS
$(document).ready(function() {
    var pag = "<?=$pag?>";
    $('#btn-confirmar-concluir').click(function(event) {
        event.preventDefault();
        $.ajax({
            url: pag + "/concluir.php",
            method: "post",
            data: $('form').serialize(),
            dataType: "text",
            success: function(mensagem) {
                if (mensagem.trim() === 'Concluído com Sucesso!') {
                    $('#btn-cancelar-concluir').click();
                    window.location = "index.php?pag=" + pag;
                }
                $('#mensagem_concluir').html('<div class="alert alert-' + (mensagem
                    .trim() === 'Concluído com Sucesso!' ? 'success' : 'danger') + '">' +
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
</script>

<!-- Estilos personalizados -->
<style>
.badge {
    font-size: 90%;
    font-weight: 500;
    padding: 0.35em 0.65em;
}

.list-group-item {
    padding: 0.75rem 1.25rem;
}

.font-weight-bold {
    font-weight: 600 !important;
}

.bg-primary {
    background-color: #7C3AED !important;
}

.bg-success {
    background-color: #1cc88a !important;
}

.bg-danger {
    background-color: #e74a3b !important;
}

.bg-warning {
    background-color: #f6c23e !important;
}

.bg-dark {
    background-color: #5a5c69 !important;
}

.text-white {
    color: white !important;
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
</style>