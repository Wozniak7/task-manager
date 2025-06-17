<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($password) || empty($confirm_password)) {
        header("Location: register.php?error=empty_fields");
        exit();
    }

    if ($password !== $confirm_password) {
        header("Location: register.php?error=passwords_do_not_match");
        exit();
    }

    if (strlen($password) < 6) {
        header("Location: register.php?error=password_too_short");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        header("Location: register.php?error=db_prepare_failed");
        exit();
    }

    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['username'] = $username;
        header("Location: index.php?message=register_success");
        exit();
    } else {
        if ($conn->errno == 1062) { 
            header("Location: register.php?error=username_taken");
        } else {
            header("Location: register.php?error=db_insert_failed");
        }
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: register.php");
    exit();
}
