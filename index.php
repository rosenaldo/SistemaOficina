<?php 
require_once("conexao.php");

// CRIAR AUTOMATICAMENTE O USUARIO ADMIN
$query = $pdo->query("SELECT * FROM usuarios where email = '$email_adm' and senha = '123'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg == 0){
    $res = $pdo->query("INSERT INTO usuarios SET nome = 'Administrador', cpf = '000.000.000-00', email = '$email_adm', senha = '123', nivel = 'admin'");    
}

// EXCLUIR ORÇAMENTO APÓS XX DIAS
$data_hoje = date('Y-m-d');
$data_15 = date('Y-m-d', strtotime("-$excluir_orcamento_dias days",strtotime($data_hoje)));

$query = $pdo->query("SELECT * FROM orcamentos where data <= '$data_15'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
for ($i=0; $i < @count($res); $i++) { 
    foreach ($res[$i] as $key => $value) {
    }
    $id_orc = $res[$i]['id'];
    $pdo->query("DELETE FROM orcamentos where id = '$id_orc'");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gestão</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Favicon -->
    <link rel="shortcut icon" href="img/logo-favicon.ico" type="image/x-icon">
    <link rel="icon" href="img/logo-favicon.ico" type="image/x-icon">

    <style>
    :root {
        --primary-color: #7C3AED;
        --primary-light: #8B5CF6;
        --primary-dark: #6D28D9;
        --dark-color: #1F2937;
        --light-color: #F9FAFB;
        --gray-color: #6B7280;
        --danger-color: #EF4444;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-image: url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
        background-size: cover;
        background-position: center;
        background-blend-mode: overlay;
    }

    .login-container {
        width: 100%;
        max-width: 400px;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        animation: fadeIn 0.5s ease-in-out;
    }

    .login-header {
        background-color: var(--primary-color);
        color: white;
        padding: 1.5rem;
        text-align: center;

    }

    .login-header img {
        max-width: 200px;
        margin-bottom: 0.5rem;
    }

    .login-header h2 {
        font-weight: 600;
        margin-bottom: 0;
        font-size: 1.5rem;
    }

    .login-body {
        padding: 2rem;
    }

    .form-control {
        height: 48px;
        border-radius: 8px;
        border: 1px solid #E5E7EB;
        padding-left: 15px;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 0.25rem rgba(124, 58, 237, 0.25);
    }

    .input-group-text {
        background-color: transparent;
        border-right: none;
    }

    .input-group .form-control {
        border-left: none;
    }

    .input-group:focus-within .input-group-text {
        color: var(--primary-color);
    }

    .btn-login {
        background-color: var(--primary-color);
        border: none;
        color: white;
        height: 48px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-login:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .forgot-password {
        color: var(--gray-color);
        text-decoration: none;
        font-size: 0.875rem;
        transition: color 0.3s;
    }

    .forgot-password:hover {
        color: var(--primary-color);
        text-decoration: none;
    }

    .divider {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
    }

    .divider::before,
    .divider::after {
        content: "";
        flex: 1;
        border-bottom: 1px solid #E5E7EB;
    }

    .divider-text {
        padding: 0 1rem;
        color: var(--gray-color);
        font-size: 0.875rem;
    }

    .footer-text {
        text-align: center;
        color: var(--gray-color);
        font-size: 0.75rem;
        margin-top: 1.5rem;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Modal de recuperação de senha */
    .modal-content {
        border-radius: 12px;
        overflow: hidden;
        border: none;
    }

    .modal-header {
        background-color: var(--primary-color);
        color: white;
        border-bottom: none;
    }

    .modal-title {
        font-weight: 600;
    }

    .btn-recover {
        background-color: var(--primary-color);
        border: none;
    }

    .btn-recover:hover {
        background-color: var(--primary-dark);
    }

    /* Estilo para o botão do Google */
    .g_id_signin {
        width: 100% !important;
        margin-bottom: 1rem;
    }

    .g_id_signin iframe {
        margin: 0 auto;
        width: 100% !important;
    }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <img src="img/logo-horizontal-branca.png" alt="Logo do Sistema">
            <h2>Acesse sua conta</h2>
        </div>

        <div class="login-body">
            <form method="post" action="autenticar.php">
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail"
                            required value="<?php echo $email_adm ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="senha" name="senha"
                            placeholder="Digite sua senha" required value="123">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="lembrar" name="lembrar">
                        <label class="form-check-label" for="lembrar">Lembrar-me</label>
                    </div>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalRecuperar" class="forgot-password">Esqueceu
                        a senha?</a>
                </div>

                <button type="submit" class="btn btn-login btn-block w-100 mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i> Entrar
                </button>

                <div id="g_id_onload" data-client_id="SEU_CLIENT_ID.apps.googleusercontent.com" data-context="signin"
                    data-ux_mode="popup" data-callback="handleGoogleSignIn" data-auto_prompt="false">
                </div>

                <div class="g_id_signin" data-type="standard" data-shape="rectangular" data-theme="outline"
                    data-text="signin_with" data-size="large" data-logo_alignment="left" data-width="300">
                </div>

                <div class="footer-text">
                    © <?php echo date('Y'); ?> Sistema de Gestão. Todos os direitos reservados.
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de recuperação de senha -->
    <div class="modal fade" id="modalRecuperar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Recuperar Senha</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form method="POST" id="formRecover">
                    <div class="modal-body">
                        <p>Digite seu e-mail para receber as instruções de recuperação de senha.</p>

                        <div class="mb-3">
                            <label for="recoverEmail" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="recoverEmail" name="email"
                                placeholder="Seu e-mail cadastrado">
                        </div>

                        <div id="mensagem" class="mt-2"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-recover text-white">Recuperar Senha</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    // AJAX para recuperação de senha
    $("#formRecover").submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: "recuperar.php",
            type: 'POST',
            data: formData,
            success: function(mensagem) {
                $('#mensagem').removeClass();
                if (mensagem.trim() == "Sua senha foi Enviada para seu Email!") {
                    $('#mensagem').addClass('alert alert-success');
                    $('#formRecover')[0].reset();
                } else {
                    $('#mensagem').addClass('alert alert-danger');
                }
                $('#mensagem').text(mensagem);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    // Efeito de foco nos campos
    $('.form-control').focus(function() {
        $(this).parent().find('.input-group-text').css('color', 'var(--primary-color)');
    }).blur(function() {
        $(this).parent().find('.input-group-text').css('color', '');
    });


    // Verificar cookies ao carregar a página
    $(document).ready(function() {
        // Verificar se existem cookies salvos
        if (getCookie('lembrar_email') && getCookie('lembrar_senha')) {
            $('#email').val(getCookie('lembrar_email'));
            $('#senha').val(getCookie('lembrar_senha'));
            $('#lembrar').prop('checked', true);
        }
    });

    // Função para obter cookies
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }
    </script>


</body>




</html>