<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $due_date = $_POST['due_date'] ?? null;
    $status = $_POST['status'] ?? 'Pendente';

    if (empty($id) || !is_numeric($id)) {
        echo "<script>alert('ID da tarefa inválido!'); window.location.href='index.php';</script>";
        exit;
    }
    if (empty($title)) {
        echo "<script>alert('O título da tarefa é obrigatório!'); window.location.href='edit.php?id=" . urlencode($id) . "';</script>";
        exit;
    }

    if (!empty($due_date) && !preg_match("/^\d{4}-\d{2}-\d{2}$/", $due_date)) {
        echo "<script>alert('Formato de data inválido. Use AAAA-MM-DD.'); window.location.href='edit.php?id=" . urlencode($id) . "';</script>";
        exit;
    }

    $sql = "UPDATE tasks SET title = ?, description = ?, due_date = ?, status = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erro na preparação da declaração: " . $conn->error);
    }

    $stmt->bind_param("ssssi", $title, $description, $due_date, $status, $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $stmt->close();
            $conn->close();
            header("Location: index.php?message=success_update");
            exit();
        } else {
            $stmt->close();
            $conn->close();
            header("Location: index.php?message=info_no_change");
            exit();
        }
    } else {
        $stmt->close();
        $conn->close();
        header("Location: index.php?message=error_update&error=" . urlencode($stmt->error));
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>