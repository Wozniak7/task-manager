<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_user_id = $_SESSION['user_id'];
    $id = $_POST['id'] ?? null;

    if (empty($id) || !is_numeric($id)) {
        header("Location: index.php?message=validation_error&type=error&error=" . urlencode("ID da tarefa inválido!"));
        exit;
    }

    $sql = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        header("Location: index.php?message=error_delete&type=error&error=" . urlencode($conn->error));
        exit;
    }

    $stmt->bind_param("ii", $id, $current_user_id);

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
