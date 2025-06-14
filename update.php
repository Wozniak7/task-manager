<?php
require_once 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $due_date = $_POST['due_date'] ?? null;
    $status = $_POST['status'] ?? 'Pendente';

    if (empty($id) || !is_numeric($id)) {
        header("Location: index.php?message=validation_error&type=error&error=" . urlencode("ID da tarefa inválido!"));
        exit;
    }
    if (empty($title)) {
        header("Location: index.php?message=validation_error&type=error&error=" . urlencode("O título da tarefa é obrigatório!"));
        exit;
    }

    if (!empty($due_date) && !preg_match("/^\d{4}-\d{2}-\d{2}$/", $due_date)) {
        header("Location: index.php?message=validation_error&type=error&error=" . urlencode("Formato de data inválido. Use AAAA-MM-DD."));
        exit;
    }

    $sql = "UPDATE tasks SET title = ?, description = ?, due_date = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        header("Location: index.php?message=error_update&type=error&error=" . urlencode($conn->error));
        exit;
    }

    $stmt->bind_param("ssssi", $title, $description, $due_date, $status, $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            header("Location: index.php?message=success_update&type=success");
            exit();
        } else {
            header("Location: index.php?message=info_no_change&type=info");
            exit();
        }
    } else {
        header("Location: index.php?message=error_update&type=error&error=" . urlencode($stmt->error));
        exit();
    }

    $stmt->close();
    $conn->close();

} else {
    header("Location: index.php");
    exit();
}
?>