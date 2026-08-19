document.addEventListener('DOMContentLoaded',()=>{
  document.querySelectorAll('input[type="password"]').forEach(input=>{
    if(input.dataset.toggleReady)return;
    input.dataset.toggleReady='1';
    const wrap=input.closest('.field-input')||input.parentElement;
    if(!wrap)return;
    if(getComputedStyle(wrap).position==='static')wrap.style.position='relative';
    const btn=document.createElement('button');
    btn.type='button';
    btn.className='password-toggle';
    btn.setAttribute('aria-label','Tampilkan password');
    btn.innerHTML='<i class="bi bi-eye"></i>';
    btn.addEventListener('click',()=>{
      const show=input.type==='password';
      input.type=show?'text':'password';
      btn.setAttribute('aria-label',show?'Sembunyikan password':'Tampilkan password');
      btn.innerHTML=show?'<i class="bi bi-eye-slash"></i>':'<i class="bi bi-eye"></i>';
      input.focus();
    });
    wrap.appendChild(btn);
  });

  document.querySelectorAll('form').forEach(form=>{
    form.addEventListener('submit',event=>{
      if(event.defaultPrevented)return;
      form.classList.add('is-submitting');
      const submitter=form.querySelector('button[type="submit"], button:not([type]), .btn-login');
      if(submitter && !submitter.dataset.originalHtml){
        submitter.dataset.originalHtml=submitter.innerHTML;
        submitter.innerHTML='<span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Memproses</span>';
      }
    });
  });

  const type=document.querySelector('#qtype'), choices=document.querySelector('#choices');
  if(type&&choices) type.addEventListener('change',()=>choices.style.display=type.value==='essay'?'none':'block');

  document.querySelectorAll('.role-select').forEach(select=>{
    const form=select.closest('form')||select.closest('.quick-form');
    const sync=()=>{
      const isStudent=select.value==='3';
      form?.querySelectorAll('.student-fields').forEach(el=>el.classList.toggle('d-none',!isStudent));
      form?.querySelectorAll('.non-student-fields').forEach(el=>el.classList.toggle('d-none',isStudent));
      form?.querySelectorAll('.non-student-fields input[name="email"]').forEach(el=>el.required=!isStudent);
    };
    select.addEventListener('change',sync);
    sync();
  });

  document.querySelectorAll('.generate-password').forEach(btn=>btn.addEventListener('click',()=>{
    const input=btn.closest('.input-group')?.querySelector('.password-input');
    if(!input)return;
    const chars='ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
    let pass='Siswa';
    for(let i=0;i<6;i++)pass+=chars[Math.floor(Math.random()*chars.length)];
    input.value=pass;
    input.type='text';
    input.focus();
  }));

  document.querySelectorAll('.select-all-questions').forEach(btn=>btn.addEventListener('click',()=>{
    const wrap=btn.closest('.quick-form')||document;
    wrap.querySelectorAll('.question-picker input[type="checkbox"]').forEach(x=>x.checked=true);
  }));
  document.querySelectorAll('.clear-questions').forEach(btn=>btn.addEventListener('click',()=>{
    const wrap=btn.closest('.quick-form')||document;
    wrap.querySelectorAll('.question-picker input[type="checkbox"]').forEach(x=>x.checked=false);
  }));

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

    function doPing(){if(resultId)fetch('index.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams({action:'ping',result:resultId})});}
    doPing();setInterval(doPing,15000);

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

    // --- Deteksi DevTools (emascript) ---
    let devtoolsOpen=false;
    const dtCheck=()=>{
      const threshold=160;
      const w=window.outerWidth-window.innerWidth>threshold;
      const h=window.outerHeight-window.innerHeight>threshold;
      const isOpen=w||h;
      if(isOpen&&!devtoolsOpen){devtoolsOpen=true;reportViolation('devtools');}
      if(!isOpen)devtoolsOpen=false;
    };
    try{
      const dtElement=new Function('debugger');
      const orig=dtElement;
      setInterval(()=>{const start=performance.now();new Function('debugger')();dtCheck();},1000);
    }catch(e){setInterval(dtCheck,1000);}

    // --- Refresh/pindah halaman dihitung sebagai pelanggaran (jaringan lemot tetap boleh refresh) ---
    window.addEventListener('beforeunload',()=>{
      if(!navigator.sendBeacon)return;
      const fd=new URLSearchParams({action:'report_violation',result:resultId,type:'page_refresh'});
      navigator.sendBeacon('index.php',fd);
    });

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
      // Blokir screenshot (PrtScn, Win+Shift+S, Win+PrtScn)
      if(e.key==='PrintScreen'){e.preventDefault();reportViolation('keyboard_shortcut')}
      if(e.ctrlKey&&e.shiftKey&&e.key.toLowerCase()==='s'){e.preventDefault();reportViolation('keyboard_shortcut')}
      // Blokir tab baru
      if((e.ctrlKey||e.metaKey)&&e.key.toLowerCase()==='t'){e.preventDefault();reportViolation('keyboard_shortcut')}
      if(e.button===1){e.preventDefault();reportViolation('keyboard_shortcut')}
    });
    // Kunci mouse di area ujian
    document.addEventListener('mousemove',e=>{
      if(e.clientX<0||e.clientY<0||e.clientX>window.innerWidth||e.clientY>window.innerHeight){reportViolation('window_blur')}
    });
    // Deteksi remote desktop / VM (heuristic)
    try{
      const ua=navigator.userAgent.toLowerCase();
      if(/teamviewer|anydesk|remote|vnc|vmware|virtualbox|qemu|hyper-v|parallels/.test(ua)){reportViolation('devtools')}
    }catch(e){}
    // Deteksi VM via hardware concurrency rendah + deviceMemory
    try{
      if(navigator.hardwareConcurrency&&navigator.hardwareConcurrency<=2){reportViolation('devtools')}
      if(navigator.deviceMemory&&navigator.deviceMemory<=2){reportViolation('devtools')}
    }catch(e){}

    const update=()=>{
      let left=Number(timer.dataset.end)-Math.floor(Date.now()/1000);
      if(left<=0){clearInterval(fullscreenInterval);examForm.submit();return}
      timer.textContent=`${String(Math.floor(left/60)).padStart(2,'0')}:${String(left%60).padStart(2,'0')}`;
      const totalMin=Number(timer.dataset.duration);
      if(!_5minShown&&left<=300&&left>180){_5minShown=true;if(timeWarning){timeWarning.querySelector('span').textContent='Sisa waktu kurang dari 5 menit! Segera periksa jawaban Anda.';timeWarning.classList.remove('d-none');setTimeout(()=>timeWarning.classList.add('d-none'),8000)}}
      if(!_1minShown&&left<=60){_1minShown=true;if(timeWarning){timeWarning.querySelector('span').textContent='Sisa waktu kurang dari 1 menit!';timeWarning.classList.remove('d-none')}}
    };
    update();setInterval(update,1000);

    // --- Enforce timer per bagian soal ---
    const sectionHeaders=document.querySelectorAll('.section-header[data-timer]');
    if(sectionHeaders.length){
      const sectionTimers={};
      let currentSection=null;
      sectionHeaders.forEach(h=>{
        const sid=h.dataset.sectionId;
        const timerMin=Number(h.dataset.timer||0);
        if(timerMin>0&&!sectionTimers[sid]){
          sectionTimers[sid]={end:Math.floor(Date.now()/1000)+timerMin*60,el:h.querySelector('.section-timer')};
        }
      });
      setInterval(()=>{
        const now=Math.floor(Date.now()/1000);
        Object.keys(sectionTimers).forEach(sid=>{
          const st=sectionTimers[sid];
          const left=st.end-now;
          if(st.el){
            if(left<=0){st.el.textContent='Waktu habis!';}
            else{st.el.textContent=String(Math.floor(left/60)).padStart(2,'0')+':'+String(left%60).padStart(2,'0');}
          }
          if(left<=0&&examForm&&!examForm.dataset.submitted){examForm.dataset.submitted='1';examForm.submit();}
        });
      },1000);
    }
    document.querySelectorAll('.answer').forEach(el=>el.addEventListener('change',()=>{
      const data=new URLSearchParams({action:'save_answer',result:resultId,question:el.dataset.question,answer:el.value});
      fetch('index.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:data});
      updateNavStatus();
    }));
    document.querySelectorAll('textarea.answer').forEach(el=>el.addEventListener('input',()=>{clearTimeout(el._save);el._save=setTimeout(()=>el.dispatchEvent(new Event('change')),700)}));
  }

  const sidebar=document.querySelector('.sidebar');
  const isMobile=()=>window.matchMedia('(max-width: 991.98px)').matches;
  if(sidebar){
    sidebar.querySelectorAll('nav a').forEach(a=>{a.addEventListener('click',()=>{if(isMobile()){sidebar.classList.remove('open');document.body.classList.remove('sidebar-open')}})});
    document.querySelector('.sidebar-backdrop')?.addEventListener('click',()=>{sidebar.classList.remove('open');document.body.classList.remove('sidebar-open')});
    document.addEventListener('keydown',e=>{if(e.key==='Escape'&&sidebar.classList.contains('open')){sidebar.classList.remove('open');document.body.classList.remove('sidebar-open')}});
    new MutationObserver(()=>document.body.classList.toggle('sidebar-open',sidebar.classList.contains('open'))).observe(sidebar,{attributes:true,attributeFilter:['class']});
  }
  window.addEventListener('resize',()=>{if(!isMobile()&&sidebar&&sidebar.classList.contains('open')){sidebar.classList.remove('open');document.body.classList.remove('sidebar-open')}});

  const navGrid=document.querySelector('.exam-nav-grid');
  const examForm=document.getElementById('examForm');
  const examQuestions=examForm?Array.from(examForm.querySelectorAll('.question')):[];
  const pager=examForm?.querySelector('.exam-pager');
  const prevQuestion=document.getElementById('prevQuestion');
  const nextQuestion=document.getElementById('nextQuestion');
  const currentQuestionNo=document.getElementById('currentQuestionNo');
  let activeQuestionIndex=0;
  const questionHeaderMap=new Map();
  if(examForm&&examQuestions.length){
    let currentHeader=null;
    Array.from(examForm.children).forEach(child=>{
      if(child.classList?.contains('section-header'))currentHeader=child;
      if(child.classList?.contains('question'))questionHeaderMap.set(child,currentHeader);
    });
    examForm.classList.add('exam-single-page');
  }

  function setActiveQuestion(index){
    if(!examQuestions.length)return;
    activeQuestionIndex=Math.max(0,Math.min(index,examQuestions.length-1));
    examForm.querySelectorAll('.section-header').forEach(h=>h.classList.remove('is-active'));
    examQuestions.forEach((q,i)=>q.classList.toggle('is-active',i===activeQuestionIndex));
    const header=questionHeaderMap.get(examQuestions[activeQuestionIndex]);
    if(header)header.classList.add('is-active');
    navGrid?.querySelectorAll('.nav-q').forEach(a=>a.classList.toggle('active-page',a.dataset.q===examQuestions[activeQuestionIndex].dataset.q));
    if(prevQuestion)prevQuestion.disabled=activeQuestionIndex===0;
    if(nextQuestion){
      nextQuestion.disabled=activeQuestionIndex===examQuestions.length-1;
      nextQuestion.innerHTML=activeQuestionIndex===examQuestions.length-1?'Soal Terakhir <i class="bi bi-check2"></i>':'Berikutnya <i class="bi bi-arrow-right"></i>';
    }
    if(currentQuestionNo)currentQuestionNo.textContent=String(activeQuestionIndex+1);
    window.scrollTo({top:0,behavior:'smooth'});
  }

  prevQuestion?.addEventListener('click',()=>setActiveQuestion(activeQuestionIndex-1));
  nextQuestion?.addEventListener('click',()=>setActiveQuestion(activeQuestionIndex+1));

  function updateNavStatus(){
    if(!navGrid)return;
    document.querySelectorAll('.question').forEach(q=>{
      const qid=q.dataset.q; const navEl=navGrid.querySelector(`[data-q="${qid}"]`);
      if(!navEl)return;
      let hasAnswer=q.querySelector('textarea.answer')?q.querySelector('textarea.answer').value.trim()!=='':!!q.querySelector('input.answer:checked');
      const isFlagged=q.querySelector('.ragu-btn.active')!==null;
      navEl.className='nav-q';
      if(isFlagged)navEl.classList.add('flagged');
      else if(hasAnswer)navEl.classList.add('answered');
      else navEl.classList.add('unanswered');
    });
  }
  if(navGrid){
    navGrid.addEventListener('click',e=>{const a=e.target.closest('.nav-q');if(a){e.preventDefault();const q=document.querySelector(`.question[data-q="${a.dataset.q}"]`);if(q){const index=examQuestions.indexOf(q);if(index>=0)setActiveQuestion(index);else q.scrollIntoView({behavior:'smooth',block:'center'});q.style.transition='box-shadow .3s';q.style.boxShadow='0 0 0 3px '+getComputedStyle(document.documentElement).getPropertyValue('--cb-primary').trim();setTimeout(()=>q.style.boxShadow='',1500)}}});
    document.querySelectorAll('.answer').forEach(el=>el.addEventListener('change',updateNavStatus));
    document.querySelectorAll('textarea.answer').forEach(el=>el.addEventListener('input',()=>{clearTimeout(el._navTimer);el._navTimer=setTimeout(updateNavStatus,500)}));
    updateNavStatus();
  }
  if(examQuestions.length)setActiveQuestion(0);

  document.querySelectorAll('.ragu-btn').forEach(btn=>{
    btn.addEventListener('click',function(){
      const qid=this.dataset.question;
      const resultId=document.querySelector('[name=result]')?.value;
      const isActive=this.classList.toggle('active');
      this.querySelector('i').className=isActive?'bi bi-flag-fill':'bi bi-flag';
      this.querySelector('span').textContent=isActive?'Ragu':'Ragu';
      if(resultId)fetch('index.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams({action:'flag_answer',result:resultId,question:qid,flagged:isActive?1:0})}).then(r=>r.json());
      setTimeout(updateNavStatus,50);
    });
  });

  try{
    const darkToggle=document.getElementById('darkModeToggle');
    if(darkToggle){
      function setDark(enabled){
        try{
          document.documentElement.classList.toggle('dark',enabled);
          if(enabled) document.documentElement.setAttribute('data-theme','dark');
          else document.documentElement.removeAttribute('data-theme');
          darkToggle.innerHTML=enabled?'<i class="bi bi-sun-fill"></i>':'<i class="bi bi-moon-stars-fill"></i>';
          darkToggle.title=enabled?'Tema Terang':'Tema Gelap';
          localStorage.setItem('opencode-dark',enabled?'1':'0');
        }catch(e){}
      }
      try{if(localStorage.getItem('opencode-dark')==='1')setDark(true);}catch(e){}
      darkToggle.addEventListener('click',function(e){e.preventDefault();try{setDark(!document.documentElement.classList.contains('dark'))}catch(e){}});
    }
  }catch(e){}
});
