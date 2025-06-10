<?php 
@session_start();
require_once("verificar_usuario.php");

$pag = "compras";
require_once("../conexao.php"); 
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-shopping-cart mr-2"></i>Histórico de Compras
        </h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Registros de Compras</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Produto</th>
                            <th>Valor</th>
                            <th>Funcionário</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = $pdo->query("SELECT * FROM compras ORDER BY id DESC");
                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
                        
                        for ($i=0; $i < @count($res); $i++) { 
                            foreach ($res[$i] as $key => $value) {
                            }
                            
                            $produto = $res[$i]['produto'];
                            $valor = $res[$i]['valor'];
                            $funcionario = $res[$i]['funcionario'];
                            $data = $res[$i]['data'];
                            $id = $res[$i]['id'];

                            $query_prod = $pdo->query("SELECT * FROM produtos WHERE id = '$produto'");
                            $res_prod = $query_prod->fetchAll(PDO::FETCH_ASSOC);
                            $nome_produto = $res_prod[0]['nome'];

                            $query_usu = $pdo->query("SELECT * FROM usuarios WHERE cpf = '$funcionario'");
                            $res_usu = $query_usu->fetchAll(PDO::FETCH_ASSOC);
                            $nome_funcionario = $res_usu[0]['nome'];

                            $valor_formatado = number_format($valor, 2, ',', '.');
                            $data_formatada = implode('/', array_reverse(explode('-', $data)));
                        ?>
                        <tr>
                            <td><?php echo $nome_produto ?></td>
                            <td>R$ <?php echo $valor_formatado ?></td>
                            <td><?php echo $nome_funcionario ?></td>
                            <td><?php echo $data_formatada ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>





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

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.03);
}

.card-header {
    border-radius: 0.35rem 0.35rem 0 0 !important;
}

.table th {
    border-top: none;
}
</style>