<?php 
@session_start();
if(@$_SESSION['nivel_usuario'] == null || @$_SESSION['nivel_usuario'] != 'admin'){
    echo "<script language='javascript'> window.location='../index.php' </script>";
}

$pag = "orcamentos";
require_once("../conexao.php"); 

$funcao = @$_GET['funcao'];
$varios_serv = '';
?>

<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-invoice-dollar mr-2"></i>Orçamentos
        </h1>
        <a href="index.php?pag=<?php echo $pag ?>&funcao=novo" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Novo Orçamento
        </a>
    </div>
