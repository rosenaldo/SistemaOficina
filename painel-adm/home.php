<?php
@session_start();
if(@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'admin'){
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

require_once("../conexao.php"); 

//totais dos cards
$hoje = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$dataInicioMes = $ano_atual."-".$mes_atual."-01";

$query_cat = $pdo->query("SELECT * FROM orcamentos where status = 'Concluído' and data >= '$dataInicioMes' and data <= curDate()");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
$totalConcluidos = @count($res_cat);

$query_cat = $pdo->query("SELECT * FROM orcamentos where status = 'Aberto' and data >= '$dataInicioMes' and data <= curDate() ");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
$totalPendentes = @count($res_cat);

$query_cat = $pdo->query("SELECT * FROM orcamentos where status = 'Aprovado' and data >= '$dataInicioMes' and data <= curDate() ");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
$totalAprovados = @count($res_cat);


$query_cat = $pdo->query("SELECT * FROM produtos  ");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
$totalProdutos = @count($res_cat);



$query_cat = $pdo->query("SELECT * FROM os where concluido != 'Sim'  ");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
$totalServPendentes = @count($res_cat);


$query_cat = $pdo->query("SELECT * FROM clientes  ");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
$totalClientes = @count($res_cat);



$query_cat = $pdo->query("SELECT * FROM mecanicos  ");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
$totalMecanicos = @count($res_cat);

$vendasDia = 0;
$query_cat = $pdo->query("SELECT * FROM vendas where data = curDate()");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
for ($i=0; $i < @count($res_cat); $i++) { 
	foreach ($res_cat[$i] as $key => $value) {
	}
	$valor = $res_cat[$i]['valor'];
	$vendasDia = $vendasDia + $valor;
	
}
$vendasDia = number_format($vendasDia, 2, ',', '.');




$totalComissoesHoje = 0;
$query_cat = $pdo->query("SELECT * FROM comissoes where data = curDate() and mecanico = '$_SESSION[cpf_usuario]' ");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
for ($i=0; $i < @count($res_cat); $i++) { 
	foreach ($res_cat[$i] as $key => $value) {
	}
	$valor = $res_cat[$i]['valor'];
	$totalComissoesHoje = $totalComissoesHoje + $valor;
	
}
$totalComissoesHoje = number_format($totalComissoesHoje, 2, ',', '.');




//TOTALIZAR MOVIMENTÃÇÕES NO DIA
$saldo = 0;
$entradas = 0;
$saidas = 0;
$query = $pdo->query("SELECT * FROM movimentacoes where data = curDate()");
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
	$corTotal2 = 'border-left-danger';
}else{
	$corTotal = 'text-success';
	$corTotal2 = 'border-left-success';
}

$entradas = number_format($entradas, 2, ',', '.');
$saidas = number_format($saidas, 2, ',', '.');
$saldo = number_format($saldo, 2, ',', '.');






//TOTALIZAR MOVIMENTÃÇÕES NO MES
$saldoMes = 0;
$entradasMes = 0;
$saidasMes = 0;
$query = $pdo->query("SELECT * FROM movimentacoes where data >= '$dataInicioMes' and data <= curDate()");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
for ($i=0; $i < @count($res); $i++) { 
foreach ($res[$i] as $key => $value) {
}
$valor = $res[$i]['valor'];
$tipo = $res[$i]['tipo'];

if($tipo == 'Entrada'){
	$entradasMes = $entradasMes + $valor;
}else{
	$saidasMes = $saidasMes + $valor;
}

}

$saldoMes = $entradasMes - $saidasMes;
if($saldoMes < 0){
	$corTotalMes = 'text-danger';
	$corTotal2Mes = 'border-left-danger';
}else{
	$corTotalMes = 'text-success';
	$corTotal2Mes = 'border-left-success';
}

$entradasMes = number_format($entradasMes, 2, ',', '.');
$saidasMes = number_format($saidasMes, 2, ',', '.');
$saldoMes = number_format($saldoMes, 2, ',', '.');



?>


