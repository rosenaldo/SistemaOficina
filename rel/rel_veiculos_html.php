<?php
require_once("../conexao.php");
@session_start();

require_once("data_formatada.php");
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Veículos na Oficina</title>
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
            width: 100px;
        }

        .info-value {
            color: #2c3e50;
        }

        .vehicles-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .vehicles-table th {
            background-color: #f8f9fa;
            text-align: left;
            padding: 10px;
            border: 1px solid #dee2e6;
            font-weight: 600;
        }

        .vehicles-table td {
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

        .no-vehicles {
            color: #7f8c8d;
            font-style: italic;
            padding: 10px;
            text-align: center;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-completed {
            background-color: #cce5ff;
            color: #004085;
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
                <h1 class="document-title">Relatório de Veículos na Oficina</h1>
            </div>
            <div class="col-md-4 text-end">
                <div class="text-muted">Data: <?php echo $data_hoje ?></div>
            </div>
        </div>

        <div class="vehicles-section">
            <table class="vehicles-table">
                <thead>
                    <tr>
                        <th width="20%">Modelo</th>
                        <th width="10%">Placa</th>
                        <th width="20%">Cliente</th>
                        <th width="15%">Mecânico</th>
                        <th width="15%">Data Entrada</th>
                        <th width="20%">Serviço</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query_c = $pdo->query("SELECT * FROM controles ORDER BY id ASC");
                    $res_c = $query_c->fetchAll(PDO::FETCH_ASSOC);

                    if (count($res_c) > 0) {
                        for ($i = 0; $i < count($res_c); $i++) {
                            $veiculo = $res_c[$i]['veiculo'];
                            $mecanico = $res_c[$i]['mecanico'];
                            $data = $res_c[$i]['data'];
                            $descricao = $res_c[$i]['descricao'];
                            $id = $res_c[$i]['id'];

                            $query = $pdo->query("SELECT * FROM veiculos WHERE id = '$veiculo'");
                            $res = $query->fetchAll(PDO::FETCH_ASSOC);

                            // Verifica se $res existe e tem pelo menos um elemento
                            if (!empty($res) && isset($res[0])) {
                                $marca = $res[0]['marca'];
                                $modelo = $res[0]['modelo'];
                                $placa = $res[0]['placa'];
                                $cliente = $res[0]['cliente'];
                            } else {
                                $marca = "Não disponível";
                                $modelo = "Não disponível";
                                $placa = "Não disponível";
                                $cliente = "Não disponível";

                            }

                            $data_formatada = implode('/', array_reverse(explode('-', $data)));

                            $query_cli = $pdo->query("SELECT * FROM clientes WHERE cpf = '$cliente'");
                            $res_cli = $query_cli->fetchAll(PDO::FETCH_ASSOC);
                            $nome_cli = !empty($res_cli[0]['nome']) ? $res_cli[0]['nome'] : 'Não informado';

                            $query_mec = $pdo->query("SELECT * FROM mecanicos WHERE cpf = '$mecanico'");
                            $res_mec = $query_mec->fetchAll(PDO::FETCH_ASSOC);
                            $nome_mec = !empty($res_mec[0]['nome']) ? $res_mec[0]['nome'] : 'Não informado';
                    ?>
                            <tr>
                                <td><?php echo $marca . ' - ' . $modelo ?></td>
                                <td><?php echo $placa ?></td>
                                <td><?php echo $nome_cli ?></td>
                                <td><?php echo $nome_mec ?></td>
                                <td><?php echo $data_formatada ?></td>
                                <td><?php echo $descricao ?></td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="6" class="no-vehicles">Nenhum veículo encontrado na oficina</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <?php echo $rodape_relatorios ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>