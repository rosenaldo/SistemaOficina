<?php 

require_once("../../conexao.php"); 
@session_start();

$id = $_GET['id'];
$email = @$_GET['email'];

$html = file_get_contents($url."painel-adm/rel/rel_pcm_html.php?id=$id");
echo $html;


?>