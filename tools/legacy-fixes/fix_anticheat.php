<?php
$p = 'assets/js/app.js';
$s = file_get_contents($p);

// Tambahkan blokir screenshot, tab baru, kunci mouse, deteksi remote/VM setelah blokir keyboard shortcut
$old = "    document.addEventListener('keydown',e=>{\n      if(e.ctrlKey&&['c','v','x','u','s','p','a'].includes(e.key.toLowerCase())){e.preventDefault();reportViolation('keyboard_shortcut')}\n      if(e.key==='F12'||(e.ctrlKey&&e.shiftKey&&['i','j','c'].includes(e.key.toLowerCase()))){e.preventDefault();reportViolation('keyboard_shortcut')}\n      if(e.key==='Escape'&&document.fullscreenElement){e.preventDefault();reportViolation('fullscreen_exit')}\n    });";

$new = "    document.addEventListener('keydown',e=>{\n      if(e.ctrlKey&&['c','v','x','u','s','p','a'].includes(e.key.toLowerCase())){e.preventDefault();reportViolation('keyboard_shortcut')}\n      if(e.key==='F12'||(e.ctrlKey&&e.shiftKey&&['i','j','c'].includes(e.key.toLowerCase()))){e.preventDefault();reportViolation('keyboard_shortcut')}\n      if(e.key==='Escape'&&document.fullscreenElement){e.preventDefault();reportViolation('fullscreen_exit')}\n      // Blokir screenshot (PrtScn, Win+Shift+S, Win+PrtScn)\n      if(e.key==='PrintScreen'){e.preventDefault();reportViolation('keyboard_shortcut')}\n      if(e.ctrlKey&&e.shiftKey&&e.key.toLowerCase()==='s'){e.preventDefault();reportViolation('keyboard_shortcut')}\n      // Blokir tab baru\n      if((e.ctrlKey||e.metaKey)&&e.key.toLowerCase()==='t'){e.preventDefault();reportViolation('keyboard_shortcut')}\n      if(e.button===1){e.preventDefault();reportViolation('keyboard_shortcut')}\n    });\n    // Kunci mouse di area ujian\n    document.addEventListener('mousemove',e=>{\n      if(e.clientX<0||e.clientY<0||e.clientX>window.innerWidth||e.clientY>window.innerHeight){reportViolation('window_blur')}\n    });\n    // Deteksi remote desktop / VM (heuristic)\n    try{\n      const ua=navigator.userAgent.toLowerCase();\n      if(/teamviewer|anydesk|remote|vnc|vmware|virtualbox|qemu|hyper-v|parallels/.test(ua)){reportViolation('devtools')}\n    }catch(e){}\n    // Deteksi VM via hardware concurrency rendah + deviceMemory\n    try{\n      if(navigator.hardwareConcurrency&&navigator.hardwareConcurrency<=2){reportViolation('devtools')}\n      if(navigator.deviceMemory&&navigator.deviceMemory<=2){reportViolation('devtools')}\n    }catch(e){}";

if (strpos($s, $old) !== false) {
    $s = str_replace($old, $new, $s);
    file_put_contents($p, $s);
    echo "anticheat added\n";
} else {
    echo "PATTERN NOT FOUND\n";
}
