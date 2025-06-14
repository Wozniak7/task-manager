<?php
require_once 'db.php'; 

$sql = "SELECT id, title, description, due_date, status FROM tasks ORDER BY created_at DESC";
$result = $conn->query($sql); 
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>📝 Gerenciador de Tarefas</h1>

        <div class="add-task-section">
            <a href="create.php" class="button add-button">➕ Adicionar Nova Tarefa</a>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <div class="tasks-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="task-card <?php echo ($row['status'] == 'Concluída') ? 'completed' : ''; ?>">
                        <div class="task-header">
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <div class="task-actions">
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="button edit-button">Editar</a>
                                <form action="delete.php" method="POST" style="display:inline;"
                                    onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="button delete-button">Excluir</button>
                                </form>
                            </div>
                        </div>
                        <p class="task-description"><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                        <div class="task-meta">
                            <span class="due-date">Data Limite: <?php echo htmlspecialchars($row['due_date']); ?></span>
                            <span
                                class="status <?php echo strtolower(htmlspecialchars($row['status'])); ?>"><?php echo htmlspecialchars($row['status']); ?></span>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="no-tasks-message">Nenhuma tarefa encontrada. Adicione uma nova!</p>
        <?php endif; ?>

        <?php $conn->close(); ?>
    </div>
</body>

</html>