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

if($status == 'Aberto'){
    $status_serv = 'Aberto';
}else if($status == 'Aprovado'){
    $status_serv = 'Aprovado';
}else if($status == 'Concluído'){
    $status_serv = 'Concluído';
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
    <title>Relatório de Orçamentos</title>
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

    .report-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .report-table th {
        background-color: #f8f9fa;
        text-align: left;
        padding: 10px;
        border: 1px solid #dee2e6;
        font-weight: 600;
    }

    .report-table td {
        padding: 10px;
        border: 1px solid #dee2e6;
        font-size: 14px;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-aberto {
        background-color: #ffebee;
        color: #c62828;
    }

    .status-aprovado {
        background-color: #e3f2fd;
        color: #1565c0;
    }

    .status-concluido {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .footer {
        margin-top: 40px;
        padding: 15px 0;
        border-top: 2px solid #e9ecef;
        text-align: center;
        font-size: 12px;
        color: #7f8c8d;
    }

    .total-box {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-top: 20px;
        text-align: right;
        font-weight: 600;
        font-size: 16px;
    }

    .period-info {
        background-color: #f5f5f5;
        padding: 10px 15px;
        border-radius: 5px;
        margin: 15px 0;
    }

    .period-label {
        font-weight: 600;
        margin-right: 10px;
    }

    .text-money {
        font-weight: 600;
        color: #2e7d32;
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
                <h1 class="document-title">RELATÓRIO DE ORÇAMENTOS <?php echo $status_serv ? strtoupper($status_serv) : '' ?></h1>
            </div>
            <div class="col-md-4 text-end">
                <div class="text-muted">Data: <?php echo $data_hoje ?></div>
            </div>
        </div>

        <div class="period-info">
            <span class="period-label">Período da Apuração:</span>
            <span><?php echo $apuracao ?></span>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th width="20%">Cliente</th>
                    <th width="15%">Veículo</th>
                    <th width="12%">Valor</th>
                    <th width="15%">Serviço</th>
                    <th width="10%">Data</th>
                    <th width="15%">Mecânico</th>
                    <th width="13%">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalValores = 0;
                $total_prod = 0;
                $total_prod_mao = 0;
                $totalValoresF = 0;
                $query = $pdo->query("SELECT * FROM orcamentos where data >= '$dataInicial' and data <= '$dataFinal' and status LIKE '$status_like' order by data asc, id asc ");
                $res = $query->fetchAll(PDO::FETCH_ASSOC);
                
                for ($i=0; $i < @count($res); $i++) { 
                    foreach ($res[$i] as $key => $value) {
                    }
                    $cliente = $res[$i]['cliente'];
                    $veiculo = $res[$i]['veiculo'];
                    $descricao = $res[$i]['descricao'];
                    $valor = $res[$i]['valor'];
                    $servico = $res[$i]['servico'];
                    $data = $res[$i]['data'];
                    $data_entrega = $res[$i]['data_entrega'];
                    $garantia = $res[$i]['garantia'];
                    $mecanico = $res[$i]['mecanico'];
                    $status = $res[$i]['status'];
                    $id = $res[$i]['id'];

                    $query_p = $pdo->query("SELECT * FROM orc_prod where orcamento = '$id' ");
                    $res_p = $query_p->fetchAll(PDO::FETCH_ASSOC);

                    for ($i2=0; $i2 < @count($res_p); $i2++) { 
                        foreach ($res_p[$i2] as $key => $value) {
                        }
                        $prod = $res_p[$i2]['produto'];

                        $query_pro = $pdo->query("SELECT * FROM produtos where id = '$prod' ");
                        $res_pro = $query_pro->fetchAll(PDO::FETCH_ASSOC);
                    
                        $valor_prod = $res_pro[0]['valor_venda'];
                        $total_prod = $valor_prod + $total_prod;
                    }

                    $total_prod_mao = $total_prod + $valor;
                    $totalValores = $total_prod_mao + $totalValores;
                    $totalValoresF = number_format($totalValores, 2, ',', '.');
                    $total_prod_maoF = number_format($total_prod_mao, 2, ',', '.');

                    $data = implode('/', array_reverse(explode('-', $data)));
                    $valor = number_format($valor, 2, ',', '.');

                    $query_cat = $pdo->query("SELECT * FROM clientes where cpf = '$cliente' ");
                    $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                    $nome_cli = $res_cat[0]['nome'];
                    $email_cli = $res_cat[0]['email'];

                    $query_cat = $pdo->query("SELECT * FROM veiculos where id = '$veiculo' ");
                    $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                    $modelo = $res_cat[0]['modelo'];
                    $marca = $res_cat[0]['marca'];

                    $query_cat = $pdo->query("SELECT * FROM servicos where id = '$servico' ");
                    $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                    $nome_serv = !empty($res_cat[0]['nome']) ? $res_cat[0]['nome'] : null;

                    $query_cat = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico' ");
                    $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                    $nome_mecanico = !empty($res_cat[0]['nome']) ? $res_cat[0]['nome'] : null;

                    $status_class = '';
                    if($status == 'Aberto'){
                        $status_class = 'status-aberto';
                    }else if($status == 'Aprovado'){
                        $status_class = 'status-aprovado';
                    }else if($status == 'Concluído'){
                        $status_class = 'status-concluido';
                    }
                ?>
                <tr>
                    <td><?php echo $nome_cli ?></td>
                    <td><?php echo $marca .' '.$modelo ?></td>
                    <td class="text-money">R$ <?php echo $total_prod_maoF ?></td>
                    <td><?php echo $nome_serv ?></td>
                    <td><?php echo $data ?></td>
                    <td><?php echo $nome_mecanico ?></td>
                    <td><span class="status-badge <?php echo $status_class ?>"><?php echo $status ?></span></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="total-box">
            <span>Total de Serviços: <span class="text-money">R$ <?php echo $totalValoresF ?></span></span>
        </div>

        <div class="footer">
            <?php echo $rodape_relatorios ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>