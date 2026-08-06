<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

// Purchase history page: list all movies bought by the current user.
requireLogin();

// Load the user's purchase history sorted by most recent purchase.
$stmt = $pdo->prepare('SELECT m.title, p.price, p.purchased_at FROM purchases p JOIN movies m ON m.id = p.movie_id WHERE p.user_id = :user_id ORDER BY p.purchased_at DESC');
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$purchases = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<div class="form-card p-4 shadow">
    <h2 class="mb-4">Purchase History</h2>
    <?php if ($purchases): ?>
        <div class="table-responsive">
            <table class="table table-dark table-striped align-middle">
                <thead>
                    <tr>
                        <th>Movie</th>
                        <th>Price</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($purchases as $purchase): ?>
                        <tr>
                            <td><?= e($purchase['title']) ?></td>
                            <td><?= formatPrice($purchase['price']) ?></td>
                            <td><?= e($purchase['purchased_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <!-- Show when the user has no purchases yet. -->
        <p class="text-secondary">No purchases yet.</p>
    <?php endif; ?>

</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
