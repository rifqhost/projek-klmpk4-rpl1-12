<?php
declare(strict_types=1);
date_default_timezone_set('Asia/Jakarta');
const APP_NAME = 'CBT Sekolah';
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'cbt_sekolah');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('BASE_URL', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/'));

if (session_status() !== PHP_SESSION_ACTIVE) {
    $sessionPath = getenv('RENDER')
        ? sys_get_temp_dir().DIRECTORY_SEPARATOR.'cbt_sessions'
        : dirname(__DIR__).DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'sessions';
    if (!is_dir($sessionPath)) mkdir($sessionPath, 0700, true);
    session_save_path($sessionPath);
    session_name('cbt_session');
    session_set_cookie_params(['httponly'=>true, 'samesite'=>'Lax', 'secure'=>(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')]);
    session_start();
}

function e(?string $text): string { return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8'); }
function csrf(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function verify_csrf(): void { if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(419); exit('Permintaan tidak valid. Silakan muat ulang halaman.'); } }
function redirect(string $url): never
{
    $url = ltrim($url, '?');
    header('Location: '.BASE_URL.'/index.php'.($url !== '' ? '?'.$url : ''));
    exit;
}
function flash(string $type, string $message): void { $_SESSION['flash'] = [$type,$message]; }
