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
    $status_serv = 'Pagas ';
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
    <title>Contas à Receber</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        color: #7f8c8d;
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
    }

    .info-value {
        color: #2c3e50;
    }

    .receivables-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 14px;
    }

    .receivables-table th {
        background-color: #f8f9fa;
        text-align: left;
        padding: 12px 10px;
        border: 1px solid #dee2e6;
        font-weight: 600;
    }

    .receivables-table td {
        padding: 10px;
        border: 1px solid #dee2e6;
    }

    .status-paid {
        color: #28a745;
        font-weight: 600;
    }

    .status-pending {
        color: #dc3545;
        font-weight: 600;
    }

    .footer {
        margin-top: 40px;
        padding: 15px 0;
        border-top: 2px solid #e9ecef;
        text-align: center;
        font-size: 12px;
        color: #7f8c8d;
    }

    .summary-box {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin: 20px 0;
        border: 1px solid #e9ecef;
    }

    .total-box {
        background-color: #e9f7ef;
        padding: 15px;
        border-radius: 5px;
        margin: 20px 0;
        border: 1px solid #c3e6cb;
        text-align: right;
        font-weight: 600;
        font-size: 16px;
    }

    .period-info {
        padding: 10px 0;
        margin-bottom: 20px;
    }

    .divider {
        border-top: 1px solid #e9ecef;
        margin: 15px 0;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <!-- <img src="../img/logo2.png" alt="Logo" class="logo"> -->
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
                <h1 class="document-title">RELATÓRIO DE CONTAS À RECEBER <?php echo strtoupper($status_serv) ?></h1>
            </div>
            <div class="col-md-4 text-end">
                <div class="text-muted">Data: <?php echo $data_hoje ?></div>
            </div>
        </div>

        <div class="period-info">
            <div class="row">
                <div class="col-md-12">
                    <p><span class="info-label">Período da Apuração:</span> 
                    <span class="info-value"><?php echo $apuracao ?></span></p>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <table class="receivables-table">
            <thead>
                <tr>
                    <th width="25%">Descrição</th>
                    <th width="12%" class="text-end">Valor</th>
                    <th width="12%" class="text-end">Adiantamento</th>
                    <th width="15%">Mecânico</th>
                    <th width="15%">Cliente</th>
                    <th width="10%">Data</th>
                    <th width="11%">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $saldo = 0;
                
                $query = $pdo->query("SELECT * FROM contas_receber where data >= '$dataInicial' and data <= '$dataFinal' and pago LIKE '$status_like' order by data asc, id asc");
                $res = $query->fetchAll(PDO::FETCH_ASSOC);
                
                for ($i=0; $i < @count($res); $i++) { 
                    $descricao = $res[$i]['descricao'];
                    $valor = $res[$i]['valor'];
                    $adiantamento = $res[$i]['adiantamento'];
                    $mecanico = $res[$i]['mecanico'];
                    $cliente = $res[$i]['cliente'];
                    $pago = $res[$i]['pago'];
                    $data = $res[$i]['data'];
                    
                    $saldo = $saldo + $valor;
                    $saldoF = number_format($saldo, 2, ',', '.');
                    
                    $query_usu = $pdo->query("SELECT * FROM clientes where cpf = '$cliente'");
                    $res_usu = $query_usu->fetchAll(PDO::FETCH_ASSOC);
                    $nome_cli = !empty($res_usu[0]['nome']) ? $res_usu[0]['nome'] : 'Não informado';

                    $query_usu = $pdo->query("SELECT * FROM mecanicos where cpf = '$mecanico'");
                    $res_usu = $query_usu->fetchAll(PDO::FETCH_ASSOC);
                    $nome_mec = !empty($res_usu[0]['nome']) ? $res_usu[0]['nome'] : 'Não informado';

                    $valorF = number_format($valor, 2, ',', '.');
                    $adiantamentoF = number_format($adiantamento, 2, ',', '.');
                    $data = implode('/', array_reverse(explode('-', $data)));
                ?>
                <tr>
                    <td><?php echo $descricao ?></td>
                    <td class="text-end">R$ <?php echo $valorF ?></td>
                    <td class="text-end">R$ <?php echo $adiantamentoF ?></td>
                    <td><?php echo $nome_mec ?></td>
                    <td><?php echo $nome_cli ?></td>
                    <td><?php echo $data ?></td>
                    <td class="<?php echo $pago == 'Sim' ? 'status-paid' : 'status-pending' ?>">
                        <?php echo $pago ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="total-box">
            Total: R$ <?php echo number_format($saldo, 2, ',', '.') ?>
        </div>

        <div class="footer">
            <?php echo $rodape_relatorios ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>