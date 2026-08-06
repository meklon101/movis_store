<?php
// Movies page: fetch all movies and render the public movie catalog.
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$stmt = $pdo->query('SELECT * FROM movies ORDER BY created_at DESC');
$movies = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Movies</h2>
        <p class="text-secondary mb-0">Browse our premium movie collection.</p>
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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-warning"><?= formatPrice($movie['price']) ?></span>
                    </div>
                    <div class="d-flex gap-2">
                        <!-- Detail page link for movie overview. -->
                        <a href="movie.php?id=<?= (int) $movie['id'] ?>" class="btn btn-outline-light flex-grow-1">Watch</a>
                        <?php if (isLoggedIn()): ?>
                            <!-- Show purchase action only for logged-in users. -->
                            <a href="purchase.php?id=<?= (int) $movie['id'] ?>" class="btn btn-warning flex-grow-1">Purchase</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-warning flex-grow-1">Login</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
