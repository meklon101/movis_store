<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

// My Movies page: show the user's purchased movie library.
requireLogin();

// Load movies that the current user has already purchased.
$stmt = $pdo->prepare('SELECT m.* FROM purchases p JOIN movies m ON m.id = p.movie_id WHERE p.user_id = :user_id ORDER BY p.purchased_at DESC');
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$movies = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">My Movies</h2>
        <p class="text-secondary mb-0">Your purchased collection.</p>
    </div>
</div>
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
    <?php foreach ($movies as $movie): ?>
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="uploads/posters/<?= e($movie['poster']) ?>" class="card-img-top poster-image" alt="<?= e($movie['title']) ?>">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= e($movie['title']) ?></h5>
                    <p class="text-secondary small mb-2"><?= e($movie['category']) ?></p>
                    <p class="card-text flex-grow-1"><?= e($movie['description']) ?></p>
                    <!-- Allow user to watch any purchased movie. -->
                    <a href="player.php?id=<?= (int) $movie['id'] ?>" class="btn btn-warning mt-2">Watch</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
