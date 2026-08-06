<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Cinema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-dark text-light">
    <?php require_once __DIR__ . '/auth.php'; require_once __DIR__ . '/functions.php'; ?>
    <!-- Page header and responsive navigation menu. -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black border-bottom border-secondary">
        <div class="container">
            <a class="navbar-brand fw-bold text-warning" href="index.php">CineVerse</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="movies.php">Movies</a></li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item"><a class="nav-link" href="my_movies.php">My Movies</a></li>
                        <li class="nav-item"><a class="nav-link" href="history.php">Purchase History</a></li>
                        <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                        <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <?php $flashMessages = getFlash(); foreach ($flashMessages as $type => $message): ?>
            <!-- Flash notifications inform the user about successes and errors. -->
            <div class="alert alert-<?= e($type) === 'error' ? 'danger' : e($type) ?> alert-dismissible fade show" role="alert">
                <?= e($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endforeach; ?>
