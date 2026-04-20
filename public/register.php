<?php
require_once __DIR__ . '/../app/auth.php';
require_guest();
require_once __DIR__ . '/../app/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    set_old($_POST);

    if ($name === '') {
        $errors[] = 'Name is required.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $check = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $check->execute([$email]);

        if ($check->fetch()) {
            $errors[] = 'That email is already registered.';
        }
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$name, $email, $hashed_password]);

        flash('success', 'Account created. You can now log in.');
        clear_old();
        redirect('login.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="auth-shell">
        <div class="auth-card">
            <div class="brand-row">
                <div>
                    <h1>Create Account</h1>
                    <p class="muted">Set up an account to access the dashboard.</p>
                </div>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="message error">
                    <?php foreach ($errors as $error): ?>
                        <p><?= e($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <label for="name">Name</label>
                <input id="name" type="text" name="name" value="<?= e(old('name')) ?>">

                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="<?= e(old('email')) ?>">

                <label for="password">Password</label>
                <input id="password" type="password" name="password">

                <label for="confirm_password">Confirm Password</label>
                <input id="confirm_password" type="password" name="confirm_password">

                <button type="submit">Create Account</button>
            </form>

            <p class="switch-link">Already have an account? <a href="login.php">Log in</a></p>
        </div>
    </div>
</body>
</html>