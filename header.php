<!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class(); ?>>
<a class="screen-reader-text" href="#content"><?php esc_html_e('Sari la conținut','expedo'); ?></a>
<?php wp_body_open(); ?>
<div class="site-shell">
<header class="site-header">
  <button class="menu-toggle" aria-label="Deschide meniul" aria-expanded="false">☰</button>
  <a class="brand" href="<?php echo esc_url(home_url('/')); ?>"><span class="logo-light">EX</span><strong class="logo-bold">PEDO</strong></a>
  <nav class="main-nav" id="primary-navigation" aria-label="Meniu principal">
    <div class="categories-nav">
      <button class="categories-trigger" type="button" aria-expanded="false">Categorii <span class="nav-chevron">⌄</span></button>
      <div class="categories-mega" aria-label="Categorii de mobilier">
        <div class="categories-primary-list">
          <?php
          $top_categories=get_terms(array('taxonomy'=>'product_cat','hide_empty'=>false,'parent'=>0,'orderby'=>'menu_order','order'=>'ASC'));
          if(!is_wp_error($top_categories) && $top_categories):
            $top_categories=array_values(array_filter($top_categories,function($term){return $term->slug!=='uncategorized';}));
          ?>
            <div class="primary-category-item all-categories-item">
              <a class="primary-category-link" href="<?php echo esc_url(function_exists('wc_get_page_id')?get_permalink(wc_get_page_id('shop')):home_url('/')); ?>"><span>Toate categoriile</span></a>
            </div>
          <?php foreach($top_categories as $index=>$top_cat):
              $children=get_terms(array('taxonomy'=>'product_cat','hide_empty'=>false,'parent'=>$top_cat->term_id,'orderby'=>'menu_order','order'=>'ASC'));
              $thumb_id=get_term_meta($top_cat->term_id,'thumbnail_id',true);
              $thumb=$thumb_id?wp_get_attachment_image_url($thumb_id,'thumbnail'):'';
          ?>
            <div class="primary-category-item <?php echo $index===0?'is-active':''; ?>" data-category="<?php echo esc_attr($top_cat->term_id); ?>">
              <a class="primary-category-link" href="<?php echo esc_url(get_term_link($top_cat)); ?>">
                <?php if($thumb): ?><img src="<?php echo esc_url($thumb); ?>" alt="" loading="lazy"><?php endif; ?>
                <span><?php echo esc_html($top_cat->name); ?></span>
              </a>
              <?php if($children && !is_wp_error($children)): ?>
                <button class="category-expand-toggle" type="button" aria-expanded="false" aria-label="Deschide subcategoriile pentru <?php echo esc_attr($top_cat->name); ?>"><span class="category-arrow" aria-hidden="true">›</span></button>
              <?php endif; ?>
              <?php if($children && !is_wp_error($children)): ?>
              <div class="secondary-category-panel" data-panel="<?php echo esc_attr($top_cat->term_id); ?>">
                <div class="secondary-panel-heading">
                  <a href="<?php echo esc_url(get_term_link($top_cat)); ?>">Toate produsele – <?php echo esc_html($top_cat->name); ?> ↗</a>
                </div>
                <div class="secondary-category-grid">
                  <?php foreach($children as $child):
                    $child_thumb_id=get_term_meta($child->term_id,'thumbnail_id',true);
                    $child_thumb=$child_thumb_id?wp_get_attachment_image_url($child_thumb_id,'woocommerce_thumbnail'):'';
                  ?>
                    <a class="secondary-category-card" href="<?php echo esc_url(get_term_link($child)); ?>">
                      <span class="secondary-category-image"><?php if($child_thumb): ?><img src="<?php echo esc_url($child_thumb); ?>" alt="" loading="lazy"><?php else: ?><span>EXPEDO</span><?php endif; ?></span>
                      <span><?php echo esc_html($child->name); ?></span>
                    </a>
                  <?php endforeach; ?>
                </div>
              </div>
              <?php endif; ?>
            </div>
          <?php endforeach; else: ?>

            <p class="categories-empty">Adaugă categoriile de produse în WooCommerce.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts')) ?: home_url('/inspiratii/')); ?>">Inspirații</a>
    <a href="<?php echo esc_url(home_url('/magazine-partenere/')); ?>">Magazine partenere</a>
    <a href="<?php echo esc_url(home_url('/glosar/')); ?>">Glosar</a>
    <a href="<?php echo esc_url(home_url('/despre-noi/')); ?>">Despre noi</a>
    <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a>
  </nav>
  <div class="header-actions"><button class="search-open" type="button" aria-label="Deschide căutarea" aria-haspopup="dialog" aria-controls="expedo-search-overlay"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"></circle><path d="m16 16 5 5"></path></svg></button><a href="<?php echo esc_url(home_url('/favorite/')); ?>" aria-label="Favorite">♡</a></div>
</header>

<div class="search-overlay" id="expedo-search-overlay" aria-hidden="true">
  <div class="search-backdrop" data-search-close></div>
  <section class="search-dialog" role="dialog" aria-modal="true" aria-labelledby="expedo-search-title">
    <button class="search-close" type="button" aria-label="Închide căutarea" data-search-close>×</button>
    <div class="search-dialog-inner">
      <p class="search-eyebrow">EXPEDO · GĂSEȘTE MOBILIERUL POTRIVIT</p>
      <h2 id="expedo-search-title">Ce cauți?</h2>
      <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="expedo-search-form">
        <label class="screen-reader-text" for="expedo-search-input">Caută produse</label>
        <input id="expedo-search-input" type="search" name="s" placeholder="Caută canapele, mese, dulapuri..." autocomplete="off">
        <input type="hidden" name="post_type" value="product">
        <button type="submit" aria-label="Caută"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"></circle><path d="m16 16 5 5"></path></svg></button>
      </form>
      <p class="search-hint">Apasă Enter pentru a vedea produsele.</p>
    </div>
  </section>
</div>

