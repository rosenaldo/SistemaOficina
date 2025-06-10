<?php 
@session_start();
require_once("verificar_usuario.php");

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
            <h6 class="m-0 font-weight-bold">Lista de Ordens de Serviço</h6>
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

                            // Formata datas e valores
                            $data = implode('/', array_reverse(explode('-', $data)));
                            $data_entrega = implode('/', array_reverse(explode('-', $data_entrega)));
                            $valorF = number_format($valor, 2, ',', '.');
                            $valor_mao_obraF = number_format($valor_mao_obra, 2, ',', '.');
                            $total_da_os_F = number_format($valor, 2, ',', '.');

                            // Busca serviços relacionados
                            $query_s = $pdo->query("SELECT * FROM orc_serv WHERE orcamento = '$id_orc'");
                            $res_s = $query_s->fetchAll(PDO::FETCH_ASSOC);
                            if(@count($res_s) > 0){
                                for ($i2=0; $i2 < @count($res_s); $i2++) { 
                                    $serv = $res_s[$i2]['servico'];
                                    $query_ser = $pdo->query("SELECT * FROM servicos WHERE id = '$serv'");
                                    $res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
                                    $nome_ser = $res_ser[0]['nome'];
                                    $valor_ser = $res_ser[0]['valor'];
                                    $id_ser = $res_ser[0]['id'];
                                }
                            }

                            // Busca informações do cliente
                            $query_cat = $pdo->query("SELECT * FROM clientes WHERE cpf = '$cliente'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $nome_cli = $res_cat[0]['nome'];
                            $email_cli = $res_cat[0]['email'];

                            // Busca informações do mecânico
                            $query_cat = $pdo->query("SELECT * FROM mecanicos WHERE cpf = '$mecanico'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $nome_mec = $res_cat[0]['nome'];
                            $nome_mecanico = $res_cat[0]['nome'];

                            // Busca informações do veículo
                            $query_cat = $pdo->query("SELECT * FROM veiculos WHERE id = '$veiculo'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $modelo = $res_cat[0]['modelo'];
                            $marca = $res_cat[0]['marca'];

                            // Define status e cores
                            if($concluido == 'Sim'){
                                $status_class = 'badge-success';
                                $status_icon = 'fas fa-check-circle';
                                $status_text = 'Concluído';
                            } else {
                                $status_class = 'badge-warning';
                                $status_icon = 'fas fa-clock';
                                $status_text = 'Em Andamento';
                            }
                        ?>
                        <tr>
                            <td>
                                <span class="badge <?php echo $status_class ?>">
                                    <i class="<?php echo $status_icon ?> mr-1"></i><?php echo $status_text ?>
                                </span>
                            </td>
                            <td><?php echo $nome_cli ?></td>
                            <td><?php echo $nome_mec ?></td>
                            <td>R$ <?php echo $total_da_os_F ?></td>
                            <td><?php echo $descricao ?></td>
                            <td><?php echo $marca .' '.$modelo ?></td>
                            <td><?php echo $data_entrega ?></td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="../painel-mecanico/rel/rel_os.php?id=<?php echo $id ?>" 
                                       target="_blank" 
                                       class="btn btn-sm btn-primary shadow-sm" 
                                       title="Imprimir OS">
                                        <i class="far fa-file-alt"></i>
                                    </a>
                                    <?php if($concluido != 'Sim') { ?>
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=concluir&id=<?php echo $id ?>" 
                                       class="btn btn-sm btn-success shadow-sm ml-1" 
                                       title="Marcar como Concluído">
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

<!-- Modal de Conclusão de OS -->
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
                <p>Deseja realmente marcar esta ordem de serviço como concluída?</p>
                <div id="mensagem_concluir" class="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancelar-concluir">Cancelar</button>
                <form method="post">
                    <input type="hidden" id="id" name="id" value="<?php echo @$_GET['id'] ?>" required>
                    <button type="button" id="btn-concluir" class="btn btn-success">Confirmar Conclusão</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "concluir") {
    echo "<script>$('#modal-concluir').modal('show');</script>";
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

    // Conclusão de OS
    var pag = "<?=$pag?>";
    $('#btn-concluir').click(function(event) {
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
                $('#mensagem_concluir').html('<div class="alert alert-' + (mensagem.trim() === 'Concluído com Sucesso!' ? 'success' : 'danger') + '">' + mensagem + '</div>');
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

.badge-success {
    background-color: #1cc88a;
}

.badge-warning {
    background-color: #f6c23e;
    color: #000;
}

.badge-danger {
    background-color: #e74a3b;
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

/* Estilo específico para o botão de imprimir */
.btn-primary {
    background-color:rgb(76, 181, 212);
    border-color: rgb(76, 181, 212);
}

.btn-primary:hover {
    background-color: rgb(63, 154, 182);
    border-color: rgb(76, 181, 212);
}

.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
}
</style>