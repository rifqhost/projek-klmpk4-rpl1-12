<?php
$u=user(); $flash=$_SESSION['flash']??null; unset($_SESSION['flash']);
function opts(string $table,string $selected='',$label='name'):string{if($table==='teachers'){$s=q("SELECT t.id,u.name FROM teachers t JOIN users u ON u.id=t.user_id ORDER BY u.name")->fetchAll(PDO::FETCH_ASSOC);$label='name';}else{$s=q("SELECT id,$label FROM $table ORDER BY $label")->fetchAll(PDO::FETCH_ASSOC);}$o='';foreach($s as $r)$o.='<option value="'.$r['id'].'" '.((string)$r['id']===(string)$selected?'selected':'').'>'.e($r[$label]).'</option>';return $o;}
function modal(string $id,string $title,string $body):void{echo '<div class="modal fade" id="'.$id.'"><div class="modal-dialog modal-lg"><form method="post" class="modal-content"><div class="modal-header"><h5>'.$title.'</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">'.csrf_field().$body.'</div><div class="modal-footer"><button class="btn btn-primary">Simpan</button></div></form></div></div>';}
function csrf_field():string{return '<input type="hidden" name="csrf" value="'.csrf().'">';}
if(!$u): ?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login | <?=APP_NAME?></title><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link href="assets/css/style.css?v=<?=md5_file('assets/css/style.css')?>" rel="stylesheet"></head><body class="login-page"><div class="login-left"><div class="login-left-inner"><div class="login-brand"><i class="bi bi-mortarboard-fill"></i> <span>CBT</span>School</div><h1>Sistem Ujian<br><span>Berbasis Komputer</span></h1><p>Kelola ujian, bank soal, dan penilaian siswa dalam satu platform terintegrasi.</p><div class="login-features"><div class="login-feature"><i class="bi bi-shield-check"></i><span>Keamanan data terjamin</span></div><div class="login-feature"><i class="bi bi-lightning-charge"></i><span>Penilaian otomatis</span></div><div class="login-feature"><i class="bi bi-graph-up"></i><span>Statistik real-time</span></div></div></div><div class="login-left-footer">&copy; <?=date('Y')?> <?=APP_NAME?></div></div><div class="login-right"><main class="login-card"><div class="login-card-head"><div class="login-logo-sm"><i class="bi bi-mortarboard-fill"></i></div><h2>Selamat Datang</h2><p>Masuk ke akun Anda untuk melanjutkan</p></div><?php if($flash):?><div class="alert alert-<?=$flash[0]?>"><i class="bi bi-exclamation-circle"></i> <?=e($flash[1])?></div><?php endif?><form method="post" action="?action=login" class="login-form"><?=csrf_field()?><div class="field"><label for="email">Email</label><div class="field-input"><i class="bi bi-envelope"></i><input id="email" required type="email" name="email" placeholder="nama@sekolah.id"></div></div><div class="field"><label for="password">Password</label><div class="field-input"><i class="bi bi-lock"></i><input id="password" required type="password" name="password" placeholder="Masukkan password"></div></div><button type="submit" class="btn-login">Masuk <i class="bi bi-arrow-right"></i></button></form></main></div></body></html>
<?php else:
$nav=['dashboard'=>['Dashboard','bi-grid-1x2'],'users'=>['Kelola User','bi-people'],'manage'=>['Data Master','bi-database'],'questions'=>['Bank Soal','bi-patch-question'],'exams'=>['Ujian & Jadwal','bi-calendar2-check'],'grading'=>['Koreksi Essay','bi-check2-square'],'reports'=>['Laporan Nilai','bi-bar-chart'],'student'=>['Ujian Saya','bi-pencil-square'],'profile'=>['Profil','bi-person-gear'],'settings'=>['Pengaturan','bi-gear'],'logs'=>['Log Aktivitas','bi-clock-history']];
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e(ucfirst($page))?> | <?=APP_NAME?></title><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css" rel="stylesheet"><link href="assets/css/style.css?v=<?=filemtime('assets/css/style.css')?>" rel="stylesheet"></head><body>
<aside class="sidebar">
  <a class="logo" href="?page=dashboard"><i class="bi bi-mortarboard-fill"></i> CBT<span>School</span></a>
  <div class="user-mini">
    <div class="avatar"><?=strtoupper($u['name'][0])?></div>
    <div><b><?=e($u['name'])?></b><small><?=e($u['role'])?></small></div>
  </div>
  <nav>
    <?php foreach($nav as $k=>$v):
      if($k==='student' && $u['role']!=='Siswa') continue;
      if($k==='grading' && $u['role']==='Siswa') continue;
      if(in_array($k,['settings','logs','users','manage']) && $u['role']!=='Admin') continue;
      if(in_array($k,['questions','exams','reports']) && $u['role']==='Siswa') continue;
    ?>
    <a class="<?=($page===$k?'active':'')?>" href="?page=<?=$k?>"><i class="bi <?=$v[1]?>"></i><?=$v[0]?></a>
    <?php endforeach?>
  </nav>
  <a class="logout" href="?action=logout"><i class="bi bi-box-arrow-left"></i> Keluar</a>
