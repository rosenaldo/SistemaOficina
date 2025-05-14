<?php 
require_once("../../conexao.php"); 
@session_start();

$id = $_GET['id'];
require_once("data_formatada.php"); 

// DADOS DO ORÇAMENTO
$query_orc = $pdo->query("SELECT * FROM pcm WHERE id = '$id'");
$res_orc = $query_orc->fetchAll(PDO::FETCH_ASSOC);
$cpf_cliente = $res_orc[0]['cliente'];
$veiculo = $res_orc[0]['veiculo'];
$descricao = $res_orc[0]['descricao'];
$mecanico = $res_orc[0]['mecanico'];
$data_orc = $res_orc[0]['data'];
$servico = $res_orc[0]['servico'];

// DADOS DO MECÂNICO
$query_mec = $pdo->query("SELECT * FROM mecanicos WHERE cpf = '$mecanico'");
$res_mec = $query_mec->fetchAll(PDO::FETCH_ASSOC);
$nome_mecanico = !empty($res_mec[0]['nome']) ? $res_mec[0]['nome'] : 'Não informado';

// DADOS DO SERVIÇO
$query_serv = $pdo->query("SELECT * FROM servicos WHERE id = '$servico'");
$res_serv = $query_serv->fetchAll(PDO::FETCH_ASSOC);
$nome_servico = !empty($res_serv[0]['nome']) ? $res_serv[0]['nome'] : 'Não informado';

// DADOS DO CLIENTE
$query_cli = $pdo->query("SELECT * FROM clientes WHERE cpf = '$cpf_cliente'");
$res_cli = $query_cli->fetchAll(PDO::FETCH_ASSOC);
$nome_cli = $res_cli[0]['nome'];
$telefone_cli = $res_cli[0]['telefone'];
$endereco_cli = $res_cli[0]['endereco'];
$email_cli = $res_cli[0]['email'];

