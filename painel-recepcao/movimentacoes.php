<?php 
@session_start();
require_once("verificar_usuario.php");

$pag = "movimentacoes";
require_once("../conexao.php"); 

// TOTALIZAR MOVIMENTAÇÕES NO DIA
$saldo = 0;
$entradas = 0;
$saidas = 0;
$query = $pdo->query("SELECT * FROM movimentacoes order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
for ($i=0; $i < @count($res); $i++) { 
    foreach ($res[$i] as $key => $value) {
    }
    $valor = $res[$i]['valor'];
    $tipo = $res[$i]['tipo'];

    if($tipo == 'Entrada'){
        $entradas = $entradas + $valor;
    }else{
        $saidas = $saidas + $valor;
    }
}

$saldo = $entradas - $saidas;
if($saldo < 0){
    $corTotal = 'text-danger';
}else{
    $corTotal = 'text-success';
}

$entradasF = number_format($entradas, 2, ',', '.');
$saidasF = number_format($saidas, 2, ',', '.');
$saldoF = number_format($saldo, 2, ',', '.');
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-money-bill-wave mr-2"></i>Movimentações Financeiras
        </h1>
        <div class="d-flex align-items-center">
            <div class="mr-4">
                <span class="text-success font-weight-bold"><i class="fas fa-arrow-up mr-1"></i> R$ <?php echo $entradasF ?></span>
            </div>
            <div class="mr-4">
                <span class="text-danger font-weight-bold"><i class="fas fa-arrow-down mr-1"></i> R$ <?php echo $saidasF ?></span>
            </div>
            <div>
                <span class="<?php echo $corTotal ?> font-weight-bold"><i class="fas fa-balance-scale mr-1"></i> R$ <?php echo $saldoF ?></span>
            </div>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Registro de Movimentações</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th width="100px">Tipo</th>
                            <th>Descrição</th>
                            <th width="150px">Valor</th>
                            <th>Funcionário</th>
                            <th width="120px">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = $pdo->query("SELECT * FROM movimentacoes order by id desc");
                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
                        
                        for ($i=0; $i < @count($res); $i++) { 
                            foreach ($res[$i] as $key => $value) {
                            }
                            $descricao = $res[$i]['descricao'];
                            $tipo = $res[$i]['tipo'];
                            $funcionario = $res[$i]['funcionario'];
                            $data = $res[$i]['data'];
                            $valor = $res[$i]['valor'];
                            $id = $res[$i]['id'];

                            $query_usu = $pdo->query("SELECT * FROM usuarios where cpf = '$funcionario'");
                            $res_usu = $query_usu->fetchAll(PDO::FETCH_ASSOC);
                            $nome_func = $res_usu[0]['nome'];

                            $valorF = number_format($valor, 2, ',', '.');
                            $data = implode('/', array_reverse(explode('-', $data)));

                            if($tipo == 'Entrada'){
                                $cor_pago = 'text-success';
                                $icone = 'fa-arrow-up';
                            }else{
                                $cor_pago = 'text-danger';
                                $icone = 'fa-arrow-down';
                            }
                        ?>
                        <tr>
                            <td>
                                <span class="<?php echo $cor_pago ?> font-weight-bold">
                                    <i class="fas <?php echo $icone ?> mr-1"></i><?php echo $tipo ?>
                                </span>
                            </td>
                            <td><?php echo $descricao ?></td>
                            <td class="font-weight-bold <?php echo $cor_pago ?>">R$ <?php echo $valorF ?></td>
                            <td><?php echo $nome_func ?></td>
                            <td><?php echo $data ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Entradas (Dia)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">R$ <?php echo $entradasF ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-arrow-up fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Total Saídas (Dia)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">R$ <?php echo $saidasF ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-arrow-down fa-2x text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Saldo do Dia</div>
                                    <div class="h5 mb-0 font-weight-bold <?php echo $corTotal ?>">R$ <?php echo $saldoF ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-balance-scale fa-2x text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#dataTable').DataTable({
        "ordering": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        },
        "dom": '<"top"f>rt<"bottom"lip><"clear">',
        "initComplete": function() {
            $(".dataTables_filter input").addClass('form-control form-control-sm');
            $(".dataTables_filter input").attr('placeholder', 'Pesquisar...');
            $(".dataTables_length select").addClass('form-control form-control-sm');
        }
    });
});
</script>

<style>
    .table td, .table th {
        vertical-align: middle;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        margin-left: 0.5em;
        display: inline-block;
        width: auto;
    }
    
    .dataTables_wrapper .dataTables_length select {
        display: inline-block;
        width: auto;
    }
    
    .card-header {
        border-radius: 0.35rem 0.35rem 0 0 !important;
    }
    
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    
    .border-left-danger {
        border-left: 0.25rem solid #e74a3b !important;
    }
    
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
