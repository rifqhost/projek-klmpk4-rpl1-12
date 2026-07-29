document.addEventListener('DOMContentLoaded',()=>{
  const type=document.querySelector('#qtype'), choices=document.querySelector('#choices');
  if(type&&choices) type.addEventListener('change',()=>choices.style.display=type.value==='essay'?'none':'block');

  const timer=document.querySelector('#timer');
  if(timer){
    const examForm=document.getElementById('examForm');
    const resultId=document.querySelector('[name=result]')?.value;
    const cheatWarning=document.getElementById('cheatWarning');
    const timeWarning=document.getElementById('timeWarning');
    const warnCount=document.getElementById('warningCount');
    let warnings=0;
    const MAX_WARNINGS=Number(warnCount?.dataset?.max||3);
    let fsAttempts=0;
    let fullscreenInterval;
    let _5minShown=false,_1minShown=false;

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

    const update=()=>{
      let left=Number(timer.dataset.end)-Math.floor(Date.now()/1000);
      if(left<=0){clearInterval(fullscreenInterval);examForm.submit();return}
      timer.textContent=`${String(Math.floor(left/60)).padStart(2,'0')}:${String(left%60).padStart(2,'0')}`;
      const totalMin=Number(timer.dataset.duration);
      if(!_5minShown&&left<=300&&left>180){_5minShown=true;if(timeWarning){timeWarning.querySelector('span').textContent='Sisa waktu kurang dari 5 menit! Segera periksa jawaban Anda.';timeWarning.classList.remove('d-none');setTimeout(()=>timeWarning.classList.add('d-none'),8000)}}
      if(!_1minShown&&left<=60){_1minShown=true;if(timeWarning){timeWarning.querySelector('span').textContent='Sisa waktu kurang dari 1 menit!';timeWarning.classList.remove('d-none')}}
    };
    update();setInterval(update,1000);
    document.querySelectorAll('.answer').forEach(el=>el.addEventListener('change',()=>{
      const data=new URLSearchParams({action:'save_answer',result:resultId,question:el.dataset.question,answer:el.value});
      fetch('index.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:data});
      updateNavStatus();
    }));
    document.querySelectorAll('textarea.answer').forEach(el=>el.addEventListener('input',()=>{clearTimeout(el._save);el._save=setTimeout(()=>el.dispatchEvent(new Event('change')),700)}));
  }

  const sidebar=document.querySelector('.sidebar');
  if(sidebar){
    sidebar.querySelectorAll('nav a').forEach(a=>{a.addEventListener('click',()=>{if(window.innerWidth<768){sidebar.classList.remove('open');document.body.classList.remove('sidebar-open')}})});
    document.addEventListener('keydown',e=>{if(e.key==='Escape'&&sidebar.classList.contains('open')){sidebar.classList.remove('open');document.body.classList.remove('sidebar-open')}});
    new MutationObserver(()=>document.body.classList.toggle('sidebar-open',sidebar.classList.contains('open'))).observe(sidebar,{attributes:true,attributeFilter:['class']});
  }
  window.addEventListener('resize',()=>{if(window.innerWidth>=768&&sidebar&&sidebar.classList.contains('open')){sidebar.classList.remove('open');document.body.classList.remove('sidebar-open')}});

  const navGrid=document.querySelector('.exam-nav-grid');
  if(navGrid){
    function updateNavStatus(){
      document.querySelectorAll('.question').forEach(q=>{
        const qid=q.dataset.q;
        const no=q.dataset.no;
        const navEl=navGrid.querySelector(`[data-q="${qid}"]`);
        if(!navEl)return;
        let hasAnswer=false;
        if(q.querySelector('textarea.answer'))hasAnswer=q.querySelector('textarea.answer').value.trim()!=='';
        else hasAnswer=!!q.querySelector('input.answer:checked');
        const isFlagged=q.querySelector('.ragu-btn.active')!==null;
        navEl.className='nav-q';
        if(isFlagged)navEl.classList.add('flagged');
        else if(hasAnswer)navEl.classList.add('answered');
        else navEl.classList.add('unanswered');
      });
    }
    navGrid.addEventListener('click',e=>{const a=e.target.closest('.nav-q');if(a){e.preventDefault();const q=document.querySelector(`.question[data-q="${a.dataset.q}"]`);ifq){q.scrollIntoView({behavior:'smooth',block:'center'});q.style.transition='box-shadow .3s';q.style.boxShadow='0 0 0 3px #4f46e5';setTimeout(()=>q.style.boxShadow='',1500)}}});
    document.querySelectorAll('.answer').forEach(el=>el.addEventListener('change',updateNavStatus));
    document.querySelectorAll('textarea.answer').forEach(el=>el.addEventListener('input',()=>{clearTimeout(el._navTimer);el._navTimer=setTimeout(updateNavStatus,500)}));
    updateNavStatus();
  }

  document.querySelectorAll('.ragu-btn').forEach(btn=>{
    btn.addEventListener('click',function(){
      const qid=this.dataset.question;
      const resultId=document.querySelector('[name=result]')?.value;
      const isActive=this.classList.toggle('active');
      this.querySelector('i').className=isActive?'bi bi-flag-fill':'bi bi-flag';
      this.querySelector('span').textContent=isActive?'Ragu':'Ragu';
      if(resultId)fetch('index.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams({action:'flag_answer',result:resultId,question:qid,flagged:isActive?1:0})}).then(r=>r.json());
      if(navGrid){
        const navEl=navGrid.querySelector(`[data-q="${qid}"]`);
        if(navEl){navEl.className='nav-q';navEl.classList.add(isActive?'flagged':'unanswered');if(document.querySelector(`[data-q="${qid}"] input.answer:checked`)||(document.querySelector(`[data-q="${qid}"] textarea.answer`)?.value.trim()))navEl.classList.add(isActive?'flagged':'answered')}
      }
    });
  });

  const darkToggle=document.getElementById('darkModeToggle');
  if(darkToggle){
    function setDark(enabled){
      document.documentElement.classList.toggle('dark',enabled);
      darkToggle.querySelector('i').className=enabled?'bi bi-sun':'bi bi-moon-stars';
      darkToggle.querySelector('span').textContent=enabled?'Tema Terang':'Tema Gelap';
      try{localStorage.setItem('darkMode',enabled?'1':'0')}catch(e){}
    }
    try{setDark(localStorage.getItem('darkMode')==='1')}catch(e){}
    darkToggle.addEventListener('click',e=>{e.preventDefault();setDark(!document.documentElement.classList.contains('dark'))});
  }
});
