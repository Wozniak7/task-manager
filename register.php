<?php
require_once 'db.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nova Conta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">🚪 Registrar Nova Conta</h1>

        <form action="process_register.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Nome de Usuário:</label>
                <input type="text" id="username" name="username" class="form-control" required placeholder="Escolha um nome de usuário">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Senha:</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="Crie uma senha segura">
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar Senha:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required placeholder="Confirme sua senha">
            </div>

            <button type="submit" class="btn btn-success w-100 mb-2">Registrar</button>
            <p class="text-center mt-3">Já tem uma conta? <a href="login.php">Faça Login aqui</a></p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhyUVhrxoeYp9i6fPUkBygktKxN" crossorigin="anonymous"></script>
</body>

</html>