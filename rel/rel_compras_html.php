<?php 
require_once("../conexao.php"); 
@session_start();

require_once("data_formatada.php"); 

$dataInicial = $_GET['dataInicial'];
$dataFinal = $_GET['dataFinal'];

$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(explode('-', $dataFinal)));

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
    <title>Relatório de Compras</title>
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
    }

    .info-value {
        color: #2c3e50;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    .report-table th {
        background-color: #f8f9fa;
        text-align: left;
        padding: 12px;
        border: 1px solid #dee2e6;
        font-weight: 600;
    }

    .report-table td {
        padding: 10px 12px;
        border: 1px solid #dee2e6;
        font-size: 14px;
    }

    .total-box {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin: 20px 0;
        text-align: right;
        font-weight: 600;
        font-size: 16px;
        border: 1px solid #e9ecef;
    }

    .footer {
        margin-top: 40px;
        padding: 15px 0;
        border-top: 2px solid #e9ecef;
        text-align: center;
        font-size: 12px;
        color: #7f8c8d;
    }

    .period-info {
        background-color: #f8f9fa;
        padding: 10px 15px;
        border-radius: 5px;
        margin: 15px 0;
        display: inline-block;
    }

    .currency {
        text-align: right;
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
                <h1 class="document-title">Relatório de Compras</h1>
            </div>
            <div class="col-md-4 text-end">
                <div class="text-muted">Data: <?php echo $data_hoje ?></div>
            </div>
        </div>

        <div class="period-info">
            <span class="info-label">Período da Apuração:</span> 
            <span class="info-value"><?php echo $apuracao ?></span>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th width="40%">Produto</th>
                    <th width="20%" class="currency">Valor</th>
                    <th width="25%">Funcionário</th>
                    <th width="15%">Data</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $saldo = 0;
                
                $query = $pdo->query("SELECT * FROM compras where data >= '$dataInicial' and data <= '$dataFinal' order by data asc, id asc");
                $res = $query->fetchAll(PDO::FETCH_ASSOC);
                
                for ($i=0; $i < @count($res); $i++) { 
                    $produto = $res[$i]['produto'];
                    $valor = $res[$i]['valor'];
                    $funcionario = $res[$i]['funcionario'];
                    $data = $res[$i]['data'];
                    $id = $res[$i]['id'];
                    
                    $saldo = $saldo + $valor;
                    $saldoF = number_format($saldo, 2, ',', '.');
                    
                    $query_prod = $pdo->query("SELECT * FROM produtos where id = '$produto' ");
                    $res_prod = $query_prod->fetchAll(PDO::FETCH_ASSOC);
                    $nome_produto = @$res_prod[0]['nome'];

                    $query_usu = $pdo->query("SELECT * FROM usuarios where cpf = '$funcionario' ");
                    $res_usu = $query_usu->fetchAll(PDO::FETCH_ASSOC);
                    $nome_funcionario = @$res_usu[0]['nome'];

                    $valorF = number_format($valor, 2, ',', '.');
                    $data = implode('/', array_reverse(explode('-', $data)));
                ?>
                <tr>
                    <td><?php echo $nome_produto ?></td>
                    <td class="currency">R$ <?php echo $valorF ?></td>
                    <td><?php echo $nome_funcionario ?></td>
                    <td><?php echo $data ?></td>
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