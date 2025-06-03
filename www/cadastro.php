<?php

    if(isset($_POST['submit']))
    {
        include_once('config.php');

        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $confirmarsenha = $_POST['confirmarsenha'];
        $sexo = $_POST['sexo'];
        $data = $_POST['data'];

        $result = mysqli_query($conn, "INSERT INTO usuarios22(nome,email,senha,confirmarsenha,sexo,data)
        VALUES ('$nome','$email','$senha','$confirmarsenha','$sexo','$data')");
        
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="css/cadastro.css">
</head>
<style>
        .erro {
            color: red;
            display: none; /* Mensagens de erro ocultas por padrão */
        }
        .error {
            border: 1px solid red; /* Destaca campos com erro */
        }
    </style>
<body>
    <div class="container">
        <h1>Cadastro</h1>
        <form action="cadastro.php" method="POST">

            <div class="form-group">
                <label for="nome-completo">Nome Completo</label>
                <input type="text" name="nome" id="nome" placeholder="Nome Completo" required>
                <span id="nome-erro" class="erro">O nome não pode estar vazio.</span>
                <br>
                <label for="email">Email</label>
                <br>
                <input type="text" name="email" id="email" placeholder="Email" required>
                <span id="email-erro" class="erro">Por favor, insira um email válido.</span>
                <label for="password">Senha</label>
                <input type="password" name="senha" id="senha" placeholder="Senha" required>
                <span id="senha-erro" class="erro">A senha deve ter pelo menos 6 caracteres.</span>
                <label for="password">ConfirmarSenha</label>
                <input type="password" name="confirmarsenha" id="confirmarsenha" placeholder="Confirmar Senha" required>
                <span id="confirmar-senha-erro" class="erro">As senhas não estão iguais.</span>
            </div>
            <div class="form-group2">
                <label for="sexo">Sexo</label>
                <div class="form-group2-radio">
                <input type="radio" name="sexo" id="sexo" value="masculino">
                <label for="sexo">Masculino</label>
                </div>  
                <div class="form-group2-radio">
                <input type="radio" name="sexo" id="sexo" value="feminino">
                <label for="sexo">Feminino</label>
                </div>
                <div class="form-group2-radio">
                <input type="radio" name="sexo" id="sexo" value="outro">
                <label for="sexo">Outro</label>
                </div>
            </div>
            <div class="form-group3">
                <label for="data-nascimento">Data de Nascimento</label>
                <input type="date" name="data" id="datao" required>
            </div>

            <!--<button class="button" type="submit">Cadastrar</button>-->
            <button class="button" name="submit" type="submit" onclick="window.location.href='index.html'">Cadastrar</button>
            <p class="login">Já possui uma conta? <a href="login.html" class="login-link">Faça login</a></p>
        </form>
    </div>

    <script>
        const form = document.getElementById('form');

        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Previne o comportamento padrão do formulário

            const nome = document.getElementById('nome');
            const email = document.getElementById('email');
            const senha = document.getElementById('senha');
            const confirmarSenha = document.getElementById('confirmarsenha');
            const sexo = document.getElementById('sexo');
            const data = document.getElementById('data');

            const nomeErro = document.getElementById('nome-erro');
            const emailErro = document.getElementById('email-erro');
            const senhaErro = document.getElementById('senha-erro');
            const confirmarSenhaErro = document.getElementById('confirmar-senha-erro');
            const sexoErro = document.getElementById('sexo-erro');
            const dataErro = document.getElementById('data-erro');

            let isValid = true;

            // Validação do nome
            if (nome.value.trim() === "") {
                nome.classList.add('error');
                nomeErro.style.display = 'block';
                isValid = false;
            } else {
                nome.classList.remove('error');
                nomeErro.style.display = 'none';
            }

            // Validação do email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                email.classList.add('error');
                emailErro.style.display = 'block';
                isValid = false;
            } else {
                email.classList.remove('error');
                emailErro.style.display = 'none';
            }

            // Validação da senha
            if (senha.value.length < 15) {
                senha.classList.add('error');
                senhaErro.style.display = 'block';
                isValid = false;
            } else {
                senha.classList.remove('error');
                senhaErro.style.display = 'none';
            }

            // Validação da confirmação da senha
            if (senha.value !== confirmarSenha.value) {
                confirmarSenha.classList.add('error');
                confirmarSenhaErro.style.display = 'block';
                isValid = false;
            } else {
                confirmarSenha.classList.remove('error');
                confirmarSenhaErro.style.display = 'none';
            }

            // Validação do Sexo
            if (sexo.value.trim() === "") {
                sexo.classList.add('error');
                sexoErro.style.display = 'block';
                isValid = false;
            } else {
                sexo.classList.remove('error');
                sexoErro.style.display = 'none';
            }

            // Validação da Data
            if (data.value.trim() === "") {
                data.classList.add('error');
                dataErro.style.display = 'block';
                isValid = false;
            } else {
                data.classList.remove('error');
                dataErro.style.display = 'none';
            }

            // Se o formulário for válido, redireciona para o index.html
            if (isValid) {
                window.location.href = 'index.html';
            }
        });
    </script>

</body>
</html>
