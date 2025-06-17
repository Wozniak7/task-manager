<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $due_date = $_POST['due_date'] ?? null;
    $status = $_POST['status'] ?? 'Pendente';

    if (empty($title)) {
        header("Location: index.php?message=validation_error&type=error&error=" . urlencode("O título da tarefa é obrigatório!"));
        exit;
    }

    if (!empty($due_date) && !preg_match("/^\d{4}-\d{2}-\d{2}$/", $due_date)) {
        header("Location: index.php?message=validation_error&type=error&error=" . urlencode("Formato de data inválido. Use AAAA-MM-DD."));
        exit;
    }

    $sql = "INSERT INTO tasks (title, description, due_date, status, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        header("Location: index.php?message=error_add&type=error&error=" . urlencode($conn->error));
        exit;
    }

    $stmt->bind_param("ssssi", $title, $description, $due_date, $status, $user_id);

    if ($stmt->execute()) {
        header("Location: index.php?message=success_add&type=success");
        exit();
    } else {
        header("Location: index.php?message=error_add&type=error&error=" . urlencode($stmt->error));
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php");
    exit();
}
