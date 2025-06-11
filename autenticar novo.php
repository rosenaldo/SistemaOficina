<?php 
require_once("conexao.php");
@session_start();

// 1. VERIFICAÇÃO DE TOKEN DE LEMBRAR-ME
if(isset($_COOKIE['remember_token']) && !isset($_SESSION['id_usuario'])) {
    try {
        $token = $_COOKIE['remember_token'];
        $query = $pdo->prepare("SELECT * FROM usuarios WHERE remember_token = ? AND token_expira > ?");
        $query->execute([$token, time()]);
        $usuario = $query->fetch(PDO::FETCH_ASSOC);
        
        if($usuario) {
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['nome_usuario'] = $usuario['nome'];
            $_SESSION['cpf_usuario'] = $usuario['cpf'];
            $_SESSION['nivel_usuario'] = $usuario['nivel'];
            
            redirectByNivel($usuario['nivel']);
            exit();
        }
    } catch (PDOException $e) {
        // Log do erro (em produção, use um sistema de logs)
        error_log("Erro ao verificar token: " . $e->getMessage());
    }
}

// 2. AUTENTICAÇÃO NORMAL
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if(!empty($email) && !empty($senha)) {
    try {
        $query = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND senha = ?");
        $query->execute([$email, $senha]);
        $usuario = $query->fetch(PDO::FETCH_ASSOC);
        
        if($usuario) {
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['nome_usuario'] = $usuario['nome'];
            $_SESSION['cpf_usuario'] = $usuario['cpf'];
            $_SESSION['nivel_usuario'] = $usuario['nivel'];

            // 3. TRATAR LEMBRAR-ME
            if(isset($_POST['lembrar']) && $_POST['lembrar'] == 'on') {
                $token = bin2hex(random_bytes(32));
                $expiracao = time() + (30 * 24 * 60 * 60); // 30 dias
                
                $update = $pdo->prepare("UPDATE usuarios SET remember_token = ?, token_expira = ? WHERE id = ?");
                $update->execute([$token, $expiracao, $usuario['id']]);
                
                setcookie('remember_token', $token, $expiracao, "/", "", false, true);
            } else {
                // Limpar token se existir
                $pdo->prepare("UPDATE usuarios SET remember_token = NULL, token_expira = NULL WHERE id = ?")
                   ->execute([$usuario['id']]);
                setcookie('remember_token', '', time() - 3600, "/");
            }

            redirectByNivel($usuario['nivel']);
            exit();
        } else {
            showErrorAndRedirect('Usuário ou Senha Incorreta!');
        }
    } catch (PDOException $e) {
        error_log("Erro na autenticação: " . $e->getMessage());
        showErrorAndRedirect('Erro no sistema. Por favor, tente mais tarde.');
    }
} else {
    showErrorAndRedirect('Por favor, preencha todos os campos!');
}

// FUNÇÕES AUXILIARES
function redirectByNivel($nivel) {
    $paginas = [
        'admin' => 'painel-adm',
        'mecanico' => 'painel-mecanico',
        'recep' => 'painel-recepcao'
    ];
    
    header("Location: " . ($paginas[$nivel] ?? 'painel'));
    exit();
}

function showErrorAndRedirect($message) {
    echo "<script>alert('".addslashes($message)."'); window.location='index.php';</script>";
    exit();
}