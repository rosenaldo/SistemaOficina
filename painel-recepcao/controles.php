<?php 
@session_start();
require_once("verificar_usuario.php");

$pag = "controles";
require_once("../conexao.php"); 
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-clipboard-list mr-2"></i>Controle de Serviços
        </h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Registros de Serviços</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Modelo</th>
                            <th>Placa</th>
                            <th>Cliente</th>
                            <th>Mecânico</th>
                            <th>Data Entrada</th>
                            <th>Serviço</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query_c = $pdo->query("SELECT * FROM controles ORDER BY id ASC");
                        $res_c = $query_c->fetchAll(PDO::FETCH_ASSOC);
                        
                        for ($i=0; $i < @count($res_c); $i++) { 
                            foreach ($res_c[$i] as $key => $value) {
                            }
                            
                            $veiculo = $res_c[$i]['veiculo'];
                            $mecanico = $res_c[$i]['mecanico'];
                            $data = $res_c[$i]['data'];
                            $descricao = $res_c[$i]['descricao'];
                            $id = $res_c[$i]['id'];

                            $query = $pdo->query("SELECT * FROM veiculos WHERE id = '$veiculo'");
                            $res = $query->fetchAll(PDO::FETCH_ASSOC);
                            $marca = $res[0]['marca'];
                            $modelo = $res[0]['modelo'];
                            $placa = $res[0]['placa'];
                            $cliente = $res[0]['cliente'];
                            
                            $data_formatada = implode('/', array_reverse(explode('-', $data)));

                            $query_cat = $pdo->query("SELECT * FROM clientes WHERE cpf = '$cliente'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $nome_cli = $res_cat[0]['nome'];

                            $query_cat = $pdo->query("SELECT * FROM mecanicos WHERE cpf = '$mecanico'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $nome_mec = $res_cat[0]['nome'];
                        ?>
                        <tr>
                            <td><?php echo $marca .' - '.$modelo ?></td>
                            <td><?php echo $placa ?></td>
                            <td><?php echo $nome_cli ?></td>
                            <td><?php echo $nome_mec ?></td>
                            <td><?php echo $data_formatada ?></td>
                            <td><?php echo $descricao ?></td>
                            <td class="text-center">
                                <a href="index.php?pag=<?php echo $pag ?>&funcao=excluir&id=<?php echo $id ?>" 
                                   class="btn btn-sm btn-danger" title="Excluir Registro">
                                   <i class="far fa-trash-alt"></i>
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

<!-- Modal de confirmação de exclusão -->
<div class="modal fade" id="modal-deletar" tabindex="-1" role="dialog" aria-labelledby="modal-deletarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modal-deletarLabel">Excluir Registro</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este registro permanentemente?</p>
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

<?php 
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "excluir") {
    echo "<script>$('#modal-deletar').modal('show');</script>";
}
?>

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

<!-- Configurações da tabela -->
<script type="text/javascript">
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
.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.03);
}

.card-header {
    border-radius: 0.35rem 0.35rem 0 0 !important;
}

.table th {
    border-top: none;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.text-center {
    text-align: center !important;
}
</style>