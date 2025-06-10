<?php 
@session_start();
require_once("verificar_usuario.php");

$pag = "pagar";
require_once("../conexao.php"); 

$data_venc2 = date('Y-m-d');
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-invoice-dollar mr-2"></i>Contas a Pagar
        </h1>
        <a href="index.php?pag=<?php echo $pag ?>&funcao=novo"
            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nova Conta
        </a>
    </div>

    <!-- Card de Contas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Contas Pendentes</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Descrição</th>
                            <th>Fornecedor</th>
                            <th>Valor</th>
                            <th>Vencimento</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = $pdo->query("SELECT * FROM contas_pagar ORDER BY pago ASC, data_venc ASC");
                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
                        
                        for ($i=0; $i < @count($res); $i++) { 
                            $descricao = $res[$i]['descricao'];
                            $valor = $res[$i]['valor'];
                            $funcionario = $res[$i]['funcionario'];
                            $data_venc = $res[$i]['data_venc'];
                            $pago = $res[$i]['pago'];
                            $imagem = $res[$i]['imagem'];
                            $fornecedor = $res[$i]['fornecedor'];
                            $id = $res[$i]['id'];

                            // Formatações
                            $data_venc = implode('/', array_reverse(explode('-', $data_venc)));
                            $valor = number_format($valor, 2, ',', '.');

                            // Busca fornecedor
                            $query_forn = $pdo->query("SELECT * FROM fornecedores WHERE id = '$fornecedor'");
                            $res_forn = $query_forn->fetchAll(PDO::FETCH_ASSOC);
                            $nome_forn = @count($res_forn) > 0 ? $res_forn[0]['nome'] : '';

                            // Status
                            if($pago == 'Sim') {
                                $status = '<span class="badge badge-success">Pago</span>';
                                $cor_linha = 'text-muted';
                            } else {
                                $status = '<span class="badge badge-danger">Pendente</span>';
                                $cor_linha = '';
                                
                                // Verifica se está vencido
                                $data_atual = date('Y-m-d');
                                if($data_venc < $data_atual) {
                                    $status = '<span class="badge badge-warning">Vencido</span>';
                                }
                            }
                        ?>
                        <tr class="<?php echo $cor_linha ?>">
                            <td>
                                <?php if($descricao == 'Compra de Produtos') { ?>
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=compra&id=<?php echo $id ?>" title="Ver Detalhes">
                                        <?php echo $descricao ?>
                                    </a>
                                <?php } else { ?>
                                    <?php echo $descricao ?>
                                <?php } ?>
                            </td>
                            <td><?php echo $nome_forn ?></td>
                            <td>R$ <?php echo $valor ?></td>
                            <td><?php echo $data_venc ?></td>
                            <td><?php echo $status ?></td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <?php if($pago != 'Sim') { ?>
                                        <?php if($descricao != 'Compra de Produtos') { ?>
                                            <a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>"
                                                class="btn btn-sm btn-primary mr-1" title="Editar">
                                                <i class="far fa-edit"></i>
                                            </a>
                                        <?php } ?>
                                        <a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>"
                                            class="btn btn-sm btn-danger mr-1" title="Excluir">
                                            <i class="far fa-trash-alt"></i>
                                        </a>
                                        <a href="index.php?pag=<?php echo $pag ?>&funcao=aprovar&id=<?php echo $id ?>"
                                            class="btn btn-sm btn-success" title="Marcar como Pago">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    <?php } ?>
                                    <?php if($imagem != "" && $imagem != "sem-foto.jpg") { ?>
                                        <a href="../img/contas/<?php echo $imagem ?>" target="_blank"
                                            class="btn btn-sm btn-info ml-1" title="Ver Arquivo">
                                            <i class="far fa-file-alt"></i>
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

<!-- Modal para Cadastro/Edição -->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="modalDadosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <?php 
                if (@$_GET['funcao'] == 'editar') {
                    $titulo = "Editar Conta";
                    $id2 = $_GET['id'];

                    $query = $pdo->query("SELECT * FROM contas_pagar WHERE id = '$id2'");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    $descricao2 = $res[0]['descricao'];
                    $valor2 = $res[0]['valor'];
                    $data_venc2 = $res[0]['data_venc'];
                    $imagem2 = $res[0]['imagem'];
                    $fornecedor2 = $res[0]['fornecedor'];
                } else {
                    $titulo = "Nova Conta a Pagar";
                }
                ?>
                <h5 class="modal-title" id="modalDadosLabel">
                    <i class="fas fa-file-invoice-dollar mr-2"></i><?php echo $titulo ?>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fornecedor</label>
                                <select name="fornecedor" class="form-control select2" id="fornecedor" style="width:100%">
                                    <option value="">Selecione um Fornecedor</option>
                                    <?php 
                                    $query = $pdo->query("SELECT * FROM fornecedores ORDER BY nome ASC");
                                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    for ($i=0; $i < @count($res); $i++) { 
                                        $nome_reg = $res[$i]['nome'];
                                        $id_reg = $res[$i]['id'];
                                    ?>
                                    <option <?php if(@$fornecedor2 == $id_reg){ ?> selected <?php } ?> value="<?php echo $id_reg ?>">
                                        <?php echo $nome_reg ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Descrição</label>
                                <input value="<?php echo @$descricao2 ?>" type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição da conta" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Valor</label>
                                        <input value="<?php echo @$valor2 ?>" type="text" class="form-control money" id="valor" name="valor" placeholder="Valor" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Data Vencimento</label>
                                        <input value="<?php echo @$data_venc2 ?>" type="date" class="form-control" id="data_venc" name="data_venc" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Comprovante</label>
                                <input type="file" class="form-control-file" id="imagem" name="imagem" onChange="carregarImg();">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12 text-center">
                            <div id="divImgConta">
                                <?php if(@$imagem2 != "") { ?>
                                    <?php if(pathinfo(@$imagem2, PATHINFO_EXTENSION) == 'pdf') { ?>
                                        <img src="../img/pdf-icon.png" width="150" id="target" class="img-thumbnail">
                                    <?php } else { ?>
                                        <img src="../img/contas/<?php echo $imagem2 ?>" width="150" id="target" class="img-thumbnail">
                                    <?php } ?>
                                <?php } else { ?>
                                    <img src="../img/sem-foto.jpg" width="150" id="target" class="img-thumbnail">
                                <?php } ?>
                            </div>
                        </div>
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
                <p>Deseja realmente marcar esta conta como paga?</p>
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

<!-- Modal de Detalhes da Compra -->
<div class="modal fade" id="modal-compra" tabindex="-1" role="dialog" aria-labelledby="modal-compraLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modal-compraLabel">Detalhes da Compra</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                if (@$_GET['funcao'] == 'compra') {
                    $id2 = $_GET['id'];
                    $query = $pdo->query("SELECT * FROM compras WHERE id_conta = '$id2'");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    $produto = $res[0]['produto'];
                    $valor = $res[0]['valor'];
                    $funcionario = $res[0]['funcionario'];
                    $data = $res[0]['data'];

                    $valor_f = number_format($valor, 2, ',', '.');
                    $data_f = implode('/', array_reverse(explode('-', $data)));
                    
                    $query_prod = $pdo->query("SELECT * FROM produtos WHERE id = '$produto'");
                    $res_prod = $query_prod->fetchAll(PDO::FETCH_ASSOC);
                    $nome_produto = $res_prod[0]['nome'];
                    $img_produto = $res_prod[0]['imagem'];

                    $query_prod = $pdo->query("SELECT * FROM usuarios WHERE cpf = '$funcionario'");
                    $res_prod = $query_prod->fetchAll(PDO::FETCH_ASSOC);
                    $nome_funcionario = $res_prod[0]['nome'];
                }
                ?>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="../img/produtos/<?php echo $img_produto ?>" class="img-fluid rounded img-thumbnail" style="max-height: 200px;">
                    </div>
                    <div class="col-md-8">
                        <h5 class="font-weight-bold"><?php echo $nome_produto ?></h5>
                        <hr>
                        <p><strong>Valor:</strong> R$ <?php echo $valor_f ?></p>
                        <p><strong>Data da Compra:</strong> <?php echo $data_f ?></p>
                        <p><strong>Responsável:</strong> <?php echo $nome_funcionario ?></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<?php 
// Abre modais conforme a função chamada
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "novo") {
    echo "<script>$('#modalDados').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "editar") {
    echo "<script>$('#modalDados').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "excluir") {
    echo "<script>$('#modal-deletar').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "aprovar") {
    echo "<script>$('#modal-aprovar').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "compra") {
    echo "<script>$('#modal-compra').modal('show');</script>";
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

    // Select2 para o campo de fornecedor
    $('.select2').select2({
        placeholder: 'Selecione um fornecedor',
        width: '100%'
    });
});

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

// Função para carregar pré-visualização da imagem
function carregarImg() {
    var target = document.getElementById('target');
    var file = document.querySelector("input[type=file]").files[0];
    var arquivo = file['name'];
    var resultado = arquivo.split(".", 2);

    if(resultado[1] === 'pdf') {
        $('#target').attr('src', "../img/pdf-icon.png");
        return;
    }

    var reader = new FileReader();
    reader.onloadend = function() {
        target.src = reader.result;
    };

    if (file) {
        reader.readAsDataURL(file);
    } else {
        target.src = "";
    }
}
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

.bg-info {
    background-color: #36b9cc !important;
}

.text-white {
    color: white !important;
}

.select2-selection__rendered {
    line-height: 36px !important;
    font-size: 14px !important;
}

.select2-selection {
    height: 38px !important;
    border: 1px solid #ced4da !important;
}

.select2-selection__arrow {
    height: 36px !important;
}

.img-thumbnail {
    max-height: 200px;
    object-fit: contain;
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