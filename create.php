<?php
require_once 'db.php'; 
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Nova Tarefa</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>➕ Adicionar Nova Tarefa</h1>

        <form action="store.php" method="POST">
            <div class="form-group">
                <label for="title">Título da Tarefa:</label>
                <input type="text" id="title" name="title" required placeholder="Ex: Comprar mantimentos">
            </div>

            <div class="form-group">
                <label for="description">Descrição:</label>
                <textarea id="description" name="description" rows="4" placeholder="Ex: Leite, ovos, pão..."></textarea>
            </div>

            <div class="form-group">
                <label for="due_date">Data Limite:</label>
                <input type="date" id="due_date" name="due_date">
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="Pendente">Pendente</option>
                    <option value="Concluída">Concluída</option>
                </select>
            </div>

            <button type="submit" class="button save-button">Salvar Tarefa</button>
            <a href="index.php" class="button back-button">Voltar para a Lista</a>
        </form>
    </div>
</body>

</html>