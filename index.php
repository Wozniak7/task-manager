<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$current_username = $_SESSION['username'];

$filter_status = $_GET['status'] ?? 'all';
$order_by = $_GET['order_by'] ?? 'created_at';
$order_direction = $_GET['order_direction'] ?? 'DESC';

$valid_statuses = ['all', 'Pendente', 'Concluída'];
if (!in_array($filter_status, $valid_statuses)) {
    $filter_status = 'all';
}

$valid_order_by = ['created_at', 'due_date', 'title'];
if (!in_array($order_by, $valid_order_by)) {
    $order_by = 'created_at';
}

$valid_order_direction = ['ASC', 'DESC'];
if (!in_array($order_direction, $valid_order_direction)) {
    $order_direction = 'DESC';
}

$tasks_per_page = 5;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $tasks_per_page;

$where_clauses = ["user_id = ?"];
$params_type = 'i';
$params_value = [$current_user_id];

if ($filter_status != 'all') {
    $where_clauses[] = "status = ?";
    $params_type .= 's';
    $params_value[] = $filter_status;
}

$where_sql = " WHERE " . implode(" AND ", $where_clauses);

$count_sql = "SELECT COUNT(*) FROM tasks" . $where_sql;
$count_stmt = $conn->prepare($count_sql);
if ($count_stmt === false) {
    die("Erro na preparação da contagem: " . $conn->error);
}

$count_stmt->bind_param($params_type, ...$params_value);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_tasks = $count_result->fetch_row()[0];
$count_stmt->close();

$total_pages = ceil($total_tasks / $tasks_per_page);
if ($current_page > $total_pages && $total_pages > 0) {
    $current_page = $total_pages;
    $offset = ($current_page - 1) * $tasks_per_page;
} elseif ($total_pages == 0 && $current_page > 1) {
    $current_page = 1;
    $offset = 0;
}


$sql = "SELECT id, title, description, due_date, status FROM tasks" . $where_sql;
$sql .= " ORDER BY " . $order_by . " " . $order_direction;
$sql .= " LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Erro na preparação da declaração: " . $conn->error);
}

$params_type .= 'ii';
$params_value[] = $tasks_per_page;
$params_value[] = $offset;

$stmt->bind_param($params_type, ...$params_value);

