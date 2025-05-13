<?php
session_start();
include 'config.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Obter informações atualizadas do usuário
$sql_usuario = "SELECT * FROM usuarios WHERE id = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $usuario_id);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();
$usuario = $result_usuario->fetch_assoc();

// Obter consultas pendentes
$sql_pendentes = "SELECT * FROM consultas WHERE usuario_id = ? AND status = 'Pendente' ORDER BY data_consulta ASC";
$stmt_pendentes = $conn->prepare($sql_pendentes);
$stmt_pendentes->bind_param("i", $usuario_id);
$stmt_pendentes->execute();
$consultas_pendentes = $stmt_pendentes->get_result();

// Obter histórico de consultas
$sql_historico = "SELECT * FROM consultas WHERE usuario_id = ? AND status != 'Pendente' ORDER BY data_consulta DESC";
$stmt_historico = $conn->prepare($sql_historico);
$stmt_historico->bind_param("i", $usuario_id);
$stmt_historico->execute();
$historico_consultas = $stmt_historico->get_result();

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
    <title>Perfil do Usuário</title>
    <!-- Inclua o CSS e as fontes -->
    <link rel="stylesheet" href="estilos.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- Inclua o Font Awesome para os ícones -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <!-- Elemento decorativo (opcional) -->
    <div class="decoracao-topo"></div>

    <!-- Botões de logout e editar perfil -->
    <div class="top-buttons">
        <a href="editar_perfil.php" class="editar-perfil-btn"><i class="fas fa-user-edit"></i> Editar Perfil</a>
        <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </div>

    <!-- Mensagem de feedback -->
    <?php if (!empty($mensagem)): ?>
        <div class="mensagem-feedback">
            <?php echo htmlspecialchars($mensagem); ?>
            <span class="fechar-mensagem">&times;</span>
        </div>
    <?php endif; ?>

    <div class="container fade-in">
        <!-- Seção de Informações do Usuário -->
        <div class="perfil">
            <!-- Foto de perfil e bio -->
            <div class="perfil-header">
                <div class="foto-bio">
                    <!-- Foto de perfil -->
                    <div class="foto-label">
                        <img src="<?php echo htmlspecialchars($usuario['foto_perfil'] ?? 'default.png'); ?>" alt="Foto de Perfil">
                    </div>
                    <!-- Bio -->
                    <div class="bio">
                        <p><?php echo nl2br(htmlspecialchars($usuario['bio'] ?? '')); ?></p>
                    </div>
                </div>
            </div>

            <!-- Dados do usuário -->
            <div class="dados-usuario">
                <h2><?php echo htmlspecialchars($usuario['nome']); ?></h2>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
                <p><strong>Telefone:</strong> <?php echo htmlspecialchars($usuario['telefone']); ?></p>
                <p><strong>Endereço:</strong> <?php echo htmlspecialchars($usuario['endereco']); ?></p>
            </div>
        </div>

        <!-- Seção de Consultas Pendentes -->
        <div class="consultas-pendentes slide-in">
            <h3>Consultas Pendentes</h3>
            <?php if ($consultas_pendentes->num_rows > 0): ?>
                <ul>
                    <?php while ($consulta = $consultas_pendentes->fetch_assoc()): ?>
                        <li>
                            <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($consulta['data_consulta'])); ?></p>
                            <p><strong>Descrição:</strong> <?php echo htmlspecialchars($consulta['descricao']); ?></p>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Não há consultas pendentes.</p>
            <?php endif; ?>
        </div>

        <!-- Seção de Histórico de Consultas -->
        <div class="historico-consultas slide-in">
            <h3>Histórico de Consultas</h3>
            <?php if ($historico_consultas->num_rows > 0): ?>
                <ul>
                    <?php while ($consulta = $historico_consultas->fetch_assoc()): ?>
                        <li>
                            <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($consulta['data_consulta'])); ?></p>
                            <p><strong>Status:</strong> <?php echo htmlspecialchars($consulta['status']); ?></p>
                            <p><strong>Descrição:</strong> <?php echo htmlspecialchars($consulta['descricao']); ?></p>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Não há histórico de consultas.</p>
            <?php endif; ?>
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

        // JavaScript para alternar a exibição das seções
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.consultas-pendentes, .historico-consultas');
            sections.forEach(function(section) {
                const header = section.querySelector('h3');
                header.addEventListener('click', function() {
                    const content = section.querySelector('ul, p');
                    content.style.display = content.style.display === 'block' ? 'none' : 'block';
                    section.classList.toggle('active');
                });
                // Iniciar com as seções abertas
                const content = section.querySelector('ul, p');
                content.style.display = 'block';
                section.classList.add('active');
            });
        });
    </script>
</body>
</html>