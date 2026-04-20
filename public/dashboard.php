<?php
require_once __DIR__ . '/../app/auth.php';
require_login();

$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-page">
        <div class="dashboard-card">
            <div class="dashboard-topbar">
                <div>
                    <p class="eyebrow">Dashboard</p>
                    <h1>Welcome, <?= e($user['name']) ?></h1>
                </div>
                <a class="logout-link" href="logout.php">Logout</a>
            </div>

            <div class="welcome-box">
                <h2>Welcome!</h2>
                <p>This was made by <a href="https://github.com/alansurowiec" target="_blank" rel="noopener noreferrer"> Alan Surowiec</a>. You logged in successfully and your account session is active.</p>
            </div>

            <div class="info-grid">
                <div class="info-card">
                    <p class="info-label">Name</p>
                    <p class="info-value"><?= e($user['name']) ?></p>
                </div>
                <div class="info-card">
                    <p class="info-label">Email</p>
                    <p class="info-value"><?= e($user['email']) ?></p>
                </div>
                <div class="info-card">
                    <p class="info-label">Status</p>
                    <p class="info-value">Logged In</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>