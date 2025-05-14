<?php
@session_start();
if(@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'mecanico'){
	echo "<script language='javascript'> window.location='../index.php' </script>";
}

require_once("../conexao.php"); 


//totais dos cards
$hoje = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$dataInicioMes = $ano_atual."-".$mes_atual."-01";

$query_cat = $pdo->query("SELECT * FROM os where mecanico = '$_SESSION[cpf_usuario]' and concluido = 'Sim' ");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
$totalAprovados = @count($res_cat);

$query_cat = $pdo->query("SELECT * FROM os where mecanico = '$_SESSION[cpf_usuario]' and concluido != 'Sim' ");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
$totalPendentes = @count($res_cat);


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


$totalComissoesMes = 0;
$query_cat = $pdo->query("SELECT * FROM comissoes where data >= '$dataInicioMes' and data <= curDate() and mecanico = '$_SESSION[cpf_usuario]' ");
$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
for ($i=0; $i < @count($res_cat); $i++) { 
	foreach ($res_cat[$i] as $key => $value) {
	}
	$valor = $res_cat[$i]['valor'];
	$totalComissoesMes = $totalComissoesMes + $valor;
	
}
$totalComissoesMes = number_format($totalComissoesMes, 2, ',', '.');

?>


<div class="container-fluid">

    <!-- Gráficos Principais -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Meus Serviços</h6>
                </div>
                <div class="card-body">
                    <canvas id="servicosChart" height="150"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Minhas Comissões</h6>
                </div>
                <div class="card-body">
                    <canvas id="comissoesChart" height="150"></canvas>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Serv. Concluídos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalAprovados ?></div>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Serv. Pendentes</div>
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
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Comissões Hoje</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">R$ <?php echo $totalComissoesHoje ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-info"></i>
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Comissões Mês</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">R$ <?php echo $totalComissoesMes ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Serviços Pendentes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">Meus Serviços Pendentes</h6>
        </div>
        <div class="card-body">
            <?php if($totalPendentes > 0) { ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>Veículo</th>
                                <th>Cliente</th>
                                <th>Descrição</th>
                                <th>Data Entrega</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = $pdo->query("SELECT o.*, c.nome as cliente, v.modelo, v.marca FROM os o LEFT JOIN clientes c ON o.cliente = c.cpf LEFT JOIN veiculos v ON o.veiculo = v.id WHERE o.mecanico = '$_SESSION[cpf_usuario]' AND o.concluido != 'Sim' ORDER BY o.data_entrega ASC");
                            $res = $query->fetchAll(PDO::FETCH_ASSOC);
                            for ($i=0; $i < @count($res); $i++) { 
                                $data_entrega = implode('/', array_reverse(explode('-', $res[$i]['data_entrega'])));
                                $status = $res[$i]['data_entrega'] <= date('Y-m-d') ? 'Atrasado' : 'Pendente';
                                $statusClass = $res[$i]['data_entrega'] <= date('Y-m-d') ? 'badge-danger' : 'badge-warning';
                                ?>
                                <tr>
                                    <td><?php echo $res[$i]['marca'] ?> - <?php echo $res[$i]['modelo'] ?></td>
                                    <td><?php echo $res[$i]['cliente'] ?></td>
                                    <td><?php echo $res[$i]['descricao'] ?></td>
                                    <td><?php echo $data_entrega ?></td>
                                    <td><span class="badge <?php echo $statusClass ?>"><?php echo $status ?></span></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <div class="alert alert-success">Você não tem serviços pendentes no momento!</div>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Scripts para os gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de Serviços
    const servicosCtx = document.getElementById('servicosChart').getContext('2d');
    const servicosChart = new Chart(servicosCtx, {
        type: 'pie',
        data: {
            labels: ['Concluídos', 'Pendentes'],
            datasets: [{
                data: [<?php echo $totalAprovados ?>, <?php echo $totalPendentes ?>],
                backgroundColor: [
                    'rgba(28, 200, 138, 0.7)',
                    'rgba(246, 194, 62, 0.7)'
                ],
                borderColor: [
                    'rgba(28, 200, 138, 1)',
                    'rgba(246, 194, 62, 1)'
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

    // Gráfico de Comissões
    const comissoesCtx = document.getElementById('comissoesChart').getContext('2d');
    const comissoesChart = new Chart(comissoesCtx, {
        type: 'bar',
        data: {
            labels: ['Hoje', 'Este Mês'],
            datasets: [{
                label: 'Comissões',
                data: [
                    <?php echo str_replace(',', '.', str_replace('.', '', $totalComissoesHoje)) ?>, 
                    <?php echo str_replace(',', '.', str_replace('.', '', $totalComissoesMes)) ?>
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(78, 115, 223, 0.7)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
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