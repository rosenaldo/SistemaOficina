<?php 
@session_start();
if(@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'admin'){
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

$pag = "fornecedores";
require_once("../conexao.php"); 

$tipo_pessoa2 = "Física";
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-truck mr-2"></i>Fornecedores
        </h1>
        <a href="index.php?pag=<?php echo $pag ?>&funcao=novo"
            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Novo Fornecedor
        </a>
        <a href="index.php?pag=<?php echo $pag ?>&funcao=novo"
            class="d-block d-sm-none btn btn-primary btn-circle">
            <i class="fas fa-plus"></i>
        </a>
    </div>

    <!-- Card de Fornecedores -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Lista de Fornecedores</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Tipo Pessoa</th>
                            <th>CPF/CNPJ</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = $pdo->query("SELECT * FROM fornecedores ORDER BY nome ASC");
                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
                        
                        for ($i=0; $i < @count($res); $i++) { 
                            $nome = $res[$i]['nome'];
                            $tipo_pessoa = $res[$i]['tipo_pessoa'];
                            $cpf = $res[$i]['cpf'];
                            $telefone = $res[$i]['telefone'];
                            $email = $res[$i]['email'];
                            $endereco = $res[$i]['endereco'];
                            $id = $res[$i]['id'];
                        ?>
                        <tr>
                            <td><?php echo $nome ?></td>
                            <td><?php echo $tipo_pessoa ?></td>
                            <td><?php echo $cpf ?></td>
                            <td><?php echo $telefone ?></td>
                            <td><?php echo $email ?></td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>"
                                        class="btn btn-sm btn-primary mr-1" title="Editar"><i class="far fa-edit"></i></a>
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>"
                                        class="btn btn-sm btn-danger" title="Excluir"><i class="far fa-trash-alt"></i></a>
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
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="modalDadosLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <?php 
                if (@$_GET['funcao'] == 'editar') {
                    $titulo = "Editar Fornecedor";
                    $id2 = $_GET['id'];

                    $query = $pdo->query("SELECT * FROM fornecedores WHERE id = '$id2'");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    $nome2 = $res[0]['nome'];
                    $tipo_pessoa2 = $res[0]['tipo_pessoa'];
                    $cpf2 = $res[0]['cpf'];
                    $telefone2 = $res[0]['telefone'];
                    $email2 = $res[0]['email'];
                    $endereco2 = $res[0]['endereco'];
                } else {
                    $titulo = "Novo Fornecedor";
                }
                ?>
                <h5 class="modal-title" id="modalDadosLabel">
                    <i class="fas fa-truck mr-2"></i><?php echo $titulo ?>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nome</label>
                                <input value="<?php echo @$nome2 ?>" type="text" class="form-control" id="nome_mec" name="nome_mec" placeholder="Nome completo" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo Pessoa</label>
                                <select name="tipo_pessoa" class="form-control" id="pessoa">
                                    <option <?php if($tipo_pessoa2 == 'Física'){ ?> selected <?php } ?> value="Física">Física</option>
                                    <option <?php if($tipo_pessoa2 == 'Jurídica'){ ?> selected <?php } ?> value="Jurídica">Jurídica</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6" id="divcpf">
                            <div class="form-group">
                                <label>CPF</label>
                                <input value="<?php echo @$cpf2 ?>" type="text" class="form-control cpf" id="cpf" name="cpf_mec" placeholder="000.000.000-00">
                            </div>
                        </div>

                        <div class="col-md-6" id="divcnpj">
                            <div class="form-group">
                                <label>CNPJ</label>
                                <input value="<?php echo @$cpf2 ?>" type="text" class="form-control cnpj" id="cnpj" name="cnpj_mec" placeholder="00.000.000/0000-00">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Telefone</label>
                                <input value="<?php echo @$telefone2 ?>" type="text" class="form-control telefone" id="telefone" name="telefone_mec" placeholder="(00) 00000-0000" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input value="<?php echo @$email2 ?>" type="email" class="form-control" id="email" name="email_mec" placeholder="email@exemplo.com">
                    </div>

                    <div class="form-group">
                        <label>Endereço</label>
                        <input value="<?php echo @$endereco2 ?>" type="text" class="form-control" id="endereco" name="endereco_mec" placeholder="Endereço completo">
                    </div>

                    <div id="mensagem" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <input value="<?php echo @$_GET['id'] ?>" type="hidden" name="txtid2" id="txtid2">
                    <input value="<?php echo @$cpf2 ?>" type="hidden" name="antigo" id="antigo">
                    <input value="<?php echo @$email2 ?>" type="hidden" name="antigo2" id="antigo2">

                    <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btn-salvar" class="btn btn-primary">
						<i class="fas fa-save"></i> Salvar
					</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="modal-deletar" tabindex="-1" role="dialog" aria-labelledby="modal-deletarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modal-deletarLabel">Excluir Fornecedor</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este fornecedor permanentemente?</p>
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

// Exclusão de fornecedor
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
                $('#mensagem_excluir').html('<div class="alert alert-' + (mensagem.trim() === 'Excluído com Sucesso!' ? 'success' : 'danger') + '">' + mensagem + '</div>');
            },
        })
    })
});

// Configurações da tabela e máscaras
$(document).ready(function() {
    $('#dataTable').dataTable({
        "ordering": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        }
    });

    // Máscaras para CPF, CNPJ e Telefone
    $('.cpf').mask('000.000.000-00', {reverse: true});
    $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
    $('.telefone').mask('(00) 00000-0000');
});

// Alternar entre CPF e CNPJ
var pessoa = "<?=$tipo_pessoa2?>";
$(document).ready(function() {
    if(pessoa === "Física") {
        document.getElementById('divcnpj').style.display = "none"; 
    } else {
        document.getElementById('divcpf').style.display = "none";
    }
});

$('#pessoa').change(function(event) {
    var select = document.getElementById('pessoa');
    var value = select.options[select.selectedIndex].value;
    if(value === 'Física') {
        document.getElementById('divcnpj').style.display = "none";
        document.getElementById('divcpf').style.display = "block";
    } else {
        document.getElementById('divcnpj').style.display = "block";
        document.getElementById('divcpf').style.display = "none";
    }
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

.btn-circle {
    width: 40px;
    height: 40px;
    padding: 6px 0;
    border-radius: 20px;
    text-align: center;
    font-size: 12px;
    line-height: 1.42857;
}

/* Ajuste para alternância CPF/CNPJ */
#divcpf, #divcnpj {
    transition: all 0.3s ease;
}
</style>