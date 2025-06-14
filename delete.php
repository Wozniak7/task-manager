<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? null;

    if (empty($id) || !is_numeric($id)) {
        echo "<script>alert('ID da tarefa inválido!'); window.location.href='index.php';</script>";
        exit;
    }

    $sql = "DELETE FROM tasks WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erro na preparação da declaração: " . $conn->error);
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $stmt->close();
            $conn->close();
            header("Location: index.php?message=success_delete");
            exit();
        } else {
            $stmt->close();
            $conn->close();
            header("Location: index.php?message=error_delete_not_found");
            exit();
        }
    } else {
        $stmt->close();
        $conn->close();
        header("Location: index.php?message=error_delete&error=" . urlencode($stmt->error));
        exit();
    }

} else {
    header("Location: index.php");
    exit();
}
?>