<?php
$p = 'assets/js/app.js';
$s = file_get_contents($p);

$old = "    update();setInterval(update,1000);";

$new = "    update();setInterval(update,1000);\n\n    // --- Enforce timer per bagian soal ---\n    const sectionHeaders=document.querySelectorAll('.section-header[data-timer]');\n    if(sectionHeaders.length){\n      const sectionTimers={};\n      let currentSection=null;\n      sectionHeaders.forEach(h=>{\n        const sid=h.dataset.sectionId;\n        const timerMin=Number(h.dataset.timer||0);\n        if(timerMin>0&&!sectionTimers[sid]){\n          sectionTimers[sid]={end:Math.floor(Date.now()/1000)+timerMin*60,el:h.querySelector('.section-timer')};\n        }\n      });\n      setInterval(()=>{\n        const now=Math.floor(Date.now()/1000);\n        Object.keys(sectionTimers).forEach(sid=>{\n          const st=sectionTimers[sid];\n          const left=st.end-now;\n          if(st.el){\n            if(left<=0){st.el.textContent='Waktu habis!';}\n            else{st.el.textContent=String(Math.floor(left/60)).padStart(2,'0')+':'+String(left%60).padStart(2,'0');}\n          }\n          if(left<=0&&examForm&&!examForm.dataset.submitted){examForm.dataset.submitted='1';examForm.submit();}\n        });\n      },1000);\n    }";

if (strpos($s, $old) !== false) {
    $s = str_replace($old, $new, $s);
    file_put_contents($p, $s);
    echo "section timer js added\n";
} else {
    echo "PATTERN NOT FOUND\n";
}