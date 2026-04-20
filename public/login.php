<?php
require_once __DIR__ . '/../app/auth.php';
require_guest();
require_once __DIR__ . '/../app/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    set_old($_POST);

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ];

        clear_old();
        redirect('dashboard.php');
    } else {
        $error = 'Invalid email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="auth-shell">
        <div class="auth-card">
            <div class="brand-row">
                <div>
                    <h1>Welcome Back</h1>
                    <p class="muted">Log in to continue to the dashboard.</p>
                </div>
            </div>

            <?php if ($message = flash('success')): ?>
                <div class="message success"><p><?= e($message) ?></p></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="message error"><p><?= e($error) ?></p></div>
            <?php endif; ?>

            <form method="POST">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="<?= e(old('email')) ?>">

                <label for="password">Password</label>
                <input id="password" type="password" name="password">

                <button type="submit">Log In</button>
            </form>

            <p class="switch-link">Need an account? <a href="register.php">Register</a></p>
        </div>
    </div>
</body>
</html>