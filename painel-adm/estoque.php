<?php 
@session_start();
if(@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'admin'){
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

$pag = "estoque";
require_once("../conexao.php"); 
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-boxes mr-2"></i>Controle de Estoque
        </h1>
        <div class="alert alert-warning d-inline-block mb-0">
            <i class="fas fa-exclamation-triangle mr-2"></i>Produtos com estoque abaixo do mínimo
        </div>
    </div>

    <!-- Card de Estoque -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-danger text-white">
            <h6 class="m-0 font-weight-bold">Produtos com Estoque Baixo</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>Fornecedor</th>
                            <th>Valor Compra</th>
                            <th>Valor Venda</th>
                            <th>Estoque</th>
                            <th>Imagem</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = $pdo->query("SELECT * FROM produtos WHERE estoque < nivel_min ORDER BY estoque ASC");
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
                            $id = $res[$i]['id'];
                            $nivel_min = $res[$i]['nivel_min'];

                            if($estoque < $nivel_min){
                                $cor = "text-danger";
                                $badge = "<span class='badge badge-danger'>Baixo</span>";
                            } else {
                                $cor = "";
                                $badge = "";
                            }

                            $valor_compra = number_format($valor_compra, 2, ',', '.');
                            $valor_venda = number_format($valor_venda, 2, ',', '.');

                            // Busca categoria
                            $query_cat = $pdo->query("SELECT * FROM categorias WHERE id = '$categoria'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $nome_cate = $res_cat[0]['nome'];

                            // Busca fornecedor
                            $query_forn = $pdo->query("SELECT * FROM fornecedores WHERE id = '$fornecedor'");
                            $res_forn = $query_forn->fetchAll(PDO::FETCH_ASSOC);
                            $nome_forn = $res_forn[0]['nome'];
                            $cpf_forn = $res_forn[0]['cpf'];
                        ?>
                        <tr>
                            <td><?php echo $nome ?></td>
                            <td><?php echo $nome_cate ?></td>
                            <td>
                                <a class="text-primary" title="Ver Dados do Fornecedor" data-toggle="tooltip" 
                                   href="index.php?pag=<?php echo $pag ?>&funcao=forn&id=<?php echo $fornecedor ?>">
                                    <?php echo $nome_forn ?>
                                </a>
                            </td>
                            <td>R$ <?php echo $valor_compra ?></td>
                            <td>R$ <?php echo $valor_venda ?></td>
                            <td>
                                <span class="<?php echo $cor ?>"><?php echo $estoque ?></span>
                                <?php echo $badge ?>
                            </td>
                            <td><img src="../img/produtos/<?php echo $imagem ?>" width="50" class="img-thumbnail"></td>
                            <td class="text-center">
                                <a href="index.php?pag=<?php echo $pag ?>&funcao=pedido&id=<?php echo $id ?>" 
                                   class='btn btn-sm btn-success' title='Fazer Pedido' data-toggle="tooltip">
                                   <i class='fas fa-plus'></i> Pedido
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

<!-- Modal para Pedido de Estoque -->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="modalDadosLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <?php 
                if (@$_GET['funcao'] == 'pedido') {
                    $titulo = "Pedido de Estoque";
                    $id2 = $_GET['id'];

                    $query = $pdo->query("SELECT * FROM produtos WHERE id = '$id2'");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    $fornecedor2 = $res[0]['fornecedor'];
                    $valor_compra2 = $res[0]['valor_compra'];
                    $valor_venda2 = $res[0]['valor_venda'];
                    $estoque2 = $res[0]['estoque'];
                    $nome_produto = $res[0]['nome'];
                }
                ?>
                <h5 class="modal-title" id="modalDadosLabel">
                    <i class="fas fa-cart-plus mr-2"></i><?php echo $titulo ?>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Produto</label>
                        <input type="text" class="form-control" value="<?php echo @$nome_produto ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Fornecedor</label>
                        <select name="fornecedor" class="form-control select2" id="fornecedor" style="width:100%">
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
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Valor Compra</label>
                                <input value="<?php echo @$valor_compra2 ?>" type="text" class="form-control money" id="valor_compra" name="valor_compra" placeholder="Valor da Compra">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Valor Venda</label>
                                <input value="<?php echo @$valor_venda2 ?>" type="text" class="form-control money" id="valor_venda" name="valor_venda" placeholder="Valor da Venda">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Quantidade</label>
                        <input value="" type="number" min="1" class="form-control" id="quantidade" name="quantidade" placeholder="Quantidade a Comprar">
                    </div>
                    
                    <div id="mensagem" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <input value="<?php echo @$_GET['id'] ?>" type="hidden" name="txtid2" id="txtid2">
                    <button type="button" id="btn-fechar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btn-salvar" id="btn-salvar" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Salvar Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Dados do Fornecedor -->
<div class="modal fade" id="modal-forn" tabindex="-1" role="dialog" aria-labelledby="modal-fornLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modal-fornLabel">
                    <i class="fas fa-truck mr-2"></i>Dados do Fornecedor
                </h5>
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
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5 class="text-primary"><?php echo $nome3 ?></h5>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-id-card mr-2"></i>CPF/CNPJ:</strong><br>
                        <?php echo $cpf3 ?></p>
                        
                        <p><strong><i class="fas fa-phone mr-2"></i>Telefone:</strong><br>
                        <?php echo $telefone3 ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-envelope mr-2"></i>Email:</strong><br>
                        <?php echo $email3 ?></p>
                        
                        <p><strong><i class="fas fa-map-marker-alt mr-2"></i>Endereço:</strong><br>
                        <?php echo $endereco3 ?></p>
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
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "pedido") {
    echo "<script>$('#modalDados').modal('show');</script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "forn") {
    echo "<script>$('#modal-forn').modal('show');</script>";
}
?>

<!-- Scripts JavaScript -->
<script type="text/javascript">
$(document).ready(function() {
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
            processData: false
        });
    });

    // Configurações da tabela
    $('#dataTable').dataTable({
        "ordering": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        }
    });

    // Inicializa tooltips
    $('[data-toggle="tooltip"]').tooltip();
});

// Máscara para valores monetários
$('.money').mask('#.##0,00', {reverse: true});
</script>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Selecione um Fornecedor',
        width: '100%'
    });
});
</script>

<!-- Máscaras -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

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

.bg-dark {
    background-color: #5a5c69 !important;
}

.text-white {
    color: white !important;
}

.img-thumbnail {
    max-width: 50px;
    height: auto;
}

.badge {
    font-size: 0.75em;
    margin-left: 5px;
}
</style>