document.addEventListener('DOMContentLoaded',function(){
 const menuButton=document.querySelector('.menu-toggle'),nav=document.querySelector('.main-nav');
 if(menuButton&&nav)menuButton.addEventListener('click',()=>{nav.classList.toggle('is-open');menuButton.setAttribute('aria-expanded',nav.classList.contains('is-open')?'true':'false')});
 const catNav=document.querySelector('.categories-nav'),catTrigger=document.querySelector('.categories-trigger');
 if(catNav&&catTrigger){
  let closeTimer;
  const mega=catNav.querySelector('.categories-mega');
  const keepOpen=()=>{clearTimeout(closeTimer);catNav.classList.add('is-open');catTrigger.setAttribute('aria-expanded','true')};
  const scheduleClose=()=>{clearTimeout(closeTimer);closeTimer=setTimeout(()=>{if(!catNav.matches(':hover')&&!catNav.contains(document.activeElement)){catNav.classList.remove('is-open');catTrigger.setAttribute('aria-expanded','false')}},500)};
  catTrigger.addEventListener('click',()=>{const o=catNav.classList.toggle('is-open');catTrigger.setAttribute('aria-expanded',o?'true':'false')});
  catNav.addEventListener('mouseenter',keepOpen);catNav.addEventListener('mouseleave',scheduleClose);
  if(mega){mega.addEventListener('mouseenter',keepOpen);mega.addEventListener('mouseleave',scheduleClose)}
 }
 // Delegated category expansion: works for all submenu levels and survives cached/late-rendered markup.
 document.addEventListener('click',function(e){
  const toggle=e.target.closest('.category-expand-toggle');
  if(!toggle)return;
  const item=toggle.closest('.primary-category-item');
  if(!item)return;
  if(window.matchMedia('(max-width: 760px)').matches){
   e.preventDefault();e.stopPropagation();
   const expanded=item.classList.toggle('mobile-expanded');
   toggle.setAttribute('aria-expanded',expanded?'true':'false');
  }else{
   document.querySelectorAll('.primary-category-item').forEach(x=>x.classList.remove('is-active'));
   item.classList.add('is-active');
  }
 });
 document.querySelectorAll('.primary-category-item').forEach(item=>{
  item.addEventListener('mouseenter',()=>{if(window.innerWidth>760){document.querySelectorAll('.primary-category-item').forEach(x=>x.classList.remove('is-active'));item.classList.add('is-active')}});
 });
 const overlay=document.getElementById('expedo-search-overlay'),open=document.querySelector('.search-open'),input=document.getElementById('expedo-search-input');
 if(overlay&&open&&input){let previous;const close=()=>{overlay.classList.remove('is-visible');overlay.setAttribute('aria-hidden','true');document.body.classList.remove('search-is-open');open.setAttribute('aria-expanded','false');if(previous)previous.focus()};open.addEventListener('click',()=>{previous=document.activeElement;overlay.classList.add('is-visible');overlay.setAttribute('aria-hidden','false');document.body.classList.add('search-is-open');open.setAttribute('aria-expanded','true');setTimeout(()=>input.focus(),80)});overlay.querySelectorAll('[data-search-close]').forEach(x=>x.addEventListener('click',close));document.addEventListener('keydown',e=>{if(e.key==='Escape'&&overlay.classList.contains('is-visible'))close()})}
 /* Product comparison: retain the v1.15 storage key and AJAX contract. */
 const compareKey='expedoCompareProducts';
 const getCompare=()=>{try{const value=JSON.parse(localStorage.getItem(compareKey)||'[]');return Array.isArray(value)?[...new Set(value.map(Number).filter(id=>Number.isInteger(id)&&id>0))].slice(0,4):[]}catch(e){return []}};
 const saveCompare=ids=>{const clean=[...new Set(ids.map(Number).filter(id=>Number.isInteger(id)&&id>0))].slice(0,4);localStorage.setItem(compareKey,JSON.stringify(clean));document.querySelectorAll('[data-compare-id]').forEach(button=>{const id=Number(button.dataset.compareId),added=clean.includes(id);button.classList.toggle('is-added',added);button.textContent=added?'✓ Adăugat':'＋ Compară'});return clean};
 const escapeHtml=value=>String(value??'').replace(/[&<>"']/g,char=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[char]));
 document.querySelectorAll('[data-compare-id]').forEach(btn=>{const id=Number(btn.dataset.compareId);const added=getCompare().includes(id);btn.classList.toggle('is-added',added);btn.textContent=added?'✓ Adăugat':'＋ Compară';btn.addEventListener('click',()=>{let ids=getCompare();ids=ids.includes(id)?ids.filter(x=>x!==id):ids.length<4?[...ids,id]:(alert('Poți compara maximum 4 produse.'),ids);saveCompare(ids);let notice=btn.parentElement.querySelector('.compare-feedback');if(!notice){notice=document.createElement('a');notice.className='compare-feedback';notice.href=(window.expedoData&&expedoData.compareUrl)||'/compara-produse/';btn.insertAdjacentElement('afterend',notice)}notice.textContent=ids.includes(id)?'Vezi comparația ('+ids.length+') →':'Produs eliminat din comparație'});});
 const compareRoot=document.getElementById('expedo-compare-results');
 if(compareRoot){
  let renderToken=0;
  const renderComparison=()=>{
   const token=++renderToken,ids=getCompare();
   if(!ids.length){compareRoot.innerHTML='<p>Nu ai selectat încă produse. Revino în catalog și apasă „Compară” la produsele dorite.</p>';return}
   if(!window.expedoData||!expedoData.ajaxUrl){compareRoot.textContent='Modulul de comparare nu este inițializat. Reîncarcă pagina.';return}
   const body=new URLSearchParams({action:'expedo_compare_data',nonce:expedoData.nonce||''});ids.forEach(id=>body.append('ids[]',id));
   compareRoot.innerHTML='<p>Se încarcă comparația…</p>';
   fetch(expedoData.ajaxUrl,{method:'POST',credentials:'same-origin',cache:'no-store',headers:{'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8'},body:body.toString()})
    .then(async response=>{const raw=await response.text();let result;try{result=JSON.parse(raw)}catch(error){throw new Error('Serverul a returnat un răspuns care nu este JSON (HTTP '+response.status+'). Verifică erorile PHP sau regulile de securitate pentru admin-ajax.php.')}if(!response.ok||!result||!result.success){const message=result&&result.data&&result.data.message?result.data.message:'Cererea către server a eșuat (HTTP '+response.status+').';throw new Error(message)}return result})
    .then(result=>{if(token!==renderToken)return;if(!result||!result.success||!Array.isArray(result.data)){throw new Error('Răspuns invalid de la server')}const products=result.data;if(!products.length){saveCompare([]);compareRoot.innerHTML='<p>Produsele selectate nu mai sunt disponibile. Lista a fost resetată.</p>';return}
     const attributeNames=[...new Set(products.flatMap(product=>Object.keys(product.attributes||{})))];
     const rows=[['Preț',product=>product.price||'Preț indisponibil'],['Lățime',product=>product.dimensions?.['Lățime']?product.dimensions['Lățime']+' cm':'—'],['Înălțime',product=>product.dimensions?.['Înălțime']?product.dimensions['Înălțime']+' cm':'—'],['Adâncime',product=>product.dimensions?.['Adâncime']?product.dimensions['Adâncime']+' cm':'—'],...attributeNames.map(name=>[name,product=>(product.attributes||{})[name]||'—']),['SKU',product=>product.sku||'—'],['Descriere',product=>product.description||'—']];
     const header='<thead><tr><th>Caracteristică</th>'+products.map(product=>'<th><div class="compare-product-head">'+(product.image?'<img width="100" src="'+escapeHtml(product.image)+'" alt="">':'')+'<a href="'+escapeHtml(product.url)+'">'+escapeHtml(product.name)+'</a><button type="button" class="compare-remove-one" data-remove-compare="'+Number(product.id)+'">Elimină</button></div></th>').join('')+'</tr></thead>';
     const tableRows=rows.map(([label,read])=>'<tr><th>'+escapeHtml(label)+'</th>'+products.map(product=>'<td>'+escapeHtml(read(product))+'</td>').join('')+'</tr>').join('');
     compareRoot.innerHTML='<div class="compare-table"><table>'+header+'<tbody>'+tableRows+'</tbody></table></div><button type="button" class="button compare-clear">Șterge toate produsele</button>';
     compareRoot.querySelectorAll('[data-remove-compare]').forEach(button=>button.addEventListener('click',()=>{const removeId=Number(button.dataset.removeCompare);saveCompare(getCompare().filter(id=>id!==removeId));renderComparison()}));
     compareRoot.querySelector('.compare-clear').addEventListener('click',()=>{saveCompare([]);renderComparison()});
    }).catch(error=>{if(token===renderToken){const message=escapeHtml(error&&error.message?error.message:'Eroare necunoscută la încărcarea comparației.');compareRoot.innerHTML='<p class="compare-error">Nu am putut încărca comparația: '+message+'</p><button type="button" class="button compare-retry">Reîncearcă</button>';const retry=compareRoot.querySelector('.compare-retry');if(retry)retry.addEventListener('click',renderComparison)}});
  };
  renderComparison();
 }
 /* Consent banner: essential only by default; optional analytics/marketing consent is explicit. */
 const consentKey='expedoCookieConsent',banner=document.getElementById('expedo-cookie-banner');
 if(banner&&!localStorage.getItem(consentKey))banner.hidden=false;
 document.querySelectorAll('[data-cookie-choice]').forEach(b=>b.addEventListener('click',()=>{localStorage.setItem(consentKey,b.dataset.cookieChoice);if(banner)banner.hidden=true}));
 document.querySelectorAll('.cookie-settings-open').forEach(b=>b.addEventListener('click',()=>{if(banner){banner.hidden=false;banner.scrollIntoView({behavior:'smooth',block:'end'})}}));
 const backToTop=document.getElementById('expedo-back-to-top');
 if(backToTop){
  const updateBackToTop=()=>backToTop.classList.toggle('is-visible',window.scrollY>420);
  updateBackToTop();window.addEventListener('scroll',updateBackToTop,{passive:true});
  backToTop.addEventListener('click',()=>window.scrollTo({top:0,behavior:'smooth'}));
 }
});

// On mobile, keep the filter panel compact by default; users can expand any group.
document.addEventListener('DOMContentLoaded', function () {
  if (window.matchMedia('(max-width: 760px)').matches) {
    document.querySelectorAll('.expedo-filter-form .filter-group[open]').forEach(function (group) { group.removeAttribute('open'); });
  }
});
