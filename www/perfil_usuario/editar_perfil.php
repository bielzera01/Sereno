<?php
session_start();
include 'config.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Processar o formulário de atualização de dados do usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $bio = $_POST['bio'];

    // Validar e sanitizar os dados
    $nome = htmlspecialchars(trim($nome));
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $telefone = htmlspecialchars(trim($telefone));
    $endereco = htmlspecialchars(trim($endereco));
    $bio = htmlspecialchars(trim($bio));

    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['mensagem'] = "Email inválido.";
        header("Location: editar_perfil.php");
        exit();
    }

    // Atualizar os dados no banco de dados
    $sql_update = "UPDATE usuarios SET nome = ?, email = ?, telefone = ?, endereco = ?, bio = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssssi", $nome, $email, $telefone, $endereco, $bio, $usuario_id);

    if ($stmt_update->execute()) {
        $_SESSION['mensagem'] = "Dados atualizados com sucesso!";
        header("Location: perfil.php");
        exit();
    } else {
        $_SESSION['mensagem'] = "Erro ao atualizar os dados.";
    }
}

// Obter informações atualizadas do usuário
$sql_usuario = "SELECT * FROM usuarios WHERE id = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $usuario_id);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();
$usuario = $result_usuario->fetch_assoc();

// Mensagem de feedback (sucesso ou erro)
$mensagem = '';
if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    unset($_SESSION['mensagem']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <!-- Inclua o CSS e as fontes -->
    <link rel="stylesheet" href="estilos.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Elemento decorativo (opcional) -->
    <div class="decoracao-topo"></div>

    <!-- Botão de voltar -->
    <a href="perfil.php" class="logout-btn"><i class="fas fa-arrow-left"></i> Voltar</a>

    <!-- Mensagem de feedback -->
    <?php if (!empty($mensagem)): ?>
        <div class="mensagem-feedback">
            <?php echo htmlspecialchars($mensagem); ?>
            <span class="fechar-mensagem">&times;</span>
        </div>
    <?php endif; ?>

    <div class="container fade-in">
        <div class="perfil">
            <h2>Editar Perfil</h2>
            <form action="editar_perfil.php" method="post" class="dados-usuario">
                <p><strong>Nome:</strong> <input type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required></p>
                <p><strong>Email:</strong> <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required></p>
                <p><strong>Telefone:</strong> <input type="text" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone']); ?>"></p>
                <p><strong>Endereço:</strong> <input type="text" name="endereco" value="<?php echo htmlspecialchars($usuario['endereco']); ?>"></p>
                <p><strong>Bio:</strong></p>
                <textarea name="bio" rows="5" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;"><?php echo htmlspecialchars($usuario['bio']); ?></textarea>
                <input type="submit" value="Salvar Alterações">
            </form>
        </div>
    </div>

    <!-- Elemento decorativo (opcional) -->
    <div class="decoracao-rodape"></div>

    <!-- JavaScript para fechar a mensagem de feedback -->
    <script>
        // Fechar mensagem de feedback
        const fecharMensagem = document.querySelector('.fechar-mensagem');
        if (fecharMensagem) {
            fecharMensagem.addEventListener('click', function() {
                this.parentElement.style.display = 'none';
            });
        }
    </script>
</body>
</html>