</aside>
<div class="sidebar-backdrop" onclick="document.querySelector('.sidebar').classList.remove('open')"></div>
<main class="content">
  <header>
    <button class="btn d-md-none" onclick="document.querySelector('.sidebar').classList.toggle('open')"><i class="bi bi-list"></i></button>
    <div><h4><?=e($nav[$page][0]??'Halaman')?></h4><small><?=date('l, d F Y')?></small></div>
    <div class="ms-auto d-flex align-items-center gap-2"><a href="#" id="darkModeToggle" title="Tema Gelap"><i class="bi bi-moon-stars"></i></a><i class="bi bi-bell"></i></div>
  </header>
  <?php if($flash):?><div class="alert alert-<?=$flash[0]?> alert-dismissible fade show"><?=e($flash[1])?><button class="btn-close" data-bs-dismiss="alert"></button></div><?php endif?>

<?php if($page==='dashboard'):
  $tid=$u['role']==='Guru'?q('SELECT id FROM teachers WHERE user_id=?',[$u['id']])->fetchColumn():null;
  if($u['role']==='Siswa'){
    $stats=['Ujian Aktif'=>q("SELECT COUNT(*) FROM schedules sc JOIN students s ON s.class_id=sc.class_id WHERE s.user_id=? AND NOW() BETWEEN sc.starts_at AND sc.ends_at",[$u['id']])->fetchColumn(),'Riwayat'=>q('SELECT COUNT(*) FROM exam_results r JOIN students s ON s.id=r.student_id WHERE s.user_id=?',[$u['id']])->fetchColumn()];
  } elseif($u['role']==='Guru') {
    $stats=['Soal Saya'=>q('SELECT COUNT(*) FROM questions WHERE teacher_id=?',[$tid])->fetchColumn(),'Ujian Saya'=>q('SELECT COUNT(*) FROM exams WHERE teacher_id=?',[$tid])->fetchColumn(),'Jadwal Aktif'=>q("SELECT COUNT(*) FROM schedules sc JOIN exams e ON e.id=sc.exam_id WHERE e.teacher_id=? AND NOW() BETWEEN sc.starts_at AND sc.ends_at",[$tid])->fetchColumn(),'Total Siswa'=>q('SELECT COUNT(*) FROM students')->fetchColumn()];
  } else {
    $stats=['Total Siswa'=>q('SELECT COUNT(*) FROM students')->fetchColumn(),'Total Guru'=>q('SELECT COUNT(*) FROM teachers')->fetchColumn(),'Bank Soal'=>q('SELECT COUNT(*) FROM questions')->fetchColumn(),'Ujian'=>q('SELECT COUNT(*) FROM exams')->fetchColumn()];
  }
?>
<section class="row g-3 mb-4">
  <?php foreach($stats as $n=>$v):?>
  <div class="col-sm-6 col-xl-3"><div class="stat-card"><div><small><?=$n?></small><h2><?=$v?></h2></div><i class="bi bi-graph-up-arrow"></i></div></div>
  <?php endforeach?>
</section>
<section class="panel"><h5>Selamat datang, <?=e($u['name'])?></h5><p class="mb-0 text-muted">Gunakan menu di samping untuk mengelola dan memantau pelaksanaan ujian.</p></section>

