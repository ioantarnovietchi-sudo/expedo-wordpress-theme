
<div class="cookie-banner" id="expedo-cookie-banner" hidden role="dialog" aria-label="Preferințe cookies">
 <div><strong>Preferințe cookies</strong><p>Folosim cookie-uri necesare funcționării site-ului. Cookie-urile opționale pot fi activate numai cu acordul tău.</p><a href="<?php echo esc_url(home_url('/politica-de-cookies/'));?>">Citește politica de cookies</a></div>
 <div class="cookie-actions"><button type="button" data-cookie-choice="essential">Doar necesare</button><button type="button" data-cookie-choice="all">Acceptă toate</button></div>
</div>
<footer class="site-footer">
  <div class="footer-columns">
    <div class="footer-column">
      <h2 class="footer-title">Descoperă</h2>
      <a href="<?php echo esc_url(function_exists('wc_get_page_id') && wc_get_page_id('shop')>0 ? get_permalink(wc_get_page_id('shop')) : home_url('/catalog/')); ?>">Catalog produse</a>
      <a href="<?php echo esc_url(home_url('/magazine-partenere/')); ?>">Magazine partenere</a>
      <a href="<?php echo esc_url(home_url('/compara-produse/')); ?>">Compară produse</a>
      <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts')) ?: home_url('/inspiratii/')); ?>">Inspirații</a>
      <a href="<?php echo esc_url(get_post_type_archive_link('glosar')); ?>">Glosar</a>
      <a href="<?php echo esc_url(home_url('/devino-partener/')); ?>">Devino partener</a>
    </div>
    <div class="footer-column">
      <h2 class="footer-title">Despre EXPEDO</h2>
      <a href="<?php echo esc_url(home_url('/despre-noi/')); ?>">Despre noi</a>
      <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a>
      <a href="<?php echo esc_url(home_url('/intrebari-frecvente/')); ?>">Întrebări frecvente</a>
      <a href="<?php echo esc_url(home_url('/cum-comand/')); ?>">Cum comand?</a>
    </div>
    <div class="footer-column">
      <h2 class="footer-title">Informații juridice</h2>
      <a href="<?php echo esc_url(home_url('/termeni-si-conditii/')); ?>">Termeni și condiții</a>
      <a href="<?php echo esc_url(home_url('/politica-de-confidentialitate/')); ?>">Politica de confidențialitate</a>
      <a href="<?php echo esc_url(home_url('/politica-de-cookies/')); ?>">Politica de cookies</a>
      <a href="<?php echo esc_url(home_url('/politica-de-retur/')); ?>">Politica de retur</a>
      <a href="<?php echo esc_url(home_url('/livrare-si-plata/')); ?>">Livrare și plată</a>
      <a href="<?php echo esc_url(home_url('/garantii-si-reclamatii/')); ?>">Garanții și reclamații</a>
      <a href="https://anpc.ro/">ANPC</a>
      <a href="https://reclamatiisal.anpc.ro/">SAL – Soluționarea alternativă a litigiilor</a>
    </div>
    <div class="footer-column">
      <h2 class="footer-title">Categorii</h2>
      <?php
      $footer_categories=get_terms(['taxonomy'=>'product_cat','hide_empty'=>false,'parent'=>0,'orderby'=>'menu_order','order'=>'ASC']);
      if(!is_wp_error($footer_categories)&&$footer_categories):
       foreach($footer_categories as $footer_category): if($footer_category->slug==='uncategorized')continue;
      ?>
       <a href="<?php echo esc_url(get_term_link($footer_category)); ?>"><?php echo esc_html($footer_category->name); ?></a>
      <?php endforeach; endif; ?>
      <a href="<?php echo esc_url(function_exists('wc_get_page_id')&&wc_get_page_id('shop')>0?get_permalink(wc_get_page_id('shop')):home_url('/catalog/')); ?>">Toate categoriile</a>
    </div>
  </div>
  <div class="footer-bottom">
    <a class="footer-brand" href="<?php echo esc_url(home_url('/')); ?>"><span class="logo-light">EX</span><strong class="logo-bold">PEDO</strong></a>
    <span>Mobilier ales cu grijă. Idei pentru un spațiu în care te simți acasă.</span>
    <span>© <?php echo esc_html(date('Y')); ?> EXPEDO</span>
  </div>
<div class="cookie-settings-link"><button type="button" class="cookie-settings-open">Setări cookies</button></div></footer>
<button type="button" id="expedo-back-to-top" class="back-to-top" aria-label="Înapoi sus" title="Înapoi sus"><span aria-hidden="true">↑</span></button>
</div>
<?php wp_footer(); ?></body></html>
