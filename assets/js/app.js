document.addEventListener('DOMContentLoaded',()=>{
  const type=document.querySelector('#qtype'), choices=document.querySelector('#choices');
  if(type&&choices) type.addEventListener('change',()=>choices.style.display=type.value==='essay'?'none':'block');

  const timer=document.querySelector('#timer');
  if(timer){
    const examForm=document.getElementById('examForm');
    const resultId=document.querySelector('[name=result]')?.value;
    const cheatWarning=document.getElementById('cheatWarning');
    const warnCount=document.getElementById('warningCount');
    let warnings=0;
    const MAX_WARNINGS=Number(warnCount?.dataset?.max||3);
    let fsAttempts=0;
    let fullscreenInterval;

    function updateWarnings(){
      if(warnCount)warnCount.querySelector('span').textContent=warnings;
      if(cheatWarning){
        const left=MAX_WARNINGS-warnings;
        cheatWarning.querySelector('span').textContent=`Peringatan ${warnings}/${MAX_WARNINGS}. ${left>0?`${left} pelanggaran lagi ujian akan dikumpulkan otomatis.`:'Ujian akan dikumpulkan!'}`;
        cheatWarning.classList.toggle('d-none',warnings===0);
      }
    }

    function reportViolation(type){
      if(!resultId)return;
      fetch('index.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams({action:'report_violation',result:resultId,type:type})}).then(r=>r.json()).then(d=>{if(d.ok){warnings=d.warnings;updateWarnings();if(warnings>=MAX_WARNINGS&&examForm){examForm.submit()}}});
    }

    function tryFullscreen(){
      fsAttempts++;
      if(!document.fullscreenElement&&!document.webkitFullscreenElement){
        const el=document.documentElement;
        if(el.requestFullscreen) el.requestFullscreen().catch(()=>{});
        else if(el.webkitRequestFullscreen) el.webkitRequestFullscreen();
      }
    }

    function onFullscreenChange(){
      if(!document.fullscreenElement&&!document.webkitFullscreenElement){
        reportViolation('fullscreen_exit');
        if(fsAttempts<5)setTimeout(tryFullscreen,500);
      }
    }

    document.addEventListener('fullscreenchange',onFullscreenChange);
    document.addEventListener('webkitfullscreenchange',onFullscreenChange);
    document.addEventListener('click',tryFullscreen,{once:true});
    document.addEventListener('touchstart',tryFullscreen,{once:true});
    fullscreenInterval=setInterval(()=>{if(!document.fullscreenElement&&!document.webkitFullscreenElement)tryFullscreen()},30000);

    document.addEventListener('visibilitychange',()=>{if(document.hidden)reportViolation('tab_switch')});
    window.addEventListener('blur',()=>reportViolation('window_blur'));
    document.addEventListener('contextmenu',e=>{e.preventDefault();reportViolation('right_click')});
    document.addEventListener('copy',e=>e.preventDefault());
    document.addEventListener('cut',e=>e.preventDefault());
    document.addEventListener('paste',e=>e.preventDefault());
    document.addEventListener('keydown',e=>{
      if(e.ctrlKey&&['c','v','x','u','s','p','a'].includes(e.key.toLowerCase())){e.preventDefault();reportViolation('keyboard_shortcut')}
      if(e.key==='F12'||(e.ctrlKey&&e.shiftKey&&['i','j','c'].includes(e.key.toLowerCase()))){e.preventDefault();reportViolation('keyboard_shortcut')}
      if(e.key==='Escape'&&document.fullscreenElement){e.preventDefault();reportViolation('fullscreen_exit')}
    });

    const update=()=>{let left=Number(timer.dataset.end)-Math.floor(Date.now()/1000);if(left<=0){clearInterval(fullscreenInterval);examForm.submit();return}timer.textContent=`${String(Math.floor(left/60)).padStart(2,'0')}:${String(left%60).padStart(2,'0')}`};
    update();setInterval(update,1000);
    document.querySelectorAll('.answer').forEach(el=>el.addEventListener('change',()=>{const data=new URLSearchParams({action:'save_answer',result:resultId,question:el.dataset.question,answer:el.value});fetch('index.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:data})}));
    document.querySelectorAll('textarea.answer').forEach(el=>el.addEventListener('input',()=>{clearTimeout(el._save);el._save=setTimeout(()=>el.dispatchEvent(new Event('change')),700)}));
  }

  const sidebar=document.querySelector('.sidebar');
  if(sidebar){
    sidebar.querySelectorAll('nav a').forEach(a=>{a.addEventListener('click',()=>{if(window.innerWidth<768){sidebar.classList.remove('open');document.body.classList.remove('sidebar-open')}})});
    document.addEventListener('keydown',e=>{if(e.key==='Escape'&&sidebar.classList.contains('open')){sidebar.classList.remove('open');document.body.classList.remove('sidebar-open')}});
    new MutationObserver(()=>document.body.classList.toggle('sidebar-open',sidebar.classList.contains('open'))).observe(sidebar,{attributes:true,attributeFilter:['class']});
  }
  window.addEventListener('resize',()=>{if(window.innerWidth>=768&&sidebar&&sidebar.classList.contains('open')){sidebar.classList.remove('open');document.body.classList.remove('sidebar-open')}});
});