<!-- Dashboard Moderno -->
<div class="container-fluid">

    <!-- Gráficos de Movimentação Financeira -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Movimentação Financeira - Mês Atual</h6>
                </div>
                <div class="card-body">
                    <canvas id="financeChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Balanço Mensal</h6>
                </div>
                <div class="card-body">
                    <canvas id="balanceChart" height="120"></canvas>
                    <div class="mt-3 text-center">
                        <span class="mr-3"><i class="fas fa-circle text-success"></i> Entradas: R$
                            <?php echo $entradasMes ?></span>
                        <span class="mr-3"><i class="fas fa-circle text-danger"></i> Saídas: R$
                            <?php echo $saidasMes ?></span>
                        <span><i class="fas fa-circle <?php echo $corTotalMes ?>"></i> Saldo: R$
                            <?php echo $saldoMes ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards Resumos Financeiros -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Entradas (Dia)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">R$ <?php echo $entradas ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Saídas (Dia)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">R$ <?php echo $saidas ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Vendas (Dia)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">R$ <?php echo $vendasDia ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cash-register fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card <?php echo $corTotal2 ?> shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold <?php echo $corTotal ?> text-uppercase mb-1">Saldo
                                (Dia)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">R$ <?php echo $saldo ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-balance-scale fa-2x <?php echo $corTotal ?>"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Orçamentos -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status dos Orçamentos</h6>
                </div>
                <div class="card-body">
                    <canvas id="orcamentosChart" height="150"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Resumo de Serviços</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card border-left-danger shadow h-100">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Serviços
                                        Pendentes</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php echo $totalServPendentes ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border-left-info shadow h-100">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Produtos
                                        Cadastrados</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalProdutos ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border-left-warning shadow h-100">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total
                                        Clientes</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalClientes ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border-left-success shadow h-100">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total
                                        Mecânicos</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalMecanicos ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Últimos Orçamentos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">Últimos Orçamentos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Veículo</th>
                            <th>Valor</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = $pdo->query("SELECT o.*, c.nome as cliente FROM orcamentos o LEFT JOIN clientes c ON o.cliente = c.cpf ORDER BY o.data DESC LIMIT 5");
                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
                        for ($i=0; $i < @count($res); $i++) { 
                            $data = implode('/', array_reverse(explode('-', $res[$i]['data'])));
                            $valor = number_format($res[$i]['valor'], 2, ',', '.');
                            $status = $res[$i]['status'];
                            
                            $statusClass = '';
                            if($status == 'Concluído') $statusClass = 'badge-success';
                            elseif($status == 'Aprovado') $statusClass = 'badge-primary';
                            else $statusClass = 'badge-warning';
                            ?>
                        <tr>
                            <td><?php echo $data ?></td>
                            <td><?php echo $res[$i]['cliente'] ?></td>
                            <td><?php echo $res[$i]['veiculo'] ?></td>
                            <td>R$ <?php echo $valor ?></td>
                            <td><span class="badge <?php echo $statusClass ?>"><?php echo $status ?></span></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Scripts para os gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de Movimentação Financeira
const financeCtx = document.getElementById('financeChart').getContext('2d');
const financeChart = new Chart(financeCtx, {
    type: 'line',
    data: {
        labels: ['1', '5', '10', '15', '20', '25', '30'],
        datasets: [{
                label: 'Entradas',
                data: [1200, 1900, 1500, 2000, 1800, 2200, 2400],
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28, 200, 138, 0.1)',
                tension: 0.3,
                fill: true
            },
            {
                label: 'Saídas',
                data: [800, 1200, 1000, 1500, 1300, 1600, 1800],
                borderColor: '#e74a3b',
                backgroundColor: 'rgba(231, 74, 59, 0.1)',
                tension: 0.3,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Gráfico de Balanço
const balanceCtx = document.getElementById('balanceChart').getContext('2d');
const balanceChart = new Chart(balanceCtx, {
    type: 'doughnut',
    data: {
        labels: ['Entradas', 'Saídas'],
        datasets: [{
            data: [<?php echo str_replace(',', '.', str_replace('.', '', $entradasMes)) ?>,
                <?php echo str_replace(',', '.', str_replace('.', '', $saidasMes)) ?>
            ],
            backgroundColor: ['#1cc88a', '#e74a3b'],
            hoverBackgroundColor: ['#17a673', '#be2617'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
    },
    options: {
        responsive: true,
        cutout: '70%',
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});

// Gráfico de Orçamentos
const orcamentosCtx = document.getElementById('orcamentosChart').getContext('2d');
const orcamentosChart = new Chart(orcamentosCtx, {
    type: 'bar',
    data: {
        labels: ['Concluídos', 'Pendentes', 'Aprovados'],
        datasets: [{
            label: 'Orçamentos',
            data: [<?php echo $totalConcluidos ?>, <?php echo $totalPendentes ?>,
                <?php echo $totalAprovados ?>
            ],
            backgroundColor: [
                'rgba(28, 200, 138, 0.7)',
                'rgba(246, 194, 62, 0.7)',
                'rgba(78, 115, 223, 0.7)'
            ],
            borderColor: [
                'rgba(28, 200, 138, 1)',
                'rgba(246, 194, 62, 1)',
                'rgba(78, 115, 223, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>