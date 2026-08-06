<?php
// Session and authentication helpers shared by all pages.
// Handles user login state, protected routes, and purchase access checks.
if (session_status() === PHP_SESSION_NONE) {
    // Start a session for authentication and flash messages.
    session_start();
}

function isLoggedIn(): bool
{
    // Determine if the user has an active session.
    return isset($_SESSION['user_id']);
}

function requireLogin(): void
{
    // Redirect unauthenticated users to the login page.
    if (!isLoggedIn()) {
        $_SESSION['flash']['error'] = 'Please log in to continue.';
        header('Location: login.php');
        exit;
    }
}

function getCurrentUser(PDO $pdo): ?array
{
    // Fetch the current user record from the database.
    if (!isLoggedIn()) {
        return null;
    }

    $stmt = $pdo->prepare('SELECT id, full_name, email, balance, created_at FROM users WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}

function isMoviePurchased(PDO $pdo, int $userId, int $movieId): bool
{
    // Check if the user already owns the selected movie.
    $stmt = $pdo->prepare('SELECT 1 FROM purchases WHERE user_id = :user_id AND movie_id = :movie_id LIMIT 1');
    $stmt->execute([':user_id' => $userId, ':movie_id' => $movieId]);
    return (bool) $stmt->fetch();
}
?>
