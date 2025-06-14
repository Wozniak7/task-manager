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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">📝 Gerenciador de Tarefas</h1>

        <?php
        ?>

        <div class="mb-3 text-center">
            <a href="create.php" class="btn btn-success">➕ Adicionar Nova Tarefa</a>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <div class="tasks-list">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="card mb-3 task-card <?php echo ($row['status'] == 'Concluída') ? 'completed' : ''; ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="form-check">
                                    <input class="form-check-input task-checkbox" type="checkbox" 
                                           id="task-<?php echo $row['id']; ?>" 
                                           data-task-id="<?php echo $row['id']; ?>"
                                           <?php echo ($row['status'] == 'Concluída') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="task-<?php echo $row['id']; ?>">
                                        <h5 class="card-title mb-0 <?php echo ($row['status'] == 'Concluída') ? 'text-decoration-line-through text-muted' : ''; ?>">
                                            <?php echo htmlspecialchars($row['title']); ?>
                                        </h5>
                                    </label>
                                </div>
                                
                                <div class="btn-group">
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Editar</a>
                                    <form action="delete.php" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                    </form>
                                </div>
                            </div>
                            <p class="card-text text-muted mb-2 text-start"><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                            <div class="d-flex justify-content-between align-items-center text-sm">
                                <small class="text-muted">Data Limite: <?php echo htmlspecialchars($row['due_date']); ?></small>
                                <?php
                                    $status_class = strtolower(htmlspecialchars($row['status']));
                                    $badge_class = 'bg-warning text-dark'; 
                                    if ($status_class == 'concluída') {
                                        $badge_class = 'bg-success';
                                    }
                                ?>
                                <span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($row['status']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-muted mt-5">Nenhuma tarefa encontrada. Adicione uma nova!</p>
        <?php endif; ?>

        <?php $conn->close(); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhyUVhrxoeYp9i6fPUkBygktKxN" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('message');
            const type = urlParams.get('type');
            const errorDetails = urlParams.get('error');

            let messageText = '';
            let backgroundColor = '#3498db'; 

            if (message) {
                switch (message) {
                    case 'success_add':
                        messageText = 'Tarefa adicionada com sucesso!';
                        backgroundColor = '#28a745';
                        break;
                    case 'success_update':
                        messageText = 'Tarefa atualizada com sucesso!';
                        backgroundColor = '#28a745';
                        break;
                    case 'success_delete':
                        messageText = 'Tarefa excluída com sucesso!';
                        backgroundColor = '#28a745';
                        break;
                    case 'info_no_change':
                        messageText = 'Nenhuma alteração foi feita na tarefa.';
                        backgroundColor = '#17a2b8'; 
                        break;
                    case 'error_add':
                        messageText = 'Erro ao adicionar tarefa.';
                        backgroundColor = '#dc3545';
                        break;
                    case 'error_update':
                        messageText = 'Erro ao atualizar tarefa.';
                        backgroundColor = '#dc3545';
                        break;
                    case 'error_delete':
                        messageText = 'Erro ao excluir tarefa.';
                        backgroundColor = '#dc3545';
                        break;
                    case 'error_not_found':
                        messageText = 'A tarefa solicitada não foi encontrada.';
                        backgroundColor = '#ffc107'; 
                        break;
                    case 'error_no_id':
                        messageText = 'ID da tarefa não fornecido ou inválido.';
                        backgroundColor = '#ffc107'; 
                        break;
                    case 'validation_error':
                        messageText = errorDetails || 'Erro de validação!';
                        backgroundColor = '#dc3545'; 
                        break;
                    case 'status_update_success': 
                        messageText = 'Status da tarefa atualizado!';
                        backgroundColor = '#28a745'; 
                        break;
                    case 'status_update_error':
                        messageText = 'Erro ao atualizar status da tarefa.';
                        backgroundColor = '#dc3545'; 
                        break;
                    default:
                        messageText = 'Operação concluída.';
                        backgroundColor = '#3498db';
                        break;
                }

                if (errorDetails && message.includes('error')) {
                    messageText += ` (Detalhes: ${decodeURIComponent(errorDetails)})`;
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
                    onClick: function(){}
                }).showToast();

                history.replaceState({}, document.title, window.location.pathname);
            }

            const taskCheckboxes = document.querySelectorAll('.task-checkbox');
            taskCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', async function() {
                    const taskId = this.dataset.taskId;
                    const newStatus = this.checked ? 'Concluída' : 'Pendente';
                    const cardElement = this.closest('.task-card'); 

                    try {
                        const response = await fetch('update_status.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ 
                                id: taskId,
                                status: newStatus
                            })
                        });

                        const result = await response.json(); 

                        if (result.success) {
                            if (this.checked) {
                                cardElement.classList.add('completed');
                                cardElement.querySelector('.card-title').classList.add('text-decoration-line-through', 'text-muted');
                                const statusBadge = cardElement.querySelector('.badge');
                                statusBadge.classList.remove('bg-warning', 'text-dark');
                                statusBadge.classList.add('bg-success');
                                statusBadge.textContent = 'Concluída';

                                Toastify({ text: 'Tarefa marcada como Concluída!', duration: 2000, style: { background: '#28a745' }, gravity: "top", position: "right" }).showToast();
                            } else {
                                cardElement.classList.remove('completed');
                                cardElement.querySelector('.card-title').classList.remove('text-decoration-line-through', 'text-muted');
                                const statusBadge = cardElement.querySelector('.badge');
                                statusBadge.classList.remove('bg-success');
                                statusBadge.classList.add('bg-warning', 'text-dark');
                                statusBadge.textContent = 'Pendente';
                                
                                Toastify({ text: 'Tarefa marcada como Pendente!', duration: 2000, style: { background: '#17a2b8' }, gravity: "top", position: "right" }).showToast();
                            }
                        } else {
                            this.checked = !this.checked; 
                            Toastify({ text: `Erro: ${result.error || 'Falha ao atualizar status.'}`, duration: 3000, style: { background: '#dc3545' }, gravity: "top", position: "right" }).showToast();
                        }
                    } catch (error) {
                        console.error('Erro na requisição AJAX:', error);
                        this.checked = !this.checked; 
                        Toastify({ text: 'Erro de conexão ao atualizar status.', duration: 3000, style: { background: '#dc3545' }, gravity: "top", position: "right" }).showToast();
                    }
                });
            });
        });
    </script>
</body>
</html>
