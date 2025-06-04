<?php 
require_once("../conexao.php"); 
@session_start();
require_once("data_formatada.php"); 

$id = $_GET['id'];

$query_vei = $pdo->query("SELECT * FROM veiculos WHERE id = '$id'");
$res_vei = $query_vei->fetchAll(PDO::FETCH_ASSOC);
$marca = $res_vei[0]['marca'] . ' - ' . $res_vei[0]['modelo'];
$placa = $res_vei[0]['placa'];
$cor = $res_vei[0]['cor'];
$ano = $res_vei[0]['ano'];
$km = $res_vei[0]['km'];
$cliente = $res_vei[0]['cliente'];

$query_cli = $pdo->query("SELECT * FROM clientes WHERE cpf = '$cliente'");
$res_cli = $query_cli->fetchAll(PDO::FETCH_ASSOC);
$nome_cli = $res_cli[0]['nome'];
$telefone_cli = $res_cli[0]['telefone'];
$endereco_cli = $res_cli[0]['endereco'];
$email_cli = $res_cli[0]['email'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviços do Veículo - <?php echo $marca ?></title>
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
    }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin: 20px 0 10px;
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 5px;
    }

    .client-info, .vehicle-info {
        margin-bottom: 20px;
    }

    .info-label {
        font-weight: 600;
        color: #7f8c8d;
        display: inline-block;
        width: 100px;
    }

    .info-value {
        color: #2c3e50;
    }

    .services-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .services-table th {
        background-color: #f8f9fa;
        text-align: left;
        padding: 10px;
        border: 1px solid #dee2e6;
        font-weight: 600;
    }

    .services-table td {
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

    .total-box {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        margin: 20px 0;
        text-align: right;
        font-weight: 600;
    }

    .no-services {
        color: #7f8c8d;
        font-style: italic;
        padding: 20px;
        text-align: center;
        border: 1px dashed #dee2e6;
        border-radius: 5px;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
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
                <h1 class="document-title">HISTÓRICO DE SERVIÇOS - <?php echo $marca ?></h1>
            </div>
            <div class="col-md-4 text-end">
                <div class="text-muted">Data: <?php echo $data_hoje ?></div>
            </div>
        </div>

        <div class="client-info">
            <h5 class="section-title">DADOS DO CLIENTE</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><span class="info-label">Nome:</span> <span class="info-value"><?php echo $nome_cli ?></span></p>
                    <p><span class="info-label">Email:</span> <span class="info-value"><?php echo $email_cli ?></span></p>
                    <p><span class="info-label">Endereço:</span> <span class="info-value"><?php echo $endereco_cli ?></span></p>
                </div>
                <div class="col-md-6">
                    <p><span class="info-label">Telefone:</span> <span class="info-value"><?php echo $telefone_cli ?></span></p>
                    <p><span class="info-label">CPF:</span> <span class="info-value"><?php echo $cliente ?></span></p>
                </div>
            </div>
        </div>

        <div class="vehicle-info">
            <h5 class="section-title">DADOS DO VEÍCULO</h5>
            <div class="row">
                <div class="col-md-12">
                    <p><span class="info-label">Marca/Modelo:</span> <span class="info-value"><?php echo $marca ?></span></p>
                    <p><span class="info-label">Placa:</span> <span class="info-value"><?php echo $placa ?></span> 
                    <span class="info-label">Cor:</span> <span class="info-value"><?php echo $cor ?></span></p>
                    <p><span class="info-label">Ano:</span> <span class="info-value"><?php echo $ano ?></span> 
                    <span class="info-label">KM:</span> <span class="info-value"><?php echo $km ?></span></p>
                </div>
            </div>
        </div>

        <h5 class="section-title">SERVIÇOS REALIZADOS</h5>
        <?php 
        $totalValores = 0;
        $query = $pdo->query("SELECT * FROM os WHERE veiculo = '$id' ORDER BY id ASC");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($res) > 0) { 
        ?>
        <table class="services-table">
            <thead>
                <tr>
                    <th width="25%">Serviço</th>
                    <th width="15%" class="text-right">Mão de Obra</th>
                    <th width="15%" class="text-right">Produtos/Peças</th>
                    <th width="15%" class="text-right">Total</th>
                    <th width="10%">Data</th>
                    <th width="10%">Garantia</th>
                    <th width="10%">Entrega</th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $i < count($res); $i++) {
                    $cliente = $res[$i]['cliente'];
                    $veiculo = $res[$i]['veiculo'];
                    $descricao = $res[$i]['descricao'];
                    $valor = $res[$i]['valor'];
                    $valor_mao_obra = $res[$i]['valor_mao_obra'];
                    $garantia = $res[$i]['garantia'];
                    $data = $res[$i]['data'];
                    $data_entrega = $res[$i]['data_entrega'];
                    $concluido = $res[$i]['concluido'];
                    $mecanico = $res[$i]['mecanico'];
                    $tipo = $res[$i]['tipo'];
                    $id_os = $res[$i]['id'];

                    $data = implode('/', array_reverse(explode('-', $data)));
                    $data_entrega = implode('/', array_reverse(explode('-', $data_entrega)));

                    $valorPeca = $valor - $valor_mao_obra;
                    $totalValores += $valor;

                    $valorF = number_format($valor, 2, ',', '.');
                    $valor_mao_obraF = number_format($valor_mao_obra, 2, ',', '.');
                    $valorPecaF = number_format($valorPeca, 2, ',', '.');
                    
                    $query_cat = $pdo->query("SELECT * FROM mecanicos WHERE cpf = '$mecanico'");
                    $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                    $nome_mecanico = !empty($res_cat[0]['nome']) ? $res_cat[0]['nome'] : 'Não informado';
                ?>
                <tr>
                    <td><?php echo $descricao ?></td>
                    <td class="text-right">R$ <?php echo $valor_mao_obraF ?></td>
                    <td class="text-right">R$ <?php echo $valorPecaF ?></td>
                    <td class="text-right">R$ <?php echo $valorF ?></td>
                    <td><?php echo $data ?></td>
                    <td><?php echo $garantia ?> Dias</td>
                    <td><?php echo $data_entrega ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <div class="total-box">
            TOTAL DE SERVIÇOS: R$ <?php echo number_format($totalValores, 2, ',', '.') ?>
        </div>
        <?php } else { ?>
            <div class="no-services">Este veículo ainda não possui serviços registrados</div>
        <?php } ?>

        <div class="footer">
            <?php echo $rodape_relatorios ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>