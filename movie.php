<?php
// Movie detail page: retrieve one movie by ID and display its details.
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM movies WHERE id = :id LIMIT 1');
$stmt->execute([':id' => $id]);
$movie = $stmt->fetch();
if (!$movie) {
    // Redirect if the requested movie does not exist.
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/includes/header.php';
?>
<div class="row g-4">
    <div class="col-lg-5">
        <img src="uploads/posters/<?= e($movie['poster']) ?>" class="img-fluid rounded shadow" alt="<?= e($movie['title']) ?>">
    </div>
    <div class="col-lg-7">
        <h2 class="mb-3"><?= e($movie['title']) ?></h2>
        <p class="text-secondary mb-3"><?= e($movie['category']) ?></p>
        <p><?= e($movie['description']) ?></p>
        <div class="fw-bold fs-4 text-warning mb-4"><?= formatPrice($movie['price']) ?></div>
        <?php if (isLoggedIn()): ?>
            <a href="purchase.php?id=<?= (int) $movie['id'] ?>" class="btn btn-warning me-2">Purchase</a>
            <?php if (isMoviePurchased($pdo, (int) $_SESSION['user_id'], (int) $movie['id'])): ?>
                <a href="player.php?id=<?= (int) $movie['id'] ?>" class="btn btn-outline-light">Watch Now</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="login.php" class="btn btn-warning">Login to Purchase</a>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