<?php elseif($page==='manage'):
  role('Admin');
  $entity=$_GET['entity']??'classes';
  $cfg=['classes'=>['Kelas',['name'=>'Nama Kelas','major_id'=>'Jurusan','academic_year_id'=>'Tahun Ajaran']],'subjects'=>['Mata Pelajaran',['name'=>'Nama','code'=>'Kode','teacher_id'=>'Guru']],'majors'=>['Jurusan',['name'=>'Nama','code'=>'Kode']],'academic_years'=>['Tahun Ajaran',['name'=>'Tahun','active'=>'Aktif (1/0)']]];
  if(!isset($cfg[$entity]))$entity='classes';
  [$title,$fields]=$cfg[$entity];
  $rows=q("SELECT * FROM $entity ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="d-flex gap-2 flex-wrap mb-3">
  <?php foreach($cfg as $k=>$x):?>
  <a href="?page=manage&entity=<?=$k?>" class="btn btn-sm <?=$entity===$k?'btn-primary':'btn-light'?>"><?=$x[0]?></a>
  <?php endforeach?>
  <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#form"><i class="bi bi-plus"></i> Tambah <?=$title?></button>
</div>
<div class="panel table-responsive">
  <table class="table data-table">
    <thead><tr><th>ID</th><?php foreach($fields as $l):?><th><?=$l?></th><?php endforeach?><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach($rows as $r):?>
    <tr>
      <td><?=$r['id']?></td>
      <?php foreach(array_keys($fields) as $f):?><td><?=e((string)($r[$f]??''))?></td><?php endforeach?>
      <td class="text-nowrap">
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit_<?=$r['id']?>" title="Edit"><i class="bi bi-pencil"></i></button>
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
<label class="form-label mt-2"><?=$l?></label>
<?php if($f==='major_id'):?>
<select class="form-select" name="major_id"><option value="">-</option><?=opts('majors')?></select>
<?php elseif($f==='academic_year_id'):?>
<select class="form-select" name="academic_year_id"><option value="">-</option><?=opts('academic_years')?></select>
<?php elseif($f==='teacher_id'):?>
<select class="form-select" name="teacher_id"><option value="">-</option><?=opts('teachers')?></select>
<?php else:?>
<input name="<?=$f?>" class="form-control" required>
<?php endif;?>
<?php endforeach?>
<?php modal('form','Tambah '.$title,ob_get_clean());?>
<?php foreach($rows as $r):?>
<?php ob_start();?>
<input type="hidden" name="entity" value="<?=$entity?>">
<input type="hidden" name="action" value="save">
<input type="hidden" name="id" value="<?=$r['id']?>">
<?php foreach($fields as $f=>$l):?>
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
<input name="<?=$f?>" class="form-control" value="<?=e((string)($r[$f]??''))?>" required>
<?php endif;?>
<?php endforeach?>
<?php modal('edit_'.$r['id'],'Edit '.$title,ob_get_clean());?>
<?php endforeach?>

<?php elseif($page==='users'):
  role('Admin');
  $rows=q('SELECT u.*,r.name role FROM users u JOIN roles r ON r.id=u.role_id ORDER BY u.id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#userform"><i class="bi bi-person-plus"></i> Tambah User</button>
<div class="panel table-responsive">
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
        <form method="post" action="?action=delete" style="display:inline" onsubmit="return confirm('Hapus user ini?')"><?=csrf_field()?><input type="hidden" name="entity" value="users"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button></form>
      </td>
    </tr>
    <?php endforeach?>
    </tbody>
  </table>
</div>
<?php ob_start();?>
<input type="hidden" name="action" value="user_save">
<label class="form-label">Nama</label>
<input name="name" required class="form-control">
<label class="form-label mt-2">Email</label>
<input name="email" type="email" required class="form-control">
<label class="form-label mt-2">Password</label>
<input name="password" type="password" required class="form-control">
<label class="form-label mt-2">Role</label>
<select name="role_id" class="form-select">
  <option value="2">Guru</option>
  <option value="3">Siswa</option>
  <option value="1">Admin</option>
</select>
<label class="form-label mt-2">Kelas (untuk siswa)</label>
<select name="class_id" class="form-select"><option value="">-</option><?=opts('classes')?></select>
<?php modal('userform','Tambah User',ob_get_clean());?>

<?php foreach($rows as $r):?>
<?php ob_start();?>
<input type="hidden" name="action" value="user_save">
<input type="hidden" name="id" value="<?=$r['id']?>">
<label class="form-label">Nama</label>
<input name="name" required class="form-control" value="<?=e($r['name'])?>">
<label class="form-label mt-2">Email</label>
<input name="email" type="email" required class="form-control" value="<?=e($r['email'])?>">
<label class="form-label mt-2">Password (kosongkan jika tidak ubah)</label>
<input name="password" type="password" class="form-control">
<label class="form-label mt-2">Role</label>
<select name="role_id" class="form-select">
  <option value="1" <?=$r['role']==='Admin'?'selected':''?>>Admin</option>
  <option value="2" <?=$r['role']==='Guru'?'selected':''?>>Guru</option>
  <option value="3" <?=$r['role']==='Siswa'?'selected':''?>>Siswa</option>
</select>
<label class="form-label mt-2">Status</label>
<select name="active" class="form-select">
  <option value="1" <?=$r['active']?'selected':''?>>Aktif</option>
  <option value="0" <?=!$r['active']?'selected':''?>>Nonaktif</option>
</select>
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
<label class="form-label">Mata Pelajaran</label>
<select name="subject_id" required class="form-select"><?=opts('subjects')?></select>
<label class="form-label mt-2">Jenis</label>
<select name="type" id="qtype" class="form-select"><option value="multiple">Pilihan Ganda</option><option value="essay">Essay</option></select>
<label class="form-label mt-2">Pertanyaan</label>
<textarea name="question" required class="form-control"></textarea>
<label class="form-label mt-2">Bobot</label>
<input name="weight" type="number" step=".01" value="1" class="form-control">
<div id="choices">
  <label class="form-label mt-3">Pilihan jawaban (centang jawaban benar)</label>
  <?php foreach(['A','B','C','D','E'] as $l):?>
  <div class="input-group mb-2"><span class="input-group-text"><input type="radio" name="correct" value="<?=$l?>" <?=$l==='A'?'checked':''?>></span><span class="input-group-text"><?=$l?></span><input name="choice_<?=$l?>" class="form-control" <?=$l!=='E'?'required':''?>></div>
  <?php endforeach?>
</div>
<?php modal('qform','Buat Soal',ob_get_clean());?>

<?php foreach($rows as $r):
  $choices=q('SELECT * FROM choices WHERE question_id=? ORDER BY label',[$r['id']])->fetchAll(PDO::FETCH_ASSOC);
  $correct=q('SELECT label FROM choices WHERE question_id=? AND is_correct=1',[$r['id']])->fetchColumn();
?>
<?php ob_start();?>
<input type="hidden" name="action" value="question_save">
<input type="hidden" name="id" value="<?=$r['id']?>">
<label class="form-label">Mata Pelajaran</label>
<select name="subject_id" required class="form-select"><?=opts('subjects',$r['subject_id'])?></select>
<label class="form-label mt-2">Jenis</label>
<select name="type" class="form-select qtype-edit" data-target="choices_<?=$r['id']?>"><option value="multiple" <?=$r['type']==='multiple'?'selected':''?>>Pilihan Ganda</option><option value="essay" <?=$r['type']==='essay'?'selected':''?>>Essay</option></select>
<label class="form-label mt-2">Pertanyaan</label>
<textarea name="question" required class="form-control"><?=e($r['question'])?></textarea>
<label class="form-label mt-2">Bobot</label>
<input name="weight" type="number" step=".01" value="<?=$r['weight']?>" class="form-control">
<div id="choices_<?=$r['id']?>" style="<?=$r['type']==='essay'?'display:none':''?>">
  <label class="form-label mt-3">Pilihan jawaban (centang jawaban benar)</label>
  <?php foreach(['A','B','C','D','E'] as $l):
    $txt=''; $chk='';
    foreach($choices as $c){if($c['label']===$l){$txt=$c['choice_text'];if($c['is_correct'])$chk='checked';break;}}
  ?>
  <div class="input-group mb-2"><span class="input-group-text"><input type="radio" name="correct" value="<?=$l?>" <?=$chk?:($l==='A'?'checked':'')?>></span><span class="input-group-text"><?=$l?></span><input name="choice_<?=$l?>" class="form-control" value="<?=e($txt)?>" <?=$l!=='E'?'required':''?>></div>
  <?php endforeach?>
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
      </td>
    </tr>
    <?php endforeach?>
    </tbody>
  </table>
</div>
<?php ob_start();
if($tid){
  $questions=q('SELECT q.id,q.question,s.name FROM questions q JOIN subjects s ON s.id=q.subject_id WHERE q.teacher_id=? ORDER BY q.id DESC',[$tid])->fetchAll(PDO::FETCH_ASSOC);
} else {
  $questions=q('SELECT q.id,q.question,s.name FROM questions q JOIN subjects s ON s.id=q.subject_id ORDER BY q.id DESC')->fetchAll(PDO::FETCH_ASSOC);
}
?>
<input type="hidden" name="action" value="exam_save">
<label class="form-label">Judul Ujian</label>
<input name="title" required class="form-control">
<label class="form-label mt-2">Mata Pelajaran</label>
<select name="subject_id" class="form-select"><?=opts('subjects')?></select>
<label class="form-label mt-2">Deskripsi</label>
<textarea name="description" class="form-control"></textarea>
<div class="row mt-2">
  <div class="col"><label class="form-label">Durasi (menit)</label><input name="duration" type="number" value="60" required class="form-control"></div>
  <div class="col"><label class="form-label">Token (kosong=otomatis)</label><input name="token" class="form-control"></div>
</div>
<div class="mt-3">
  <label><input type="checkbox" name="random_questions" checked> Acak soal</label>
  <label class="ms-3"><input type="checkbox" name="random_choices" checked> Acak pilihan</label>
  <label class="ms-3"><input type="checkbox" name="show_score"> Tampilkan nilai</label>
</div>
<label class="form-label mt-3">Pilih Soal</label>
<div class="question-picker">
<?php foreach($questions as $x):?>
<label><input type="checkbox" name="questions[]" value="<?=$x['id']?>"> [<?=e($x['name'])?>] <?=e(mb_strimwidth($x['question'],0,60,'...'))?></label>
<?php endforeach?>
</div>
<div class="row mt-3">
  <div class="col-md-4"><label class="form-label">Kelas</label><select name="schedule_class" class="form-select"><option value="">-</option><?=opts('classes')?></select></div>
  <div class="col-md-4"><label class="form-label">Mulai</label><input name="starts_at" type="datetime-local" class="form-control"></div>
  <div class="col-md-4"><label class="form-label">Selesai</label><input name="ends_at" type="datetime-local" class="form-control"></div>
</div>
<?php modal('eform','Buat Ujian',ob_get_clean());?>

<?php foreach($rows as $r):
  $eq=q('SELECT question_id FROM exam_questions WHERE exam_id=?',[$r['id']])->fetchAll(PDO::FETCH_COLUMN);
  $sc=q('SELECT * FROM schedules WHERE exam_id=? LIMIT 1',[$r['id']])->fetch(PDO::FETCH_ASSOC);
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
  <label><input type="checkbox" name="random_questions" <?=$r['random_questions']?'checked':''?>> Acak soal</label>
  <label class="ms-3"><input type="checkbox" name="random_choices" <?=$r['random_choices']?'checked':''?>> Acak pilihan</label>
  <label class="ms-3"><input type="checkbox" name="show_score" <?=$r['show_score']?'checked':''?>> Tampilkan nilai</label>
</div>
<label class="form-label mt-3">Pilih Soal</label>
<div class="question-picker">
<?php foreach($editQ as $x):?>
<label><input type="checkbox" name="questions[]" value="<?=$x['id']?>" <?=in_array($x['id'],$eq)?'checked':''?>> [<?=e($x['name'])?>] <?=e(mb_strimwidth($x['question'],0,60,'...'))?></label>
<?php endforeach?>
</div>
<div class="row mt-3">
  <div class="col-md-4"><label class="form-label">Kelas</label><select name="schedule_class" class="form-select"><option value="">-</option><?=opts('classes',$sc['class_id']??'')?></select></div>
  <div class="col-md-4"><label class="form-label">Mulai</label><input name="starts_at" type="datetime-local" class="form-control" value="<?=isset($sc['starts_at'])?str_replace(' ','T',$sc['starts_at']):''?>"></div>
  <div class="col-md-4"><label class="form-label">Selesai</label><input name="ends_at" type="datetime-local" class="form-control" value="<?=isset($sc['ends_at'])?str_replace(' ','T',$sc['ends_at']):''?>"></div>
</div>
<?php modal('eedit_'.$r['id'],'Edit Ujian',ob_get_clean());?>
<?php endforeach?>

<?php elseif($page==='student'):
  role('Siswa');
  $active=q("SELECT e.*,sc.starts_at,sc.ends_at FROM schedules sc JOIN exams e ON e.id=sc.exam_id JOIN students s ON s.class_id=sc.class_id WHERE s.user_id=? ORDER BY sc.starts_at DESC",[$u['id']])->fetchAll(PDO::FETCH_ASSOC);
  $history=q('SELECT e.title,r.* FROM exam_results r JOIN students s ON s.id=r.student_id JOIN exams e ON e.id=r.exam_id WHERE s.user_id=? ORDER BY r.id DESC',[$u['id']])->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="panel mb-3">
  <h5>Ujian Tersedia</h5>
  <?php
    $now=date('Y-m-d H:i:s');
    $available=array_filter($active,fn($x)=>$x['starts_at']<=$now&&$x['ends_at']>=$now);
    $inProgress=q('SELECT exam_id FROM exam_results r JOIN students s ON s.id=r.student_id WHERE s.user_id=? AND r.status="in_progress"',[$u['id']])->fetchAll(PDO::FETCH_COLUMN);
  ?>
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
  <p class="text-muted mb-0">Tidak ada ujian aktif untuk kelas Anda saat ini.</p>
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
  <?php if(!$active)echo '<p class="text-muted mb-0">Belum ada jadwal untuk kelas Anda.</p>'?>
</div>
<div class="panel">
  <h5>Riwayat Ujian</h5>
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
      <td><?php if($x['status']!=='in_progress'):?><a href="?page=review&result=<?=$x['id']?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a><?php endif?></td>
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
  $qs=q('SELECT q.* FROM exam_questions eq JOIN questions q ON q.id=eq.question_id WHERE eq.exam_id=?'.($result['random_questions']?' ORDER BY RAND()':' ORDER BY q.id'),[$result['exam_id']])->fetchAll(PDO::FETCH_ASSOC); $totalQ=count($qs);
  $answerData=[]; foreach($qs as $x){$ans=q('SELECT answer_text,flagged FROM answers WHERE result_id=? AND question_id=?',[$rid,$x['id']])->fetch(PDO::FETCH_ASSOC);$answerData[$x['id']]=['answer'=>$ans['answer_text']??'','flagged'=>(int)($ans['flagged']??0)];}
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
      <?php foreach($qs as $n=>$x):
        $ad=$answerData[$x['id']]; $ans=$ad['answer']; $flag=$ad['flagged'];
      ?>
      <section class="panel question" data-q="<?=$x['id']?>" data-no="<?=$n+1?>">
        <div class="d-flex justify-content-between align-items-start">
          <span class="q-num">Soal <?=$n+1?></span>
          <button type="button" class="btn btn-sm btn-outline-warning ragu-btn <?=$flag?'active':''?>" data-question="<?=$x['id']?>" title="Tandai ragu-ragu"><i class="bi bi-flag<?=$flag?'-fill':''?>"></i> <span>Ragu</span></button>
        </div>
        <p><?=nl2br(e($x['question']))?></p>
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
      <button class="btn btn-success btn-lg mb-5" onclick="return confirm('Kumpulkan jawaban sekarang?')"><i class="bi bi-send"></i> Kumpulkan Ujian</button>
    </form>
  </div>
  <div class="exam-nav" id="examNav">
    <div class="exam-nav-header">Navigasi Soal</div>
    <div class="exam-nav-grid">
      <?php foreach($qs as $n=>$x):
        $ad=$answerData[$x['id']]; $cls='unanswered'; $title='Belum dijawab';
        if($ad['flagged']){$cls='flagged';$title='Ditandai ragu';}
        if($ad['answer']!==''){$cls=$cls==='flagged'?'flagged':($x['type']==='essay'&&$ad['answer']!==''?'answered':'answered');$title=$ad['flagged']?'Terjawab & ditandai':'Terjawab';}
      ?>
      <a href="#q<?=$x['id']?>" class="nav-q <?=$cls?>" title="<?=$title?>" data-q="<?=$x['id']?>"><?=$n+1?></a>
      <?php endforeach?>
    </div>
    <div class="exam-nav-legend">
      <span><i class="bi bi-check-circle-fill text-success"></i> Terjawab</span>
      <span><i class="bi bi-flag-fill text-warning"></i> Ragu</span>
      <span><i class="bi bi-circle text-secondary"></i> Kosong</span>
    </div>
  </div>
</div>

<?php elseif($page==='review'):
  role('Siswa');
  $rid=(int)$_GET['result'];
  $result=q('SELECT r.*,e.title,e.duration,e.show_score,s.name subject_name FROM exam_results r JOIN exams e ON e.id=r.exam_id JOIN subjects s ON s.id=e.subject_id JOIN students st ON st.id=r.student_id WHERE r.id=? AND st.user_id=? AND r.status IN("submitted","graded")',[$rid,$u['id']])->fetch(PDO::FETCH_ASSOC);
  if(!$result)redirect('page=student');
  $qs=q('SELECT q.* FROM exam_questions eq JOIN questions q ON q.id=eq.question_id WHERE eq.exam_id=? ORDER BY q.id',[$result['exam_id']])->fetchAll(PDO::FETCH_ASSOC);
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
<?php foreach($qs as $n=>$x):
  $a=q('SELECT a.*,c.label correct_label,c.choice_text correct_text FROM answers a LEFT JOIN choices c ON c.question_id=a.question_id AND c.is_correct=1 WHERE a.result_id=? AND a.question_id=?',[$rid,$x['id']])->fetch(PDO::FETCH_ASSOC);
  $choices=$x['type']==='multiple'?q('SELECT * FROM choices WHERE question_id=? ORDER BY label',[$x['id']])->fetchAll(PDO::FETCH_ASSOC):[];
?>
<div class="panel question mb-2">
  <span class="q-num">Soal <?=$n+1?></span>
  <?php if($a && $a['is_correct']!==null):?><span class="badge ms-2 <?=$a['is_correct']?'badge-active':'badge-inactive'?>"><?=$a['is_correct']?'Benar':'Salah'?></span><?php endif?>
  <p><?=nl2br(e($x['question']))?></p>
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
      $essays=q('SELECT q.id,q.question,q.weight,a.answer_text,a.score a_score FROM exam_questions eq JOIN questions q ON q.id=eq.question_id LEFT JOIN answers a ON a.question_id=q.id AND a.result_id=? WHERE q.type="essay" AND eq.exam_id=?',[$result['id'],$eid])->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="panel mb-3"><h5><?=e($result['title'])?> – <?=e($result['siswa'])?></h5></div>
    <form method="post" action="?action=grade_save">
      <?=csrf_field()?>
      <input type="hidden" name="result_id" value="<?=$result['id']?>">
      <?php foreach($essays as $e):?>
      <div class="panel mb-2">
        <p><strong>Soal:</strong> <?=nl2br(e($e['question']))?></p>
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
  <?php if($eid):
    $students=q("SELECT DISTINCT u.id user_id,u.name,r.id result_id,r.status FROM exam_results r JOIN students s ON s.id=r.student_id JOIN users u ON u.id=s.user_id JOIN exams e ON e.id=r.exam_id WHERE r.exam_id=? AND r.status='submitted'".($tid?" AND e.teacher_id=$tid":'')." ORDER BY u.name",[$eid])->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <table class="table data-table">
    <thead><tr><th>Siswa</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if(!$students):?><tr><td colspan="3" class="text-muted">Tidak ada ujian dengan status "Dikumpulkan" yang perlu dikoreksi.</td></tr><?php endif?>
    <?php foreach($students as $s):?>
    <tr><td><?=e($s['name'])?></td><td><span class="badge badge-info">Dikumpulkan</span></td><td><a href="?page=grading&exam_id=<?=$eid?>&student_id=<?=$s['user_id']?>" class="btn btn-sm btn-primary">Koreksi</a></td></tr>
    <?php endforeach?>
    </tbody>
  </table>
  <?php endif?>
</div>
<?php endif;?>

<?php else:?>
<div class="panel">Halaman tidak ditemukan.</div>
<?php endif;?>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
<script>$('.data-table').DataTable({language:{search:'Cari:',lengthMenu:'Tampilkan _MENU_ data',info:'Menampilkan _START_–_END_ dari _TOTAL_ data',zeroRecords:'Tidak ada data'}})</script>
<script>
document.querySelectorAll('.qtype-edit').forEach(el=>el.addEventListener('change',function(){document.getElementById(this.dataset.target).style.display=this.value==='essay'?'none':'block'}));
</script>
<script src="assets/js/app.js"></script>
</body></html>
<?php endif?>
