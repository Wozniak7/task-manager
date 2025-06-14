<?php
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $id = $data['id'] ?? null;
    $status = $data['status'] ?? null;

    if (empty($id) || !is_numeric($id) || !in_array($status, ['Pendente', 'Concluída'])) {
        echo json_encode(['success' => false, 'error' => 'Dados inválidos. ID ou status ausente/inválido.']);
        exit;
    }

    $sql = "UPDATE tasks SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(['success' => false, 'error' => 'Erro na preparação da declaração: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("si", $status, $id);

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
?>