<?php get_header(); ?>
<main class="site-shell" id="content">
<?php while(have_posts()): the_post(); global $product; ?>
<?php expedo_product_breadcrumb(get_the_ID()); ?>
<div class="single-product-layout">
 <div class="single-product-gallery">
  <?php woocommerce_show_product_images(); ?>
 </div>
 <div class="summary">
  <h1><?php the_title(); ?></h1>
  <div class="product-price"><?php echo wp_kses_post($product->get_price_html()); ?></div>
  <?php $merchant=get_post_meta(get_the_ID(),'_expedo_merchant_name',true); $checked=get_post_meta(get_the_ID(),'_expedo_price_checked_at',true); $availability=get_post_meta(get_the_ID(),'_expedo_availability',true); ?>
  <?php if($merchant): ?><p class="product-merchant">Magazin: <strong><?php echo esc_html($merchant); ?></strong></p><?php endif; ?>
  <?php if($checked): ?><p class="product-checked-date">Preț verificat la: <?php echo esc_html(date_i18n('d.m.Y',strtotime($checked))); ?></p><?php endif; ?>
  <?php if($availability): ?><p class="product-availability"><?php echo esc_html($availability); ?></p><?php endif; ?>
  <p class="price-disclaimer"><?php if(get_post_meta(get_the_ID(),'_expedo_vat_confirmed',true)==='1'):?>TVA inclus, conform informațiilor confirmate de magazin.<?php else:?>Preț afișat de magazin. Verifică TVA-ul și prețul final la comerciant.<?php endif;?></p>
  <?php $delivery=get_post_meta(get_the_ID(),'_expedo_delivery_time',true); if($delivery):?><p class="product-delivery"><strong>Termen de livrare:</strong> <?php echo esc_html($delivery);?></p><?php else:?><p class="product-delivery">Termenul de livrare este comunicat de magazinul partener.</p><?php endif;?>

  <?php echo expedo_product_button(get_the_ID()); ?>
  <button class="compare-add" type="button" data-compare-id="<?php the_ID(); ?>">＋ Compară produsul</button>
  <p class="affiliate-note">Unele linkuri pot fi linkuri de afiliere. EXPEDO poate primi un comision fără cost suplimentar pentru tine.</p>
  <div class="product-specs"><?php foreach(['width'=>'Lățime','height'=>'Înălțime','depth'=>'Adâncime'] as $k=>$label): $v=get_post_meta(get_the_ID(),'_expedo_'.$k.'_cm',true); if($v): ?><p><strong><?php echo esc_html($label); ?>:</strong> <?php echo esc_html($v); ?> cm</p><?php endif; endforeach; ?></div>
 </div>
 <section class="product-seo-description">
  <h2>Despre produs</h2>
  <?php if(trim(get_the_content())): the_content(); elseif(get_the_excerpt()): ?><p><?php echo esc_html(get_the_excerpt()); ?></p><?php else: ?><p>Informațiile detaliate despre acest produs vor fi publicate după verificarea datelor furnizorului.</p><?php endif; ?>
 </section>
</div>
<section class="related-products-section"><?php remove_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_add_to_cart',10); woocommerce_related_products(['posts_per_page'=>4,'columns'=>4]); add_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_add_to_cart',10); ?></section>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>