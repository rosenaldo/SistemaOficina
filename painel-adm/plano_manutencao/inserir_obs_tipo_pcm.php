<?php 
require_once("../../conexao.php"); 
@session_start();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$tipo_pcm = isset($_POST['tipo_pcm']) ? trim($_POST['tipo_pcm']) : '';
$observacao = isset($_POST['observacao']) ? trim($_POST['observacao']) : null;

if ($id == 0 || $tipo_pcm == '') {
    echo "Dados incompletos.";
    exit();
}

try {
    // Define a tabela com base no tipo de PCM
    switch (strtolower($tipo_pcm)) {
        case 'preventiva':
            $tabela = "pcm_preventiva";
            break;
        case 'preditiva':
            $tabela = "pcm_preditiva";
            break;
        case 'corretiva':
            $tabela = "pcm_corretiva";
            break;
        default:
            echo "Tipo de PCM inválido.";
            exit();
    }

    // Atualiza apenas o campo observacao
    $update = $pdo->prepare("UPDATE $tabela SET observacao = :observacao WHERE id = :id");
    $update->bindValue(':observacao', $observacao !== '' ? $observacao : null, PDO::PARAM_STR);
    $update->bindValue(':id', $id, PDO::PARAM_INT);
    $update->execute();

    if ($update->rowCount() > 0) {
        echo "Observação atualizada com sucesso!";
    } else {
        echo "Nada foi alterado.";
    }

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>
