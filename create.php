<?php
require_once 'db.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Nova Tarefa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">➕ Adicionar Nova Tarefa</h1>

        <form action="store.php" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Título da Tarefa:</label>
                <input type="text" id="title" name="title" class="form-control" required
                    placeholder="Ex: Comprar mantimentos">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrição:</label>
                <textarea id="description" name="description" class="form-control" rows="4"
                    placeholder="Ex: Leite, ovos, pão..."></textarea>
            </div>

            <div class="mb-3">
                <label for="due_date" class="form-label">Data Limite:</label>
                <input type="date" id="due_date" name="due_date" class="form-control">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="Pendente">Pendente</option>
                    <option value="Concluída">Concluída</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success w-100 mb-2">Salvar Tarefa</button>
            <a href="index.php" class="btn btn-secondary w-100">Voltar para a Lista</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhyUVhrxoeYp9i6fPUkBygktKxN"
        crossorigin="anonymous"></script>
</body>

</html>