// DADOS DO VEÍCULO
$query_vei = $pdo->query("SELECT * FROM veiculos WHERE id = '$veiculo'");
$res_vei = $query_vei->fetchAll(PDO::FETCH_ASSOC);
$marca = $res_vei[0]['marca'] . ' - ' . $res_vei[0]['modelo'];
$placa = $res_vei[0]['placa'];
$cor = $res_vei[0]['cor'];
$ano = $res_vei[0]['ano'];
$km = $res_vei[0]['km'];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plano de Controle de Manutenção</title>
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

    .maintenance-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .maintenance-table th {
        background-color: #f8f9fa;
        text-align: left;
        padding: 10px;
        border: 1px solid #dee2e6;
        font-weight: 600;
    }

    .maintenance-table td {
        padding: 10px;
        border: 1px solid #dee2e6;
    }

    .check-box {
        text-align: center;
        font-size: 18px;
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

    .no-maintenance {
        color: #7f8c8d;
        font-style: italic;
        padding: 10px;
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
                <h1 class="document-title">PLANO DE CONTROLE DE MANUTENÇÃO</h1>
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
                    <p><span class="info-label">CPF/CNPJ:</span> <span class="info-value"><?php echo $cpf_cliente ?></span></p>
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
            <div class="observation">
                <p><strong>Observações:</strong> <?php echo $descricao ?></p>
            </div>
        </div>

        <!-- MANUTENÇÃO PREVENTIVA -->
        <div class="maintenance-section">
            <h5 class="section-title">MANUTENÇÃO PREVENTIVA</h5>
            <?php 
            $query = $pdo->query("SELECT * FROM pcm_preventiva WHERE pcm = '$id'");
            $res = $query->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($res) > 0) { 
            ?>
            <table class="maintenance-table">
                <thead>
                    <tr>
                        <th width="50%">Serviço</th>
                        <th width="40%">Observação</th>
                        <th width="10%" class="text-center">Check</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < count($res); $i++) {
                        $serv = $res[$i]['servico'];
                        
                        $query_ser = $pdo->query("SELECT * FROM tipo_pcm WHERE id = '$serv'");
                        $res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
                        $nome_ser = $res_ser[0]['descricao'];
                        
                        $query_obs = $pdo->query("SELECT observacao FROM pcm_preventiva WHERE pcm = '$id' AND servico = '$serv' LIMIT 1");
                        $obs = $query_obs->fetch(PDO::FETCH_ASSOC);
                        $observacao = $obs ? $obs['observacao'] : '';
                    ?>
                    <tr>
                        <td><?php echo $nome_ser ?></td>
                        <td><?php echo htmlspecialchars($observacao) ?></td>
                        <td class="check-box">☐</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
                <div class="no-maintenance">Nenhuma manutenção preventiva cadastrada</div>
            <?php } ?>
        </div>

        <!-- MANUTENÇÃO CORRETIVA -->
        <div class="maintenance-section">
            <h5 class="section-title">MANUTENÇÃO CORRETIVA</h5>
            <?php 
            $query = $pdo->query("SELECT * FROM pcm_corretiva WHERE pcm = '$id'");
            $res = $query->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($res) > 0) { 
            ?>
            <table class="maintenance-table">
                <thead>
                    <tr>
                        <th width="50%">Serviço</th>
                        <th width="40%">Observação</th>
                        <th width="10%" class="text-center">Check</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < count($res); $i++) {
                        $serv = $res[$i]['servico'];
                        
                        $query_ser = $pdo->query("SELECT * FROM tipo_pcm WHERE id = '$serv'");
                        $res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
                        $nome_ser = $res_ser[0]['descricao'];
                        
                        $query_obs = $pdo->query("SELECT observacao FROM pcm_corretiva WHERE pcm = '$id' AND servico = '$serv' LIMIT 1");
                        $obs = $query_obs->fetch(PDO::FETCH_ASSOC);
                        $observacao = $obs ? $obs['observacao'] : '';
                    ?>
                    <tr>
                        <td><?php echo $nome_ser ?></td>
                        <td><?php echo htmlspecialchars($observacao) ?></td>
                        <td class="check-box">☐</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
                <div class="no-maintenance">Nenhuma manutenção corretiva cadastrada</div>
            <?php } ?>
        </div>

        <!-- MANUTENÇÃO PREDITIVA -->
        <div class="maintenance-section">
            <h5 class="section-title">MANUTENÇÃO PREDITIVA</h5>
            <?php 
            $query = $pdo->query("SELECT * FROM pcm_preditiva WHERE pcm = '$id'");
            $res = $query->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($res) > 0) { 
            ?>
            <table class="maintenance-table">
                <thead>
                    <tr>
                        <th width="50%">Serviço</th>
                        <th width="40%">Observação</th>
                        <th width="10%" class="text-center">Check</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < count($res); $i++) {
                        $serv = $res[$i]['servico'];
                        
                        $query_ser = $pdo->query("SELECT * FROM tipo_pcm WHERE id = '$serv'");
                        $res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
                        $nome_ser = $res_ser[0]['descricao'];
                        
                        $query_obs = $pdo->query("SELECT observacao FROM pcm_preditiva WHERE pcm = '$id' AND servico = '$serv' LIMIT 1");
                        $obs = $query_obs->fetch(PDO::FETCH_ASSOC);
                        $observacao = $obs ? $obs['observacao'] : '';
                    ?>
                    <tr>
                        <td><?php echo $nome_ser ?></td>
                        <td><?php echo htmlspecialchars($observacao) ?></td>
                        <td class="check-box">☐</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
                <div class="no-maintenance">Nenhuma manutenção preditiva cadastrada</div>
            <?php } ?>
        </div>

        <div class="signature-area">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Mecânico Responsável:</strong> <?php echo $nome_mecanico ?></p>
                </div>
                <div class="col-md-6 text-end">
                    <div class="signature-line">Assinatura do Cliente</div>
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