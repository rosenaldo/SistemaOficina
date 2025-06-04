<?php 
@session_start();
if(@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'admin'){
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

$pag = "servicos";
require_once("../conexao.php"); 
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tools mr-2"></i>Serviços
        </h1>
        <a href="index.php?pag=<?php echo $pag ?>&funcao=novo"
            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Novo Serviço
        </a>
    </div>

    <!-- Card de Serviços -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Lista de Serviços</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Valor</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = $pdo->query("SELECT * FROM servicos ORDER BY nome ASC");
                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
                        
                        for ($i=0; $i < @count($res); $i++) { 
                            $nome = $res[$i]['nome'];
                            $valor = $res[$i]['valor'];
                            $id = $res[$i]['id'];

                            $valor = number_format($valor, 2, ',', '.');
                        ?>
                        <tr>
                            <td><?php echo $nome ?></td>
                            <td>R$ <?php echo $valor ?></td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>"
                                        class="btn btn-sm btn-primary mr-1" title="Editar"><i
                                            class="far fa-edit"></i></a>
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>"
                                        class="btn btn-sm btn-danger" title="Excluir"><i
                                            class="far fa-trash-alt"></i></a>
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

<!-- Modal para Cadastro/Edição -->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="modalDadosLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <?php 
                if (@$_GET['funcao'] == 'editar') {
                    $titulo = "Editar Serviço";
                    $id2 = $_GET['id'];

                    $query = $pdo->query("SELECT * FROM servicos WHERE id = '$id2'");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    $nome2 = $res[0]['nome'];
                    $valor2 = $res[0]['valor'];
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
                    <div class="form-group">
                        <label>Nome do Serviço</label>
                        <input value="<?php echo @$nome2 ?>" type="text" class="form-control" id="nome_reg" 
                            name="nome_reg" placeholder="Digite o nome do serviço" required>
                    </div>

                    <div class="form-group">
                        <label>Valor (R$)</label>
                        <input value="<?php echo @$valor2 ?>" type="text" class="form-control" id="valor_reg" 
                            name="valor_reg" placeholder="Digite o valor do serviço" required>
                    </div>

                    <div id="mensagem" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <input value="<?php echo @$_GET['id'] ?>" type="hidden" name="txtid2" id="txtid2">
                    <input value="<?php echo @$nome2 ?>" type="hidden" name="antigo" id="antigo">

                    <button type="button" id="btn-fechar" class="btn btn-secondary"
                        data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btn-salvar" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="modal-deletar" tabindex="-1" role="dialog" aria-labelledby="modal-deletarLabel"
    aria-hidden="true">
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    id="btn-cancelar-excluir">Cancelar</button>
                <form method="post">
                    <input type="hidden" id="id" name="id" value="<?php echo @$_GET['id'] ?>" required>
                    <button type="button" id="btn-deletar" class="btn btn-danger">Confirmar Exclusão</button>
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
?>

<!-- Scripts JavaScript -->
<script type="text/javascript">
// Formulário principal
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

// Exclusão de serviço
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
                    .trim() === 'Excluído com Sucesso!' ? 'success' : 'danger') + '">' +
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

// Máscara para o campo de valor
$(document).ready(function(){
    $('#valor_reg').mask('###0,00', {reverse: true});
});
</script>

<!-- Estilos personalizados -->
<style>
.card-header {
    border-radius: 0.35rem 0.35rem 0 0 !important;
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

.bg-primary {
    background-color: #7C3AED !important;
}

.bg-danger {
    background-color: #e74a3b !important;
}

.text-white {
    color: white !important;
}

/* Estilo para tabela responsiva */
.table-responsive {
    overflow-x: auto;
}

/* Ajuste para ícones de ação */
.btn-group .btn i {
    font-size: 0.8rem;
}

/* Espaçamento entre botões */
.btn-group .btn {
    margin-right: 0.25rem;
}

/* Hover nas linhas da tabela */
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}
</style>