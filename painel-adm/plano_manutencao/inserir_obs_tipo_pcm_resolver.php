<?php 
require_once("../../conexao.php"); 
@session_start();

$id_pcm = isset($_POST['id_pcm']) ? intval($_POST['id_pcm']) : 0;
$id_tipo_pcm = isset($_POST['id_tipo_pcm']) ? intval($_POST['id_tipo_pcm']) : 0;
$observacao = isset($_POST['observacao']) ? trim($_POST['observacao']) : '';

if ($id_pcm == 0 || $id_tipo_pcm == 0 || $observacao == '') {
    echo "Dados incompletos.";
    exit();
}

try {
    // Inicializa variáveis
    $id_pcm_preventiva = null;
    $id_pcm_preditiva = null;
    $id_pcm_corretiva = null;

    // Busca em pcm_preventiva
    $stmt = $pdo->prepare("SELECT id FROM pcm_preventiva WHERE pcm = :id_pcm LIMIT 1");
    $stmt->execute([':id_pcm' => $id_pcm]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id_pcm_preventiva = $row['id'];
    }

    // Busca em pcm_preditiva
    $stmt = $pdo->prepare("SELECT id FROM pcm_preditiva WHERE pcm = :id_pcm LIMIT 1");
    $stmt->execute([':id_pcm' => $id_pcm]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id_pcm_preditiva = $row['id'];
    }

    // Busca em pcm_corretiva
    $stmt = $pdo->prepare("SELECT id FROM pcm_corretiva WHERE pcm = :id_pcm LIMIT 1");
    $stmt->execute([':id_pcm' => $id_pcm]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id_pcm_corretiva = $row['id'];
    }

    // Verifica se já existe o registro
    $query = $pdo->prepare("SELECT id FROM obs_tipo_pcm WHERE id_pcm = :id_pcm AND id_tipo_pcm = :id_tipo_pcm");
    $query->execute([
        ':id_pcm' => $id_pcm,
        ':id_tipo_pcm' => $id_tipo_pcm
    ]);

    if ($query->rowCount() > 0) {
        // Atualiza registro existente
        $update = $pdo->prepare("
            UPDATE obs_tipo_pcm SET 
                observacao = :obs,
                id_pcm_preventiva = :id_pcm_preventiva,
                id_pcm_preditiva = :id_pcm_preditiva,
                id_pcm_corretiva = :id_pcm_corretiva
            WHERE id_pcm = :id_pcm AND id_tipo_pcm = :id_tipo_pcm
        ");
        $update->execute([
            ':obs' => $observacao,
            ':id_pcm_preventiva' => $id_pcm_preventiva,
            ':id_pcm_preditiva' => $id_pcm_preditiva,
            ':id_pcm_corretiva' => $id_pcm_corretiva,
            ':id_pcm' => $id_pcm,
            ':id_tipo_pcm' => $id_tipo_pcm
        ]);
        echo "Atualizado";
    } else {
        // Insere novo registro
        $insert = $pdo->prepare("
            INSERT INTO obs_tipo_pcm 
                (id_pcm, id_tipo_pcm, observacao, id_pcm_preventiva, id_pcm_preditiva, id_pcm_corretiva)
            VALUES 
                (:id_pcm, :id_tipo_pcm, :obs, :id_pcm_preventiva, :id_pcm_preditiva, :id_pcm_corretiva)
        ");
        $insert->execute([
            ':id_pcm' => $id_pcm,
            ':id_tipo_pcm' => $id_tipo_pcm,
            ':obs' => $observacao,
            ':id_pcm_preventiva' => $id_pcm_preventiva,
            ':id_pcm_preditiva' => $id_pcm_preditiva,
            ':id_pcm_corretiva' => $id_pcm_corretiva
        ]);
        echo "Inserido";
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>
