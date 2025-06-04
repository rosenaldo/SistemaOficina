<?php 
@session_start();
if(@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'admin'){
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

$pag = "produtos";
require_once("../conexao.php"); 
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-boxes mr-2"></i>Produtos
        </h1>
        <a href="index.php?pag=<?php echo $pag ?>&funcao=novo" 
           class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Novo Produto
        </a>
    </div>

    <!-- Card de Produtos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Lista de Produtos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>Fornecedor</th>
                            <th>Fabricante</th>
                            <th>Valor Compra</th>
                            <th>Valor Venda</th>
                            <th>Estoque</th>
                            <th>Imagem</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = $pdo->query("SELECT * FROM produtos ORDER BY id DESC");
                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
                        
                        for ($i=0; $i < @count($res); $i++) { 
                            $nome = $res[$i]['nome'];
                            $categoria = $res[$i]['categoria'];
                            $fornecedor = $res[$i]['fornecedor'];
                            $valor_compra = $res[$i]['valor_compra'];
                            $valor_venda = $res[$i]['valor_venda'];
                            $estoque = $res[$i]['estoque'];
                            $descricao = $res[$i]['descricao'];
                            $imagem = $res[$i]['imagem'];
                            $nivel_min = $res[$i]['nivel_min'];
                            $fabricante = $res[$i]['fabricante'];
                            $id = $res[$i]['id'];

                            if($estoque < $nivel_min){
                                $cor = "text-danger";
                            } else {
                                $cor = "";
                            }

                            $valor_compra = number_format($valor_compra, 2, ',', '.');
                            $valor_venda = number_format($valor_venda, 2, ',', '.');

                            // Busca categoria
                            $query_cat = $pdo->query("SELECT * FROM categorias WHERE id = '$categoria'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $nome_cate = @$res_cat[0]['nome'];

                            // Busca fornecedor
                            $query_forn = $pdo->query("SELECT * FROM fornecedores WHERE id = '$fornecedor'");
                            $res_forn = $query_forn->fetchAll(PDO::FETCH_ASSOC);
                            $nome_forn = @$res_forn[0]['nome'];
                        ?>
                        <tr>
                            <td><?php echo $nome ?></td>
                            <td><?php echo $nome_cate ?></td>
                            <td>
                                <a class="text-primary" title="Ver Dados do Fornecedor" 
                                   href="index.php?pag=<?php echo $pag ?>&funcao=forn&id=<?php echo $fornecedor ?>">
                                    <?php echo $nome_forn ?>
                                </a>
                            </td>
                            <td><?php echo $fabricante ?></td>
                            <td>R$ <?php echo $valor_compra ?></td>
                            <td>R$ <?php echo $valor_venda ?></td>
                            <td><span class="<?php echo $cor ?>"><?php echo $estoque ?></span></td>
                            <td><img src="../img/produtos/<?php echo $imagem ?>" width="50"></td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=editar&id=<?php echo $id ?>" 
                                       class="btn btn-sm btn-primary mr-1" title="Editar"><i class="far fa-edit"></i></a>
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>" 
                                       class="btn btn-sm btn-danger mr-1" title="Excluir"><i class="far fa-trash-alt"></i></a>
                                    <a href="#" onclick="mostrarDescricao('<?php echo $descricao ?>', '<?php echo $imagem ?>')" 
                                       class="btn btn-sm btn-info mr-1" title="Descrição"><i class="fas fa-info-circle"></i></a>
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=pedido&id=<?php echo $id ?>" 
                                       class="btn btn-sm btn-success" title="Pedido"><i class="fas fa-plus"></i></a>
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
                    $titulo = "Editar Produto";
                    $id2 = $_GET['id'];

                    $query = $pdo->query("SELECT * FROM produtos WHERE id = '$id2'");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    $nome2 = $res[0]['nome'];
                    $categoria2 = $res[0]['categoria'];
                    $fornecedor2 = $res[0]['fornecedor'];
                    $valor_compra2 = $res[0]['valor_compra'];
                    $valor_venda2 = $res[0]['valor_venda'];
                    $estoque2 = $res[0]['estoque'];
                    $descricao2 = $res[0]['descricao'];
                    $imagem2 = $res[0]['imagem'];
                    $nivel_min = $res[0]['nivel_min'];
                    $fabricante = $res[0]['fabricante'];
                } else {
                    $titulo = "Novo Produto";
                }
                ?>
                <h5 class="modal-title" id="modalDadosLabel">
                    <i class="fas fa-box mr-2"></i><?php echo $titulo ?>
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
                                <input value="<?php echo @$nome2 ?>" type="text" class="form-control" id="nome_reg" name="nome_reg" placeholder="Nome do Produto" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fabricante</label>
                                <input value="<?php echo @$fabricante ?>" type="text" class="form-control" id="fabricante" name="fabricante" placeholder="Fabricante">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Categoria</label>
                                <select name="categoria" class="form-control select2" id="categoria" style="width:100%" required>
                                    <option value="">Selecione uma Categoria</option>
                                    <?php 
                                    $query = $pdo->query("SELECT * FROM categorias ORDER BY nome ASC");
                                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    for ($i=0; $i < @count($res); $i++) { 
                                        $nome_reg = $res[$i]['nome'];
                                        $id_reg = $res[$i]['id'];
                                    ?>
                                    <option <?php if(@$categoria2 == $id_reg){ ?> selected <?php } ?> value="<?php echo $id_reg ?>">
                                        <?php echo $nome_reg ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fornecedor</label>
                                <select name="fornecedor" class="form-control select2" id="fornecedor" style="width:100%" required>
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
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nível Mínimo Estoque</label>
                                <input value="<?php echo @$nivel_min ?>" type="number" class="form-control" id="nivel_min" name="nivel_min" placeholder="Nível Mínimo" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Valor Compra</label>
                                <input value="<?php echo @$valor_compra2 ?>" type="text" class="form-control money" id="valor_compra" name="valor_compra" placeholder="Valor de Compra" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Valor Venda</label>
                                <input value="<?php echo @$valor_venda2 ?>" type="text" class="form-control money" id="valor_venda" name="valor_venda" placeholder="Valor de Venda" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Estoque Atual</label>
                                <input value="<?php echo @$estoque2 ?>" type="number" class="form-control" id="estoque" name="estoque" placeholder="Quantidade em Estoque" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="3"><?php echo @$descricao2 ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Imagem</label>
                                <input type="file" class="form-control-file" id="imagem" name="imagem" onChange="carregarImg()">
                            </div>
                            <?php if(@$imagem2 != ""){ ?>
                                <img src="../img/produtos/<?php echo $imagem2 ?>" width="100" height="100" id="target">
                            <?php } else { ?>
                                <img src="../img/produtos/sem-foto.jpg" width="100" height="100" id="target">
                            <?php } ?>
                        </div>
                    </div>

                    <div id="mensagem" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <input value="<?php echo @$_GET['id'] ?>" type="hidden" name="txtid2" id="txtid2">
                    <input value="<?php echo @$nome2 ?>" type="hidden" name="antigo" id="antigo">

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
                <h5 class="modal-title" id="modal-deletarLabel">Excluir Produto</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este produto permanentemente?</p>
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

<!-- Modal de Descrição do Produto -->
<div class="modal fade" id="modal-descricao" tabindex="-1" role="dialog" aria-labelledby="modal-descricaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modal-descricaoLabel">Descrição do Produto</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <img src="" width="100%" id="imagemDescricao">
                    </div>
                    <div class="col-md-6">
                        <span id="spanDescricao"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Dados do Fornecedor -->
<div class="modal fade" id="modal-forn" tabindex="-1" role="dialog" aria-labelledby="modal-fornLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modal-fornLabel">Dados do Fornecedor</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                if (@$_GET['funcao'] == 'forn') {
                    $id2 = $_GET['id'];
                    $query = $pdo->query("SELECT * FROM fornecedores WHERE id = '$id2'");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    $nome3 = $res[0]['nome'];
                    $cpf3 = $res[0]['cpf'];
                    $telefone3 = $res[0]['telefone'];
                    $email3 = $res[0]['email'];
                    $endereco3 = $res[0]['endereco'];
                }
                ?>
                <p><b>Nome: </b> <?php echo $nome3 ?></p>
                <p><b>CPF: </b> <?php echo $cpf3 ?></p>
                <p><b>Telefone: </b> <?php echo $telefone3 ?></p>
                <p><b>Email: </b> <?php echo $email3 ?></p>
                <p><b>Endereço: </b> <?php echo $endereco3 ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Pedido de Estoque -->
<div class="modal fade" id="modal-compra" tabindex="-1" role="dialog" aria-labelledby="modal-compraLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modal-compraLabel">Pedido de Estoque</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-pedido" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Fornecedor</label>
                        <select name="fornecedor" class="form-control select2" id="fornecedor" style="width:100%" required>
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
                        <label>Valor Compra</label>
                        <input value="<?php echo @$valor_compra2 ?>" type="text" class="form-control money" id="valor_compra" name="valor_compra" placeholder="Valor de Compra" required>
                    </div>
                    <div class="form-group">
                        <label>Valor Venda</label>
                        <input value="<?php echo @$valor_venda2 ?>" type="text" class="form-control money" id="valor_venda" name="valor_venda" placeholder="Valor de Venda" required>
                    </div>
                    <div class="form-group">
                        <label>Quantidade</label>
                        <input value="" type="number" class="form-control" id="quantidade" name="quantidade" placeholder="Quantidade a Comprar" required>
                    </div>
                    <div id="mensagem_pedido" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <input value="<?php echo @$_GET['id'] ?>" type="hidden" name="txtid2" id="txtid2">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Confirmar Pedido
                    </button>
                </div>
            </form>
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

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "forn") {
    echo "<script>$('#modal-forn').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "pedido") {
    echo "<script>$('#modal-compra').modal('show');</script>";
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

// Formulário de pedido
$("#form-pedido").submit(function() {
    var pag = "<?=$pag?>";
    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: pag + "/pedido.php",
        type: 'POST',
        data: formData,
        success: function(mensagem) {
            $('#mensagem_pedido').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso!") {
                $('#btn-fechar').click();
                window.location = "index.php?pag=" + pag;
            } else {
                $('#mensagem_pedido').addClass('alert alert-danger')
            }
            $('#mensagem_pedido').text(mensagem)
        },
        cache: false,
        contentType: false,
        processData: false
    });
});

// Exclusão de produto
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

// Carregar imagem
function carregarImg() {
    var target = document.getElementById('target');
    var file = document.querySelector("input[type=file]").files[0];
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

// Mostrar descrição
function mostrarDescricao(descricao, imagem) {
    event.preventDefault();
    $('#spanDescricao').text(descricao);
    $('#imagemDescricao').attr('src', "../img/produtos/" + imagem);
    $('#modal-descricao').modal('show');
}

// Configurações da tabela
$(document).ready(function() {
    $('#dataTable').dataTable({
        "ordering": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        }
    });

    // Máscara para valores monetários
    $('.money').mask('000.000.000.000.000,00', {reverse: true});
});
</script>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Selecione uma opção',
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
    background-color: #e6f7ff !important;
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

.bg-info {
    background-color: #36b9cc !important;
}

.text-white {
    color: white !important;
}

#target {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
    object-fit: cover;
}

#imagemDescricao {
    max-height: 400px;
    object-fit: contain;
}

#spanDescricao {
    white-space: pre-line;
    font-size: 16px;
    line-height: 1.6;
}
</style>