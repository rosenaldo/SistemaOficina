<?php 
@session_start();
require_once("verificar_usuario.php");

$pag = "orcamentos";
require_once("../conexao.php"); 

$funcao = @$_GET['funcao'];
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-invoice-dollar mr-2"></i>Orçamentos
        </h1>
    </div>

    <!-- Card de Orçamentos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Lista de Orçamentos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Status</th>
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
                        $query = $pdo->query("SELECT * FROM orcamentos ORDER BY status ASC, id ASC");
                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
                        
                        for ($i=0; $i < @count($res); $i++) { 
                            $cliente = $res[$i]['cliente'];
                            $veiculo = $res[$i]['veiculo'];
                            $descricao = $res[$i]['descricao'];
                            $valor = $res[$i]['valor'];
                            $servico = $res[$i]['servico'];
                            $data = $res[$i]['data'];
                            $data_entrega = $res[$i]['data_entrega'];
                            $garantia = $res[$i]['garantia'];
                            $mecanico = $res[$i]['mecanico'];
                            $status = $res[$i]['status'];
                            $id = $res[$i]['id'];

                            $data = implode('/', array_reverse(explode('-', $data)));
                            $valor = number_format($valor, 2, ',', '.');

                            // Busca cliente
                            $query_cat = $pdo->query("SELECT * FROM clientes WHERE cpf = '$cliente'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $nome_cli = $res_cat[0]['nome'];
                            $email_cli = $res_cat[0]['email'];

                            // Busca veículo
                            $query_cat = $pdo->query("SELECT * FROM veiculos WHERE id = '$veiculo'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $modelo = $res_cat[0]['modelo'];
                            $marca = $res_cat[0]['marca'];

                            // Busca serviços
                            $query_cat = $pdo->query("SELECT * FROM orc_serv WHERE orcamento = '$id'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            if(@count($res_cat) == 0){
                                $nome_serv = "Não Lançado!";
                            } else if(@count($res_cat) == 1){
                                $serv = $res_cat[0]['servico'];
                                $query_ser = $pdo->query("SELECT * FROM servicos WHERE id = '$serv'");
                                $res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
                                $nome_serv = $res_ser[0]['nome'];
                            } else if(@count($res_cat) > 1){
                                $nome_serv = @count($res_cat) . ' Serviços';
                            }
                            
                            // Busca mecânico
                            $query_cat = $pdo->query("SELECT * FROM mecanicos WHERE cpf = '$mecanico'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $nome_mecanico = $res_cat[0]['nome'];

                            // Define cores e ícones para status
                            if($status == 'Aberto'){
                                $status_class = 'badge-danger';
                                $status_icon = 'far fa-clock';
                            } else if($status == 'Aprovado'){
                                $status_class = 'badge-primary';
                                $status_icon = 'fas fa-check-circle';
                            } else {
                                $status_class = 'badge-success';
                                $status_icon = 'fas fa-check';
                            }
                        ?>
                        <tr>
                            <td>
                                <span class="badge <?php echo $status_class ?>">
                                    <i class="<?php echo $status_icon ?> mr-1"></i><?php echo $status ?>
                                </span>
                            </td>
                            <td><?php echo $nome_cli ?></td>
                            <td><?php echo $marca .' '.$modelo ?></td>
                            <td>R$ <?php echo $valor ?></td>
                            <td><?php echo $nome_serv ?></td>
                            <td><?php echo $data ?></td>
                            <td><?php echo $nome_mecanico ?></td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="../painel-mecanico/rel/rel_orcamento.php?id=<?php echo $id ?>" 
                                       target="_blank" 
                                       class="btn btn-sm btn-info mr-1" 
                                       title="Imprimir Orçamento">
                                        <i class="far fa-file-alt"></i>
                                    </a>
                                    
                                    <?php if($status == 'Aberto'){ ?>
                                        <a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>" 
                                           class="btn btn-sm btn-danger mr-1" 
                                           title="Excluir Registro">
                                            <i class="far fa-trash-alt"></i>
                                        </a>
                                        
                                        <a href="../painel-mecanico/rel/rel_orcamento.php?id=<?php echo $id ?>&email=<?php echo $email_cli ?>" 
                                           target="_blank" 
                                           class="btn btn-sm btn-warning mr-1" 
                                           title="Enviar por Email">
                                            <i class="far fa-envelope"></i>
                                        </a>
                                        
                                        <a href="index.php?pag=<?php echo $pag ?>&funcao=aprovar&id=<?php echo $id ?>" 
                                           class="btn btn-sm btn-success" 
                                           title="Aprovar Orçamento">
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

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="modal-deletar" tabindex="-1" role="dialog" aria-labelledby="modal-deletarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modal-deletarLabel">Excluir Orçamento</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este orçamento permanentemente?</p>
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

<!-- Modal de Aprovação de Orçamento -->
<div class="modal fade" id="modal-aprovar" tabindex="-1" role="dialog" aria-labelledby="modal-aprovarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modal-aprovarLabel">Aprovar Orçamento</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Deseja realmente aprovar este orçamento?</p>
                <div id="mensagem_orc" class="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancelar-orc">Cancelar</button>
                <form method="post">
                    <input type="hidden" id="id" name="id" value="<?php echo @$_GET['id'] ?>" required>
                    <button type="button" id="btn-orc" class="btn btn-success">Confirmar Aprovação</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "excluir") {
    echo "<script>$('#modal-deletar').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "aprovar") {
    echo "<script>$('#modal-aprovar').modal('show');</script>";
}
?>

<!-- Scripts JavaScript -->
<script type="text/javascript">
$(document).ready(function() {
    // Configuração da tabela
    $('#dataTable').DataTable({
        "ordering": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        }
    });

    // Exclusão de orçamento
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
                $('#mensagem_excluir').html('<div class="alert alert-' + (mensagem.trim() === 'Excluído com Sucesso!' ? 'success' : 'danger') + '">' + mensagem + '</div>');
            },
        })
    });

    // Aprovação de orçamento
    $('#btn-orc').click(function(event) {
        event.preventDefault();
        $.ajax({
            url: pag + "/aprovar.php",
            method: "post",
            data: $('form').serialize(),
            dataType: "text",
            success: function(mensagem) {
                if (mensagem.trim() === 'Aprovado com Sucesso!') {
                    $('#btn-cancelar-orc').click();
                    window.location = "index.php?pag=" + pag;
                }
                $('#mensagem_orc').html('<div class="alert alert-' + (mensagem.trim() === 'Aprovado com Sucesso!' ? 'success' : 'danger') + '">' + mensagem + '</div>');
            },
        })
    });
});
</script>

<!-- Estilos personalizados -->
<style>
.badge {
    font-size: 0.85em;
    font-weight: 500;
    padding: 0.35em 0.65em;
}

.badge-danger {
    background-color: #e74a3b;
}

.badge-primary {
    background-color: #4e73df;
}

.badge-success {
    background-color: #1cc88a;
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

.table th {
    border-top: none;
    vertical-align: middle;
}

.table td {
    vertical-align: middle;
}

.card-header {
    border-radius: 0.35rem 0.35rem 0 0 !important;
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

.text-white {
    color: white !important;
}
</style>