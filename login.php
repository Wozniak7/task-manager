<?php
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">🔑 Fazer Login</h1>

        <form action="process_login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Nome de Usuário:</label>
                <input type="text" id="username" name="username" class="form-control" required placeholder="Seu nome de usuário">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Senha:</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="Sua senha">
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-2">Login</button>
            <p class="text-center mt-3">Não tem uma conta? <a href="register.php">Registre-se aqui</a></p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhyUVhrxoeYp9i6fPUkBygktKxN" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');

            let messageText = '';
            let backgroundColor = '#dc3545'; 

            if (error) {
                switch (error) {
                    case 'empty_fields':
                        messageText = 'Preencha todos os campos.';
                        break;
                    case 'invalid_credentials':
                        messageText = 'Nome de usuário ou senha incorretos.';
                        break;
                    case 'db_error':
                        messageText = 'Erro no banco de dados ao tentar logar.';
                        break;
                    default:
                        messageText = 'Ocorreu um erro ao tentar fazer login.';
                        break;
                }

                Toastify({
                    text: messageText,
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: backgroundColor,
                    },
                    onClick: function() {}
                }).showToast();

                history.replaceState({}, document.title, window.location.pathname);
            }
        });
    </script>
</body>

</html>