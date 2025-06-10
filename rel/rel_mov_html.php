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

if($status == 'Entrada'){
    $status_serv = 'de Entradas';
}else if($status == 'Saída'){
    $status_serv = 'de Saídas';
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
    <title>Relatório de Movimentações</title>
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
        width: 120px;
    }

    .info-value {
        color: #2c3e50;
    }

    .movement-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .movement-table th {
        background-color: #f8f9fa;
        text-align: left;
        padding: 10px;
        border: 1px solid #dee2e6;
        font-weight: 600;
    }

    .movement-table td {
        padding: 10px;
        border: 1px solid #dee2e6;
        font-size: 14px;
    }

    .text-success {
        color: #28a745;
    }

    .text-danger {
        color: #dc3545;
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
        margin-bottom: 20px;
    }

    .period-info {
        background-color: #f1f8fe;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .status-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 5px;
    }

    .status-entrada {
        background-color: #28a745;
    }

    .status-saida {
        background-color: #dc3545;
    }

    .totals-box {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-top: 20px;
    }

    .signature-area {
        margin-top: 50px;
        padding-top: 20px;
        border-top: 1px dashed #7f8c8d;
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
                <h1 class="document-title">RELATÓRIO DE MOVIMENTAÇÕES <?php echo $status_serv ?></h1>
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

        <table class="movement-table">
            <thead>
                <tr>
                    <th width="15%">Tipo</th>
                    <th width="40%">Descrição</th>
                    <th width="15%">Valor</th>
                    <th width="15%">Funcionário</th>
                    <th width="15%">Data</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $saldo = 0;
                $entradas = 0;
                $saidas = 0;
                $query = $pdo->query("SELECT * FROM movimentacoes where data >= '$dataInicial' and data <= '$dataFinal' and tipo LIKE '$status_like' order by data asc, id asc ");
                $res = $query->fetchAll(PDO::FETCH_ASSOC);

                for ($i=0; $i < @count($res); $i++) { 
                    foreach ($res[$i] as $key => $value) {
                    }
                    $descricao = $res[$i]['descricao'];
                    $tipo = $res[$i]['tipo'];
                    $funcionario = $res[$i]['funcionario'];
                    $data = $res[$i]['data'];
                    $valor = $res[$i]['valor'];

                    if($tipo == 'Entrada'){
                        $entradas = $entradas + $valor;
                        $status_class = 'status-entrada';
                        $text_class = 'text-success';
                    }else{
                        $saidas = $saidas + $valor;
                        $status_class = 'status-saida';
                        $text_class = 'text-danger';
                    }
                    $saldo = $entradas - $saidas;

                    $entradasF = number_format($entradas, 2, ',', '.');
                    $saidasF = number_format($saidas, 2, ',', '.');
                    $saldoF = number_format($saldo, 2, ',', '.');

                    $id = $res[$i]['id'];

                    $query_usu = $pdo->query("SELECT * FROM usuarios where cpf = '$funcionario'");
                    $res_usu = $query_usu->fetchAll(PDO::FETCH_ASSOC);
                    if(@count($res_usu) > 0){
                        $nome_func = $res_usu[0]['nome'];
                    }else{
                        $nome_func = "Sem Registro";
                    }
                    
                    $valorF = number_format($valor, 2, ',', '.');
                    $data = implode('/', array_reverse(explode('-', $data)));

                    if($saldo >= 0){
                        $cor_saldo = 'text-success';
                    }else{
                        $cor_saldo = 'text-danger';
                    }
                ?>
                <tr>
                    <td>
                        <span class="status-indicator <?php echo $status_class ?>"></span>
                        <?php echo $tipo ?>
                    </td>
                    <td><?php echo $descricao ?></td>
                    <td class="<?php echo $text_class ?>">R$ <?php echo $valorF ?></td>
                    <td><?php echo $nome_func ?></td>
                    <td><?php echo $data ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="totals-box">
            <div class="row">
                <div class="col-md-4">
                    <p class="text-success"><strong>Total Entradas:</strong> R$ <?php echo @$entradasF ?></p>
                </div>
                <div class="col-md-4">
                    <p class="text-danger"><strong>Total Saídas:</strong> R$ <?php echo @$saidasF ?></p>
                </div>
                <div class="col-md-4">
                    <p class="<?php echo $cor_saldo ?>"><strong>Saldo Final:</strong> R$ <?php echo @$saldoF ?></p>
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