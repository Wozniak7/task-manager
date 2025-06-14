<?php
require_once 'db.php';

$task = null;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT id, title, description, due_date, status FROM tasks WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erro na preparação da declaração: " . $conn->error);
    }

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
    } else {
        header("Location: index.php?message=error_not_found");
        exit();
    }

    $stmt->close();
} else {
    header("Location: index.php?message=error_no_id");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>✏️ Editar Tarefa</h1>

        <?php if ($task): ?>
            <form action="update.php" method="POST">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($task['id']); ?>">

                <div class="form-group">
                    <label for="title">Título da Tarefa:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($task['title']); ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="description">Descrição:</label>
                    <textarea id="description" name="description"
                        rows="4"><?php echo htmlspecialchars($task['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="due_date">Data Limite:</label>
                    <input type="date" id="due_date" name="due_date"
                        value="<?php echo htmlspecialchars($task['due_date']); ?>">
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="Pendente" <?php echo ($task['status'] == 'Pendente') ? 'selected' : ''; ?>>Pendente
                        </option>
                        <option value="Concluída" <?php echo ($task['status'] == 'Concluída') ? 'selected' : ''; ?>>Concluída
                        </option>
                    </select>
                </div>

                <button type="submit" class="button save-button">Atualizar Tarefa</button>
                <a href="index.php" class="button back-button">Cancelar e Voltar</a>
            </form>
        <?php else: ?>
            <p class="message error">Tarefa não encontrada ou ID inválido.</p>
            <a href="index.php" class="button back-button">Voltar para a Lista</a>
        <?php endif; ?>
    </div>
</body>

</html>