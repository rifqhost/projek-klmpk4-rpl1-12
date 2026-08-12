<?php
$p = 'index.php';
$s = file_get_contents($p);

// Tambah handler halaman exam_card sebelum require layout
$marker = "require __DIR__.'/views/layout.php';";
$newBlock = "if(\$page==='exam_card'){\$u=role('Siswa');\$eid=(int)(\$_GET['exam']??0);\$st=q('SELECT id,class_id FROM students WHERE user_id=?',[\$u['id']])->fetch(PDO::FETCH_ASSOC);\$ex=q('SELECT e.title,e.token,e.duration,e.description,s.name subject,sc.starts_at,sc.ends_at,c.name class_name,m.name major_name FROM exams e JOIN subjects s ON s.id=e.subject_id LEFT JOIN schedules sc ON sc.exam_id=e.id LEFT JOIN classes c ON c.id=sc.class_id LEFT JOIN majors m ON m.id=c.major_id WHERE e.id=? AND sc.class_id=? AND e.active=1',[\$eid,\$st['class_id']])->fetch(PDO::FETCH_ASSOC);if(!\$ex){flash('danger','Ujian tidak ditemukan.');redirect('page=student');}?><!doctype html><html lang=\"id\"><head><meta charset=\"utf-8\"><title>Kartu Ujian - <?=e(\$ex['title'])?></title><style>body{font-family:sans-serif;padding:30px;color:#111}h2{margin:0 0 4px}.card{max-width:420px;margin:0 auto;border:2px solid #4f46e5;border-radius:12px;padding:20px}.header{display:flex;justify-content:space-between;align-items:center;border-bottom:2px solid #4f46e5;padding-bottom:12px;margin-bottom:12px}.brand{font-size:18px;font-weight:700;color:#4f46e5}.info td{padding:6px 8px;font-size:14px}.info td:first-child{color:#666;width:110px}.token{font-size:20px;font-weight:700;letter-spacing:2px;background:#eef2ff;padding:4px 10px;border-radius:6px}.footer{margin-top:16px;font-size:11px;color:#999;text-align:center}@media print{body{padding:10px}.card{box-shadow:none}}</style></head><body><div class=\"card\"><div class=\"header\"><div class=\"brand\">CBT<span style=\"color:#000\">School</span></div><div><?=date('d/m/Y')?></div></div><h2><?=e(\$ex['title'])?></h2><small style=\"color:#666\"><?=e(\$ex['subject'])?> | <?=e(\$ex['class_name']??'-')?> (<?=e(\$ex['major_name']??'-')?>)</small><table class=\"info\"><tr><td>Nama</td><td>: <?=e(\$u['name'])?></td></tr><tr><td>Waktu</td><td>: <?=e(\$ex['starts_at'])?> – <?=e(\$ex['ends_at'])?></td></tr><tr><td>Durasi</td><td>: <?=\$ex['duration']?> menit</td></tr><tr><td>Token</td><td>: <span class=\"token\"><?=e(\$ex['token'])?></span></td></tr></table><div style=\"margin-top:12px;font-size:13px;color:#555\"><?=nl2br(e(\$ex['description']??''))?></div><div class=\"footer\">Dicetak dari <?=APP_NAME?> &mdash; Jaga kerahasiaan token ujian Anda</div></div><script>window.print()</script></body></html><?php exit;}";

if (strpos($s, $marker) !== false) {
    $s = str_replace($marker, $newBlock . "\n" . $marker, $s);
    file_put_contents($p, $s);
    echo "exam card page added\n";
} else {
    echo "MARKER NOT FOUND\n";
}
