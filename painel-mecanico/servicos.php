<?php 
@session_start();
if(@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'mecanico'){
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

$pag = "servicos";
require_once("../conexao.php"); 
$funcao = @$_GET['funcao'];
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tools mr-2"></i>Serviços Mecânicos
        </h1>
        <a href="index.php?pag=<?php echo $pag ?>&funcao=novo"
            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Novo Serviço
        </a>
        <a href="index.php?pag=<?php echo $pag ?>&funcao=novo"
            class="d-sm-none btn btn-primary btn-circle">
            <i class="fas fa-plus"></i>
        </a>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Serviços Atribuídos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Cliente</th>
                            <th>Mão de Obra</th>
                            <th>Valor Serviço</th>
                            <th>Descrição</th>
                            <th>Veículo</th>
                            <th>Entrega</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_da_os = 0;
                        $query = $pdo->query("SELECT * FROM os where mecanico = '$_SESSION[cpf_usuario]' order by concluido asc, data_entrega asc");
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
                            $total_ser = 0;
                            
                            if(@count($res_s) > 0){
                                for ($i2=0; $i2 < @count($res_s); $i2++) { 
                                    $serv = $res_s[$i2]['servico'];

                                    $query_ser = $pdo->query("SELECT * FROM servicos where id = '$serv' ");
                                    $res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
                                    $valor_ser = $res_ser[0]['valor'];
                                    $total_ser = $valor_ser + $total_ser;
                                    $total_da_os = $valor;
                                }
                            }

                            $total_da_os_F = number_format($total_da_os, 2, ',', '.');
                            $total_serF = number_format($total_ser, 2, ',', '.');

                            $query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $nome_cli = $res_cat[0]['nome'];
                            $email_cli = $res_cat[0]['email'];

                            $query_cat = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $modelo = $res_cat[0]['modelo'];
                            $marca = $res_cat[0]['marca'];

                            $query_cat = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico' ");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $nome_mecanico = $res_cat[0]['nome'];

                            if($concluido == 'Sim'){
                                $cor_pago = 'text-success';
                                $status_badge = '<span class="badge badge-success">Concluído</span>';
                            } else {
                                $cor_pago = 'text-danger';
                                $status_badge = '<span class="badge badge-warning">Pendente</span>';
                            }
                        ?>
                        <tr>
                            <td>
                                <i class='fas fa-square mr-1 <?php echo $cor_pago ?>'></i>
                                <?php echo $nome_cli ?>
                            </td>
                            <td>R$ <?php echo $valor_mao_obraF ?></td>
                            <td>R$ <?php echo $total_serF ?></td>
                            <td><?php echo $descricao ?></td>
                            <td><?php echo $marca .' '.$modelo ?></td>
                            <td>
                                <?php echo $data_entrega ?>
                                <?php echo $status_badge ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <?php if($concluido == 'Não'){ ?>
                                        <?php if($tipo != 'Orçamento'){ ?>
                                            <a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>" 
                                               class="btn btn-sm btn-primary mr-1" title="Editar">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>" 
                                               class="btn btn-sm btn-danger mr-1" title="Excluir">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                        <?php } ?>
                                        <a href="index.php?pag=<?php echo $pag ?>&funcao=concluir&id=<?php echo $id ?>" 
                                           class="btn btn-sm btn-success mr-1" title="Concluir">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    <?php } ?>
                                    <a href="rel/rel_os.php?id=<?php echo $id ?>" target="_blank" 
                                       class="btn btn-sm btn-info" title="Imprimir">
                                        <i class="far fa-file-alt"></i>
                                    </a>
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
                    $titulo = "Editar Serviço";
                    $id2 = $_GET['id'];

                    $query = $pdo->query("SELECT * FROM os where id = '$id2' ");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    $cliente2 = $res[0]['cliente'];
                    $descricao2 = $res[0]['descricao'];
                    $valor2 = $res[0]['valor'];
                    $valor_mao_obra2 = $res[0]['valor_mao_obra'];
                    $data2 = $res[0]['data'];
                    $data_entrega2 = $res[0]['data_entrega'];
                    $concluido2 = $res[0]['concluido'];
                    $mecanico2 = $res[0]['mecanico'];
                    $garantia2 = $res[0]['garantia'];
                    $obs2 = $res[0]['obs'];
                } else {
                    $titulo = "Novo Serviço";
                }
                ?>
                <h5 class="modal-title" id="modalDadosLabel">
                    <i class="fas fa-tools mr-2"></i><?php echo $titulo ?>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
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

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Veículo</label>
                                <div id="div-veiculo" class="form-control">
                                    <?php if(@$funcao != 'editar') { ?>
                                    Selecione um cliente primeiro
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Serviço (Valor Tabelado)</label>
                                <select name="servico" class="form-control select2" id="servico" style="width:100%">
                                    <option value="">Selecione um Serviço</option>
                                    <?php 
                                    $query = $pdo->query("SELECT * FROM servicos where valor > 0 order by nome asc");
                                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    for ($i=0; $i < @count($res); $i++) { 
                                        $nome_reg = $res[$i]['nome'];
                                        $id_reg = $res[$i]['id'];
                                    ?>
                                    <option <?php if(@$servico2 == $id_reg){ ?> selected <?php } ?> value="<?php echo $id_reg ?>">
                                        <?php echo $nome_reg ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Data da Entrega</label>
                                <input value="<?php echo @$data_entrega2 ?>" type="date" class="form-control" id="data_entrega" name="data_entrega">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Garantia (Dias)</label>
                                <input value="<?php echo @$garantia2 ?>" type="text" class="form-control" id="garantia" name="garantia" placeholder="Total de Dias Garantia">
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
                <h5 class="modal-title" id="modal-deletarLabel">Excluir Serviço</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este serviço permanentemente?</p>
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

<!-- Modal de confirmação de conclusão -->
<div class="modal fade" id="modal-concluir" tabindex="-1" role="dialog" aria-labelledby="modal-concluirLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modal-concluirLabel">Concluir Serviço</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Deseja realmente marcar este serviço como concluído?</p>
                <div id="mensagem_concluir" class="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form method="post">
                    <input type="hidden" id="id" name="id" value="<?php echo @$_GET['id'] ?>" required>
                    <button type="button" id="btn-concluir" class="btn btn-success">
                        <i class="fas fa-check"></i> Concluir
                    </button>
                </form>
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

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "concluir") {
    echo "<script>$('#modal-concluir').modal('show');</script>";
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

<!-- AJAX para conclusão dos serviços -->
<script type="text/javascript">
$(document).ready(function() {
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
                $('#mensagem_concluir').html('<div class="alert alert-' + (mensagem
                        .trim() === 'Concluído com Sucesso!' ? 'success' : 'danger') +
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
        placeholder: 'Selecione um Serviço',
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

.badge {
    font-size: 85%;
    font-weight: 500;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.btn-group .btn {
    margin-right: 0.25rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.text-success {
    color: #1cc88a !important;
}

.text-danger {
    color: #e74a3b !important;
}

.text-warning {
    color: #f6c23e !important;
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

.badge-success {
    background-color: #1cc88a;
}

.badge-warning {
    background-color: #f6c23e;
    color: #1a1a1a;
}

.h3 {
    font-size: 1.75rem;
    font-weight: 500;
    line-height: 1.2;
}

.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
}

.shadow {
    box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15)!important;
}

.mr-1 {
    margin-right: 0.25rem!important;
}

.mr-2 {
    margin-right: 0.5rem!important;
}

.mb-4 {
    margin-bottom: 1.5rem!important;
}

.mt-3 {
    margin-top: 1rem!important;
}

.form-control {
    display: block;
    width: 100%;
    height: calc(1.5em + .75rem + 2px);
    padding: .375rem .75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #6e707e;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #d1d3e2;
    border-radius: .35rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}

.form-control:focus {
    color: #6e707e;
    background-color: #fff;
    border-color: #bac8f3;
    outline: 0;
    box-shadow: 0 0 0 .2rem rgba(78,115,223,.25);
}

.input-group-append {
    margin-left: -1px;
}

.input-group>.form-control:not(:last-child), .input-group>.custom-select:not(:last-child) {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.input-group>.input-group-append>.btn, .input-group>.input-group-append>.input-group-text, .input-group>.input-group-prepend:first-child>.btn:not(:first-child), .input-group>.input-group-prepend:first-child>.input-group-text:not(:first-child), .input-group>.input-group-prepend:not(:first-child)>.btn, .input-group>.input-group-prepend:not(:first-child)>.input-group-text {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.btn-primary {
    color: #fff;
    background-color: #7C3AED;
    border-color: #7C3AED;
}

.btn-primary:hover {
    color: #fff;
    background-color: #2e59d9;
    border-color: #2653d4;
}

.btn-secondary {
    color: #fff;
    background-color: #858796;
    border-color: #858796;
}

.btn-secondary:hover {
    color: #fff;
    background-color: #717384;
    border-color: #6b6d7d;
}

.btn-success {
    color: #fff;
    background-color: #1cc88a;
    border-color: #1cc88a;
}

.btn-success:hover {
    color: #fff;
    background-color: #17a673;
    border-color: #169b6b;
}

.btn-danger {
    color: #fff;
    background-color: #e74a3b;
    border-color: #e74a3b;
}

.btn-danger:hover {
    color: #fff;
    background-color: #e02d1b;
    border-color: #d52a1a;
}

.btn-info {
    color: #fff;
    background-color: #36b9cc;
    border-color: #36b9cc;
}

.btn-info:hover {
    color: #fff;
    background-color: #2c9faf;
    border-color: #2a96a5;
}

.btn-circle {
    border-radius: 100%;
    width: 3.5rem;
    height: 3.5rem;
    padding: 0;
    font-size: 1.5rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-circle.btn-sm {
    width: 2.5rem;
    height: 2.5rem;
    font-size: 1rem;
}

.alert {
    position: relative;
    padding: .75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: .35rem;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.text-white {
    color: #fff !important;
}

.close {
    float: right;
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    opacity: .5;
}

.close:hover {
    color: #000;
    text-decoration: none;
}

.close:not(:disabled):not(.disabled):hover, .close:not(:disabled):not(.disabled):focus {
    opacity: .75;
}

button.close {
    padding: 0;
    background-color: transparent;
    border: 0;
    -webkit-appearance: none;
}

.modal-header .close {
    padding: 1rem 1rem;
    margin: -1rem -1rem -1rem auto;
}

.fa, .far, .fas {
    font-family: "Font Awesome 5 Free";
}

.fa, .fas {
    font-weight: 900;
}

.far {
    font-weight: 400;
}
</style>