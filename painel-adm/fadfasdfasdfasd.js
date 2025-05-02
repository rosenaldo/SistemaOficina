vou passar todos os codigos envolvidos 

<input type="text" name="obs_preventiva_<?php echo $id_prd ?>" id="obs_<?php echo $id_prd ?>"
class="form-control form-control-sm d-inline-block obs-preventiva" style="width: 200px;"
placeholder="Ex: 5 litros" data-tipo-pcm="preventiva" data-id="<?php echo $id_prd ?>"
data-id-pcm="<?php echo $id_orc ?>" value="<?php echo $valor_observacao ?>"
onblur="enviarObservacao(<?php echo $id_prd ?>, <?php echo $id_orc ?>)">


if (@$_GET["funcao2"] != null && @$_GET["funcao2"] == "adicionarServ") {
    $id_orc = $_GET['id'];
    $id_serv = $_GET['id_serv'];
    $obs = isset($_GET["obs"]) ? $_GET["obs"] : '';

    if (!isset($_GET["funcao3"])) {
        $query = $pdo->prepare("SELECT COUNT(*) FROM pcm_preventiva WHERE pcm = :pcm AND servico = :servico");
        $query->execute([
            ':pcm' => $id_orc,
            ':servico' => $id_serv
        ]);
        $existe = $query->fetchColumn();

        if ($existe == 0) {
            $stmt = $pdo->prepare("INSERT INTO pcm_preventiva (pcm, servico, observacao) VALUES (:pcm, :servico, :obs)");
            $stmt->execute([
                ':pcm' => $id_orc,
                ':servico' => $id_serv,
                ':obs' => $obs
            ]);

            $update = $pdo->prepare("UPDATE pcm SET servico = :servico WHERE id = :id");
            $update->execute([
                ':servico' => $id_serv,
                ':id' => $id_orc
            ]);
        } else {
            // Atualiza observação se já existir
            $updateObs = $pdo->prepare("UPDATE pcm_preventiva SET observacao = :obs WHERE pcm = :pcm AND servico = :servico");
            $updateObs->execute([
                ':obs' => $obs,
                ':pcm' => $id_orc,
                ':servico' => $id_serv
            ]);

            // echo "<script>alert('Serviço já existia. Observação atualizada.');</script>";
        }
    }

    echo "<script>window.location='index.php?pag=$pag&id=$id_orc&funcao=detalhesServ';</script>";
}



<script>
function enviarObservacao(idServ, idOrc) {
    const obs = document.getElementById('obs_' + idServ).value;
    const pag = "<?php echo $pag ?>";

    // Cria o objeto XMLHttpRequest para enviar dados via AJAX
    const xhr = new XMLHttpRequest();

    // Configura o tipo de requisição e a URL de destino
    xhr.open('GET',
        `index.php?pag=${pag}&funcao=servicos&funcao2=adicionarServ&id_serv=${idServ}&id=${idOrc}&obs=${encodeURIComponent(obs)}`,
        true);

    // Define a função que será chamada quando a resposta chegar
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            // A resposta foi bem-sucedida
            // Exibe um alerta ou notificação, caso queira

        } else {
            // Algo deu errado
            alert('Erro ao atualizar observação. Tente novamente.');
        }
    };

    // Envia a requisição
    xhr.send();
}
</script>





<input type="text" name="obs_corretiva_<?php echo $id_prd ?>" id="obs_<?php echo $id_prd ?>"
        class="form-control form-control-sm d-inline-block obs-corretiva" style="width: 200px;"
        placeholder="Ex: 5 litros" data-tipo-pcm="corretiva" data-id="<?php echo $id_prd ?>"
        data-id-pcm="<?php echo $id_orc ?>" value="<?php echo $valor_observacao ?>"
        onblur="enviarObservacao(<?php echo $id_prd ?>, <?php echo $id_orc ?>)">




if (@$_GET["funcao2"] != null && @$_GET["funcao2"] == "adicionarServ2") {
    $id_orc = $_GET['id'];
    $id_serv = $_GET['id_serv'];
    $obs = isset($_GET["obs"]) ? $_GET["obs"] : '';

    if (!isset($_GET["funcao3"])) {
        $query = $pdo->prepare("SELECT COUNT(*) FROM pcm_corretiva WHERE pcm = :pcm AND servico = :servico");
        $query->execute([
            ':pcm' => $id_orc,
            ':servico' => $id_serv
        ]);
        $existe = $query->fetchColumn();

        if ($existe == 0) {
            // Inserir com observação
            $stmt = $pdo->prepare("INSERT INTO pcm_corretiva (pcm, servico, observacao) VALUES (:pcm, :servico, :obs)");
            $stmt->execute([
                ':pcm' => $id_orc,
                ':servico' => $id_serv,
                ':obs' => $obs
            ]);

            $update = $pdo->prepare("UPDATE pcm SET servico = :servico WHERE id = :id");
            $update->execute([
                ':servico' => $id_serv,
                ':id' => $id_orc
            ]);
        } else { 
            // Atualiza observação se já existir
            $updateObs = $pdo->prepare("UPDATE pcm_corretiva SET observacao = :obs WHERE pcm = :pcm AND servico = :servico");
            $updateObs->execute([
                ':obs' => $obs,
                ':pcm' => $id_orc,
                ':servico' => $id_serv
            ]);
        }
    }

    echo "<script>window.location='index.php?pag=$pag&id=$id_orc&funcao=detalhesServ2';</script>";
}



<script>
function enviarObservacao(idServ, idOrc) {
    const obs = document.getElementById('obs_' + idServ).value;
    const pag = "<?php echo $pag ?>";

    // Cria o objeto XMLHttpRequest para enviar dados via AJAX
    const xhr = new XMLHttpRequest();

    // Configura o tipo de requisição e a URL de destino
    xhr.open('GET',
        `index.php?pag=${pag}&funcao=servicos&funcao2=adicionarServ2&id_serv=${idServ}&id=${idOrc}&obs=${encodeURIComponent(obs)}`,
        true);

    // Define a função que será chamada quando a resposta chegar
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            // A resposta foi bem-sucedida
            // Exibe um alerta ou notificação, caso queira

        } else {
            // Algo deu errado
            alert('Erro ao atualizar observação. Tente novamente.');
        }
    };

    // Envia a requisição
    xhr.send();
}
</script>