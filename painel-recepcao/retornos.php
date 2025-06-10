<?php 
@session_start();
require_once("verificar_usuario.php");

$pag = "retornos";
require_once("../conexao.php"); 

$data_hoje = date('Y-m-d');
$data_retorno = date('Y-m-d', strtotime("-$dias_alerta_retorno days",strtotime($data_hoje)));
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-undo mr-2"></i>Controle de Retornos
        </h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Clientes para Retorno</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Modelo</th>
                            <th>Placa</th>
                            <th>Cliente</th>
                            <th>Telefone</th>
                            <th>Último Serviço</th>
                            <th>Serviço</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query_c = $pdo->query("SELECT * FROM retornos WHERE data_serv <= '$data_retorno' AND data_contato <= '$data_retorno' ORDER BY id ASC");
                        $res_c = $query_c->fetchAll(PDO::FETCH_ASSOC);
                        
                        for ($i=0; $i < @count($res_c); $i++) { 
                            foreach ($res_c[$i] as $key => $value) {
                            }
                            
                            $veiculo = $res_c[$i]['veiculo'];
                            $data_serv = $res_c[$i]['data_serv'];
                            $data_contato = $res_c[$i]['data_contato'];
                            $id = $res_c[$i]['id'];

                            $data_serv_formatada = implode('/', array_reverse(explode('-', $data_serv)));

                            $query = $pdo->query("SELECT * FROM veiculos WHERE id = '$veiculo'");
                            $res = $query->fetchAll(PDO::FETCH_ASSOC);
                            $marca = $res[0]['marca'] .' - ' .$res[0]['modelo'];                        
                            $placa = $res[0]['placa'];
                            $cliente = $res[0]['cliente'];
                            
                            $query_cat = $pdo->query("SELECT * FROM clientes WHERE cpf = '$cliente'");
                            $res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
                            $nome_cli = $res_cat[0]['nome'];
                            $tel_cli = $res_cat[0]['telefone'];
                            $email_cli = $res_cat[0]['email'];

                            $query_orc = $pdo->query("SELECT * FROM os WHERE veiculo = '$veiculo' ORDER BY id DESC LIMIT 1");
                            $res_orc = $query_orc->fetchAll(PDO::FETCH_ASSOC);
                            $descricao = $res_orc[0]['descricao'];
                        ?>
                        <tr>
                            <td><?php echo $marca ?></td>
                            <td><?php echo $placa ?></td>
                            <td><?php echo $nome_cli ?></td>
                            <td><?php echo $tel_cli ?></td>
                            <td><?php echo $data_serv_formatada ?></td>
                            <td><?php echo $descricao ?></td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=atualizar&id=<?php echo $id ?>" 
                                       class="btn btn-sm btn-success mr-1" title="Atualizar Retorno">
                                       <i class="fas fa-check"></i>
                                    </a>
                                    <a href="index.php?pag=<?php echo $pag ?>&funcao=email&email=<?php echo $email_cli ?>&nome=<?php echo $nome_cli ?>" 
                                       class="btn btn-sm btn-primary" title="Enviar Email">
                                       <i class="far fa-envelope"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php 
if (@$_GET["funcao"] != null && @$_GET["funcao"] == "atualizar") {
    $id_ret = $_GET["id"];
    $pdo->query("UPDATE retornos SET data_contato = curDate() WHERE id = '$id_ret'");
    echo "<script language='javascript'> window.location = 'index.php?pag=$pag'; </script>";
}

if (@$_GET["funcao"] != null && @$_GET["funcao"] == "email") {
    //ENVIAR O EMAIL COM A SENHA
    $destinatario = $_GET['email'];
    $assunto = utf8_decode($nome_oficina . ' - Promoção de Serviços');
    $mensagem = utf8_decode('Olá '.$_GET['nome']. ', '. $mensagem_retorno . "\n" .$endereco_oficina. "\n" .$telefone_oficina);
    $cabecalhos = "From: ".$email_adm;
    @mail($destinatario, $assunto, $mensagem, $cabecalhos);
}
?>

<!-- Configurações da tabela -->
<script type="text/javascript">
$(document).ready(function() {
    $('#dataTable').dataTable({
        "ordering": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        }
    });
});
</script>

<!-- Estilos personalizados -->
<style>
.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.03);
}

.card-header {
    border-radius: 0.35rem 0.35rem 0 0 !important;
}

.table th {
    border-top: none;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.text-center {
    text-align: center !important;
}

.btn-group {
    display: flex;
    justify-content: center;
}
</style>