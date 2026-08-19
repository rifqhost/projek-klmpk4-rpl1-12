  <?php
$u=user(); $flash=$_SESSION['flash']??null; unset($_SESSION['flash']);
function opts(string $table,string $selected='',$label='name'):string{if($table==='teachers'){$s=q("SELECT t.id,u.name FROM teachers t JOIN users u ON u.id=t.user_id WHERE u.active=1 ORDER BY u.name")->fetchAll(PDO::FETCH_ASSOC);$label='name';}else{$where=in_array($table,['majors','academic_years','classes','subjects'],true)?' WHERE active=1':'';$s=q("SELECT id,$label FROM $table$where ORDER BY $label")->fetchAll(PDO::FETCH_ASSOC);}$o='';foreach($s as $r)$o.='<option value="'.$r['id'].'" '.((string)$r['id']===(string)$selected?'selected':'').'>'.e($r[$label]).'</option>';return $o;}
function modal(string $id,string $title,string $body):void{echo '<div class="modal fade" id="'.$id.'"><div class="modal-dialog modal-lg"><form method="post" class="modal-content"><div class="modal-header"><h5>'.$title.'</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">'.csrf_field().$body.'</div><div class="modal-footer"><button class="btn btn-primary">Simpan</button></div></form></div></div>';}
function csrf_field():string{return '<input type="hidden" name="csrf" value="'.csrf().'">';}
function vendor_asset(string $local,string $cdn):string{return is_file(dirname(__DIR__).'/'.$local)?$local:$cdn;}
function empty_state(string $title,string $message='',string $icon='bi-inbox'):void{echo '<div class="empty-state"><i class="bi '.$icon.'"></i><div><b>'.e($title).'</b>'.($message!==''?'<small>'.e($message).'</small>':'').'</div></div>';}
if(!$u): ?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login | <?=APP_NAME?></title><link href="<?=vendor_asset('assets/vendor/inter/inter.css','https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap')?>" rel="stylesheet"><link href="<?=vendor_asset('assets/vendor/bootstrap-icons/bootstrap-icons.css','https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css')?>" rel="stylesheet"><link href="assets/css/style.css?v=<?=md5_file('assets/css/style.css')?>" rel="stylesheet"></head><body class="login-page"><div class="login-left"><div class="login-left-inner"><div class="login-brand"><i class="bi bi-mortarboard-fill"></i> <span>CBT</span>School</div><h1>Sistem Ujian<br><span>Berbasis Komputer</span></h1><p>Kelola ujian, bank soal, dan penilaian siswa dalam satu platform terintegrasi.</p><div class="login-features"><div class="login-feature"><i class="bi bi-shield-check"></i><span>Keamanan data terjamin</span></div><div class="login-feature"><i class="bi bi-lightning-charge"></i><span>Penilaian otomatis</span></div><div class="login-feature"><i class="bi bi-graph-up"></i><span>Statistik real-time</span></div></div></div><div class="login-left-footer">&copy; <?=date('Y')?> <?=APP_NAME?></div></div><div class="login-right"><main class="login-card"><div class="login-card-head"><div class="login-logo-sm"><i class="bi bi-mortarboard-fill"></i></div><h2>Selamat Datang</h2><p>Masuk ke akun Anda untuk melanjutkan</p></div><?php if($flash):?><div class="alert alert-<?=$flash[0]?>"><i class="bi bi-exclamation-circle"></i> <?=e($flash[1])?></div><?php endif?><form method="post" action="?action=login" class="login-form"><?=csrf_field()?><div class="field"><label for="email">NISN / Email</label><div class="field-input"><i class="bi bi-person"></i><input id="email" required type="text" name="email" placeholder="NISN atau email"></div></div><div class="field"><label for="password">Password</label><div class="field-input"><i class="bi bi-lock"></i><input id="password" required type="password" name="password" placeholder="Masukkan password"></div></div><button type="submit" class="btn-login">Masuk <i class="bi bi-arrow-right"></i></button></form></main></div><script src="assets/js/app.js?v=<?=filemtime('assets/js/app.js')?>"></script></body></html>
<?php else:
$nav=['dashboard'=>['Dashboard','bi-grid-1x2'],'users'=>['Kelola User','bi-people'],'manage'=>['Data Master','bi-database'],'questions'=>['Bank Soal','bi-patch-question'],'exams'=>['Ujian & Jadwal','bi-calendar2-check'],'grading'=>['Koreksi Essay','bi-check2-square'],'reports'=>['Laporan Nilai','bi-bar-chart'],'monitoring'=>['Monitoring','bi-camera-video'],'student'=>['Ujian Saya','bi-pencil-square'],'profile'=>['Profil','bi-person-gear'],'settings'=>['Pengaturan','bi-gear'],'logs'=>['Log Aktivitas','bi-clock-history']];
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e(ucfirst($page))?> | <?=APP_NAME?></title><link href="<?=vendor_asset('assets/vendor/inter/inter.css','https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap')?>" rel="stylesheet"><link href="<?=vendor_asset('assets/vendor/bootstrap/bootstrap.min.css','https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css')?>" rel="stylesheet"><link href="<?=vendor_asset('assets/vendor/bootstrap-icons/bootstrap-icons.css','https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css')?>" rel="stylesheet"><link href="<?=vendor_asset('assets/vendor/datatables/dataTables.bootstrap5.css','https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css')?>" rel="stylesheet"><link href="<?=vendor_asset('assets/vendor/summernote/summernote-bs5.min.css','https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.css')?>" rel="stylesheet"><link href="assets/css/style.css?v=<?=filemtime('assets/css/style.css')?>" rel="stylesheet"></head><body><a href="#main-content" class="skip-link">Langsung ke konten</a>
<aside class="sidebar" id="sidebar">
  <a class="logo" href="?page=<?=default_page_for_user($u)?>"><i class="bi bi-mortarboard-fill"></i> CBT<span>School</span></a>
  <div class="user-mini">
    <div class="avatar"><?=strtoupper($u['name'][0])?></div>
    <div><b><?=e($u['name'])?></b><small><?=e($u['role'])?></small></div>
  </div>
  <nav>
    <?php foreach($nav as $k=>$v):
      if($k==='student' && $u['role']!=='Siswa') continue;
      if($k==='grading' && $u['role']==='Siswa') continue;
      if(in_array($k,['monitoring']) && in_array($u['role'],['Siswa'])) continue;
      if(in_array($k,['settings','users','manage']) && $u['role']!=='Admin') continue;
      if(in_array($k,['questions','exams','reports','grading','logs']) && $u['role']==='Siswa') continue;
      if($u['role']==='Proktor' && !in_array($k,['dashboard','monitoring','profile'])) continue;
    ?>
    <a class="<?=($page===$k?'active':'')?>" href="?page=<?=$k?>"><i class="bi <?=$v[1]?>"></i><?=$v[0]?></a>
    <?php endforeach?>
  </nav>
  <a class="logout" href="?action=logout"><i class="bi bi-box-arrow-left"></i> Keluar</a>
</aside>
<div class="sidebar-backdrop" onclick="document.querySelector('.sidebar').classList.remove('open')"></div>
<main class="content" id="main-content">
  <header>
    <button class="btn d-md-none" onclick="var s=document.querySelector('.sidebar');s.classList.toggle('open');this.setAttribute('aria-expanded',s.classList.contains('open'))" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="sidebar"><i class="bi bi-list"></i></button>
    <div><h4><?=e($nav[$page][0]??'Halaman')?></h4><small><?=date('l, d F Y')?></small></div>
    <div class="ms-auto d-flex align-items-center gap-2"><a href="#" id="darkModeToggle" title="Tema Gelap"><i class="bi bi-moon-stars"></i></a><div class="dropdown" id="notifDropdown"><a class="position-relative" data-bs-toggle="dropdown"><i class="bi bi-bell"></i><span class="notif-badge" id="notifCount">0</span></a><div class="dropdown-menu dropdown-menu-end notif-menu" style="width:320px;max-height:400px;overflow-y:auto"><div class="d-flex justify-content-between px-3 py-2 border-bottom"><b>Notifikasi</b><a href="?action=mark_all_read" class="small">Tandai dibaca</a></div><div id="notifList"><div class="px-3 py-2 text-muted small">Memuat...</div></div></div></div></div>
  </header>
  <?php if($flash):?><div class="alert alert-<?=$flash[0]?> alert-dismissible fade show"><?=e($flash[1])?><button class="btn-close" data-bs-dismiss="alert"></button></div><?php endif?>

