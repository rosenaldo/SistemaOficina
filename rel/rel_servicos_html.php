<?php 
require_once("../conexao.php"); 
@session_start();
require_once("data_formatada.php"); 

$dataInicial = $_GET['dataInicial'];
$dataFinal = $_GET['dataFinal'];
$status = $_GET['status'];

$status_like = '%'.$status.'%';

$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(explode('-', $dataFinal)));

if($status == 'Sim'){
    $status_serv = 'Concluídos';
}else if($status == 'Não'){
    $status_serv = 'Pendentes';
}else{
    $status_serv = '';
}

if($dataInicial != $dataFinal){
    $apuracao = $dataInicialF. ' até '. $dataFinalF;
}else{
    $apuracao = $dataInicialF;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Serviços</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
    @page {
        margin: 0;
        size: A4;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        line-height: 1.6;
    }

    .header {
        background-color: #f8f9fa;
        padding: 20px 0;
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 30px;
    }

    .logo {
        max-width: 150px;
        height: auto;
    }

    .company-name {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .company-info {
        font-size: 14px;
        color: #7f8c8d;
    }

    .document-title {
        font-size: 22px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 20px;
        text-transform: uppercase;
    }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin: 20px 0 10px;
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 5px;
    }

    .info-label {
        font-weight: 600;
        color: #7f8c8d;
        display: inline-block;
        width: 150px;
    }

    .info-value {
        color: #2c3e50;
    }

    .service-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .service-table th {
        background-color: #f8f9fa;
        text-align: left;
        padding: 10px;
        border: 1px solid #dee2e6;
        font-weight: 600;
    }

    .service-table td {
        padding: 10px;
        border: 1px solid #dee2e6;
    }

    .footer {
        margin-top: 40px;
        padding: 15px 0;
        border-top: 2px solid #e9ecef;
        text-align: center;
        font-size: 12px;
        color: #7f8c8d;
    }

    .observation {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        margin: 10px 0;
    }

    .signature-area {
        margin-top: 50px;
        padding-top: 20px;
        border-top: 1px dashed #7f8c8d;
    }

    .signature-line {
        width: 300px;
        border-top: 1px solid #7f8c8d;
        margin: 30px auto 0;
        text-align: center;
        padding-top: 5px;
        font-size: 12px;
    }

    .total-box {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-top: 20px;
        border: 1px solid #dee2e6;
    }

    .total-label {
        font-weight: 600;
        color: #2c3e50;
    }

    .total-value {
        font-weight: 700;
        color: #2c3e50;
        font-size: 18px;
    }

    .no-items {
        color: #7f8c8d;
        font-style: italic;
        padding: 10px;
    }

    .badge-completed {
        background-color: #d4edda;
        color: #155724;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    .badge-pending {
        background-color: #f8d7da;
        color: #721c24;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    .period-info {
        margin-bottom: 20px;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <!-- <img src="../../img/logo2.png" alt="Logo" class="logo"> -->
                </div>
                <div class="col-md-10">
                    <div class="company-name"><?php echo strtoupper($nome_oficina) ?></div>
                    <div class="company-info">
                        <?php echo $endereco_oficina ?> | Tel: <?php echo $telefone_oficina ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="document-title">RELATÓRIO DE SERVIÇOS <?php echo $status_serv ?></h1>
            </div>
            <div class="col-md-4 text-end">
                <div class="text-muted">Data: <?php echo $data_hoje ?></div>
            </div>
        </div>

        <div class="period-info">
            <h5 class="section-title">PERÍODO DA APURAÇÃO</h5>
            <p><span class="info-label">Intervalo:</span> <span class="info-value"><?php echo $apuracao ?></span></p>
        </div>

        <h5 class="section-title">DADOS DOS SERVIÇOS</h5>
        <?php 
        $totalValores = 0;
        $query = $pdo->query("SELECT * FROM os where data >= '$dataInicial' and data <= '$dataFinal' and concluido LIKE '$status_like' order by data asc");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($res) > 0) { 
        ?>
        <table class="service-table">
            <thead>
                <tr>
                    <th width="20%">Cliente</th>
                    <th width="15%">Mecânico</th>
                    <th width="12%">Valor (R$)</th>
                    <th width="20%">Serviço</th>
                    <th width="15%">Veículo</th>
                    <th width="10%">Entrega</th>
                    <th width="8%">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i=0; $i < count($res); $i++) { 
                    $cliente = $res[$i]['cliente'];
                    $veiculo = $res[$i]['veiculo'];
                    $descricao = $res[$i]['descricao'];
                    $valor = $res[$i]['valor'];
                    $valor_mao_obra = $res[$i]['valor_mao_obra'];
                    
                    $data = $res[$i]['data'];
                    $data_entrega = $res[$i]['data_entrega'];
                    $concluido = $res[$i]['concluido'];
                    $mecanico = $res[$i]['mecanico'];
                    $tipo = $res[$i]['tipo'];
                    $id = $res[$i]['id'];

                    $totalValores = $valor + $totalValores;
                    $totalValoresF = number_format($totalValores, 2, ',', '.');

                    $data = implode('/', array_reverse(explode('-', $data)));
                    $data_entrega = implode('/', array_reverse(explode('-', $data_entrega)));
                    $valorF = number_format($valor, 2, ',', '.');
                    $valor_mao_obra = number_format($valor_mao_obra, 2, ',', '.');

                    $query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
                    $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
					$nome_cli = !empty($res_cat[0]['nome']) ? $res_cat[0]['nome'] : 'Não informado';
					$email_cli = !empty($res_cat[0]['email']) ? $res_cat[0]['email'] : 'Não informado';
					

                    $query_cat = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico' ");
                    $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                    $nome_mec = !empty($res_cat[0]['nome']) ? $res_cat[0]['nome'] : 'Não informado';
                    
                    $query_cat = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
                    $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
					$modelo = !empty($res_cat[0]['modelo']) ? $res_cat[0]['modelo'] : 'Não informado';
					$marca = !empty($res_cat[0]['marca']) ? $res_cat[0]['marca'] : 'Não informado';

                    
                    $status_class = ($concluido == 'Sim') ? 'badge-completed' : 'badge-pending';
                ?>
                <tr>
                    <td><?php echo $nome_cli ?></td>
                    <td><?php echo $nome_mec ?></td>
                    <td>R$ <?php echo $valorF ?></td>
                    <td><?php echo $descricao ?></td>
                    <td><?php echo $marca .' '.$modelo ?></td>
                    <td><?php echo $data_entrega ?></td>
                    <td><span class="<?php echo $status_class ?>"><?php echo $concluido ?></span></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
            <div class="no-items">Nenhum serviço encontrado para o período selecionado</div>
        <?php } ?>

        <div class="total-box">
            <div class="row">
                <div class="col-6">
                    <p class="total-label">Total de Serviços:</p>
                </div>
                <div class="col-6 text-end">
                    <p class="total-value">R$ <?php echo number_format($totalValores, 2, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <div class="signature-area">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="signature-line">Responsável Técnico</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <?php echo $rodape_relatorios ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>