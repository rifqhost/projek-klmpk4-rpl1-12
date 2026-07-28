document.addEventListener('DOMContentLoaded',()=>{
  const type=document.querySelector('#qtype'), choices=document.querySelector('#choices');
  if(type&&choices) type.addEventListener('change',()=>choices.style.display=type.value==='essay'?'none':'block');
  const timer=document.querySelector('#timer');
  if(timer){const update=()=>{let left=Number(timer.dataset.end)-Math.floor(Date.now()/1000);if(left<=0){document.querySelector('#examForm').submit();return}timer.textContent=`${String(Math.floor(left/60)).padStart(2,'0')}:${String(left%60).padStart(2,'0')}`};update();setInterval(update,1000);document.addEventListener('visibilitychange',()=>{if(document.hidden) console.warn('Ujian tetap berjalan di latar belakang')});document.querySelectorAll('.answer').forEach(el=>el.addEventListener('change',()=>{const data=new URLSearchParams({action:'save_answer',result:document.querySelector('[name=result]').value,question:el.dataset.question,answer:el.value});fetch('index.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:data})}));document.querySelectorAll('textarea.answer').forEach(el=>el.addEventListener('input',()=>{clearTimeout(el._save);el._save=setTimeout(()=>el.dispatchEvent(new Event('change')),700)}));}

  // Mobile sidebar: close on nav click, escape key, backdrop click
  const sidebar=document.querySelector('.sidebar');
  if(sidebar){
    sidebar.querySelectorAll('nav a').forEach(a=>{
      a.addEventListener('click',()=>{
        if(window.innerWidth<768){
          sidebar.classList.remove('open');
          document.body.classList.remove('sidebar-open');
        }
      });
    });
    document.addEventListener('keydown',e=>{
      if(e.key==='Escape'&&sidebar.classList.contains('open')){
        sidebar.classList.remove('open');
        document.body.classList.remove('sidebar-open');
      }
    });
    // Observe sidebar class changes to sync body scroll lock
    const obs=new MutationObserver(()=>{
      document.body.classList.toggle('sidebar-open',sidebar.classList.contains('open'));
    });
    obs.observe(sidebar,{attributes:true,attributeFilter:['class']});
  }

  // Close any open dropdowns/modals on resize past mobile breakpoint
  window.addEventListener('resize',()=>{
    if(window.innerWidth>=768&&sidebar&&sidebar.classList.contains('open')){
      sidebar.classList.remove('open');
      document.body.classList.remove('sidebar-open');
    }
  });
});
