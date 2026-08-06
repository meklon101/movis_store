<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

// Profile page: show current user data and purchase summary.
requireLogin();
$user = getCurrentUser($pdo);
if (!$user) {
    header('Location: login.php');
    exit;
}

// Fetch summary data for the current user's profile.
$stmt = $pdo->prepare('SELECT COUNT(*) AS total_purchases FROM purchases WHERE user_id = :user_id');
$stmt->execute([':user_id' => $user['id']]);
$totalPurchases = (int) $stmt->fetchColumn();

require_once __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="form-card p-4 shadow">
            <h2 class="mb-4">Profile</h2>
            <ul class="list-group list-group-flush">
                <li class="list-group-item bg-transparent text-light"><strong>Full Name:</strong> <?= e($user['full_name']) ?></li>
                <li class="list-group-item bg-transparent text-light"><strong>Email:</strong> <?= e($user['email']) ?></li>
                <li class="list-group-item bg-transparent text-light"><strong>Current Balance:</strong> <?= formatPrice($user['balance']) ?></li>
                <li class="list-group-item bg-transparent text-light"><strong>Total Purchased Movies:</strong> <?= $totalPurchases ?></li>
                <li class="list-group-item bg-transparent text-light"><strong>Registration Date:</strong> <?= e($user['created_at']) ?></li>
            </ul>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
