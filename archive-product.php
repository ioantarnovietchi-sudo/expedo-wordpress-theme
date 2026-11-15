<?php
get_header();
$shop_id=wc_get_page_id('shop');$title='Catalog mobilier';
$attrs=function_exists('wc_get_attribute_taxonomies')?(array)wc_get_attribute_taxonomies():[];
$known_attr_names=array_map(function($a){return $a->attribute_name;},$attrs);
foreach(['culoare'=>'Culoare','material'=>'Material'] as $slug=>$label){
 if(!in_array($slug,$known_attr_names,true)&&taxonomy_exists('pa_'.$slug)){
  $attrs[]=(object)['attribute_name'=>$slug,'attribute_label'=>$label];
 }
}

?>
<main class="site-shell" id="content">
<header class="page-heading"><h1><?php echo esc_html($title); ?></h1></header>
<form class="catalog-search-inline" method="get" action="<?php echo esc_url(get_permalink($shop_id)); ?>"><input type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Caută produse în catalog"><input type="hidden" name="post_type" value="product"><button aria-label="Caută"><svg viewBox="0 0 24 24"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 5 5"/></svg></button></form>
<div class="shop-toolbar"><?php woocommerce_catalog_ordering(); ?></div>
<div class="catalog-layout"><aside class="filter-sidebar"><form method="get" class="expedo-filter-form"><h2>Filtrează produsele</h2>
<?php foreach($_GET as $k=>$v)if($k==='s'&&is_scalar($v))echo '<input type="hidden" name="s" value="'.esc_attr(wp_unslash($v)).'">';?>
<details class="filter-group" open><summary>Preț (RON)</summary><div class="filter-fields"><label>Minim<input type="number" min="0" name="min_price" value="<?php echo esc_attr($_GET['min_price']??''); ?>"></label><label>Maxim<input type="number" min="0" name="max_price" value="<?php echo esc_attr($_GET['max_price']??''); ?>"></label></div></details>
<details class="filter-group" open><summary>Dimensiuni (cm)</summary><?php foreach(['width'=>'Lățime','height'=>'Înălțime','depth'=>'Adâncime'] as $k=>$label):?><div class="dimension-filter-row"><strong><?php echo esc_html($label);?></strong><div class="filter-fields"><label>Min.<input type="number" min="0" name="min_<?php echo esc_attr($k);?>" value="<?php echo esc_attr($_GET['min_'.$k]??'');?>"></label><label>Max.<input type="number" min="0" name="max_<?php echo esc_attr($k);?>" value="<?php echo esc_attr($_GET['max_'.$k]??'');?>"></label></div></div><?php endforeach;?></details>
<?php foreach($attrs as $a):$tax='pa_'.$a->attribute_name;if(!taxonomy_exists($tax))continue;$terms=get_terms(['taxonomy'=>$tax,'hide_empty'=>false]);if(is_wp_error($terms)||!$terms)continue;?><details class="filter-group"><summary><?php echo esc_html($a->attribute_label);?></summary><div class="filter-options"><?php foreach($terms as $term):?><label><input type="checkbox" name="<?php echo esc_attr($tax);?>[]" value="<?php echo esc_attr($term->slug);?>" <?php checked(in_array($term->slug,(array)($_GET[$tax]??[]),true));?>><?php echo esc_html($term->name);?></label><?php endforeach;?></div></details><?php endforeach;?>
<div class="filter-actions"><button class="button filter-submit" type="submit">Aplică filtrele</button><a class="filter-reset" href="<?php echo esc_url(get_permalink($shop_id));?>">Resetează filtrele</a></div></form></aside>
<section class="category-products"><?php if(woocommerce_product_loop()):?><div class="product-grid"><?php while(have_posts()):the_post();global $product;?><article class="product-card"><a href="<?php the_permalink();?>"><div class="product-image"><?php if(has_post_thumbnail())the_post_thumbnail('woocommerce_thumbnail');?></div><div class="product-title"><?php the_title();?></div></a><div class="product-price"><?php echo wp_kses_post($product->get_price_html());?></div><?php echo expedo_product_button(get_the_ID());?><button class="compare-add" type="button" data-compare-id="<?php the_ID();?>">＋ Compară</button></article><?php endwhile;?></div><div class="catalog-result-count"><?php woocommerce_result_count(); ?></div><?php do_action('woocommerce_after_shop_loop');?><?php else:?><p>Nu am găsit produse care să corespundă criteriilor.</p><?php endif;?></section></div></main><?php get_footer();?>
