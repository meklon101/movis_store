<?php
// Video player page: allow playback only if the user has purchased the movie.
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

requireLogin();

// Validate the movie ID and ensure the user can watch it.
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    setFlash('error', 'Invalid movie selection.');
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id, title, video_file FROM movies WHERE id = :id LIMIT 1');
$stmt->execute([':id' => $id]);
$movie = $stmt->fetch();
if (!$movie) {
    setFlash('error', 'Movie not found.'); 
    header('Location: index.php');
    exit;
}

if (!isMoviePurchased($pdo, (int) $_SESSION['user_id'], (int) $movie['id'])) {
    // Prevent access unless the movie is already purchased.
    setFlash('error', 'You must purchase this movie before watching.');
    header('Location: movie.php?id=' . $movie['id']);
    exit;
}

require_once __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <h2 class="mb-3"><?= e($movie['title']) ?></h2>
        <div class="video-frame shadow">
            <video controls autoplay class="w-100" style="max-height: 70vh; background:#000;">
                <source src="uploads/videos/<?= e($movie['video_file']) ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
