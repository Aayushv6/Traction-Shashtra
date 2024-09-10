<?php
require '../backend/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password !== $password_confirm) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
        exit();
    }

    // Check if the email already exists
    $checkEmailSql = "SELECT COUNT(*) FROM users WHERE email = :email";
    $checkEmailStmt = $pdo->prepare($checkEmailSql);
    $checkEmailStmt->execute(['email' => $email]);
    $emailExists = $checkEmailStmt->fetchColumn();

    if ($emailExists) {
        echo json_encode(['success' => false, 'message' => 'Email is already registered.']);
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashedPassword]);

    echo json_encode(['success' => true, 'message' => 'Registration successful.']);
}
?>