<?php if($page==='dashboard'):
  $tid=$u['role']==='Guru'?q('SELECT id FROM teachers WHERE user_id=?',[$u['id']])->fetchColumn():null;
  if($u['role']==='Guru')$upcomingLessons=q("SELECT e.title,s.name subject,c.name class_name,sc.starts_at FROM schedules sc JOIN exams e ON e.id=sc.exam_id JOIN subjects s ON s.id=e.subject_id JOIN classes c ON c.id=sc.class_id WHERE e.teacher_id=? AND sc.starts_at>NOW() ORDER BY sc.starts_at LIMIT 5",[$tid])->fetchAll(PDO::FETCH_ASSOC);
  else $upcomingLessons=q("SELECT e.title,s.name subject,c.name class_name,sc.starts_at FROM schedules sc JOIN exams e ON e.id=sc.exam_id JOIN subjects s ON s.id=e.subject_id JOIN classes c ON c.id=sc.class_id WHERE sc.starts_at>NOW() ORDER BY sc.starts_at LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
  if($u['role']==='Siswa'){
    $stats=['Ujian Aktif'=>q("SELECT COUNT(*) FROM schedules sc JOIN students s ON s.class_id=sc.class_id WHERE s.user_id=? AND NOW() BETWEEN sc.starts_at AND sc.ends_at",[$u['id']])->fetchColumn(),'Riwayat'=>q('SELECT COUNT(*) FROM exam_results r JOIN students s ON s.id=r.student_id WHERE s.user_id=?',[$u['id']])->fetchColumn()];
  } elseif($u['role']==='Guru') {
    $stats=['Soal Saya'=>q('SELECT COUNT(*) FROM questions WHERE teacher_id=?',[$tid])->fetchColumn(),'Ujian Saya'=>q('SELECT COUNT(*) FROM exams WHERE teacher_id=?',[$tid])->fetchColumn(),'Jadwal Aktif'=>q("SELECT COUNT(*) FROM schedules sc JOIN exams e ON e.id=sc.exam_id WHERE e.teacher_id=? AND NOW() BETWEEN sc.starts_at AND sc.ends_at",[$tid])->fetchColumn(),'Total Siswa'=>q('SELECT COUNT(*) FROM students')->fetchColumn()];
    $teacherBenefits=[
      ['Koreksi Essay',q("SELECT COUNT(DISTINCT r.id) FROM exam_results r JOIN exams e ON e.id=r.exam_id JOIN exam_questions eq ON eq.exam_id=e.id JOIN questions qx ON qx.id=eq.question_id WHERE e.teacher_id=? AND r.status='submitted' AND qx.type='essay'",[$tid])->fetchColumn(),'Jawaban essay yang perlu dinilai.','bi-check2-square','?page=grading'],
      ['Peserta Aktif',q("SELECT COUNT(*) FROM exam_results r JOIN exams e ON e.id=r.exam_id WHERE e.teacher_id=? AND r.status='in_progress'",[$tid])->fetchColumn(),'Siswa yang sedang mengerjakan ujian.','bi-camera-video','?page=monitoring'],
      ['Nilai Rata-rata',q("SELECT ROUND(AVG(r.score),1) FROM exam_results r JOIN exams e ON e.id=r.exam_id WHERE e.teacher_id=? AND r.score IS NOT NULL",[$tid])->fetchColumn()?:0,'Ringkasan performa ujian yang sudah dinilai.','bi-bar-chart','?page=reports'],
      ['Bank Soal',q('SELECT COUNT(*) FROM questions WHERE teacher_id=?',[$tid])->fetchColumn(),'Soal siap dipakai untuk menyusun ujian.','bi-patch-question','?page=questions']
    ];
  } else {
    $stats=['Total Siswa'=>q('SELECT COUNT(*) FROM students')->fetchColumn(),'Total Guru'=>q('SELECT COUNT(*) FROM teachers')->fetchColumn(),'Bank Soal'=>q('SELECT COUNT(*) FROM questions')->fetchColumn(),'Ujian'=>q('SELECT COUNT(*) FROM exams')->fetchColumn()];
  }
?>
<section class="row g-3 mb-4">
  <?php foreach($stats as $n=>$v):?>
  <div class="col-sm-6 col-xl-3"><div class="stat-card"><div><small><?=$n?></small><h2><?=$v?></h2></div><i class="bi bi-graph-up-arrow"></i></div></div>
  <?php endforeach?>
</section>
<section class="panel mb-3"><h5>Selamat datang, <?=e($u['name'])?></h5><p class="mb-0 text-muted">Gunakan menu di samping untuk mengelola dan memantau pelaksanaan ujian.</p></section>
<?php if($u['role']==='Guru'):?>
<section class="panel mb-3">
  <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
    <h5 class="mb-0">Benefit Guru</h5>
    <a href="?page=exams" class="btn btn-sm btn-primary"><i class="bi bi-calendar2-plus"></i> Buat Ujian</a>
  </div>
  <div class="benefit-grid">
    <?php foreach($teacherBenefits as $b):?>
    <a class="benefit-item" href="<?=$b[4]?>">
      <i class="bi <?=$b[3]?>"></i>
      <div><b><?=e($b[0])?></b><strong><?=e((string)$b[1])?></strong><small><?=e($b[2])?></small></div>
    </a>
    <?php endforeach?>
  </div>
</section>
<?php endif?>
<section class="panel">
  <h5>Pelajaran Segera Hadir</h5>
  <?php if($upcomingLessons): foreach($upcomingLessons as $x):?>
  <div class="border-bottom py-2 d-flex justify-content-between gap-3 align-items-center">
    <div><b><?=e($x['subject'])?></b><small class="d-block text-muted"><?=e($x['title'])?> - <?=e($x['class_name'])?></small></div>
    <span class="badge badge-warning"><?=date('d/m H:i',strtotime($x['starts_at']))?></span>
  </div>
  <?php endforeach; else:?>
  <?php empty_state('Belum ada pelajaran terjadwal','Jadwal ujian/pelajaran mendatang akan muncul di sini.','bi-calendar-event');?>
  <?php endif?>
</section>

<?php elseif($page==='manage'):
  role('Admin');
  $entity=$_GET['entity']??'classes';
  $cfg=['classes'=>['Kelas',['name'=>'Nama Kelas','major_id'=>'Jurusan','academic_year_id'=>'Tahun Ajaran','active'=>'Status']],'subjects'=>['Mata Pelajaran',['name'=>'Nama','code'=>'Kode','teacher_id'=>'Guru','active'=>'Status']],'majors'=>['Jurusan',['name'=>'Nama','code'=>'Kode','active'=>'Status']],'academic_years'=>['Tahun Ajaran',['name'=>'Tahun','active'=>'Status']]];
  if(!isset($cfg[$entity]))$entity='classes';
  [$title,$fields]=$cfg[$entity];
  if($entity==='classes'){$rows=q('SELECT c.*,m.name major_name,ay.name academic_year_name FROM classes c LEFT JOIN majors m ON m.id=c.major_id LEFT JOIN academic_years ay ON ay.id=c.academic_year_id ORDER BY c.id DESC')->fetchAll(PDO::FETCH_ASSOC);}else{$rows=q("SELECT * FROM $entity ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);}
?>
<div class="d-flex gap-2 flex-wrap mb-3">
  <?php foreach($cfg as $k=>$x):?>
  <a href="?page=manage&entity=<?=$k?>" class="btn btn-sm <?=$entity===$k?'btn-primary':'btn-light'?>"><?=$x[0]?></a>
  <?php endforeach?>
  <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#form"><i class="bi bi-plus"></i> Tambah <?=$title?></button>
</div>
<div class="panel table-responsive">
  <?php if(!$rows)empty_state('Belum ada '.$title,'Klik tombol tambah untuk membuat data pertama.','bi-database-add');?>
  <table class="table data-table">
    <thead><tr><th>ID</th><?php foreach($fields as $l):?><th><?=$l?></th><?php endforeach?><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach($rows as $r):?>
    <tr>
      <td><?=$r['id']?></td>
      <?php foreach(array_keys($fields) as $f):?><td><?php if($f==='active'):?><span class="badge <?=$r['active']?'badge-active':'badge-inactive'?>"><?=$r['active']?'Aktif':'Nonaktif'?></span><?php else:?><?=$f==='major_id'?e($r['major_name']??'-'):($f==='academic_year_id'?e($r['academic_year_name']??'-'):e((string)($r[$f]??'')))?><?php endif?></td><?php endforeach?>
      <td class="text-nowrap">
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit_<?=$r['id']?>" title="Edit"><i class="bi bi-pencil"></i></button>
        <form method="post" action="?action=toggle_active" style="display:inline" onsubmit="return confirm('Ubah status data ini?')"><?=csrf_field()?><input type="hidden" name="entity" value="<?=$entity?>"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="btn btn-sm btn-outline-secondary" title="Aktif/Nonaktif"><i class="bi bi-power"></i></button></form>
        <form method="post" action="?action=delete" style="display:inline" onsubmit="return confirm('Hapus data ini?')"><?=csrf_field()?><input type="hidden" name="entity" value="<?=$entity?>"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button></form>
      </td>
    </tr>
    <?php endforeach?>
    </tbody>
  </table>
</div>
<?php ob_start();?>
<input type="hidden" name="entity" value="<?=$entity?>">
<input type="hidden" name="action" value="save">
<?php foreach($fields as $f=>$l):?>
<?php if(in_array($entity,['subjects','majors'],true)&&$f==='code'):?><input type="hidden" name="code" value=""><?php continue; endif?>
<label class="form-label mt-2"><?=$l?></label>
<?php if($f==='major_id'):?>
<select class="form-select" name="major_id"><option value="">-</option><?=opts('majors')?></select>
<?php elseif($f==='academic_year_id'):?>
<select class="form-select" name="academic_year_id"><option value="">-</option><?=opts('academic_years')?></select>
<?php elseif($f==='teacher_id'):?>
<select class="form-select" name="teacher_id"><option value="">-</option><?=opts('teachers')?></select>
<?php elseif($f==='active'):?>
<select class="form-select" name="active"><option value="1" selected>Aktif</option><option value="0">Nonaktif</option></select>
<?php else:?>
<input name="<?=$f?>" class="form-control <?=$entity==='classes'&&$f==='name'?'grade-roman':''?>" required>
<?php endif;?>
<?php endforeach?>
<?php modal('form','Tambah '.$title,ob_get_clean());?>
<?php foreach($rows as $r):?>
<?php ob_start();?>
<input type="hidden" name="entity" value="<?=$entity?>">
<input type="hidden" name="action" value="save">
<input type="hidden" name="id" value="<?=$r['id']?>">
<?php foreach($fields as $f=>$l):?>
<?php if(in_array($entity,['subjects','majors'],true)&&$f==='code'):?><input type="hidden" name="code" value="<?=e((string)($r[$f]??''))?>"><?php continue; endif?>
<label class="form-label mt-2"><?=$l?></label>
<?php if($f==='major_id'):?>
<select class="form-select" name="major_id"><option value="">-</option><?=opts('majors',$r[$f]??'')?></select>
<?php elseif($f==='academic_year_id'):?>
<select class="form-select" name="academic_year_id"><option value="">-</option><?=opts('academic_years',$r[$f]??'')?></select>
<?php elseif($f==='teacher_id'):?>
<select class="form-select" name="teacher_id"><option value="">-</option><?=opts('teachers',$r[$f]??'')?></select>
<?php elseif($f==='active'):?>
<select class="form-select" name="active"><option value="1" <?=$r[$f]??''=='1'?'selected':''?>>Ya</option><option value="0" <?=$r[$f]??''=='0'?'selected':''?>>Tidak</option></select>
<?php else:?>
<input name="<?=$f?>" class="form-control <?=$entity==='classes'&&$f==='name'?'grade-roman':''?>" value="<?=e((string)($r[$f]??''))?>" required>
<?php endif;?>
<?php endforeach?>
<?php modal('edit_'.$r['id'],'Edit '.$title,ob_get_clean());?>
<?php endforeach?>

<?php elseif($page==='users'):
  role('Admin');
  $rows=q('SELECT u.*,r.name role FROM users u JOIN roles r ON r.id=u.role_id ORDER BY u.id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#userform"><i class="bi bi-person-plus"></i> Tambah User</button>
<button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#importModal"><i class="bi bi-upload"></i> Import CSV</button>
<a class="btn btn-outline-secondary mb-3" href="?action=backup_db"><i class="bi bi-database-down"></i> Backup Database</a>
<div class="panel table-responsive">
  <?php if(!$rows)empty_state('Belum ada user','Tambahkan user satu per satu atau import dari CSV.','bi-people');?>
  <table class="table data-table">
    <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach($rows as $r):?>
    <tr>
      <td><?=e($r['name'])?></td>
      <td><?=e($r['email'])?></td>
      <td><span class="badge text-bg-primary"><?=$r['role']?></span></td>
      <td>
        <form method="post" action="?action=toggle_active" style="display:inline" onsubmit="return confirm('Ubah status?')">
          <?=csrf_field()?>
          <input type="hidden" name="entity" value="users">
          <input type="hidden" name="id" value="<?=$r['id']?>">
          <button class="btn btn-sm badge <?=e($r['active']?'badge-active':'badge-inactive')?>"><?=$r['active']?'Aktif':'Nonaktif'?></button>
        </form>
      </td>
      <td class="text-nowrap">
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userform_<?=$r['id']?>" title="Edit"><i class="bi bi-pencil"></i></button>
        <form method="post" action="?action=reset_password" style="display:inline" onsubmit="return confirm('Reset password user ini?')"><?=csrf_field()?><input type="hidden" name="id" value="<?=$r['id']?>"><button class="btn btn-sm btn-outline-warning" title="Reset Password"><i class="bi bi-key"></i></button></form>
        <form method="post" action="?action=delete" style="display:inline" onsubmit="return confirm('Hapus user ini?')"><?=csrf_field()?><input type="hidden" name="entity" value="users"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button></form>
      </td>
    </tr>
    <?php endforeach?>
    </tbody>
  </table>
</div>
<?php ob_start();?>
<input type="hidden" name="action" value="user_save">
<div class="quick-form">
<div class="form-section-title"><i class="bi bi-person-plus"></i> Data Utama</div>
<label class="form-label">Nama</label>
<input name="name" required class="form-control" placeholder="Nama lengkap">
<label class="form-label mt-2">Role</label>
<select name="role_id" class="form-select role-select">
  <option value="3" selected>Siswa</option>
  <option value="2">Guru</option>
  <option value="1">Admin</option>
</select>
<div class="student-fields">
  <label class="form-label mt-2">NISN</label>
  <input name="nisn" class="form-control" placeholder="Nomor induk siswa">
  <label class="form-label mt-2">Kelas</label>
  <select name="class_id" class="form-select"><option value="">Pilih kelas</option><?=opts('classes')?></select>
</div>
<div class="non-student-fields d-none">
  <label class="form-label mt-2">Email</label>
  <input name="email" type="text" class="form-control" placeholder="email@sekolah.id">
</div>
<label class="form-label mt-2">Password</label>
<div class="input-group"><input name="password" type="text" required class="form-control password-input" placeholder="Minimal 6 karakter"><button type="button" class="btn btn-outline-primary generate-password">Generate</button></div>
<div class="field-note">Untuk siswa, email boleh kosong. Login siswa bisa memakai NISN.</div>
</div>
<?php modal('userform','Tambah User',ob_get_clean());?>

<div class="modal fade" id="importModal"><div class="modal-dialog"><form method="post" action="?action=import_users" enctype="multipart/form-data" class="modal-content"><div class="modal-header"><h5>Import User dari CSV</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><?=csrf_field()?><div class="mb-3"><label class="form-label">File CSV</label><input type="file" name="csv_file" accept=".csv" required class="form-control"></div><div class="mb-3"><label class="form-label">Role</label><select name="role_id" class="form-select"><option value="2">Guru</option><option value="3" selected>Siswa</option><option value="1">Admin</option></select></div><div class="mb-3"><label class="form-label">Kelas (untuk siswa)</label><select name="class_id" class="form-select"><option value="">-</option><?=opts('classes')?></select></div><div class="small text-muted"><strong>Format CSV:</strong> baris pertama header <code>nama,email,password,nisn_nip</code>. Email boleh kosong (auto-generate untuk siswa). NISN/NIP boleh kosong (auto-generate).<br><a href="data:text/csv;charset=utf-8,nama,email,password,nisn_nip%0ABudi Santoso,,rahasia123," download="template-import-user.csv">Download Template CSV</a></div></div><div class="modal-footer"><button class="btn btn-primary">Import</button></div></form></div></div>

<?php foreach($rows as $r):
  $st=$r['role_id']==3?q('SELECT nis,class_id FROM students WHERE user_id=?',[$r['id']])->fetch(PDO::FETCH_ASSOC):null;
?>
<?php ob_start();?>
<input type="hidden" name="action" value="user_save">
<input type="hidden" name="id" value="<?=$r['id']?>">
<div class="quick-form">
<div class="form-section-title"><i class="bi bi-person-gear"></i> Data User</div>
<label class="form-label">Nama</label>
<input name="name" required class="form-control" value="<?=e($r['name'])?>">
<label class="form-label mt-2">Role</label>
<select name="role_id" class="form-select role-select">
  <option value="3" <?=$r['role']==='Siswa'?'selected':''?>>Siswa</option>
  <option value="2" <?=$r['role']==='Guru'?'selected':''?>>Guru</option>
  <option value="1" <?=$r['role']==='Admin'?'selected':''?>>Admin</option>
</select>
<div class="student-fields">
  <label class="form-label mt-2">NISN</label>
  <input name="nisn" class="form-control" value="<?=e($st['nis']??'')?>">
  <label class="form-label mt-2">Kelas</label>
  <select name="class_id" class="form-select"><option value="">-</option><?=opts('classes',$st['class_id']??'')?></select>
</div>
<div class="non-student-fields">
  <label class="form-label mt-2">Email</label>
  <input name="email" type="text" class="form-control" value="<?=e($r['email'])?>" placeholder="email@sekolah.id">
</div>
<label class="form-label mt-2">Password</label>
<div class="input-group"><input name="password" type="text" class="form-control password-input" placeholder="Kosongkan jika tidak diubah"><button type="button" class="btn btn-outline-primary generate-password">Generate</button></div>
<label class="form-label mt-2">Status</label>
<select name="active" class="form-select">
  <option value="1" <?=$r['active']?'selected':''?>>Aktif</option>
  <option value="0" <?=!$r['active']?'selected':''?>>Nonaktif</option>
</select>
</div>
<?php modal('userform_'.$r['id'],'Edit User',ob_get_clean());?>
<?php endforeach?>

<?php elseif($page==='questions'):
  role('Admin','Guru');
  $tid=$u['role']==='Guru'?q('SELECT id FROM teachers WHERE user_id=?',[$u['id']])->fetchColumn():null;
  if($tid){
    $rows=q('SELECT q.*,s.name subject FROM questions q JOIN subjects s ON s.id=q.subject_id WHERE q.teacher_id=? ORDER BY q.id DESC',[$tid])->fetchAll(PDO::FETCH_ASSOC);
  } else {
    $rows=q('SELECT q.*,s.name subject FROM questions q JOIN subjects s ON s.id=q.subject_id ORDER BY q.id DESC')->fetchAll(PDO::FETCH_ASSOC);
  }
?>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#qform"><i class="bi bi-plus"></i> Buat Soal</button>
<div class="panel table-responsive">
  <?php if(!$rows)empty_state('Belum ada soal','Buat soal baru sebelum menyusun ujian.','bi-patch-question');?>
  <table class="table data-table">
    <thead><tr><th>Mapel</th><th>Tipe</th><th>Pertanyaan</th><th>Bobot</th><th></th></tr></thead>
    <tbody>
    <?php foreach($rows as $r):?>
    <tr>
      <td><?=e($r['subject'])?></td>
      <td><?=e($r['type'])?></td>
      <td><?=e(mb_strimwidth($r['question'],0,80,'...'))?></td>
      <td><?=$r['weight']?></td>
      <td><form method="post" action="?action=delete" style="display:inline" onsubmit="return confirm('Hapus soal?')"><?=csrf_field()?><input type="hidden" name="entity" value="questions"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#qedit_<?=$r['id']?>" title="Edit"><i class="bi bi-pencil"></i></button>
      </td>
    </tr>
    <?php endforeach?>
    </tbody>
  </table>
</div>
<?php ob_start();?>
<input type="hidden" name="action" value="question_save">
<div class="quick-form">
<div class="form-section-title"><i class="bi bi-patch-question"></i> Soal Baru</div>
<label class="form-label">Mata Pelajaran</label>
<select name="subject_id" required class="form-select"><?=opts('subjects')?></select>
<label class="form-label mt-2">Jenis</label>
<select name="type" id="qtype" class="form-select"><option value="multiple">Pilihan Ganda</option><option value="essay">Essay</option></select>
<label class="form-label mt-2">Pertanyaan</label>
<textarea name="question" required class="form-control summernote"></textarea>
<div id="choices">
  <label class="form-label mt-3">Pilihan Jawaban</label>
  <div class="field-note mb-2">Pilih radio di kiri untuk menandai jawaban benar. Pilihan E boleh dikosongkan.</div>
  <?php foreach(['A','B','C','D','E'] as $l):?>
  <div class="input-group mb-2"><span class="input-group-text"><input type="radio" name="correct" value="<?=$l?>" <?=$l==='A'?'checked':''?>></span><span class="input-group-text"><?=$l?></span><input name="choice_<?=$l?>" class="form-control" placeholder="Jawaban <?=$l?><?=$l==='E'?' (opsional)':''?>" <?=$l!=='E'?'required':''?>></div>
  <?php endforeach?>
</div>
<details class="advanced-options mt-3"><summary>Opsi lanjutan</summary><label class="form-label mt-2">Bobot</label><input name="weight" type="number" step=".01" value="1" class="form-control"></details>
</div>
<?php modal('qform','Buat Soal',ob_get_clean());?>

<?php foreach($rows as $r):
  $choices=q('SELECT * FROM choices WHERE question_id=? ORDER BY label',[$r['id']])->fetchAll(PDO::FETCH_ASSOC);
  $correct=q('SELECT label FROM choices WHERE question_id=? AND is_correct=1',[$r['id']])->fetchColumn();
?>
<?php ob_start();?>
<input type="hidden" name="action" value="question_save">
<input type="hidden" name="id" value="<?=$r['id']?>">
<div class="quick-form">
<div class="form-section-title"><i class="bi bi-pencil-square"></i> Edit Soal</div>
<label class="form-label">Mata Pelajaran</label>
<select name="subject_id" required class="form-select"><?=opts('subjects',$r['subject_id'])?></select>
<label class="form-label mt-2">Jenis</label>
<select name="type" class="form-select qtype-edit" data-target="choices_<?=$r['id']?>"><option value="multiple" <?=$r['type']==='multiple'?'selected':''?>>Pilihan Ganda</option><option value="essay" <?=$r['type']==='essay'?'selected':''?>>Essay</option></select>
<label class="form-label mt-2">Pertanyaan</label>
<textarea name="question" required class="form-control summernote"><?=e($r['question'])?></textarea>
<div id="choices_<?=$r['id']?>" style="<?=$r['type']==='essay'?'display:none':''?>">
  <label class="form-label mt-3">Pilihan jawaban (centang jawaban benar)</label>
  <?php foreach(['A','B','C','D','E'] as $l):
    $txt=''; $chk='';
    foreach($choices as $c){if($c['label']===$l){$txt=$c['choice_text'];if($c['is_correct'])$chk='checked';break;}}
  ?>
  <div class="input-group mb-2"><span class="input-group-text"><input type="radio" name="correct" value="<?=$l?>" <?=$chk?:($l==='A'?'checked':'')?>></span><span class="input-group-text"><?=$l?></span><input name="choice_<?=$l?>" class="form-control" value="<?=e($txt)?>" <?=$l!=='E'?'required':''?>></div>
  <?php endforeach?>
</div>
<details class="advanced-options mt-3"><summary>Opsi lanjutan</summary><label class="form-label mt-2">Bobot</label><input name="weight" type="number" step=".01" value="<?=$r['weight']?>" class="form-control"></details>
</div>
<?php modal('qedit_'.$r['id'],'Edit Soal',ob_get_clean());?>
<?php endforeach?>

<?php elseif($page==='exams'):
  role('Admin','Guru');
  $tid=$u['role']==='Guru'?q('SELECT id FROM teachers WHERE user_id=?',[$u['id']])->fetchColumn():null;
  if($tid){
    $rows=q('SELECT e.*,s.name subject FROM exams e JOIN subjects s ON s.id=e.subject_id WHERE e.teacher_id=? ORDER BY e.id DESC',[$tid])->fetchAll(PDO::FETCH_ASSOC);
  } else {
    $rows=q('SELECT e.*,s.name subject FROM exams e JOIN subjects s ON s.id=e.subject_id ORDER BY e.id DESC')->fetchAll(PDO::FETCH_ASSOC);
  }
?>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#eform"><i class="bi bi-plus"></i> Buat Ujian</button>
<div class="panel table-responsive">
  <?php if(!$rows)empty_state('Belum ada ujian','Buat ujian lalu atur jadwal untuk kelas.','bi-calendar2-plus');?>
  <table class="table data-table">
    <thead><tr><th>Ujian</th><th>Mapel</th><th>Token</th><th>Durasi</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach($rows as $r):?>
    <tr>
      <td><?=e($r['title'])?></td>
      <td><?=e($r['subject'])?></td>
      <td><code><?=$r['token']?></code></td>
      <td><?=$r['duration']?> menit</td>
      <td>
        <form method="post" action="?action=toggle_active" style="display:inline" onsubmit="return confirm('Ubah status ujian?')">
          <?=csrf_field()?>
          <input type="hidden" name="entity" value="exams">
          <input type="hidden" name="id" value="<?=$r['id']?>">
          <button class="btn btn-sm badge <?=e($r['active']?'badge-active':'badge-inactive')?>"><?=$r['active']?'Aktif':'Nonaktif'?></button>
        </form>
      </td>
      <td>
        <form method="post" action="?action=delete" style="display:inline" onsubmit="return confirm('Hapus ujian ini?')"><?=csrf_field()?><input type="hidden" name="entity" value="exams"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#eedit_<?=$r['id']?>" title="Edit"><i class="bi bi-pencil"></i></button>
        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#sched_<?=$r['id']?>" title="Jadwal"><i class="bi bi-clock-history"></i></button>
      </td>
    </tr>
    <?php endforeach?>
    </tbody>
  </table>
</div>
<?php foreach($rows as $r): $schedRows=q('SELECT sc.*,c.name class_name FROM schedules sc JOIN classes c ON c.id=sc.class_id WHERE sc.exam_id=? ORDER BY sc.starts_at',[$r['id']])->fetchAll(PDO::FETCH_ASSOC);?>
<div class="modal fade" id="sched_<?=$r['id']?>"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5>Jadwal - <?=e($r['title'])?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><?php if($schedRows):?><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Kelas</th><th>Mulai</th><th>Selesai</th><th></th></tr></thead><tbody><?php foreach($schedRows as $scx):?><tr><td><?=e($scx['class_name'])?></td><td><?=e($scx['starts_at'])?></td><td><?=e($scx['ends_at'])?></td><td><form method="post" action="?action=schedule_delete" onsubmit="return confirm('Hapus jadwal ini?')"><?=csrf_field()?><input type="hidden" name="id" value="<?=$scx['id']?>"><button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr><?php endforeach?></tbody></table></div><?php else:?><?php empty_state('Belum ada jadwal','Tambahkan jadwal dari form edit ujian.','bi-calendar-x');?><?php endif?></div></div></div></div>
<?php endforeach?>
<?php ob_start();
if($tid){
  $questions=q('SELECT q.id,q.question,s.name FROM questions q JOIN subjects s ON s.id=q.subject_id WHERE q.teacher_id=? ORDER BY q.id DESC',[$tid])->fetchAll(PDO::FETCH_ASSOC);
} else {
  $questions=q('SELECT q.id,q.question,s.name FROM questions q JOIN subjects s ON s.id=q.subject_id ORDER BY q.id DESC')->fetchAll(PDO::FETCH_ASSOC);
}
?>
<input type="hidden" name="action" value="exam_save">
<div class="quick-form exam-builder">
<div class="form-section-title"><i class="bi bi-calendar2-plus"></i> Informasi Ujian</div>
<label class="form-label">Judul Ujian</label>
<input name="title" required class="form-control" placeholder="Contoh: Ulangan Harian Basis Data">
<label class="form-label mt-2">Mata Pelajaran</label>
<select name="subject_id" class="form-select"><?=opts('subjects')?></select>
<div class="row mt-2">
  <div class="col-md-6"><label class="form-label">Durasi</label><div class="input-group"><input name="duration" type="number" value="60" required class="form-control"><span class="input-group-text">menit</span></div></div>
  <div class="col-md-6"><label class="form-label">Token</label><input name="token" class="form-control" placeholder="Kosongkan untuk otomatis"></div>
</div>
<div class="form-section-title mt-3"><i class="bi bi-list-check"></i> Soal</div>
<div class="picker-actions"><button type="button" class="btn btn-sm btn-light select-all-questions">Pilih semua</button><button type="button" class="btn btn-sm btn-light clear-questions">Kosongkan</button></div>
<div class="question-picker">
<?php foreach($questions as $x):?>
<label class="d-flex align-items-center gap-2 mb-1"><input type="checkbox" name="questions[]" value="<?=$x['id']?>"><span class="flex-grow-1">[<?=e($x['name'])?>] <?=e(mb_strimwidth($x['question'],0,60,'...'))?></span><select name="question_section[<?=$x['id']?>]" class="form-select form-select-sm advanced-section-select" style="width:auto"><option value="0">-</option><option value="1">Bagian 1</option><option value="2">Bagian 2</option><option value="3">Bagian 3</option><option value="4">Bagian 4</option><option value="5">Bagian 5</option></select></label>
<?php endforeach?>
</div>
<div class="form-section-title mt-3"><i class="bi bi-clock"></i> Jadwal</div>
<div class="row mt-2">
  <div class="col-md-4"><label class="form-label">Kelas</label><select name="schedule_class" class="form-select"><option value="">Pilih kelas</option><?=opts('classes')?></select></div>
  <div class="col-md-4"><label class="form-label">Mulai</label><input name="starts_at" type="datetime-local" class="form-control"></div>
  <div class="col-md-4"><label class="form-label">Selesai</label><input name="ends_at" type="datetime-local" class="form-control"></div>
</div>
<details class="advanced-options mt-3"><summary>Opsi lanjutan</summary>
<label class="form-label mt-2">Deskripsi</label>
<textarea name="description" class="form-control"></textarea>
<div class="mt-3">
  <label><input type="checkbox" name="random_questions" checked> Acak urutan soal</label>
  <label class="ms-3"><input type="checkbox" name="random_choices" checked> Acak pilihan</label>
  <label class="ms-3"><input type="checkbox" name="show_score"> Tampilkan nilai</label>
  <div class="mt-2"><label class="form-label">Jumlah soal per siswa (0=semua soal)</label><input name="random_questions_count" type="number" value="0" min="0" class="form-control" style="max-width:120px"></div>
</div>
<div class="mt-3"><label class="form-label">Kelompok Soal (opsional)</label>
<div id="sectionList">
  <div class="section-entry d-flex gap-2 mb-1"><input name="section_title[]" class="form-control" placeholder="Nama bagian (misal: PG)" style="max-width:200px"><input name="section_timer[]" type="number" class="form-control" placeholder="Timer (menit)" style="max-width:120px" min="0"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button></div>
</div>
<button type="button" class="btn btn-sm btn-outline-primary mt-1" onclick="var e=document.querySelector('#sectionList .section-entry');var c=e.cloneNode(true);c.querySelectorAll('input').forEach(i=>i.value='');document.getElementById('sectionList').appendChild(c)"><i class="bi bi-plus"></i> Tambah Kelompok</button>
</div>
</details>
</div>
<?php modal('eform','Buat Ujian',ob_get_clean());?>

<?php foreach($rows as $r):
  $eq=q('SELECT question_id,section_id FROM exam_questions WHERE exam_id=?',[$r['id']])->fetchAll(PDO::FETCH_ASSOC);
  $eqFlat=array_column($eq,'question_id'); $eqSections=[]; foreach($eq as $e){$eqSections[$e['question_id']]=$e['section_id'];}
  $sc=q('SELECT * FROM schedules WHERE exam_id=? LIMIT 1',[$r['id']])->fetch(PDO::FETCH_ASSOC);
  $editSections=q('SELECT * FROM exam_sections WHERE exam_id=? ORDER BY ordering',[$r['id']])->fetchAll(PDO::FETCH_ASSOC);
  if($tid){$editQ=q('SELECT q.id,q.question,s.name FROM questions q JOIN subjects s ON s.id=q.subject_id WHERE q.teacher_id=? ORDER BY q.id DESC',[$tid])->fetchAll(PDO::FETCH_ASSOC);}
  else{$editQ=q('SELECT q.id,q.question,s.name FROM questions q JOIN subjects s ON s.id=q.subject_id ORDER BY q.id DESC')->fetchAll(PDO::FETCH_ASSOC);}
?>
<?php ob_start();?>
<input type="hidden" name="action" value="exam_save">
<input type="hidden" name="id" value="<?=$r['id']?>">
<label class="form-label">Judul Ujian</label>
<input name="title" required class="form-control" value="<?=e($r['title'])?>">
<label class="form-label mt-2">Mata Pelajaran</label>
<select name="subject_id" class="form-select"><?=opts('subjects',$r['subject_id'])?></select>
<label class="form-label mt-2">Deskripsi</label>
<textarea name="description" class="form-control"><?=e($r['description'])?></textarea>
<div class="row mt-2">
  <div class="col"><label class="form-label">Durasi (menit)</label><input name="duration" type="number" value="<?=$r['duration']?>" required class="form-control"></div>
  <div class="col"><label class="form-label">Token</label><input name="token" class="form-control" value="<?=e($r['token'])?>"></div>
</div>
<div class="mt-3">
  <label><input type="checkbox" name="random_questions" <?=$r['random_questions']?'checked':''?>> Acak urutan soal</label>
  <label class="ms-3"><input type="checkbox" name="random_choices" <?=$r['random_choices']?'checked':''?>> Acak pilihan</label>
  <label class="ms-3"><input type="checkbox" name="show_score" <?=$r['show_score']?'checked':''?>> Tampilkan nilai</label>
  <div class="mt-2"><label class="form-label">Jumlah soal per siswa (0=semua)</label><input name="random_questions_count" type="number" value="<?=$r['random_questions_count']?>" min="0" class="form-control" style="max-width:120px"></div>
</div>
<div class="mt-3"><label class="form-label">Kelompok Soal (opsional)</label>
<div id="sectionList_<?=$r['id']?>">
<?php if($editSections): foreach($editSections as $es):?>
  <div class="section-entry d-flex gap-2 mb-1"><input name="section_title[]" class="form-control" value="<?=e($es['title'])?>" placeholder="Nama bagian" style="max-width:200px"><input name="section_timer[]" type="number" class="form-control" value="<?=$es['timer']??''?>" placeholder="Timer (menit)" style="max-width:120px" min="0"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button></div>
<?php endforeach; else:?>
  <div class="section-entry d-flex gap-2 mb-1"><input name="section_title[]" class="form-control" placeholder="Nama bagian" style="max-width:200px"><input name="section_timer[]" type="number" class="form-control" placeholder="Timer (menit)" style="max-width:120px" min="0"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button></div>
<?php endif?>
</div>
<button type="button" class="btn btn-sm btn-outline-primary mt-1" onclick="var e=document.querySelector('#sectionList_<?=$r['id']?> .section-entry');var c=e.cloneNode(true);c.querySelectorAll('input').forEach(i=>i.value='');document.getElementById('sectionList_<?=$r['id']?>').appendChild(c)"><i class="bi bi-plus"></i> Tambah Kelompok</button>
</div>
<label class="form-label mt-3">Pilih Soal</label>
<div class="question-picker">
<?php foreach($editQ as $x): $secId=$eqSections[$x['id']]??null; $secNum=$secId?q('SELECT ordering+1 FROM exam_sections WHERE id=?',[$secId])->fetchColumn():0;?>
<label class="d-flex align-items-center gap-2 mb-1"><input type="checkbox" name="questions[]" value="<?=$x['id']?>" <?=in_array($x['id'],$eqFlat)?'checked':''?>><span class="flex-grow-1">[<?=e($x['name'])?>] <?=e(mb_strimwidth($x['question'],0,60,'...'))?></span><select name="question_section[<?=$x['id']?>]" class="form-select form-select-sm" style="width:auto"><option value="0">-</option><option value="1" <?=$secNum==1?'selected':''?>>Bagian 1</option><option value="2" <?=$secNum==2?'selected':''?>>Bagian 2</option><option value="3" <?=$secNum==3?'selected':''?>>Bagian 3</option><option value="4" <?=$secNum==4?'selected':''?>>Bagian 4</option><option value="5" <?=$secNum==5?'selected':''?>>Bagian 5</option></select></label>
<?php endforeach?>
</div>
<div class="row mt-3">
  <div class="col-md-4"><label class="form-label">Kelas</label><select name="schedule_class" class="form-select"><option value="">-</option><?=opts('classes',$sc['class_id']??'')?></select></div>
  <div class="col-md-4"><label class="form-label">Mulai</label><input name="starts_at" type="datetime-local" class="form-control" value="<?=isset($sc['starts_at'])?str_replace(' ','T',$sc['starts_at']):''?>"></div>
  <div class="col-md-4"><label class="form-label">Selesai</label><input name="ends_at" type="datetime-local" class="form-control" value="<?=isset($sc['ends_at'])?str_replace(' ','T',$sc['ends_at']):''?>"></div>
</div>
<?php modal('eedit_'.$r['id'],'Edit Ujian',ob_get_clean());?>
<?php endforeach?>

<?php elseif($page==='monitoring'):
  role('Admin','Guru','Proktor');
  $tid=$u['role']==='Guru'?q('SELECT id FROM teachers WHERE user_id=?',[$u['id']])->fetchColumn():null;
  $exams=$tid?q('SELECT id,title FROM exams WHERE teacher_id=? ORDER BY title',[$tid])->fetchAll(PDO::FETCH_ASSOC):q('SELECT id,title FROM exams ORDER BY title')->fetchAll(PDO::FETCH_ASSOC);
  $eid=(int)($_GET['exam_id']??0);
?>
<div class="panel mb-3">
  <h5>Live Monitoring Ujian</h5>
  <form class="row g-3" method="get">
    <input type="hidden" name="page" value="monitoring">
    <div class="col-md-4"><select name="exam_id" class="form-select" onchange="this.form.submit()"><option value="">- Pilih Ujian -</option><?php foreach($exams as $x):?><option value="<?=$x['id']?>" <?=$eid==$x['id']?'selected':''?>><?=e($x['title'])?></option><?php endforeach?></select></div>
  </form>
</div>
<?php if(!$exams):?>
<?php empty_state('Belum ada ujian','Buat ujian terlebih dahulu sebelum membuka monitoring.','bi-camera-video-off');?>
<?php elseif(!$eid):?>
<?php empty_state('Pilih ujian','Pilih ujian dari daftar untuk melihat monitoring peserta.','bi-camera-video');?>
<?php endif?>
<?php if($eid):?>
<div class="panel table-responsive">
  <table class="table" id="monitorTable">
    <thead><tr><th>Siswa</th><th>Status</th><th>Terjawab</th><th>Pelanggaran</th><th>Mulai</th><th>Ping Terakhir</th></tr></thead>
    <tbody id="monitorBody"><tr><td colspan="6" class="text-muted">Memuat data...</td></tr></tbody>
  </table>
</div>
<script>
function loadMonitor(){$.get('?action=monitor_data&exam_id=<?=$eid?>',function(d){var h='';d.forEach(function(x){var online=x.online?'<span class="badge badge-active">Online</span>':'<span class="badge badge-inactive">Offline</span>';var status=x.status==='in_progress'?'<span class="badge badge-warning">Berlangsung</span>':x.status==='submitted'?'<span class="badge badge-info">Dikumpulkan</span>':'<span class="badge badge-active">Selesai</span>';var vioClass=x.violations>0?'text-danger fw-bold':'';h+='<tr><td>'+x.student_name+'</td><td>'+status+' '+online+'</td><td>'+x.answered+'/'+x.total_questions+'</td><td class="'+vioClass+'">'+x.violations+'</td><td>'+(x.started_at||'-')+'</td><td>'+(x.last_ping||'-')+'</td></tr>';});$('#monitorBody').html(h||'<tr><td colspan="6" class="text-muted">Belum ada peserta.</td></tr>');});}
<?php if($eid):?>setInterval(loadMonitor,5000);loadMonitor();<?php endif?>
</script>
<?php endif?>

<?php elseif($page==='student'):
  role('Siswa');
  $active=q("SELECT e.*,sc.starts_at,sc.ends_at FROM schedules sc JOIN exams e ON e.id=sc.exam_id JOIN students s ON s.class_id=sc.class_id WHERE s.user_id=? ORDER BY sc.starts_at DESC",[$u['id']])->fetchAll(PDO::FETCH_ASSOC);
  $history=q('SELECT e.title,r.* FROM exam_results r JOIN students s ON s.id=r.student_id JOIN exams e ON e.id=r.exam_id WHERE s.user_id=? ORDER BY r.id DESC',[$u['id']])->fetchAll(PDO::FETCH_ASSOC);
  $now=date('Y-m-d H:i:s');
  $available=array_filter($active,fn($x)=>$x['starts_at']<=$now&&$x['ends_at']>=$now);
  $inProgress=q('SELECT exam_id FROM exam_results r JOIN students s ON s.id=r.student_id WHERE s.user_id=? AND r.status="in_progress"',[$u['id']])->fetchAll(PDO::FETCH_COLUMN);
  $doneCount=q('SELECT COUNT(*) FROM exam_results r JOIN students s ON s.id=r.student_id WHERE s.user_id=? AND r.status IN("submitted","graded")',[$u['id']])->fetchColumn();
  $avgScore=q('SELECT ROUND(AVG(r.score),1) FROM exam_results r JOIN students s ON s.id=r.student_id WHERE s.user_id=? AND r.score IS NOT NULL',[$u['id']])->fetchColumn()?:0;
  $nextExam=q("SELECT e.title,sc.starts_at FROM schedules sc JOIN exams e ON e.id=sc.exam_id JOIN students s ON s.class_id=sc.class_id WHERE s.user_id=? AND sc.starts_at>NOW() ORDER BY sc.starts_at LIMIT 1",[$u['id']])->fetch(PDO::FETCH_ASSOC);
?>
<?php if($u['role']==='Siswa'):
  $todayExams=q("SELECT e.title,sc.starts_at,sc.ends_at FROM schedules sc JOIN exams e ON e.id=sc.exam_id JOIN students s ON s.class_id=sc.class_id WHERE s.user_id=? AND DATE(sc.starts_at)=CURDATE() ORDER BY sc.starts_at",[$u['id']])->fetchAll(PDO::FETCH_ASSOC);
  if($todayExams):?>
  <div class="alert alert-info d-flex align-items-center gap-2"><i class="bi bi-megaphone-fill"></i> <div><b>Ujian Hari Ini:</b> <?=implode(', ',array_map(fn($x)=>e($x['title']).' ('.date('H:i',strtotime($x['starts_at'])).'-'.date('H:i',strtotime($x['ends_at'])).')',$todayExams))?></div></div>
  <?php endif?>
<?php endif?>
<section class="panel mb-3">
  <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
    <h5 class="mb-0">Benefit Siswa</h5>
    <?php if($nextExam):?><span class="badge badge-warning">Berikutnya <?=date('d/m H:i',strtotime($nextExam['starts_at']))?></span><?php endif?>
  </div>
  <div class="benefit-grid">
    <div class="benefit-item">
      <i class="bi bi-play-circle"></i>
      <div><b>Ujian Aktif</b><strong><?=count($available)?></strong><small>Langsung mulai atau lanjutkan ujian sesuai jadwal.</small></div>
    </div>
    <div class="benefit-item">
      <i class="bi bi-check2-circle"></i>
      <div><b>Ujian Selesai</b><strong><?=$doneCount?></strong><small>Riwayat pengerjaan tersimpan otomatis.</small></div>
    </div>
    <div class="benefit-item">
      <i class="bi bi-graph-up"></i>
      <div><b>Rata-rata Nilai</b><strong><?=$avgScore?></strong><small>Nilai muncul setelah ujian dikoreksi.</small></div>
    </div>
    <div class="benefit-item">
      <i class="bi bi-calendar-event"></i>
      <div><b>Jadwal Berikutnya</b><strong><?=e($nextExam['title']??'-')?></strong><small><?=isset($nextExam['starts_at'])?date('d/m/Y H:i',strtotime($nextExam['starts_at'])):'Belum ada jadwal baru.'?></small></div>
    </div>
  </div>
</section>
<section class="panel mb-3">
  <h5>Ujian Tersedia</h5>
  <?php if($available):?>
  <div class="row g-3">
    <?php foreach($available as $x):
      $isStarted=in_array($x['id'],$inProgress);
    ?>
    <div class="col-md-6">
      <div class="card border" style="border-radius:12px">
        <div class="card-body">
          <h6 class="mb-1"><?=e($x['title'])?></h6>
          <small class="text-muted d-block mb-2"><?=e($x['starts_at'])?> s/d <?=e($x['ends_at'])?></small>
          <button type="button" class="btn btn-sm btn-outline-info mb-2" onclick="window.open('?page=exam_card&exam=<?=$x['id']?>','_blank','width=500,height=700')"><i class="bi bi-card-text"></i> Kartu Ujian</button>
          <?php if($isStarted):?>
            <a href="?page=attempt&result=<?=q('SELECT id FROM exam_results WHERE exam_id=? AND student_id=(SELECT id FROM students WHERE user_id=?)',[$x['id'],$u['id']])->fetchColumn()?>" class="btn btn-warning btn-sm"><i class="bi bi-arrow-right"></i> Lanjutkan</a>
          <?php else:?>
            <form method="post" action="?action=start" style="display:inline"><?=csrf_field()?><input type="hidden" name="exam_id" value="<?=$x['id']?>"><button class="btn btn-primary btn-sm"><i class="bi bi-play"></i> Mulai Ujian</button></form>
          <?php endif?>
        </div>
      </div>
    </div>
    <?php endforeach?>
  </div>
  <?php else:?>
  <?php empty_state('Tidak ada ujian aktif','Ujian akan muncul di sini sesuai jadwal kelas Anda.','bi-calendar-x');?>
  <?php endif?>
</section>
<div class="panel mb-3">
  <h5>Jadwal Ujian (Semua)</h5>
  <?php foreach($active as $x):?>
  <div class="border-bottom py-2 d-flex justify-content-between align-items-center">
    <div><b><?=e($x['title'])?></b><small class="d-block text-muted"><?=e($x['starts_at'])?> s/d <?=e($x['ends_at'])?></small></div>
    <?php if($x['starts_at']<=$now&&$x['ends_at']>$now):?><span class="badge badge-active">Sedang Berlangsung</span><?php elseif($x['ends_at']<$now):?><span class="badge badge-inactive">Selesai</span><?php else:?><span class="badge badge-warning">Akan Datang</span><?php endif?>
  </div>
  <?php endforeach?>
  <?php if(!$active)empty_state('Belum ada jadwal','Jadwal ujian untuk kelas Anda belum tersedia.','bi-calendar-week')?>
</div>
<div class="panel">
  <h5>Riwayat Ujian</h5>
  <?php if(!$history)empty_state('Belum ada riwayat','Riwayat akan terisi setelah Anda mulai atau menyelesaikan ujian.','bi-clock-history');?>
  <table class="table">
    <thead><tr><th>Ujian</th><th>Status</th><th>Nilai</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach($history as $x):?>
    <tr>
      <td><?=e($x['title'])?></td>
      <td>
        <?php if($x['status']==='in_progress'):?>
          <span class="badge badge-warning">Berlangsung</span>
        <?php elseif($x['status']==='submitted'):?>
          <span class="badge badge-info">Dikumpulkan</span>
        <?php else:?>
          <span class="badge badge-active">Selesai</span>
        <?php endif?>
      </td>
      <td><?=$x['score']??'-'?></td>
      <td>
        <?php if($x['status']!=='in_progress'):?>
        <a href="?page=review&result=<?=$x['id']?>" class="btn btn-sm btn-outline-primary" title="Lihat"><i class="bi bi-eye"></i></a>
        <a href="?action=download_result&result=<?=$x['id']?>" class="btn btn-sm btn-outline-secondary" title="Cetak / PDF"><i class="bi bi-printer"></i></a>
        <?php endif?>
      </td>
    </tr>
    <?php endforeach?>
    </tbody>
  </table>
</div>

<?php elseif($page==='attempt'):
  role('Siswa');
  $rid=(int)$_GET['result'];
  $result=q('SELECT r.*,e.title,e.duration,e.random_questions,e.random_choices,e.show_score FROM exam_results r JOIN exams e ON e.id=r.exam_id JOIN students s ON s.id=r.student_id WHERE r.id=? AND s.user_id=?',[$rid,$u['id']])->fetch(PDO::FETCH_ASSOC);
  if(!$result||$result['status']!=='in_progress')redirect('page=student');
  $sq=q('SELECT question_id FROM student_questions WHERE result_id=? ORDER BY RAND()',[$rid])->fetchAll(PDO::FETCH_COLUMN);
  if($sq){$placeholders=implode(',',array_fill(0,count($sq),'?'));$qs=q("SELECT q.* FROM questions q WHERE q.id IN ($placeholders) ORDER BY FIELD(q.id,$placeholders)",array_merge($sq,$sq))->fetchAll(PDO::FETCH_ASSOC);}else{$qs=q('SELECT q.* FROM exam_questions eq JOIN questions q ON q.id=eq.question_id WHERE eq.exam_id=?'.($result['random_questions']?' ORDER BY RAND()':' ORDER BY q.id'),[$result['exam_id']])->fetchAll(PDO::FETCH_ASSOC);}$totalQ=count($qs);
  $answerData=[]; foreach($qs as $x){$ans=q('SELECT answer_text,flagged FROM answers WHERE result_id=? AND question_id=?',[$rid,$x['id']])->fetch(PDO::FETCH_ASSOC);$answerData[$x['id']]=['answer'=>$ans['answer_text']??'','flagged'=>(int)($ans['flagged']??0)];}
  $sections=q('SELECT s.*,eq.question_id FROM exam_sections s LEFT JOIN exam_questions eq ON eq.section_id=s.id WHERE s.exam_id=? ORDER BY s.ordering,eq.question_id',[$result['exam_id']])->fetchAll(PDO::FETCH_ASSOC);$questionSection=[];foreach($sections as $s){$questionSection[$s['question_id']]=$s;}
?>
<div class="exam-layout">
  <div class="exam-main">
    <div class="exam-top">
      <div><small>UJIAN BERLANGSUNG</small><h5><?=e($result['title'])?></h5></div>
      <div class="d-flex align-items-center gap-3">
        <div class="warning-count" id="warningCount" data-max="<?=q('SELECT setting_value FROM settings WHERE setting_key="max_warnings"')->fetchColumn()?:3?>"><i class="bi bi-shield-exclamation"></i> <span>0</span></div>
        <div class="timer" id="timer" data-end="<?=strtotime($result['started_at'])+$result['duration']*60?>" data-duration="<?=$result['duration']?>">--:--</div>
      </div>
    </div>
    <div id="cheatWarning" class="alert alert-danger d-none align-items-center gap-2 mb-2 py-2"><i class="bi bi-exclamation-triangle"></i> <span></span></div>
    <div id="timeWarning" class="alert alert-warning d-none align-items-center gap-2 mb-2 py-2"><i class="bi bi-clock"></i> <span></span></div>
    <form id="examForm" method="post" action="?action=submit">
      <?=csrf_field()?>
      <input type="hidden" name="result" value="<?=$rid?>">
      <?php $prevSection=null; $gn=0; foreach($qs as $n=>$x):
        $ad=$answerData[$x['id']]; $ans=$ad['answer']; $flag=$ad['flagged'];
        $curSection=$questionSection[$x['id']]??null; $sid=$curSection['id']??0;
        if($sid && $sid!==$prevSection): $prevSection=$sid;?>
<div class="section-header" data-section-id="<?=$sid?>" data-timer="<?=$curSection['timer']??0?>">
          <h6 class="mb-0"><?=e($curSection['title'])?></h6>
          <?php if($curSection['timer']):?><small><i class="bi bi-clock"></i> <span class="section-timer" data-end="0"><?=$curSection['timer']?> menit</span></small><?php endif?>
        </div>
      <?php endif?>
      <section class="panel question" id="q<?=$x['id']?>" data-q="<?=$x['id']?>" data-no="<?=++$gn?>">
        <div class="d-flex justify-content-between align-items-start">
          <span class="q-num">Soal <?=$n+1?></span>
          <button type="button" class="btn btn-sm btn-outline-warning ragu-btn <?=$flag?'active':''?>" data-question="<?=$x['id']?>" title="Tandai ragu-ragu"><i class="bi bi-flag<?=$flag?'-fill':''?>"></i> <span>Ragu</span></button>
        </div>
        <div class="question-content"><?=$x['question']?></div>
        <?php if($x['type']==='essay'):?>
          <textarea class="form-control answer" data-question="<?=$x['id']?>" rows="5"><?=e($ans)?></textarea>
        <?php else:
          $choices=q('SELECT * FROM choices WHERE question_id=? '.($result['random_choices']?'ORDER BY RAND()':'ORDER BY label'),[$x['id']])->fetchAll(PDO::FETCH_ASSOC);
          foreach($choices as $c):?>
          <label class="choice"><input class="answer" data-question="<?=$x['id']?>" type="radio" name="a<?=$x['id']?>" value="<?=$c['label']?>" <?=$ans===$c['label']?'checked':''?>> <b><?=$c['label']?>.</b> <?=e($c['choice_text'])?></label>
        <?php endforeach;
        endif?>
      </section>
      <?php endforeach?>
      <div class="exam-pager" data-total="<?=$totalQ?>">
        <button type="button" class="btn btn-outline-secondary btn-lg" id="prevQuestion"><i class="bi bi-arrow-left"></i> Sebelumnya</button>
        <div class="exam-pager-status"><span id="currentQuestionNo">1</span><small>/ <?=$totalQ?> soal</small></div>
        <button type="button" class="btn btn-primary btn-lg" id="nextQuestion">Berikutnya <i class="bi bi-arrow-right"></i></button>
      </div>
      <div class="exam-submit-actions d-flex gap-2 mb-5 flex-wrap">
        <a href="?page=preview&result=<?=$rid?>" class="btn btn-warning btn-lg"><i class="bi bi-eye"></i> Review Jawaban</a>
        <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Kumpulkan ujian sekarang?')"><i class="bi bi-send"></i> Kumpulkan</button>
      </div>
    </form>
  </div>
  <div class="exam-nav" id="examNav">
    <div class="exam-nav-header">Navigasi Soal</div>
    <div class="exam-nav-grid">
      <?php $navSection=null; $ns=0; foreach($qs as $n=>$x):
        $ad=$answerData[$x['id']]; $cls='unanswered'; $title='Belum dijawab';
        $curNavSec=$questionSection[$x['id']]??null;
        $nsid=$curNavSec['id']??0;
        if($nsid && $nsid!==$navSection): $navSection=$nsid;?>
        <div class="nav-section-label"><?=e($curNavSec['title'])?></div>
      <?php endif; $ns++;
        if($ad['flagged']){$cls='flagged';$title='Ditandai ragu';}
        if($ad['answer']!==''){$cls=$cls==='flagged'?'flagged':($x['type']==='essay'&&$ad['answer']!==''?'answered':'answered');$title=$ad['flagged']?'Terjawab & ditandai':'Terjawab';}
      ?>
      <a href="#q<?=$x['id']?>" class="nav-q <?=$cls?>" title="<?=$title?>" data-q="<?=$x['id']?>"><?=$ns?></a>
      <?php endforeach?>
    </div>
    <div class="exam-nav-legend">
      <span><i class="bi bi-check-circle-fill text-success"></i> Terjawab</span>
      <span><i class="bi bi-flag-fill text-warning"></i> Ragu</span>
      <span><i class="bi bi-circle text-secondary"></i> Kosong</span>
    </div>
  </div>
</div>

<?php elseif($page==='preview'):
  role('Siswa');
  $rid=(int)$_GET['result'];
  $result=q('SELECT r.*,e.title,e.duration,e.show_score FROM exam_results r JOIN exams e ON e.id=r.exam_id JOIN students s ON s.id=r.student_id WHERE r.id=? AND s.user_id=? AND r.status="in_progress"',[$rid,$u['id']])->fetch(PDO::FETCH_ASSOC);
  if(!$result)redirect('page=student');
  $sq=q('SELECT question_id FROM student_questions WHERE result_id=?',[$rid])->fetchAll(PDO::FETCH_COLUMN);
  if($sq){$placeholders=implode(',',array_fill(0,count($sq),'?'));$qs=q("SELECT q.* FROM questions q WHERE q.id IN ($placeholders) ORDER BY FIELD(q.id,$placeholders)",array_merge($sq,$sq))->fetchAll(PDO::FETCH_ASSOC);}else{$qs=q('SELECT q.* FROM exam_questions eq JOIN questions q ON q.id=eq.question_id WHERE eq.exam_id=? ORDER BY q.id',[$result['exam_id']])->fetchAll(PDO::FETCH_ASSOC);}
  $answerData=[]; foreach($qs as $x){$ans=q('SELECT answer_text,flagged FROM answers WHERE result_id=? AND question_id=?',[$rid,$x['id']])->fetch(PDO::FETCH_ASSOC);$answerData[$x['id']]=['answer'=>$ans['answer_text']??'','flagged'=>(int)($ans['flagged']??0)];}
  $psections=q('SELECT s.*,eq.question_id FROM exam_sections s LEFT JOIN exam_questions eq ON eq.section_id=s.id WHERE s.exam_id=? ORDER BY s.ordering,eq.question_id',[$result['exam_id']])->fetchAll(PDO::FETCH_ASSOC);$pquestionSection=[];foreach($psections as $s){$pquestionSection[$s['question_id']]=$s;}
  $timer=new DateTime($result['started_at']); $timer->add(new DateInterval('PT'.$result['duration'].'M'));
  if(new DateTime() > $timer) redirect('?action=submit&result='.$rid);
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Review: <?=e($result['title'])?></h4>
  <div>
    <a href="?page=attempt&result=<?=$rid?>" class="btn btn-light btn-sm me-2"><i class="bi bi-arrow-left"></i> Kembali</a>
    <form method="post" action="?action=submit" style="display:inline" onsubmit="return confirm('Kumpulkan jawaban sekarang?')">
      <?=csrf_field()?>
      <input type="hidden" name="result" value="<?=$rid?>">
      <button class="btn btn-success btn-sm"><i class="bi bi-send"></i> Kumpulkan Ujian</button>
    </form>
  </div>
</div>
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> Periksa kembali jawaban Anda sebelum mengumpulkan.</div>
<?php $pvSection=null; $pvn=0; foreach($qs as $n=>$x): $ad=$answerData[$x['id']]; $ans=$ad['answer']; $flag=$ad['flagged'];
  $pcurSec=$pquestionSection[$x['id']]??null; $psid=$pcurSec['id']??0;
  if($psid && $psid!==$pvSection): $pvSection=$psid;?>
  <div class="section-header"><h6 class="mb-0"><?=e($pcurSec['title'])?></h6></div>
<?php endif; $pvn++; ?>
<div class="panel question mb-2" id="preview_q<?=$x['id']?>">
  <div class="d-flex justify-content-between align-items-start">
    <span class="q-num">Soal <?=$n+1?> <?=$flag?'<span class="badge bg-warning ms-1">Ragu</span>':''?></span>
    <a href="?page=attempt&result=<?=$rid?>#q<?=$x['id']?>" class="btn btn-sm btn-outline-secondary" title="Ubah jawaban"><i class="bi bi-pencil"></i></a>
  </div>
  <div class="question-content"><?=$x['question']?></div>
  <?php if($x['type']==='essay'):?>
    <div class="border rounded p-3 bg-light"><small class="text-muted">Jawaban Anda:</small><p class="mb-0"><?=nl2br(e($ans?:'(belum dijawab)'))?></p></div>
  <?php else:
    $choices=q('SELECT * FROM choices WHERE question_id=? ORDER BY label',[$x['id']])->fetchAll(PDO::FETCH_ASSOC);
    foreach($choices as $c): $sel=$ans===$c['label'];?>
    <div class="choice <?=$sel?'choice-selected':''?>">
      <input type="radio" disabled <?=$sel?'checked':''?>> <b><?=$c['label']?>.</b> <?=e($c['choice_text'])?>
      <?php if($sel):?><i class="bi bi-check-circle-fill text-success ms-auto"></i><?php endif?>
    </div>
    <?php endforeach;
    if(!$ans):?><small class="text-warning"><i class="bi bi-exclamation-circle"></i> Belum dijawab</small><?php endif?>
  <?php endif?>
</div>
<?php endforeach?>

<?php elseif($page==='review'):
  role('Siswa');
  $rid=(int)$_GET['result'];
  $result=q('SELECT r.*,e.title,e.duration,e.show_score,s.name subject_name FROM exam_results r JOIN exams e ON e.id=r.exam_id JOIN subjects s ON s.id=e.subject_id JOIN students st ON st.id=r.student_id WHERE r.id=? AND st.user_id=? AND r.status IN("submitted","graded")',[$rid,$u['id']])->fetch(PDO::FETCH_ASSOC);
  if(!$result)redirect('page=student');
  $sq=q('SELECT question_id FROM student_questions WHERE result_id=?',[$rid])->fetchAll(PDO::FETCH_COLUMN);
  if($sq){$pl=implode(',',array_fill(0,count($sq),'?'));$qs=q("SELECT q.* FROM questions q WHERE q.id IN ($pl) ORDER BY FIELD(q.id,$pl)",array_merge($sq,$sq))->fetchAll(PDO::FETCH_ASSOC);}else{$qs=q('SELECT q.* FROM exam_questions eq JOIN questions q ON q.id=eq.question_id WHERE eq.exam_id=? ORDER BY q.id',[$result['exam_id']])->fetchAll(PDO::FETCH_ASSOC);}
  $rsections=q('SELECT s.*,eq.question_id FROM exam_sections s LEFT JOIN exam_questions eq ON eq.section_id=s.id WHERE s.exam_id=? ORDER BY s.ordering,eq.question_id',[$result['exam_id']])->fetchAll(PDO::FETCH_ASSOC);$rquestionSection=[];foreach($rsections as $s){$rquestionSection[$s['question_id']]=$s;}
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Review: <?=e($result['title'])?></h4>
  <a href="?action=download_result&result=<?=$rid?>" class="btn btn-success btn-sm"><i class="bi bi-download"></i> Download Hasil</a>
</div>
<div class="row g-3 mb-4">
  <div class="col-md-3"><div class="panel text-center"><small class="text-muted">Nilai</small><h3 class="mb-0"><?=$result['score']??'-'?></h3></div></div>
  <div class="col-md-3"><div class="panel text-center"><small class="text-muted">Mapel</small><p class="mb-0 fw-semibold"><?=e($result['subject_name'])?></p></div></div>
  <div class="col-md-3"><div class="panel text-center"><small class="text-muted">Durasi</small><p class="mb-0 fw-semibold"><?=$result['duration']?> menit</p></div></div>
  <div class="col-md-3"><div class="panel text-center"><small class="text-muted">Status</small><p class="mb-0 fw-semibold"><?=$result['status']==='graded'?'Dinilai':'Menunggu Koreksi'?></p></div></div>
</div>
<?php $rvSection=null; $rvn=0; foreach($qs as $n=>$x):
  $a=q('SELECT a.*,c.label correct_label,c.choice_text correct_text FROM answers a LEFT JOIN choices c ON c.question_id=a.question_id AND c.is_correct=1 WHERE a.result_id=? AND a.question_id=?',[$rid,$x['id']])->fetch(PDO::FETCH_ASSOC);
  $choices=$x['type']==='multiple'?q('SELECT * FROM choices WHERE question_id=? ORDER BY label',[$x['id']])->fetchAll(PDO::FETCH_ASSOC):[];
  $rvcurSec=$rquestionSection[$x['id']]??null; $rvsid=$rvcurSec['id']??0;
  if($rvsid && $rvsid!==$rvSection): $rvSection=$rvsid;?>
  <div class="section-header"><h6 class="mb-0"><?=e($rvcurSec['title'])?></h6></div>
<?php endif; $rvn++; ?>
<div class="panel question mb-2">
  <span class="q-num">Soal <?=$rvn?></span>
  <?php if($a && $a['is_correct']!==null):?><span class="badge ms-2 <?=$a['is_correct']?'badge-active':'badge-inactive'?>"><?=$a['is_correct']?'Benar':'Salah'?></span><?php endif?>
  <div class="question-content"><?=$x['question']?></div>
  <?php if($x['type']==='multiple'):?>
    <?php foreach($choices as $c):
      $isSelected=$a&&$a['answer_text']===$c['label'];
      $isCorrect=$c['is_correct'];
    ?>
    <div class="choice <?=$isCorrect?'choice-correct':''?> <?=$isSelected&&!$isCorrect?'choice-wrong':''?> <?=$isSelected?'choice-selected':''?>">
      <input type="radio" disabled <?=$isSelected?'checked':''?>> <b><?=$c['label']?>.</b> <?=e($c['choice_text'])?>
      <?php if($isCorrect):?><i class="bi bi-check-circle-fill text-success ms-auto"></i><?php elseif($isSelected):?><i class="bi bi-x-circle-fill text-danger ms-auto"></i><?php endif?>
    </div>
    <?php endforeach?>
    <small class="text-muted">Jawaban benar: <b><?=e($a['correct_label']??'-')?></b></small>
  <?php else:?>
    <div class="border rounded p-3 bg-light"><small class="text-muted">Jawaban Anda:</small><p class="mb-0"><?=nl2br(e($a['answer_text']??'-'))?></p></div>
    <?php if($a && $a['score']!==null):?><small class="text-muted">Skor: <b><?=$a['score']?></b> / <?=$x['weight']?></small><?php endif?>
  <?php endif?>
</div>
<?php endforeach?>

<?php elseif($page==='reports'):
  role('Admin','Guru');
  $tid=$u['role']==='Guru'?q('SELECT id FROM teachers WHERE user_id=?',[$u['id']])->fetchColumn():null;
  if($tid){
    $rows=q('SELECT u.name siswa,e.title ujian,r.score,r.status,r.submitted_at FROM exam_results r JOIN students s ON s.id=r.student_id JOIN users u ON u.id=s.user_id JOIN exams e ON e.id=r.exam_id WHERE e.teacher_id=? ORDER BY r.id DESC',[$tid])->fetchAll(PDO::FETCH_ASSOC);
  } else {
    $rows=q('SELECT u.name siswa,e.title ujian,r.score,r.status,r.submitted_at FROM exam_results r JOIN students s ON s.id=r.student_id JOIN users u ON u.id=s.user_id JOIN exams e ON e.id=r.exam_id ORDER BY r.id DESC')->fetchAll(PDO::FETCH_ASSOC);
  }
?>
<div class="d-flex gap-2 mb-3 flex-wrap">
  <a class="btn btn-success" href="?action=export"><i class="bi bi-file-earmark-excel"></i> Export Excel (CSV)</a>
  <button class="btn btn-danger" onclick="window.print()"><i class="bi bi-file-earmark-pdf"></i> Cetak / PDF</button>
</div>
<div class="panel table-responsive">
  <?php if(!$rows)empty_state('Belum ada laporan nilai','Laporan akan muncul setelah siswa mengerjakan ujian.','bi-bar-chart');?>
  <table class="table data-table">
    <thead><tr><th>Siswa</th><th>Ujian</th><th>Nilai</th><th>Status</th><th>Dikumpulkan</th></tr></thead>
    <tbody>
    <?php foreach($rows as $x):?>
    <tr>
      <td><?=e($x['siswa'])?></td>
      <td><?=e($x['ujian'])?></td>
      <td><?=$x['score']??'-'?></td>
      <td>
        <?php if($x['status']==='in_progress'):?>
          <span class="badge badge-warning">Berlangsung</span>
        <?php elseif($x['status']==='submitted'):?>
          <span class="badge badge-info">Dikumpulkan</span>
        <?php else:?>
          <span class="badge badge-active">Selesai</span>
        <?php endif?>
      </td>
      <td><?=$x['submitted_at']??'-'?></td>
    </tr>
    <?php endforeach?>
    </tbody>
  </table>
</div>

<?php elseif($page==='profile'):
  $tid=$u['role']==='Guru'?q('SELECT id,nip,phone FROM teachers WHERE user_id=?',[$u['id']])->fetch(PDO::FETCH_ASSOC):null;
  $sid=$u['role']==='Siswa'?q('SELECT id,nis,class_id FROM students WHERE user_id=?',[$u['id']])->fetch(PDO::FETCH_ASSOC):null;
?>
<div class="panel mb-3">
  <h5>Profil Saya</h5>
  <form method="post" action="?action=profile_save" class="row g-3" style="max-width:500px">
    <?=csrf_field()?>
    <div class="col-12"><label class="form-label">Nama</label><input type="text" name="name" required class="form-control" value="<?=e($u['name'])?>"></div>
    <div class="col-12"><label class="form-label">Email</label><input type="text" name="email" class="form-control" value="<?=e($u['email'])?>"></div>
    <div class="col-12"><button class="btn btn-primary btn-sm">Simpan Profil</button></div>
  </form>
  <hr>
  <table class="table" style="max-width:500px">
    <tr><td>Nama</td><td><?=e($u['name'])?></td></tr>
    <tr><td>Email</td><td><?=e($u['email'])?></td></tr>
    <tr><td>Role</td><td><span class="badge text-bg-primary"><?=e($u['role'])?></span></td></tr>
    <?php if($tid):?><tr><td>NIP</td><td><?=e($tid['nip'])?></td></tr>
    <tr><td>Telepon</td><td><?=e($tid['phone']??'-')?></td></tr><?php endif?>
    <?php if($sid):?><tr><td>NIS</td><td><?=e($sid['nis'])?></td></tr>
    <tr><td>Kelas</td><td><?=e(q('SELECT name FROM classes WHERE id=?',[$sid['class_id']])->fetchColumn()?:'-')?></td></tr><?php endif?>
  </table>
</div>
<div class="panel">
  <h5>Ganti Password</h5>
  <form method="post" action="?action=password_save" class="row g-3" style="max-width:400px">
    <?=csrf_field()?>
    <div class="col-12"><label class="form-label">Password Lama</label><input type="password" name="old_password" required class="form-control"></div>
    <div class="col-12"><label class="form-label">Password Baru (min 6 karakter)</label><input type="password" name="new_password" required class="form-control" minlength="6"></div>
    <div class="col-12"><label class="form-label">Konfirmasi Password Baru</label><input type="password" name="confirm_password" required class="form-control" minlength="6"></div>
    <div class="col-12"><button class="btn btn-primary">Simpan Password</button></div>
  </form>
</div>

<?php elseif($page==='settings'):
  role('Admin');
  $rows=q('SELECT * FROM settings ORDER BY setting_key')->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="panel">
  <h5>Pengaturan Aplikasi</h5>
  <form method="post" action="?action=settings_save" style="max-width:500px">
    <?=csrf_field()?>
    <?php foreach($rows as $s):?>
    <label class="form-label mt-2"><?=e(ucfirst(str_replace('_',' ',$s['setting_key'])))?></label>
    <input name="key_<?=$s['setting_key']?>" class="form-control" value="<?=e($s['setting_value'])?>">
    <?php endforeach?>
    <button class="btn btn-primary mt-3">Simpan Pengaturan</button>
  </form>
</div>

<?php elseif($page==='logs'):
  role('Admin');
  $logs=q('SELECT l.*,u.name user FROM logs l LEFT JOIN users u ON u.id=l.user_id ORDER BY l.id DESC LIMIT 500')->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="panel table-responsive">
  <h5>Log Aktivitas</h5>
  <?php if(!$logs)empty_state('Belum ada log','Aktivitas pengguna akan tercatat di sini.','bi-clock-history');?>
  <table class="table data-table">
    <thead><tr><th>ID</th><th>User</th><th>Aksi</th><th>Waktu</th></tr></thead>
    <tbody>
    <?php foreach($logs as $l):?>
    <tr><td><?=$l['id']?></td><td><?=e($l['user']??'-')?></td><td><?=e($l['action'])?></td><td><?=$l['created_at']?></td></tr>
    <?php endforeach?>
    </tbody>
  </table>
</div>

<?php elseif($page==='grading'):
  role('Admin','Guru');
  $tid=$u['role']==='Guru'?q('SELECT id FROM teachers WHERE user_id=?',[$u['id']])->fetchColumn():null;
  if($tid){
    $exams=q('SELECT id,title FROM exams WHERE teacher_id=? ORDER BY title',[$tid])->fetchAll(PDO::FETCH_ASSOC);
  } else {
    $exams=q('SELECT id,title FROM exams ORDER BY title')->fetchAll(PDO::FETCH_ASSOC);
  }
  $eid=(int)($_GET['exam_id']??0);
  $uid=(int)($_GET['student_id']??0);
  if($eid && $uid):
    $result=q('SELECT r.*,u.name siswa,e.title FROM exam_results r JOIN students s ON s.id=r.student_id JOIN users u ON u.id=s.user_id JOIN exams e ON e.id=r.exam_id WHERE r.exam_id=? AND s.user_id=? AND r.status="submitted"',[$eid,$uid])->fetch(PDO::FETCH_ASSOC);
    if(!$result):?><div class="panel"><p class="text-muted">Belum ada jawaban essay yang perlu dikoreksi.</p></div>
    <?php else:
      $sq=q('SELECT question_id FROM student_questions WHERE result_id=?',[$result['id']])->fetchAll(PDO::FETCH_COLUMN);
      if($sq){$pl=implode(',',array_fill(0,count($sq),'?'));$sqParams=array_merge($sq,$sq);$essays=q("SELECT q.id,q.question,q.weight,a.answer_text,a.score a_score FROM questions q LEFT JOIN answers a ON a.question_id=q.id AND a.result_id=? WHERE q.id IN ($pl) AND q.type='essay' ORDER BY FIELD(q.id,$pl)",array_merge([$result['id']],$sqParams))->fetchAll(PDO::FETCH_ASSOC);}else{$essays=q('SELECT q.id,q.question,q.weight,a.answer_text,a.score a_score FROM exam_questions eq JOIN questions q ON q.id=eq.question_id LEFT JOIN answers a ON a.question_id=q.id AND a.result_id=? WHERE q.type="essay" AND eq.exam_id=?',[$result['id'],$eid])->fetchAll(PDO::FETCH_ASSOC);}
    ?>
    <div class="panel mb-3"><h5><?=e($result['title'])?> – <?=e($result['siswa'])?></h5></div>
    <form method="post" action="?action=grade_save">
      <?=csrf_field()?>
      <input type="hidden" name="result_id" value="<?=$result['id']?>">
      <?php foreach($essays as $e):?>
      <div class="panel mb-2">
        <p><strong>Soal:</strong></p>
        <div class="question-content"><?=$e['question']?></div>
        <p><strong>Bobot:</strong> <?=$e['weight']?></p>
        <p><strong>Jawaban siswa:</strong></p>
        <blockquote class="border-start border-3 ps-3 text-muted"><?=nl2br(e($e['answer_text']??'-'))?></blockquote>
        <label class="form-label">Skor (0 – <?=$e['weight']?>)</label>
        <input type="number" step=".01" name="score_<?=$e['id']?>" class="form-control" style="max-width:150px" value="<?=$e['a_score']??''?>" min="0" max="<?=$e['weight']?>">
      </div>
      <?php endforeach?>
      <button class="btn btn-success mt-2">Simpan Nilai Essay</button>
      <a href="?page=grading&exam_id=<?=$eid?>" class="btn btn-light mt-2">Kembali</a>
    </form>
    <?php endif;
  else:?>
<div class="panel">
  <h5>Koreksi Jawaban Essay</h5>
  <form class="row g-3 mb-3" method="get">
    <input type="hidden" name="page" value="grading">
    <div class="col-md-4"><label class="form-label">Pilih Ujian</label><select name="exam_id" class="form-select" onchange="this.form.submit()"><option value="">- Pilih Ujian -</option><?php foreach($exams as $x):?><option value="<?=$x['id']?>" <?=$eid==$x['id']?'selected':''?>><?=e($x['title'])?></option><?php endforeach?></select></div>
  </form>
  <?php if(!$exams):?>
    <?php empty_state('Belum ada ujian','Buat ujian terlebih dahulu sebelum koreksi essay.','bi-check2-square');?>
  <?php elseif(!$eid):?>
    <?php empty_state('Pilih ujian','Pilih ujian untuk melihat jawaban essay yang perlu dikoreksi.','bi-check2-square');?>
  <?php endif?>
  <?php if($eid):
    $sql="SELECT DISTINCT u.id user_id,u.name,r.id result_id,r.status FROM exam_results r JOIN students s ON s.id=r.student_id JOIN users u ON u.id=s.user_id JOIN exams e ON e.id=r.exam_id WHERE r.exam_id=? AND r.status='submitted'";$params=[$eid];if($tid){$sql.=' AND e.teacher_id=?';$params[]=$tid;}$sql.=' ORDER BY u.name';$students=q($sql,$params)->fetchAll(PDO::FETCH_ASSOC);
    if($students):?>
    <table class="table data-table">
      <thead><tr><th>Siswa</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php foreach($students as $s):?>
      <tr><td><?=e($s['name'])?></td><td><span class="badge badge-info">Dikumpulkan</span></td><td><a href="?page=grading&exam_id=<?=$eid?>&student_id=<?=$s['user_id']?>" class="btn btn-sm btn-primary">Koreksi</a></td></tr>
      <?php endforeach?>
      </tbody>
    </table>
    <?php else:?><?php empty_state('Tidak ada jawaban essay','Belum ada ujian berstatus dikumpulkan yang perlu dikoreksi.','bi-inbox');?>
    <?php endif?>
  <?php endif?>
</div>
<?php endif;?>

<?php else:?>
<div class="panel">Halaman tidak ditemukan.</div>
<?php endif;?>
</main>
<script src="<?=vendor_asset('assets/vendor/bootstrap/bootstrap.bundle.min.js','https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js')?>"></script>
<script src="<?=vendor_asset('assets/vendor/jquery/jquery-3.7.1.min.js','https://code.jquery.com/jquery-3.7.1.min.js')?>"></script>
<script src="<?=vendor_asset('assets/vendor/datatables/dataTables.js','https://cdn.datatables.net/2.0.8/js/dataTables.js')?>"></script>
<script src="<?=vendor_asset('assets/vendor/datatables/dataTables.bootstrap5.js','https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js')?>"></script>
<script src="<?=vendor_asset('assets/vendor/summernote/summernote-bs5.min.js','https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.js')?>"></script>
<script>$('.data-table').DataTable({language:{search:'Cari:',lengthMenu:'Tampilkan _MENU_ data',info:'Menampilkan _START_–_END_ dari _TOTAL_ data',zeroRecords:'Tidak ada data'}})</script>
<script>document.querySelectorAll('.grade-roman').forEach(function(el){el.addEventListener('input',function(){var v=this.value.replace(/^(10|11|12)(?=\s|$)/,function(m){return {10:'X',11:'XI',12:'XII'}[m]});if(v!==this.value)this.value=v;});});</script>
<script>
document.querySelectorAll('.qtype-edit').forEach(el=>el.addEventListener('change',function(){document.getElementById(this.dataset.target).style.display=this.value==='essay'?'none':'block'}));
$(function(){ $('.summernote').summernote({height:200,placeholder:'Tulis soal di sini...',callbacks:{onImageUpload:function(files){var fd=new FormData();fd.append('image',files[0]);fd.append('csrf','<?=csrf()?>');$.ajax({url:'?action=upload_image',method:'POST',data:fd,contentType:false,processData:false,success:function(r){if(r.url)$('.summernote').summernote('insertImage',r.url);else alert('Gagal upload: '+r.error);}});}}}); });
function loadNotif(){$.get('?action=get_notifications',function(d){$('#notifCount').text(d.count).toggleClass('has-notif',d.count>0);var h='';if(d.list.length){d.list.forEach(function(n){h+='<a class="dropdown-item notif-item '+(n.is_read?'':'fw-semibold')+'" href="javascript:;" onclick="markNotif('+n.id+')"><small class="text-muted float-end">'+n.created_at.slice(0,10)+'</small><div>'+n.title+'</div>'+ (n.message?'<small class="text-muted">'+n.message+'</small>':'')+'</a>';});}else{h='<div class="px-3 py-2 text-muted small">Tidak ada notifikasi</div>';}$('#notifList').html(h);});}
function markNotif(id){$.post('?action=mark_notification_read',{id:id,csrf:'<?=csrf()?>'},function(){loadNotif();});}
<?php if(user()):?>setInterval(loadNotif,30000);loadNotif();<?php endif?>
</script>
<script src="assets/js/app.js"></script>
</body></html>
<?php endif?>
