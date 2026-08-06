<?php
// Home page: load all movies and display the landing page catalog.
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$stmt = $pdo->query('SELECT * FROM movies ORDER BY created_at DESC');
$movies = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<div class="hero p-4 mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold">Stream the best movies in one place</h1>
            <p class="lead text-secondary">Discover blockbuster titles, buy access instantly, and enjoy your collection anytime.</p>
            <a class="btn btn-warning btn-lg" href="movies.php">Browse Movies</a>
        </div>
        <div class="col-lg-4 text-center">
            <div class="display-1 fw-bold text-warning">🎬</div>
        </div>
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
                        <!-- Link to the detail page for this movie. -->
                        <a href="movie.php?id=<?= (int) $movie['id'] ?>" class="btn btn-outline-light flex-grow-1">Details</a>
                        <?php if (isLoggedIn()): ?>
                            <!-- Logged-in users can purchase directly. -->
                            <a href="purchase.php?id=<?= (int) $movie['id'] ?>" class="btn btn-warning flex-grow-1">Purchase</a>
                        <?php else: ?>
                            <!-- Guests are prompted to log in first. -->
                            <a href="login.php" class="btn btn-warning flex-grow-1">Login to Buy</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
