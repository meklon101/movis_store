<?php
// Common utility helpers used by pages to improve security and display.
function e($value): string
{
    // Escape user-facing text to prevent XSS attacks.
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function formatPrice($value): string
{
    // Format numeric values as a local currency string.
    return 'ILS ' . number_format((float) $value, 2);
}

function setFlash(string $type, string $message): void
{
    // Store a temporary message for the next page load.
    $_SESSION['flash'][$type] = $message;
}

function getFlash(): array
{
    // Retrieve and clear flash messages from the session.
    $flash = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flash;
}
?>
