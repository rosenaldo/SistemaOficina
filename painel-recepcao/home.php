<?php
@session_start();
require_once("../conexao.php"); 
require_once("verificar_usuario.php");

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


$query_cat = $pdo->query("SELECT * FROM os where data_entrega = curDate()  ");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
$totalEntregas = @count($res_cat);


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



<div class="container-fluid">

    <!-- Gráficos Principais -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status dos Orçamentos</h6>
                </div>
                <div class="card-body">
                    <canvas id="orcamentosChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Balanço do Dia</h6>
                </div>
                <div class="card-body">
                    <canvas id="balanceChart" height="120"></canvas>
                    <div class="mt-3 text-center">
                        <span class="mr-3"><i class="fas fa-circle text-success"></i> Entradas: R$
                            <?php echo $entradas ?></span>
                        <span><i class="fas fa-circle text-danger"></i> Saídas: R$ <?php echo $saidas ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards Resumos -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Orç. Concluídos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalConcluidos ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Orç. Pendentes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalPendentes ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-warning"></i>
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Orç. Aprovados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalAprovados ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-thumbs-up fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Entregas Hoje</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalEntregas ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Entregas para Hoje -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">Veículos para Entrega Hoje</h6>
        </div>
        <div class="card-body">
            <?php if($totalEntregas > 0) { ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Cliente</th>
                            <th>Veículo</th>
                            <th>Placa</th>
                            <th>Serviço</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = $pdo->query("SELECT o.*, c.nome as cliente, v.modelo, v.placa FROM os o LEFT JOIN clientes c ON o.cliente = c.cpf LEFT JOIN veiculos v ON o.veiculo = v.id WHERE o.data_entrega = curDate()");
                            $res = $query->fetchAll(PDO::FETCH_ASSOC);
                            for ($i=0; $i < @count($res); $i++) { 
                                $status = $res[$i]['concluido'] == 'Sim' ? 'Pronto' : 'Em Andamento';
                                $statusClass = $res[$i]['concluido'] == 'Sim' ? 'badge-success' : 'badge-warning';
                                ?>
                        <tr>
                            <td><?php echo $res[$i]['cliente'] ?></td>
                            <td><?php echo $res[$i]['modelo'] ?></td>
                            <td><?php echo $res[$i]['placa'] ?></td>
                            <td><?php echo $res[$i]['descricao'] ?></td>
                            <td><span class="badge <?php echo $statusClass ?>"><?php echo $status ?></span></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } else { ?>
            <div class="alert alert-info">Nenhum veículo para entrega hoje.</div>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Scripts para os gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de Orçamentos
const orcamentosCtx = document.getElementById('orcamentosChart').getContext('2d');
const orcamentosChart = new Chart(orcamentosCtx, {
    type: 'doughnut',
    data: {
        labels: ['Concluídos', 'Pendentes', 'Aprovados'],
        datasets: [{
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
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});

// Gráfico de Balanço
const balanceCtx = document.getElementById('balanceChart').getContext('2d');
const balanceChart = new Chart(balanceCtx, {
    type: 'bar',
    data: {
        labels: ['Entradas', 'Saídas', 'Saldo'],
        datasets: [{
            label: 'Valores',
            data: [
                <?php echo str_replace(',', '.', str_replace('.', '', $entradas)) ?>,
                <?php echo str_replace(',', '.', str_replace('.', '', $saidas)) ?>,
                <?php echo str_replace(',', '.', str_replace('.', '', $saldo)) ?>
            ],
            backgroundColor: [
                'rgba(28, 200, 138, 0.7)',
                'rgba(231, 74, 59, 0.7)',
                '<?php echo $corTotal == 'text-success' ? 'rgba(28, 200, 138, 0.7)' : 'rgba(231, 74, 59, 0.7)' ?>'
            ],
            borderColor: [
                'rgba(28, 200, 138, 1)',
                'rgba(231, 74, 59, 1)',
                '<?php echo $corTotal == 'text-success' ? 'rgba(28, 200, 138, 1)' : 'rgba(231, 74, 59, 1)' ?>'
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