<?php
// 1. Tambah tabel user_devices di database.php
$p = 'config/database.php';
$s = file_get_contents($p);

$old = "\"CREATE TABLE IF NOT EXISTS login_attempts (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, identifier VARCHAR(120) NOT NULL, ip VARCHAR(45) NOT NULL, success TINYINT(1) NOT NULL DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, INDEX(identifier), INDEX(ip)) ENGINE=InnoDB\"";
$new = "\"CREATE TABLE IF NOT EXISTS login_attempts (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, identifier VARCHAR(120) NOT NULL, ip VARCHAR(45) NOT NULL, success TINYINT(1) NOT NULL DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, INDEX(identifier), INDEX(ip)) ENGINE=InnoDB\",\n      \"CREATE TABLE IF NOT EXISTS user_devices (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT UNSIGNED NOT NULL, device_hash VARCHAR(64) NOT NULL, device_name VARCHAR(150) NOT NULL, ip VARCHAR(45), last_seen DATETIME, UNIQUE(user_id,device_hash), FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE) ENGINE=InnoDB\"";

if (strpos($s, $old) !== false) {
    $s = str_replace($old, $new, $s);
    file_put_contents($p, $s);
    echo "user_devices table added\n";
} else {
    echo "TABLE PATTERN NOT FOUND\n";
}

// 2. Tambah validasi device di index.php (modifikasi baris login)
$p2 = 'index.php';
$s2 = file_get_contents($p2);

// Cari baris login lalu sisipkan device check setelah session_regenerate_id dan sebelum redirect dashboard
$needle = "q('INSERT INTO login_attempts(identifier,ip,success) VALUES(?,?,1)',[\$input,\$ip]); session_regenerate_id(true); \$_SESSION['user']=\$u; q('UPDATE users SET session_id=? WHERE id=?',[session_id(),\$u['id']]); log_action('Login'); redirect('page=dashboard');";

$replace = "q('INSERT INTO login_attempts(identifier,ip,success) VALUES(?,?,1)',[\$input,\$ip]); session_regenerate_id(true); \$_SESSION['user']=\$u; q('UPDATE users SET session_id=? WHERE id=?',[session_id(),\$u['id']]); \$did=substr(hash('sha256',(\$_SERVER['HTTP_USER_AGENT']??'ua').'|'.(\$_SERVER['HTTP_ACCEPT_LANGUAGE']??'id')),0,32); \$dname=substr((\$_SERVER['HTTP_USER_AGENT']??'Perangkat'),0,80); q(\"INSERT INTO user_devices(user_id,device_hash,device_name,ip,last_seen) VALUES(?,?,?,?,NOW()) ON DUPLICATE KEY UPDATE last_seen=NOW(),ip=VALUES(ip)\",[\$u['id'],\$did,\$dname,\$ip]); \$devCount=q('SELECT COUNT(*) FROM user_devices WHERE user_id=? AND last_seen>DATE_SUB(NOW(),INTERVAL 30 DAY)',[\$u['id']])->fetchColumn(); \$_SESSION['device_hash']=\$did; log_action('Login'); redirect('page=dashboard');";

if (strpos($s2, $needle) !== false) {
    $s2 = str_replace($needle, $replace, $s2);
    file_put_contents($p2, $s2);
    echo "device check added\n";
} else {
    echo "LOGIN DEVICE PATTERN NOT FOUND\n";
}
