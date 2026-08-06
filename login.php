<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

// Login page: handle user authentication and set session values on success.
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process login submission from the form.
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Please enter your email and password.';
    } else {
        $stmt = $pdo->prepare('SELECT id, full_name, password FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Successful login: regenerate session and store user data.
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            setFlash('success', 'Welcome back!');
            header('Location: index.php');
            exit;
        }

        $errors[] = 'Invalid email or password.';
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
        <div class="form-card p-4 shadow">
            <h2 class="mb-3">Login</h2>
            <p class="text-secondary">Access your account and continue watching.</p>
            <?php foreach ($errors as $error): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endforeach; ?>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <button class="btn btn-warning w-100">Login</button>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
