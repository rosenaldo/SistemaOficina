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
    <title>Catálogo de Produtos</title>
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

    .product-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .product-table th {
        background-color: #f8f9fa;
        text-align: left;
        padding: 10px;
        border: 1px solid #dee2e6;
        font-weight: 600;
    }

    .product-table td {
        padding: 10px;
        border: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
    }

    .low-stock {
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

    .summary-card {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-top: 20px;
        border: 1px solid #e9ecef;
    }

    .text-danger {
        color: #dc3545;
    }

    .text-success {
        color: #28a745;
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
                <h1 class="document-title">CATÁLOGO DE PRODUTOS</h1>
            </div>
            <div class="col-md-4 text-end">
                <div class="text-muted">Data: <?php echo $data_hoje ?></div>
            </div>
        </div>

        <table class="product-table">
            <thead>
                <tr>
                    <th width="20%">Nome</th>
                    <th width="15%">Categoria</th>
                    <th width="15%">Fornecedor</th>
                    <th width="10%">Fabricante</th>
                    <th width="10%" class="currency">Compra</th>
                    <th width="10%" class="currency">Venda</th>
                    <th width="10%">Estoque</th>
                    <th width="10%">Imagem</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $query = $pdo->query("SELECT * FROM produtos ORDER BY estoque ASC");
                $res = $query->fetchAll(PDO::FETCH_ASSOC);
                $totalProdutos = @count($res);
                
                for ($i=0; $i < @count($res); $i++) { 
                    $nome = $res[$i]['nome'];
                    $categoria = $res[$i]['categoria'];
                    $fornecedor = $res[$i]['fornecedor'];
                    $fabricante = $res[$i]['fabricante'];
                    $valor_compra = $res[$i]['valor_compra'];
                    $valor_venda = $res[$i]['valor_venda'];
                    $estoque = $res[$i]['estoque'];
                    $descricao = $res[$i]['descricao'];
                    $imagem = $res[$i]['imagem'];
                    $id = $res[$i]['id'];

                    $stock_class = ($estoque < $nivel_estoque) ? 'low-stock' : '';

                    $valor_compra_fmt = number_format($valor_compra, 2, ',', '.');
                    $valor_venda_fmt = number_format($valor_venda, 2, ',', '.');

                    $query_cat = $pdo->query("SELECT * FROM categorias WHERE id = '$categoria'");
                    $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                    $nome_cate = $res_cat[0]['nome'];

                    $query_forn = $pdo->query("SELECT * FROM fornecedores WHERE id = '$fornecedor'");
                    $res_forn = $query_forn->fetchAll(PDO::FETCH_ASSOC);
                    $nome_forn = $res_forn[0]['nome'];
                ?>
                <tr>
                    <td><?php echo $nome ?></td>
                    <td><?php echo $nome_cate ?></td>
                    <td><?php echo $nome_forn ?></td>
                    <td><?php echo $fabricante ?></td>
                    <td class="currency">R$ <?php echo $valor_compra_fmt ?></td>
                    <td class="currency">R$ <?php echo $valor_venda_fmt ?></td>
                    <td class="<?php echo $stock_class ?>"><?php echo $estoque ?></td>
                    <td><img src="../img/produtos/<?php echo $imagem ?>" class="product-image"></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="row">
            <div class="col-md-12">
                <div class="summary-card text-end">
                    <h5>Total de Produtos: <strong><?php echo $totalProdutos ?></strong></h5>
                    <?php if($totalProdutos == 0): ?>
                        <div class="alert alert-warning mt-2">Nenhum produto cadastrado</div>
                    <?php endif; ?>
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