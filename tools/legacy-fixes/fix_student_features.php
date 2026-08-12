<?php
$p = 'views/layout.php';
$s = file_get_contents($p);

// 1. Tambah pengumuman banner + kartu ujian digital di halaman student
$old = "<section class=\"panel mb-3\">\n  <h5>Ujian Tersedia</h5>";

$new = "<?php if(\$u['role']==='Siswa'):\n  \$todayExams=q(\"SELECT e.title,sc.starts_at,sc.ends_at FROM schedules sc JOIN exams e ON e.id=sc.exam_id JOIN students s ON s.class_id=sc.class_id WHERE s.user_id=? AND DATE(sc.starts_at)=CURDATE() ORDER BY sc.starts_at\",[\$u['id']])->fetchAll(PDO::FETCH_ASSOC);\n  if(\$todayExams):?>\n  <div class=\"alert alert-info d-flex align-items-center gap-2\"><i class=\"bi bi-megaphone-fill\"></i> <div><b>Ujian Hari Ini:</b> <?=implode(', ',array_map(fn(\$x)=>e(\$x['title']).' ('.date('H:i',strtotime(\$x['starts_at'])).'-'.date('H:i',strtotime(\$x['ends_at'])).')',\$todayExams))?></div></div>\n  <?php endif?>\n<?php endif?>\n<section class=\"panel mb-3\">\n  <h5>Ujian Tersedia</h5>";

if (strpos($s, $old) !== false) {
    $s = str_replace($old, $new, $s);
    echo "banner added\n";
} else {
    echo "BANNER PATTERN NOT FOUND\n";
}

// 2. Tambah tombol kartu ujian digital di card ujian tersedia
$old2 = "<h6 class=\"mb-1\"><?=e(\$x['title'])?></h6>\n          <small class=\"text-muted d-block mb-2\"><?=e(\$x['starts_at'])?> s/d <?=e(\$x['ends_at'])?></small>";

$new2 = "<h6 class=\"mb-1\"><?=e(\$x['title'])?></h6>\n          <small class=\"text-muted d-block mb-2\"><?=e(\$x['starts_at'])?> s/d <?=e(\$x['ends_at'])?></small>\n          <button type=\"button\" class=\"btn btn-sm btn-outline-info mb-2\" onclick=\"window.open('?page=exam_card&exam=<?=\$x['id']?>','_blank','width=500,height=700')\"><i class=\"bi bi-card-text\"></i> Kartu Ujian</button>";

if (strpos($s, $old2) !== false) {
    $s = str_replace($old2, $new2, $s);
    echo "exam card button added\n";
} else {
    echo "CARD BUTTON PATTERN NOT FOUND\n";
}

file_put_contents($p, $s);
echo "done\n";
