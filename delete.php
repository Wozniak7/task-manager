<?php
require_once 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? null;

    if (empty($id) || !is_numeric($id)) {
        header("Location: index.php?message=validation_error&type=error&error=" . urlencode("ID da tarefa inválido!"));
        exit;
    }

    $sql = "DELETE FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        header("Location: index.php?message=error_delete&type=error&error=" . urlencode($conn->error));
        exit;
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            header("Location: index.php?message=success_delete&type=success");
            exit();
        } else {
            header("Location: index.php?message=error_delete_not_found&type=warning");
            exit();
        }
    } else {
        header("Location: index.php?message=error_delete&type=error&error=" . urlencode($stmt->error));
        exit();
    }

    $stmt->close();
    $conn->close();

} else {
    header("Location: index.php");
    exit();
}
?>