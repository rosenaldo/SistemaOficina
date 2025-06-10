<?php 
@session_start();
require_once("verificar_usuario.php");

$pag = "receber";
require_once("../conexao.php"); 

$data_venc2 = date('Y-m-d');
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-invoice-dollar mr-2"></i>Contas a Receber
        </h1>
    </div>

    <!-- Card de Contas a Receber -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Contas em Aberto</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Adiantamento</th>
                            <th>Mecânico</th>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = $pdo->query("SELECT * FROM contas_receber ORDER BY pago ASC, data ASC, id ASC");
                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
                        
                        for ($i=0; $i < @count($res); $i++) { 
                            $descricao = $res[$i]['descricao'];
                            $valor = $res[$i]['valor'];
                            $adiantamento = $res[$i]['adiantamento'];
                            $mecanico = $res[$i]['mecanico'];
                            $cliente = $res[$i]['cliente'];
                            $pago = $res[$i]['pago'];
                            $data = $res[$i]['data'];
                            $id = $res[$i]['id'];

                            // Busca cliente
                            $query_cli = $pdo->query("SELECT * FROM clientes WHERE cpf = '$cliente'");
                            $res_cli = $query_cli->fetchAll(PDO::FETCH_ASSOC);
                            $nome_cli = @count($res_cli) > 0 ? $res_cli[0]['nome'] : '';

                            // Busca mecânico
                            $query_mec = $pdo->query("SELECT * FROM mecanicos WHERE cpf = '$mecanico'");
                            $res_mec = $query_mec->fetchAll(PDO::FETCH_ASSOC);
                            $nome_mec = @count($res_mec) > 0 ? $res_mec[0]['nome'] : '';

                            // Formatações
                            $valor_f = number_format($valor, 2, ',', '.');
                            $adiantamento_f = number_format($adiantamento, 2, ',', '.');
                            $data_f = implode('/', array_reverse(explode('-', $data)));

                            // Status
                            if($pago == 'Sim') {
                                $status = '<span class="badge badge-success">Pago</span>';
                                $cor_linha = 'text-muted';
                            } else {
                                $status = '<span class="badge badge-danger">Pendente</span>';
                                $cor_linha = '';
                            }
                        ?>
                        <tr class="<?php echo $cor_linha ?>">
                            <td>
                                <i class='fas fa-square mr-1 <?php echo $pago == 'Sim' ? 'text-success' : 'text-danger' ?>'></i>
                                <?php echo $descricao ?>
                            </td>
                            <td>R$ <?php echo $valor_f ?></td>
                            <td>R$ <?php echo $adiantamento_f ?></td>
                            <td><?php echo $nome_mec ?></td>
                            <td><?php echo $nome_cli ?></td>
                            <td><?php echo $data_f ?></td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <?php if($pago != 'Sim') { ?>
                                        <a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>"
                                            class="btn btn-sm btn-primary mr-1" title="Lançar Adiantamento">
                                            <i class="far fa-edit"></i>
                                        </a>
                                        <a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>"
                                            class="btn btn-sm btn-danger mr-1" title="Excluir">
                                            <i class="far fa-trash-alt"></i>
                                        </a>
                                        <a href="index.php?pag=<?php echo $pag ?>&funcao=aprovar&id=<?php echo $id ?>"
                                            class="btn btn-sm btn-success" title="Aprovar Pagamento">
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

<!-- Modal para Lançamento de Adiantamento -->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="modalDadosLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalDadosLabel">
                    <i class="fas fa-money-bill-wave mr-2"></i>Lançar Adiantamento
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Valor do Adiantamento</label>
                        <input type="text" class="form-control money" id="valor" name="valor" placeholder="Valor" required>
                    </div>

                    <div id="mensagem" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <input value="<?php echo @$_GET['id'] ?>" type="hidden" name="txtid2" id="txtid2">
                    <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btn-salvar" id="btn-salvar" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Exclusão -->
<div class="modal fade" id="modal-deletar" tabindex="-1" role="dialog" aria-labelledby="modal-deletarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modal-deletarLabel">Excluir Conta</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir esta conta permanentemente?</p>
                <div id="mensagem_excluir" class="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancelar-excluir">Cancelar</button>
                <form method="post">
                    <input type="hidden" id="id" name="id" value="<?php echo @$_GET['id'] ?>" required>
                    <button type="button" id="btn-deletar" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Aprovação -->
<div class="modal fade" id="modal-aprovar" tabindex="-1" role="dialog" aria-labelledby="modal-aprovarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modal-aprovarLabel">Confirmar Pagamento</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Deseja realmente marcar esta conta como recebida?</p>
                <div id="mensagem_aprovar" class="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-cancelar-aprovar">Cancelar</button>
                <form method="post">
                    <input type="hidden" id="id" name="id" value="<?php echo @$_GET['id'] ?>" required>
                    <button type="button" id="btn-aprovar" class="btn btn-success">
                        <i class="fas fa-check"></i> Confirmar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
// Abre modais conforme a função chamada
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "editar") {
    echo "<script>$('#modalDados').modal('show');</script>";
}

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

    // Máscara para o campo de valor
    $('.money').mask('#.##0,00', {reverse: true});
});

// Formulário de adiantamento
$("#form").submit(function() {
    var pag = "<?=$pag?>";
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: pag + "/adiantamento.php",
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
        }
    });
});

// Exclusão de registro
$('#btn-deletar').click(function(event) {
    event.preventDefault();
    var pag = "<?=$pag?>";
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

// Aprovação de pagamento
$('#btn-aprovar').click(function(event) {
    event.preventDefault();
    var pag = "<?=$pag?>";
    $.ajax({
        url: pag + "/aprovar.php",
        method: "post",
        data: $('form').serialize(),
        dataType: "text",
        success: function(mensagem) {
            if (mensagem.trim() === 'Aprovado com Sucesso!') {
                $('#btn-cancelar-aprovar').click();
                window.location = "index.php?pag=" + pag;
            }
            $('#mensagem_aprovar').html('<div class="alert alert-' + (mensagem.trim() === 'Aprovado com Sucesso!' ? 'success' : 'danger') + '">' + mensagem + '</div>');
        },
    })
});
</script>

<!-- Estilos personalizados -->
<style>
.card-header {
    border-radius: 0.35rem 0.35rem 0 0 !important;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.modal-header {
    border-bottom: none;
    padding: 1rem 1.5rem;
}

.modal-footer {
    border-top: none;
    padding: 1rem 1.5rem;
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

.badge {
    font-size: 90%;
    font-weight: 600;
    padding: 0.35em 0.65em;
}

.text-muted {
    opacity: 0.7;
}
</style>