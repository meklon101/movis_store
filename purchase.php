<?php
// Purchase handler: verify login, balance, and previous purchase before saving the transaction.
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

requireLogin();

// Validate the selected movie ID from the query string.
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    setFlash('error', 'Invalid movie selection.');
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id, title, price FROM movies WHERE id = :id LIMIT 1');
$stmt->execute([':id' => $id]);
$movie = $stmt->fetch();
if (!$movie) {
    setFlash('error', 'Movie not found.');
    header('Location: index.php');
    exit;
}

$user = getCurrentUser($pdo);
if (!$user) {
    setFlash('error', 'Please log in again.');
    header('Location: login.php');
    exit;
}

$check = $pdo->prepare('SELECT 1 FROM purchases WHERE user_id = :user_id AND movie_id = :movie_id LIMIT 1');
$check->execute([':user_id' => $user['id'], ':movie_id' => $movie['id']]);
if ($check->fetch()) {
    setFlash('error', 'Movie already purchased.');
    header('Location: movie.php?id=' . $movie['id']);
    exit;
}

if ((float) $user['balance'] < (float) $movie['price']) {
    setFlash('error', 'Insufficient balance.');
    header('Location: movie.php?id=' . $movie['id']);
    exit;
}

// Deduct price and save purchase in a transaction to keep data consistent.
$pdo->beginTransaction();
try {
    $newBalance = (float) $user['balance'] - (float) $movie['price'];
    $update = $pdo->prepare('UPDATE users SET balance = :balance WHERE id = :id');
    $update->execute([':balance' => $newBalance, ':id' => $user['id']]);

    $insert = $pdo->prepare('INSERT INTO purchases (user_id, movie_id, price) VALUES (:user_id, :movie_id, :price)');
    $insert->execute([
        ':user_id' => $user['id'],
        ':movie_id' => $movie['id'],
        ':price' => $movie['price'],
    ]);

    $pdo->commit();
    setFlash('success', 'Purchase successful. Enjoy your movie.');
    header('Location: movie.php?id=' . $movie['id']);
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    setFlash('error', 'Purchase failed. Please try again.');
    header('Location: movie.php?id=' . $movie['id']);
    exit;
}
