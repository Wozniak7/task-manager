<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $due_date = $_POST['due_date'] ?? null;
    $status = $_POST['status'] ?? 'Pendente';

    if (empty($title)) {
        echo "<script>alert('O título da tarefa é obrigatório!'); window.location.href='create.php';</script>";
        exit;
    }

    if (!empty($due_date) && !preg_match("/^\d{4}-\d{2}-\d{2}$/", $due_date)) {
        echo "<script>alert('Formato de data inválido. Use YYYY-MM-DD.'); window.location.href='create.php';</script>";
        exit;
    }

    $sql = "INSERT INTO tasks (title, description, due_date, status) VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erro na preparação da declaração: " . $conn->error);
    }

    $stmt->bind_param("ssss", $title, $description, $due_date, $status);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: index.php?message=success_add");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: index.php?message=error_add&error=" . urlencode($stmt->error));
        exit();
    }

} else {
    header("Location: index.php");
    exit();
}
?>