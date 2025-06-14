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
        header("Location: index.php?message=error_not_found&type=warning");
        exit();
    }

    $stmt->close();
} else {
    header("Location: index.php?message=error_no_id&type=warning");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container mt-5">
        <h1>✏️ Editar Tarefa</h1>

        <?php if ($task): ?>
            <form action="update.php" method="POST">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($task['id']); ?>">

                <div class="mb-3">
                    <label for="title" class="form-label">Título da Tarefa:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($task['title']); ?>"
                        class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descrição:</label>
                    <textarea id="description" name="description" class="form-control"
                        rows="4"><?php echo htmlspecialchars($task['description']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="due_date" class="form-label">Data Limite:</label>
                    <input type="date" id="due_date" name="due_date"
                        value="<?php echo htmlspecialchars($task['due_date']); ?>" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status:</label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="Pendente" <?php echo ($task['status'] == 'Pendente') ? 'selected' : ''; ?>>Pendente
                        </option>
                        <option value="Concluída" <?php echo ($task['status'] == 'Concluída') ? 'selected' : ''; ?>>Concluída
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success w-100 mb-2">Atualizar Tarefa</button>
                <a href="index.php" class="btn btn-secondary w-100">Cancelar e Voltar</a>
            </form>
        <?php else: ?>
            <p class="alert alert-warning text-center">Tarefa não encontrada ou ID inválido.</p>
            <a href="index.php" class="btn btn-secondary w-100">Voltar para a Lista</a>
        <?php endif; ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhyUVhrxoeYp9i6fPUkBygktKxN"
            crossorigin="anonymous"></script>
</body>

</html>