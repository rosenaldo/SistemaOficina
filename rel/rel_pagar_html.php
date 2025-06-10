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
    <title>Contas à Pagar</title>
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

    .table-custom {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .table-custom th {
        background-color: #f8f9fa;
        text-align: left;
        padding: 10px;
        border: 1px solid #dee2e6;
        font-weight: 600;
    }

    .table-custom td {
        padding: 10px;
        border: 1px solid #dee2e6;
        font-size: 14px;
    }

    .footer {
        margin-top: 40px;
        padding: 15px 0;
        border-top: 2px solid #e9ecef;
        text-align: center;
        font-size: 12px;
        color: #7f8c8d;
    }

    .periodo-info {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        margin: 15px 0;
    }

    .total-box {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin: 20px 0;
        text-align: right;
        font-weight: 600;
        font-size: 16px;
    }

    .badge-paga {
        background-color: #28a745;
        color: white;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 12px;
    }

    .badge-pendente {
        background-color: #dc3545;
        color: white;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 12px;
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
                <h1 class="document-title">RELATÓRIO DE CONTAS À PAGAR <?php echo strtoupper($status_serv) ?></h1>
            </div>
            <div class="col-md-4 text-end">
                <div class="text-muted">Data: <?php echo $data_hoje ?></div>
            </div>
        </div>

        <div class="periodo-info">
            <div class="row">
                <div class="col-md-12">
                    <p><span class="info-label">Período de apuração:</span> 
                    <span class="info-value"><?php echo $apuracao ?></span></p>
                </div>
            </div>
        </div>

        <table class="table-custom">
            <thead>
                <tr>
                    <th width="30%">Descrição</th>
                    <th width="15%">Valor</th>
                    <th width="15%">Fornecedor</th>
                    <th width="15%">Funcionário</th>
                    <th width="10%">Vencimento</th>
                    <th width="10%">Data</th>
                    <th width="5%">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $saldo = 0;
                
                $query = $pdo->query("SELECT * FROM contas_pagar where data >= '$dataInicial' and data <= '$dataFinal' and pago LIKE '$status_like' order by data asc, id asc");
                $res = $query->fetchAll(PDO::FETCH_ASSOC);
                
                for ($i=0; $i < @count($res); $i++) { 
                    $descricao = $res[$i]['descricao'];
                    $valor = $res[$i]['valor'];
                    $funcionario = $res[$i]['funcionario'];
                    $data_venc = $res[$i]['data_venc'];
                    $data = $res[$i]['data'];
                    $pago = $res[$i]['pago'];
                    $fornecedor = $res[$i]['fornecedor'];
                    
                    $saldo = $saldo + $valor;
                    $saldoF = number_format($saldo, 2, ',', '.');
                    
                    $id = $res[$i]['id'];

                    
                    $query_usu = $pdo->query("SELECT * FROM usuarios where cpf = '$funcionario'");
                    $res_usu = $query_usu->fetchAll(PDO::FETCH_ASSOC);
                    if(@count($res_usu) > 0){
                        $nome_func = $res_usu[0]['nome'];
                    }else{
                        $nome_func = "";
                    }
                    

                    $query_usu = $pdo->query("SELECT * FROM fornecedores where id = '$fornecedor'");
                    $res_usu = $query_usu->fetchAll(PDO::FETCH_ASSOC);
                    if(@count($res_usu) > 0){
                        $nome_forn = $res_usu[0]['nome'];
                    }else{
                        $nome_forn = "";
                    }

                    $valorF = number_format($valor, 2, ',', '.');
                    $data_venc = implode('/', array_reverse(explode('-', $data_venc)));
                    $data = implode('/', array_reverse(explode('-', $data)));
                ?>
                <tr>
                    <td><?php echo $descricao ?></td>
                    <td>R$ <?php echo $valorF ?></td>
                    <td><?php echo $nome_forn ?></td>
                    <td><?php echo $nome_func ?></td>
                    <td><?php echo $data_venc ?></td>
                    <td><?php echo $data ?></td>
                    <td>
                        <?php if($pago == 'Sim'): ?>
                            <span class="badge-paga">Paga</span>
                        <?php else: ?>
                            <span class="badge-pendente">Pendente</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="total-box">
            Total: R$ <?php echo @number_format($saldo, 2, ',', '.') ?>
        </div>

        <div class="footer">
            <?php echo $rodape_relatorios ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>