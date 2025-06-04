<?php 
require_once("../../conexao.php"); 
@session_start();

$id = $_GET['id'];
require_once("data_formatada.php"); 

// DADOS DO ORÇAMENTO
$query_orc = $pdo->query("SELECT * FROM orcamentos WHERE id = '$id'");
$res_orc = $query_orc->fetchAll(PDO::FETCH_ASSOC);
$cpf_cliente = $res_orc[0]['cliente'];
$veiculo = $res_orc[0]['veiculo'];
$descricao = $res_orc[0]['descricao'];
$obs = $res_orc[0]['obs'];
$valor_orc = $res_orc[0]['valor'];
$mecanico = $res_orc[0]['mecanico'];
$data_orc = $res_orc[0]['data'];
$data_entrega = $res_orc[0]['data_entrega'];
$servico = $res_orc[0]['servico'];
$garantia = $res_orc[0]['garantia'];

$data_entrega = implode('/', array_reverse(explode('-', $data_entrega)));
$valor_orc_f = number_format($valor_orc, 2, ',', '.');

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
$nome_cli = !empty($res_cli[0]['nome']) ? $res_cli[0]['nome'] : 'Não informado';
$telefone_cli = !empty($res_cli[0]['telefone']) ? $res_cli[0]['telefone'] : 'Não informado';
$endereco_cli = !empty($res_cli[0]['endereco']) ? $res_cli[0]['endereco'] : 'Não informado';
$email_cli = !empty($res_cli[0]['email']) ? $res_cli[0]['email'] : 'Não informado';

// DADOS DO VEÍCULO
$query_vei = $pdo->query("SELECT * FROM veiculos WHERE id = '$veiculo'");
$res_vei = $query_vei->fetchAll(PDO::FETCH_ASSOC);

// Valores padrão
$dados_veiculo = [
    'marca' => 'Não informada',
    'modelo' => 'Não informado',
    'placa' => 'Não informada',
    'cor' => 'Não informada',
    'ano' => 'Não informado',
    'km' => 'Não informado'
];

if(count($res_vei) > 0) {
    $dados_veiculo['marca'] = !empty($res_vei[0]['marca']) ? $res_vei[0]['marca'] : $dados_veiculo['marca'];
    $dados_veiculo['modelo'] = !empty($res_vei[0]['modelo']) ? $res_vei[0]['modelo'] : $dados_veiculo['modelo'];
    $dados_veiculo['placa'] = !empty($res_vei[0]['placa']) ? $res_vei[0]['placa'] : $dados_veiculo['placa'];
    $dados_veiculo['cor'] = !empty($res_vei[0]['cor']) ? $res_vei[0]['cor'] : $dados_veiculo['cor'];
    $dados_veiculo['ano'] = !empty($res_vei[0]['ano']) ? $res_vei[0]['ano'] : $dados_veiculo['ano'];
    $dados_veiculo['km'] = !empty($res_vei[0]['km']) ? $res_vei[0]['km'] : $dados_veiculo['km'];
}

