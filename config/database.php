<?php
require_once __DIR__.'/config.php';
function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $databaseName = DB_NAME;
    $dsn = 'mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.$databaseName.';charset=utf8mb4';
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        try {
            $pdo = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';charset=utf8mb4', DB_USER, DB_PASS, $options);
            try {
                $pdo->exec('USE `'.$databaseName.'`');
            } catch (PDOException $useError) {
                $databaseName = ' '.$databaseName;
                $pdo->exec('USE `'.$databaseName.'`');
            }
        } catch (PDOException $fallbackError) {
            error_log('Koneksi MySQL gagal: '.$fallbackError->getMessage());
            http_response_code(500);
            exit('Koneksi MySQL gagal. Periksa environment variable DB_HOST, DB_PORT, DB_NAME, DB_USER, dan DB_PASS.');
        }
    }
    install_database($pdo);
    return $pdo;
}
function install_database(PDO $p): void {
    $tables = [
      "CREATE TABLE IF NOT EXISTS roles (id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(30) NOT NULL UNIQUE) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS users (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, role_id TINYINT UNSIGNED NOT NULL, name VARCHAR(100) NOT NULL, email VARCHAR(120) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL DEFAULT 1, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(role_id) REFERENCES roles(id)) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS majors (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, code VARCHAR(20) NOT NULL UNIQUE) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS academic_years (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(30) NOT NULL UNIQUE, active TINYINT(1) NOT NULL DEFAULT 1) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS classes (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(60) NOT NULL, major_id INT UNSIGNED NULL, academic_year_id INT UNSIGNED NULL, FOREIGN KEY(major_id) REFERENCES majors(id) ON DELETE SET NULL, FOREIGN KEY(academic_year_id) REFERENCES academic_years(id) ON DELETE SET NULL) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS teachers (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT UNSIGNED NOT NULL UNIQUE, nip VARCHAR(40) NOT NULL UNIQUE, phone VARCHAR(30), FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS students (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT UNSIGNED NOT NULL UNIQUE, nis VARCHAR(40) NOT NULL UNIQUE, class_id INT UNSIGNED NULL, FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE, FOREIGN KEY(class_id) REFERENCES classes(id) ON DELETE SET NULL) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS subjects (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, code VARCHAR(20) NOT NULL UNIQUE, teacher_id INT UNSIGNED NULL, FOREIGN KEY(teacher_id) REFERENCES teachers(id) ON DELETE SET NULL) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS exams (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, subject_id INT UNSIGNED NOT NULL, teacher_id INT UNSIGNED NULL, title VARCHAR(150) NOT NULL, description TEXT, duration INT UNSIGNED NOT NULL DEFAULT 60, token VARCHAR(12) NOT NULL UNIQUE, random_questions TINYINT(1) DEFAULT 1, random_choices TINYINT(1) DEFAULT 1, show_score TINYINT(1) DEFAULT 0, active TINYINT(1) DEFAULT 1, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(subject_id) REFERENCES subjects(id), FOREIGN KEY(teacher_id) REFERENCES teachers(id) ON DELETE SET NULL) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS schedules (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, exam_id INT UNSIGNED NOT NULL, class_id INT UNSIGNED NOT NULL, starts_at DATETIME NOT NULL, ends_at DATETIME NOT NULL, FOREIGN KEY(exam_id) REFERENCES exams(id) ON DELETE CASCADE, FOREIGN KEY(class_id) REFERENCES classes(id) ON DELETE CASCADE) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS questions (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, subject_id INT UNSIGNED NOT NULL, teacher_id INT UNSIGNED NULL, type ENUM('multiple','essay') NOT NULL DEFAULT 'multiple', question TEXT NOT NULL, answer_key TEXT NULL, weight DECIMAL(6,2) NOT NULL DEFAULT 1, FOREIGN KEY(subject_id) REFERENCES subjects(id) ON DELETE CASCADE, FOREIGN KEY(teacher_id) REFERENCES teachers(id) ON DELETE SET NULL) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS choices (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, question_id INT UNSIGNED NOT NULL, label CHAR(1) NOT NULL, choice_text TEXT NOT NULL, is_correct TINYINT(1) NOT NULL DEFAULT 0, FOREIGN KEY(question_id) REFERENCES questions(id) ON DELETE CASCADE, UNIQUE(question_id,label)) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS exam_questions (exam_id INT UNSIGNED NOT NULL, question_id INT UNSIGNED NOT NULL, PRIMARY KEY(exam_id,question_id), FOREIGN KEY(exam_id) REFERENCES exams(id) ON DELETE CASCADE, FOREIGN KEY(question_id) REFERENCES questions(id) ON DELETE CASCADE) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS exam_results (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, exam_id INT UNSIGNED NOT NULL, student_id INT UNSIGNED NOT NULL, started_at DATETIME NOT NULL, submitted_at DATETIME NULL, score DECIMAL(6,2) NULL, status ENUM('in_progress','submitted','graded') DEFAULT 'in_progress', UNIQUE(exam_id,student_id), FOREIGN KEY(exam_id) REFERENCES exams(id) ON DELETE CASCADE, FOREIGN KEY(student_id) REFERENCES students(id) ON DELETE CASCADE) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS answers (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, result_id INT UNSIGNED NOT NULL, question_id INT UNSIGNED NOT NULL, answer_text TEXT NULL, is_correct TINYINT(1) NULL, score DECIMAL(6,2) NULL, UNIQUE(result_id,question_id), FOREIGN KEY(result_id) REFERENCES exam_results(id) ON DELETE CASCADE, FOREIGN KEY(question_id) REFERENCES questions(id) ON DELETE CASCADE) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS settings (setting_key VARCHAR(80) PRIMARY KEY, setting_value TEXT NOT NULL) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS logs (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT UNSIGNED NULL, action VARCHAR(150) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS violations (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, result_id INT UNSIGNED NOT NULL, type VARCHAR(50) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(result_id) REFERENCES exam_results(id) ON DELETE CASCADE) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS student_questions (result_id INT UNSIGNED NOT NULL, question_id INT UNSIGNED NOT NULL, PRIMARY KEY(result_id,question_id), FOREIGN KEY(result_id) REFERENCES exam_results(id) ON DELETE CASCADE) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS exam_sections (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, exam_id INT UNSIGNED NOT NULL, title VARCHAR(100) NOT NULL, ordering INT UNSIGNED NOT NULL DEFAULT 0, timer INT UNSIGNED NULL, FOREIGN KEY(exam_id) REFERENCES exams(id) ON DELETE CASCADE) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS notifications (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT UNSIGNED NOT NULL, title VARCHAR(150) NOT NULL, message TEXT, type VARCHAR(30) DEFAULT 'info', is_read TINYINT(1) NOT NULL DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS login_attempts (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, identifier VARCHAR(120) NOT NULL, ip VARCHAR(45) NOT NULL, success TINYINT(1) NOT NULL DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, INDEX(identifier), INDEX(ip)) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS user_devices (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT UNSIGNED NOT NULL, device_hash VARCHAR(64) NOT NULL, device_name VARCHAR(150) NOT NULL, ip VARCHAR(45), last_seen DATETIME, UNIQUE(user_id,device_hash), FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE) ENGINE=InnoDB"
    ];
    foreach($tables as $sql) $p->exec($sql);
    try{$p->exec("ALTER TABLE answers ADD COLUMN flagged TINYINT(1) NOT NULL DEFAULT 0 AFTER score");}catch(PDOException$e){}
    $p->exec("INSERT IGNORE INTO roles (id,name) VALUES (1,'Admin'),(2,'Guru'),(3,'Siswa'),(4,'Proktor')");
    try{$p->exec("ALTER TABLE exams ADD COLUMN random_questions_count INT UNSIGNED NOT NULL DEFAULT 0 AFTER random_choices");}catch(PDOException$e){}
    try{$p->exec("ALTER TABLE exam_questions ADD COLUMN section_id INT UNSIGNED NULL AFTER question_id");}catch(PDOException$e){}
    try{$p->exec("ALTER TABLE student_questions ADD COLUMN ordering INT UNSIGNED NOT NULL DEFAULT 0 AFTER question_id");}catch(PDOException$e){}
    try{$p->exec("ALTER TABLE exam_results ADD COLUMN last_ping DATETIME NULL AFTER submitted_at");}catch(PDOException$e){}
    try{$p->exec("ALTER TABLE exam_results ADD COLUMN ping_count INT UNSIGNED NOT NULL DEFAULT 0 AFTER last_ping");}catch(PDOException$e){}
    try{$p->exec("ALTER TABLE users ADD COLUMN session_id VARCHAR(128) NULL AFTER created_at");}catch(PDOException$e){}
    foreach(['majors','classes','subjects'] as $t){try{$p->exec("ALTER TABLE $t ADD COLUMN active TINYINT(1) NOT NULL DEFAULT 1");}catch(PDOException$e){}}
    $p->exec("INSERT IGNORE INTO academic_years (id,name,active) VALUES (1,'2026/2027',1)");
    $p->exec("INSERT IGNORE INTO majors (id,name,code) VALUES (1,'Rekayasa Perangkat Lunak','RPL')");
    $p->exec("INSERT IGNORE INTO classes (id,name,major_id,academic_year_id) VALUES (1,'XII RPL 1',1,1)");
    $p->exec("INSERT IGNORE INTO settings VALUES ('school_name','Sekolah Digital'),('school_address','Indonesia'),('exam_warning','Pastikan koneksi internet stabil selama ujian.'),('max_warnings','2'),('student_email_domain','school.local')");
    $p->exec("UPDATE settings SET setting_value='2' WHERE setting_key='max_warnings' AND setting_value='3'");
$p->exec("INSERT IGNORE INTO settings (setting_key,setting_value) VALUES ('dark_mode','0') ON DUPLICATE KEY UPDATE setting_value=setting_value");
    $check=$p->prepare('SELECT id FROM users WHERE email=?'); $check->execute(['admin@school.test']);
    if(!$check->fetch()) { $s=$p->prepare('INSERT INTO users(role_id,name,email,password) VALUES(1,?,?,?)'); $s->execute(['Administrator','admin@school.test',password_hash('admin123',PASSWORD_DEFAULT)]); }
}