$stmt->execute();
$result = $stmt->get_result();
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">📝 Gerenciador de Tarefas</h1>
            <div>
                <span class="me-2">Olá, <?php echo htmlspecialchars($current_username); ?>!</span>
                <a href="logout.php" class="btn btn-sm btn-outline-danger">Sair</a>
            </div>
        </div>

        <?php
        if (isset($_GET['message'])) {
            $message_type = 'info';
            $message_text = '';

            switch ($_GET['message']) {
                case 'success_add':
                    $message_type = 'success';
                    $message_text = 'Tarefa adicionada com sucesso!';
                    break;
                case 'success_update':
                    $message_type = 'success';
                    $message_text = 'Tarefa atualizada com sucesso!';
                    break;
                case 'success_delete':
                    $message_type = 'success';
                    $message_text = 'Tarefa excluída com sucesso!';
                    break;
                case 'info_no_change':
                    $message_type = 'info';
                    $message_text = 'Nenhuma alteração foi feita na tarefa.';
                    break;
                case 'error_add':
                    $message_type = 'danger';
                    $message_text = 'Erro ao adicionar tarefa.';
                    if (isset($_GET['error'])) {
                        $message_text .= ' Detalhes: ' . htmlspecialchars($_GET['error']);
                    }
                    break;
                case 'error_update':
                    $message_type = 'danger';
                    $message_text = 'Erro ao atualizar tarefa.';
                    if (isset($_GET['error'])) {
                        $message_text .= ' Detalhes: ' . htmlspecialchars($_GET['error']);
                    }
                    break;
                case 'error_delete':
                    $message_type = 'danger';
                    $message_text = 'Erro ao excluir tarefa.';
                    if (isset($_GET['error'])) {
                        $message_text .= ' Detalhes: ' . htmlspecialchars($_GET['error']);
                    }
                    break;
                case 'error_not_found':
                    $message_type = 'warning';
                    $message_text = 'A tarefa solicitada não foi encontrada.';
                    break;
                case 'error_no_id':
                    $message_type = 'warning';
                    $message_text = 'ID da tarefa não fornecido ou inválido.';
                    break;
                case 'validation_error':
                    $message_type = 'danger';
                    $message_text = $_GET['error'] || 'Erro de validação!';
                    break;
                case 'status_update_success':
                    $message_type = 'success';
                    $message_text = 'Status da tarefa atualizado!';
                    break;
                case 'status_update_error':
                    $message_type = 'danger';
                    $message_text = 'Erro ao atualizar status da tarefa.';
                    break;
                case 'login_success':
                    $message_type = 'success';
                    $message_text = 'Login realizado com sucesso!';
                    break;
                case 'register_success':
                    $message_type = 'success';
                    $message_text = 'Conta criada e login realizado com sucesso!';
                    break;
                case 'logout_success':
                    $message_type = 'info';
                    $message_text = 'Você foi desconectado.';
                    break;
                default:
                    $message_type = 'info';
                    $message_text = 'Operação concluída.';
                    break;
            }
            echo '<div class="alert alert-' . $message_type . ' text-center" role="alert">' . $message_text . '</div>';
        }
        ?>

        <div class="mb-3 text-center">
            <a href="create.php" class="btn btn-success">➕ Adicionar Nova Tarefa</a>
        </div>

        <form action="index.php" method="GET" class="mb-4 p-3 border rounded bg-light">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="statusFilter" class="form-label">Filtrar por Status:</label>
                    <select class="form-select" id="statusFilter" name="status">
                        <option value="all" <?php echo ($filter_status == 'all') ? 'selected' : ''; ?>>Todos</option>
                        <option value="Pendente" <?php echo ($filter_status == 'Pendente') ? 'selected' : ''; ?>>Pendente</option>
                        <option value="Concluída" <?php echo ($filter_status == 'Concluída') ? 'selected' : ''; ?>>Concluída</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="orderBy" class="form-label">Ordenar por:</label>
                    <select class="form-select" id="orderBy" name="order_by">
                        <option value="created_at" <?php echo ($order_by == 'created_at') ? 'selected' : ''; ?>>Data de Criação</option>
                        <option value="due_date" <?php echo ($order_by == 'due_date') ? 'selected' : ''; ?>>Data Limite</option>
                        <option value="title" <?php echo ($order_by == 'title') ? 'selected' : ''; ?>>Título</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="orderDirection" class="form-label">Direção:</label>
                    <select class="form-select" id="orderDirection" name="order_direction">
                        <option value="DESC" <?php echo ($order_direction == 'DESC') ? 'selected' : ''; ?>>Decrescente</option>
                        <option value="ASC" <?php echo ($order_direction == 'ASC') ? 'selected' : ''; ?>>Crescente</option>
                    </select>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-info mt-3">Aplicar Filtros</button>
                </div>
            </div>
        </form>

        <?php if ($result->num_rows > 0): ?>
            <div class="tasks-list">
                <?php while ($row = $result->fetch_assoc()): ?>
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
                                    <button type="button" class="btn btn-sm btn-danger delete-task-btn"
                                        data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                        data-task-id="<?php echo $row['id']; ?>"
                                        data-task-title="<?php echo htmlspecialchars($row['title']); ?>">
                                        Excluir
                                    </button>
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

            <?php if ($total_pages > 1): ?>
                <nav aria-label="Paginação de Tarefas">
                    <ul class="pagination justify-content-center mt-4">
                        <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $current_page - 1; ?>&status=<?php echo $filter_status; ?>&order_by=<?php echo $order_by; ?>&order_direction=<?php echo $order_direction; ?>">Anterior</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&status=<?php echo $filter_status; ?>&order_by=<?php echo $order_by; ?>&order_direction=<?php echo $order_direction; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $current_page + 1; ?>&status=<?php echo $filter_status; ?>&order_by=<?php echo $order_by; ?>&order_direction=<?php echo $order_direction; ?>">Próxima</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>

        <?php else: ?>
            <p class="text-center text-muted mt-5">Nenhuma tarefa encontrada. Adicione uma nova!</p>
        <?php endif; ?>

        <?php $conn->close(); ?>
    </div>

    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir a tarefa "<span id="taskTitleToDelete"></span>"?
                    Esta ação não pode ser desfeita.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Excluir</button>
                </div>
            </div>
        </div>
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
                    case 'login_success':
                        messageText = 'Login realizado com sucesso!';
                        backgroundColor = '#28a745';
                        break;
                    case 'register_success':
                        messageText = 'Conta criada e login realizado com sucesso!';
                        backgroundColor = '#28a745';
                        break;
                    case 'logout_success':
                        messageText = 'Você foi desconectado.';
                        backgroundColor = '#17a2b8';
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
                    onClick: function() {}
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

                                Toastify({
                                    text: 'Tarefa marcada como Concluída!',
                                    duration: 2000,
                                    style: {
                                        background: '#28a745'
                                    },
                                    gravity: "top",
                                    position: "right"
                                }).showToast();
                            } else {
                                cardElement.classList.remove('completed');
                                cardElement.querySelector('.card-title').classList.remove('text-decoration-line-through', 'text-muted');
                                const statusBadge = cardElement.querySelector('.badge');
                                statusBadge.classList.remove('bg-success');
                                statusBadge.classList.add('bg-warning', 'text-dark');
                                statusBadge.textContent = 'Pendente';

                                Toastify({
                                    text: 'Tarefa marcada como Pendente!',
                                    duration: 2000,
                                    style: {
                                        background: '#17a2b8'
                                    },
                                    gravity: "top",
                                    position: "right"
                                }).showToast();
                            }
                        } else {
                            this.checked = !this.checked;
                            Toastify({
                                text: `Erro: ${result.error || 'Falha ao atualizar status.'}`,
                                duration: 3000,
                                style: {
                                    background: '#dc3545'
                                },
                                gravity: "top",
                                position: "right"
                            }).showToast();
                        }
                    } catch (error) {
                        console.error('Erro na requisição AJAX:', error);
                        this.checked = !this.checked;
                        Toastify({
                            text: 'Erro de conexão ao atualizar status.',
                            duration: 3000,
                            style: {
                                background: '#dc3545'
                            },
                            gravity: "top",
                            position: "right"
                        }).showToast();
                    }
                });
            });

            const confirmDeleteModal = document.getElementById('confirmDeleteModal');
            const confirmDeleteButton = document.getElementById('confirmDeleteButton');
            const taskTitleToDeleteSpan = document.getElementById('taskTitleToDelete');
            let formToDelete = null;

            document.querySelectorAll('.delete-task-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const taskId = this.dataset.taskId;
                    const taskTitle = this.dataset.taskTitle;

                    formToDelete = this.closest('form');

                    taskTitleToDeleteSpan.textContent = taskTitle;
                });
            });

            confirmDeleteButton.addEventListener('click', function() {
                if (formToDelete) {
                    formToDelete.submit();
                }
            });
        });
    </script>
</body>

</html>