// Atribuição das variáveis
$marca = $dados_veiculo['marca'] . ' - ' . $dados_veiculo['modelo'];
$placa = $dados_veiculo['placa'];
$cor = $dados_veiculo['cor'];
$ano = $dados_veiculo['ano'];
$km = $dados_veiculo['km'];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Orçamento</title>
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

    .client-info,
    .vehicle-info {
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

    .badge-guarantee {
        background-color: #e3f2fd;
        color: #1976d2;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
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
                <h1 class="document-title">ORÇAMENTO Nº <?php echo $id ?></h1>
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
                    <p><span class="info-label">Email:</span> <span class="info-value"><?php echo $email_cli ?></span>
                    </p>
                    <p><span class="info-label">Endereço:</span> <span
                            class="info-value"><?php echo $endereco_cli ?></span></p>
                </div>
                <div class="col-md-6">
                    <p><span class="info-label">Telefone:</span> <span
                            class="info-value"><?php echo $telefone_cli ?></span></p>
                    <p><span class="info-label">CPF/CNPJ:</span> <span
                            class="info-value"><?php echo $cpf_cliente ?></span></p>
                </div>
            </div>
        </div>

        <div class="vehicle-info">
            <h5 class="section-title">DADOS DO VEÍCULO</h5>
            <div class="row">
                <div class="col-md-12">
                    <p><span class="info-label">Marca/Modelo:</span> <span
                            class="info-value"><?php echo $marca ?></span></p>
                    <p><span class="info-label">Placa:</span> <span class="info-value"><?php echo $placa ?></span>
                        <span class="info-label">Cor:</span> <span class="info-value"><?php echo $cor ?></span>
                    </p>
                    <p><span class="info-label">Ano:</span> <span class="info-value"><?php echo $ano ?></span>
                        <span class="info-label">KM:</span> <span class="info-value"><?php echo $km ?></span>
                    </p>
                </div>
            </div>
            <div class="observation">
                <p><strong>Observações do Veículo:</strong> <?php echo $obs ?></p>
            </div>
        </div>

        <!-- SERVIÇOS -->
        <div class="service-section">
            <h5 class="section-title">SERVIÇOS PROPOSTOS</h5>
            <?php 
            $query_s = $pdo->query("SELECT * FROM orc_serv WHERE orcamento = '$id'");
            $res_s = $query_s->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($res_s) > 0) { 
            ?>
            <table class="service-table">
                <thead>
                    <tr>
                        <th width="70%">Descrição</th>
                        <th width="30%">Valor (R$)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_ser = 0;
                    for ($i = 0; $i < count($res_s); $i++) {
                        $serv = $res_s[$i]['servico'];
                        
                        $query_ser = $pdo->query("SELECT * FROM servicos WHERE id = '$serv'");
                        $res_ser = $query_ser->fetchAll(PDO::FETCH_ASSOC);
                        $nome_ser = $res_ser[0]['nome'];
                        $valor_ser = $res_ser[0]['valor'];
                        
                        $total_ser += $valor_ser;
                        $valor_ser_f = number_format($valor_ser, 2, ',', '.');
                    ?>
                    <tr>
                        <td><?php echo $nome_ser ?></td>
                        <td>R$ <?php echo $valor_ser_f ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
            <div class="no-items">Nenhum serviço cadastrado</div>
            <?php } ?>
        </div>

        <!-- PRODUTOS/PEÇAS -->
        <div class="service-section">
            <h5 class="section-title">PEÇAS/PRODUTOS</h5>
            <?php 
            $query = $pdo->query("SELECT * FROM orc_prod WHERE orcamento = '$id'");
            $res = $query->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($res) > 0) { 
            ?>
            <table class="service-table">
                <thead>
                    <tr>
                        <th width="60%">Descrição</th>
                        <th width="20%">Valor (R$)</th>
                        <th width="20%">Quantidade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_prod = 0;
                    for ($i = 0; $i < count($res); $i++) {
                        $prod = $res[$i]['produto'];
                        
                        $query_pro = $pdo->query("SELECT * FROM produtos WHERE id = '$prod'");
                        $res_pro = $query_pro->fetchAll(PDO::FETCH_ASSOC);
                        $nome_prod = $res_pro[0]['nome'];
                        $valor_prod = $res_pro[0]['valor_venda'];
                        
                        $total_prod += $valor_prod;
                        $valor_prod_f = number_format($valor_prod, 2, ',', '.');
                    ?>
                    <tr>
                        <td><?php echo $nome_prod ?></td>
                        <td>R$ <?php echo $valor_prod_f ?></td>
                        <td>1</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
            <div class="no-items">Nenhum produto/peça cadastrado</div>
            <?php } ?>
        </div>

        <!-- RESUMO DO ORÇAMENTO -->
        <div class="row">
            <div class="col-md-6">
                <div class="observation">
                    <h5 class="section-title">LAUDO DO MECÂNICO</h5>
                    <p><?php echo $descricao ?></p>
                    <?php if($garantia > 0) { ?>
                    <span class="badge-guarantee">Garantia: <?php echo $garantia ?> dias</span>
                    <?php } ?>
                    <p class="mt-2"><strong>Mecânico Responsável:</strong> <?php echo $nome_mecanico ?></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="total-box">
                    <h5 class="section-title">RESUMO FINANCEIRO</h5>
                    <div class="row">
                        <div class="col-6">
                            <p class="total-label">Serviços:</p>
                            <p class="total-label">Produtos:</p>
                            <p class="total-label">Mão de Obra:</p>
                            <hr>
                            <p class="total-label">Total:</p>
                        </div>
                        <div class="col-6 text-end">
                            <p>R$ <?php echo isset($total_ser) && is_numeric($total_ser) ? number_format($total_ser, 2, ',', '.') : '0,00' ?></p>
                            <p>R$ <?php echo isset($total_prod) && is_numeric($total_prod) ? number_format($total_prod, 2, ',', '.') : '0,00' ?></p>
                            <p>R$ <?php echo $valor_orc_f ?></p>
                            <hr>
                            <p class="total-value">R$
                            <?php echo number_format(($total_ser ?? 0) + ($total_prod ?? 0) + ($valor_orc ?? 0), 2, ',', '.') ?>

                        </div>
                    </div>
                    <div class="mt-3">
                        <p><strong>Previsão de Entrega:</strong> <?php echo $data_entrega ?></p>
                        <p><small>Orçamento válido até:
                                <?php echo date('d/m/Y', strtotime("+$validade_orcamento_dias days",strtotime($data_orc))) ?></small>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="signature-area">
            <div class="row">
                <div class="col-md-12 text-center">
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