<?php
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Não autenticado.']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_user_id = $_SESSION['user_id'];
    $data = json_decode(file_get_contents("php://input"), true);

    $id = $data['id'] ?? null;
    $status = $data['status'] ?? null;

    if (empty($id) || !is_numeric($id) || !in_array($status, ['Pendente', 'Concluída'])) {
        echo json_encode(['success' => false, 'error' => 'Dados inválidos. ID ou status ausente/inválido.']);
        exit;
    }

    $sql = "UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(['success' => false, 'error' => 'Erro na preparação da declaração: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("sii", $status, $id, $current_user_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Status atualizado com sucesso.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Tarefa não encontrada ou status inalterado.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Erro ao executar a atualização: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Método de requisição inválido.']);
}
