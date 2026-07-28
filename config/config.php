<?php
declare(strict_types=1);
date_default_timezone_set('Asia/Jakarta');
const APP_NAME = 'CBT Sekolah';
const DB_HOST = 'localhost';
const DB_NAME = 'cbt_sekolah';
const DB_USER = 'root';
const DB_PASS = '';
const BASE_URL = '/cbt-sekolah'; // Lokasi aplikasi pada Apache/XAMPP.

if (session_status() !== PHP_SESSION_ACTIVE) {
    $sessionPath = dirname(__DIR__).DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'sessions';
    if (!is_dir($sessionPath)) mkdir($sessionPath, 0700, true);
    session_save_path($sessionPath);
    session_name('cbt_session');
    session_set_cookie_params(['httponly'=>true, 'samesite'=>'Lax', 'secure'=>(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')]);
    session_start();
}

function e(?string $text): string { return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8'); }
function csrf(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function verify_csrf(): void { if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(419); exit('Permintaan tidak valid. Silakan muat ulang halaman.'); } }
function redirect(string $url): never { header('Location: '.BASE_URL.'/index.php?'.$url); exit; }
function flash(string $type, string $message): void { $_SESSION['flash'] = [$type,$message]; }
