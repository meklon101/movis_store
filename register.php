<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

// Registration page: create a new user account and log them in automatically.
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle registration form submission and validate input.
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($fullName === '' || $email === '' || $password === '' || $confirmPassword === '') {
        $errors[] = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email.';
    } elseif ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email already registered.';
        } else {
            // Create new user with default balance and log them in.
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare('INSERT INTO users (full_name, email, password, balance) VALUES (:full_name, :email, :password, 1000.00)');
            $insert->execute([
                ':full_name' => $fullName,
                ':email' => $email,
                ':password' => $hashed,
            ]);

            $userId = (int) $pdo->lastInsertId();
            session_regenerate_id(true);
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $fullName;
            setFlash('success', 'Registration successful. Your balance is 1000 ILS.');
            header('Location: index.php');
            exit;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
        <div class="form-card p-4 shadow">
            <h2 class="mb-3">Create account</h2>
            <p class="text-secondary">Join CineVerse and start watching instantly.</p>
            <?php foreach ($errors as $error): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endforeach; ?>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="full_name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_password" required>
                </div>
                <button class="btn btn-warning w-100">Register</button>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
