<?php
$p = 'views/layout.php';
$s = file_get_contents($p);

// Cari section-header di halaman attempt yang berisi $curSection['timer']
$pattern = '/<div class="section-header">\s*<h6 class="mb-0">\s*<\?=e\(\$curSection\[\'title\'\]\)\?>\s*<\/h6>\s*<\?php if\(\$curSection\[\'timer\'\]\):\?><small><i class="bi bi-clock"><\/i> <\?=\$curSection\[\'timer\'\]\?> menit<\/small><\?php endif\?>\s*<\/div>/';

$replacement = '<div class="section-header" data-section-id="<?=$sid?>" data-timer="<?=$curSection[\'timer\']??0?>">
          <h6 class="mb-0"><?=e($curSection[\'title\'])?></h6>
          <?php if($curSection[\'timer\']):?><small><i class="bi bi-clock"></i> <span class="section-timer" data-end="0"><?=$curSection[\'timer\']?> menit</span></small><?php endif?>
        </div>';

if (preg_match($pattern, $s, $m)) {
    $s = preg_replace($pattern, $replacement, $s, 1);
    file_put_contents($p, $s);
    echo "section timer attr added\n";
} else {
    echo "PATTERN NOT FOUND\n";
}