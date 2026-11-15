<?php
if (!defined('ABSPATH')) exit;
function expedo_setup(){load_theme_textdomain('expedo',get_template_directory().'/languages');add_theme_support('title-tag');add_theme_support('post-thumbnails');add_theme_support('custom-logo',['height'=>80,'width'=>240,'flex-height'=>true,'flex-width'=>true]);add_theme_support('html5',['search-form','comment-form','comment-list','gallery','caption','style','script']);add_theme_support('woocommerce');register_nav_menus(['primary'=>__('Meniu principal','expedo')]);}
add_action('after_setup_theme','expedo_setup');
/** Breadcrumb navigation for WooCommerce product categories. */
function expedo_category_breadcrumb($term_id = 0) {
    $term = $term_id ? get_term($term_id, 'product_cat') : get_queried_object();
    if (!$term || is_wp_error($term) || empty($term->term_id)) return;

    $ancestors = array_reverse(get_ancestors($term->term_id, 'product_cat', 'taxonomy'));
    $items = ['<a href="' . esc_url(home_url('/')) . '">Acasă</a>'];
    foreach ($ancestors as $ancestor_id) {
        $ancestor = get_term($ancestor_id, 'product_cat');
        if ($ancestor && !is_wp_error($ancestor)) {
            $url = get_term_link($ancestor);
            if (!is_wp_error($url)) $items[] = '<a href="' . esc_url($url) . '">' . esc_html($ancestor->name) . '</a>';
        }
    }
    if (!$items) {
        $shop_id = wc_get_page_id('shop');
        if ($shop_id > 0) $items[] = '<a href="' . esc_url(get_permalink($shop_id)) . '">Toate categoriile</a>';
    }
    $items[] = '<span aria-current="page">' . esc_html($term->name) . '</span>';
    echo '<nav class="expedo-breadcrumb" aria-label="Navigare" >' . implode('<span class="breadcrumb-separator" aria-hidden="true">/</span>', $items) . '</nav>';
}

/** Breadcrumb for a product, using its deepest assigned product category. */
function expedo_product_breadcrumb($product_id = 0) {
    $product_id = $product_id ?: get_the_ID();
    $terms = get_the_terms($product_id, 'product_cat');
    if (is_wp_error($terms) || empty($terms)) return;
    usort($terms, function($a, $b) { return count(get_ancestors($b->term_id, 'product_cat', 'taxonomy')) <=> count(get_ancestors($a->term_id, 'product_cat', 'taxonomy')); });
    $term = $terms[0]; $items = ['<a href="' . esc_url(home_url('/')) . '">Acasă</a>'];
    foreach (array_reverse(get_ancestors($term->term_id, 'product_cat', 'taxonomy')) as $ancestor_id) {
        $ancestor = get_term($ancestor_id, 'product_cat');
        if ($ancestor && !is_wp_error($ancestor)) { $url = get_term_link($ancestor); if (!is_wp_error($url)) $items[] = '<a href="' . esc_url($url) . '">' . esc_html($ancestor->name) . '</a>'; }
    }
    $url = get_term_link($term); if (!is_wp_error($url)) $items[] = '<a href="' . esc_url($url) . '">' . esc_html($term->name) . '</a>';
    $items[] = '<span aria-current="page">' . esc_html(get_the_title($product_id)) . '</span>';
    echo '<nav class="expedo-breadcrumb" aria-label="Navigare">' . implode('<span class="breadcrumb-separator" aria-hidden="true">/</span>', $items) . '</nav>';
}

/** Generic breadcrumb helper for editorial and informational pages. */
function expedo_render_breadcrumb($items = []) {
    $parts = ['<a href="' . esc_url(home_url('/')) . '">Acasă</a>'];
    foreach ($items as $index => $item) {
        if (!is_array($item) || empty($item['label'])) continue;
        $label = esc_html($item['label']);
        if (!empty($item['url']) && $index !== count($items) - 1) {
            $parts[] = '<a href="' . esc_url($item['url']) . '">' . $label . '</a>';
        } else {
            $parts[] = '<span aria-current="page">' . $label . '</span>';
        }
    }
    if (count($parts) > 1) echo '<nav class="expedo-breadcrumb" aria-label="Navigare">' . implode('<span class="breadcrumb-separator" aria-hidden="true">/</span>', $parts) . '</nav>';
}

function expedo_assets(){
 $theme_dir=get_template_directory();
 $css_file=$theme_dir.'/style.css';
 $js_file=$theme_dir.'/assets/js/theme.js';
 $css_version=file_exists($css_file)?(string)filemtime($css_file):wp_get_theme()->get('Version');
 $js_version=file_exists($js_file)?(string)filemtime($js_file):wp_get_theme()->get('Version');
 wp_enqueue_style('expedo-style',get_stylesheet_uri(),[],$css_version);
 wp_enqueue_script('expedo-script',get_template_directory_uri().'/assets/js/theme.js',[],$js_version,true);
 wp_localize_script('expedo-script','expedoData',['compareUrl'=>home_url('/compara-produse/'),'ajaxUrl'=>admin_url('admin-ajax.php'),'nonce'=>wp_create_nonce('expedo_compare')]);
}
add_action('wp_enqueue_scripts','expedo_assets');

function expedo_affiliate_box(){add_meta_box('expedo_affiliate','EXPEDO – Link afiliat','expedo_affiliate_html','product','side');}
add_action('add_meta_boxes','expedo_affiliate_box');
function expedo_affiliate_html($post){wp_nonce_field('expedo_save_affiliate','expedo_affiliate_nonce');$url=get_post_meta($post->ID,'_expedo_affiliate_url',true);echo '<p><label>URL afiliere</label></p><input style="width:100%" type="url" name="expedo_affiliate_url" value="'.esc_attr($url).'">';}
function expedo_save_affiliate($id){if(!isset($_POST['expedo_affiliate_nonce'])||!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['expedo_affiliate_nonce'])),'expedo_save_affiliate')||(defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE)||!current_user_can('edit_post',$id))return;if(isset($_POST['expedo_affiliate_url']))update_post_meta($id,'_expedo_affiliate_url',esc_url_raw(wp_unslash($_POST['expedo_affiliate_url'])));}
add_action('save_post_product','expedo_save_affiliate');
function expedo_product_button($id){
 $url=get_post_meta($id,'_expedo_affiliate_url',true);
 if($url)return '<a class="button expedo-offer-button" rel="sponsored nofollow noopener" target="_blank" href="'.esc_url($url).'"><svg class="offer-cart-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 4h2l2.2 11.1a2 2 0 0 0 2 1.6h8.9a2 2 0 0 0 1.9-1.4L22 8H6"/><circle cx="10" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></svg> Vezi produsul</a>';
 return '<a class="button expedo-offer-button" href="'.esc_url(get_permalink($id)).'"><svg class="offer-cart-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 4h2l2.2 11.1a2 2 0 0 0 2 1.6h8.9a2 2 0 0 0 1.9-1.4L22 8H6"/><circle cx="10" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></svg> Vezi produsul</a>';
}

/* Content types used by the affiliate directory and glossary. */
function expedo_register_content_types(){
 register_post_type('glosar',['labels'=>['name'=>'Glosar','singular_name'=>'Termen','add_new_item'=>'Adaugă termen','all_items'=>'Toți termenii'],'public'=>true,'has_archive'=>'glosar','rewrite'=>['slug'=>'glosar'],'supports'=>['title','editor','excerpt','thumbnail'],'show_in_rest'=>true,'menu_icon'=>'dashicons-book-alt']);
 register_post_type('magazin_partener',['labels'=>['name'=>'Magazine partenere','singular_name'=>'Magazin partener','add_new_item'=>'Adaugă magazin'],'public'=>true,'has_archive'=>false,'rewrite'=>['slug'=>'magazin-partener'],'supports'=>['title','editor','thumbnail','excerpt'],'show_in_rest'=>true,'menu_icon'=>'dashicons-store']);
}
add_action('init','expedo_register_content_types',5);
function expedo_glossary_letter($title){$first=mb_strtoupper(mb_substr(trim($title),0,1));return strtr($first,['Ă'=>'A','Â'=>'A','Î'=>'I','Ș'=>'S','Ş'=>'S','Ț'=>'T','Ţ'=>'T']);}
function expedo_seed_glossary_terms(){
 $file=get_template_directory().'/inc/glosar-termeni.php';
 if(!file_exists($file))return;
 $terms=include $file;
 foreach((array)$terms as $title=>$definition){
  $existing=get_posts(['post_type'=>'glosar','post_status'=>['publish','draft','pending','private'],'title'=>$title,'numberposts'=>1,'fields'=>'ids']);
  if($existing)continue;
  wp_insert_post(['post_type'=>'glosar','post_title'=>$title,'post_content'=>$definition,'post_excerpt'=>wp_strip_all_tags($definition),'post_status'=>'publish']);
 }
 update_option('expedo_glossary_seed_v2',1);
}
add_action('init','expedo_seed_glossary_terms',20);
function expedo_glossary_related($post_id,$letter,$limit=6){$posts=get_posts(['post_type'=>'glosar','post_status'=>'publish','posts_per_page'=>200,'orderby'=>'title','order'=>'ASC','exclude'=>[$post_id]]);$same=[];$other=[];foreach($posts as $item){if(expedo_glossary_letter($item->post_title)===$letter)$same[]=$item;else $other[]=$item;}return array_slice(array_merge(array_slice($same,0,$limit),array_slice($other,0,max(0,$limit-count($same)))),0,$limit);}

/* Numeric dimensions stored consistently in centimetres. */
function expedo_dimension_fields(){add_meta_box('expedo_dimensions','EXPEDO – Dimensiuni (cm)','expedo_dimension_fields_html','product','normal','default');}
add_action('add_meta_boxes','expedo_dimension_fields');
function expedo_dimension_fields_html($post){
 wp_nonce_field('expedo_save_dimensions','expedo_dimensions_nonce');
 foreach(['width'=>'Lățime','height'=>'Înălțime','depth'=>'Adâncime'] as $key=>$label){
  $v=get_post_meta($post->ID,'_expedo_'.$key.'_cm',true);
  echo '<label style="display:inline-block;margin:8px 18px 8px 0">'.esc_html($label).' <input type="number" min="0" step=".1" name="expedo_'.$key.'_cm" value="'.esc_attr($v).'" /></label>';
 }
 $delivery=get_post_meta($post->ID,'_expedo_delivery_time',true);
 $vat=get_post_meta($post->ID,'_expedo_vat_confirmed',true);
 echo '<p><label>Termen de livrare comunicat de magazin <input type="text" style="width:100%" name="expedo_delivery_time" value="'.esc_attr($delivery).'" placeholder="Ex.: 3–5 zile lucrătoare"></label></p>';
 echo '<p><label><input type="checkbox" name="expedo_vat_confirmed" value="1" '.checked($vat,'1',false).'> TVA inclus – confirmat de magazin</label></p>';
}
function expedo_save_dimensions($id){
 if(!isset($_POST['expedo_dimensions_nonce'])||!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['expedo_dimensions_nonce'])),'expedo_save_dimensions')||(defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE)||!current_user_can('edit_post',$id))return;
 foreach(['width','height','depth'] as $key){$field='expedo_'.$key.'_cm';if(isset($_POST[$field])&&$_POST[$field]!=='')update_post_meta($id,'_expedo_'.$key.'_cm',wc_format_decimal(wp_unslash($_POST[$field])));else delete_post_meta($id,'_expedo_'.$key.'_cm');}
 update_post_meta($id,'_expedo_delivery_time',sanitize_text_field(wp_unslash($_POST['expedo_delivery_time']??'')));
 update_post_meta($id,'_expedo_vat_confirmed',isset($_POST['expedo_vat_confirmed'])?'1':'0');
}
add_action('save_post_product','expedo_save_dimensions');
function expedo_catalog_query($query){
 if(is_admin()||!$query->is_main_query()||!(is_shop()||is_product_taxonomy()))return;
 $mq=(array)$query->get('meta_query');$tq=(array)$query->get('tax_query');
 foreach(['min_price'=>['_price','>='],'max_price'=>['_price','<='],'min_width'=>['_expedo_width_cm','>='],'max_width'=>['_expedo_width_cm','<='],'min_height'=>['_expedo_height_cm','>='],'max_height'=>['_expedo_height_cm','<='],'min_depth'=>['_expedo_depth_cm','>='],'max_depth'=>['_expedo_depth_cm','<=']] as $param=>$spec){if(isset($_GET[$param])&&$_GET[$param]!=='')$mq[]=['key'=>$spec[0],'value'=>(float)wc_clean(wp_unslash($_GET[$param])),'compare'=>$spec[1],'type'=>'DECIMAL'];}
 if(function_exists('wc_get_attribute_taxonomies'))foreach((array)wc_get_attribute_taxonomies() as $a){
  $tax='pa_'.$a->attribute_name;
  if(!taxonomy_exists($tax)||!isset($_GET[$tax]))continue;
  $raw=(array)wp_unslash($_GET[$tax]);
  $selected=array_values(array_unique(array_filter(array_map('sanitize_title',$raw))));
  if(!$selected)continue;
  // Resolve selected values to actual taxonomy terms; support common Romanian/English colour aliases.
  $candidate_slugs=$selected;
  if($tax==='pa_culoare'){
   $aliases=['bej'=>['beige'],'beige'=>['bej'],'gri'=>['gray','grey'],'gray'=>['gri','grey'],'grey'=>['gri','gray']];
   foreach($selected as $slug)if(isset($aliases[$slug]))$candidate_slugs=array_merge($candidate_slugs,$aliases[$slug]);
  }
  $term_ids=[];
  foreach(array_unique($candidate_slugs) as $candidate_slug){
   $term=get_term_by('slug',$candidate_slug,$tax);
   if($term && !is_wp_error($term))$term_ids[]=(int)$term->term_id;
  }
  // Also resolve by normalized display name, in case the term slug differs from its label.
  $all_terms=get_terms(['taxonomy'=>$tax,'hide_empty'=>false]);
  if(!is_wp_error($all_terms)){
   foreach($all_terms as $term){
    $normalized=sanitize_title(remove_accents($term->name));
    if(in_array($normalized,$candidate_slugs,true))$term_ids[]=(int)$term->term_id;
   }
  }
  $term_ids=array_values(array_unique(array_filter($term_ids)));
  if($term_ids)$tq[]=['taxonomy'=>$tax,'field'=>'term_id','terms'=>$term_ids,'operator'=>'IN'];
 }
 if($mq)$query->set('meta_query',$mq);if($tq)$query->set('tax_query',$tq);
}
add_action('pre_get_posts','expedo_catalog_query',30);

/* Optional merchant transparency fields; values are entered only when verified. */
function expedo_product_trust_box(){ add_meta_box('expedo_product_trust','EXPEDO – Informații comerciant','expedo_product_trust_html','product','side'); }
add_action('add_meta_boxes','expedo_product_trust_box');
function expedo_product_trust_html($post){
 wp_nonce_field('expedo_product_trust_save','expedo_product_trust_nonce');
 $merchant=get_post_meta($post->ID,'_expedo_merchant_name',true); $checked=get_post_meta($post->ID,'_expedo_price_checked_at',true); $availability=get_post_meta($post->ID,'_expedo_availability',true);
 echo '<p><label for="expedo_merchant_name">Magazin partener</label><input id="expedo_merchant_name" name="expedo_merchant_name" type="text" style="width:100%" value="'.esc_attr($merchant).'"></p>';
 echo '<p><label for="expedo_price_checked_at">Preț verificat la</label><input id="expedo_price_checked_at" name="expedo_price_checked_at" type="date" style="width:100%" value="'.esc_attr($checked).'"></p>';
 echo '<p><label for="expedo_availability">Disponibilitate (opțional)</label><input id="expedo_availability" name="expedo_availability" type="text" style="width:100%" value="'.esc_attr($availability).'"></p><p class="description">Completează numai informații confirmate de comerciant.</p>';
}
function expedo_product_trust_save($id){
 if(!isset($_POST['expedo_product_trust_nonce'])||!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['expedo_product_trust_nonce'])),'expedo_product_trust_save')||(defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE)||!current_user_can('edit_post',$id)) return;
 update_post_meta($id,'_expedo_merchant_name',sanitize_text_field(wp_unslash($_POST['expedo_merchant_name']??'')));
 $date=sanitize_text_field(wp_unslash($_POST['expedo_price_checked_at']??'')); update_post_meta($id,'_expedo_price_checked_at',preg_match('/^\d{4}-\d{2}-\d{2}$/',$date)?$date:'');
 update_post_meta($id,'_expedo_availability',sanitize_text_field(wp_unslash($_POST['expedo_availability']??'')));
}
add_action('save_post_product','expedo_product_trust_save');

/* Front-end product comparison uses localStorage; this endpoint supplies validated product data. */
function expedo_compare_data(){
 // Public, read-only endpoint. Validate the request nonce while allowing guest visitors.
 if(!check_ajax_referer('expedo_compare','nonce',false)){
  wp_send_json_error(['message'=>'Sesiunea comparației a expirat. Reîncarcă pagina și încearcă din nou.'],403);
 }
 if(!function_exists('wc_get_product')){
  wp_send_json_error(['message'=>'WooCommerce nu este disponibil momentan.'],503);
 }
 // Only published, catalog-visible products are returned.
 $ids=isset($_POST['ids'])?array_slice(array_values(array_unique(array_filter(array_map('absint',(array)wp_unslash($_POST['ids']))))),0,4):[];
 if(!$ids){wp_send_json_error(['message'=>'Nu au fost primite produse pentru comparație.'],400);}
 $out=[];
 foreach($ids as $id){
  $p=wc_get_product($id);if(!$p||'publish'!==get_post_status($id)||!$p->is_visible())continue;
  $dimensions=[];
  foreach(['Lățime'=>'width','Înălțime'=>'height','Adâncime'=>'depth'] as $label=>$key){
   $value=get_post_meta($id,'_expedo_'.$key.'_cm',true);
   if($value==='' && $p->{"get_".$key}()){
    $value=wc_get_dimension($p->{"get_".$key}(),'cm');
   }
   $dimensions[$label]=$value;
  }
  $attributes=[];
  if(function_exists('wc_get_attribute_taxonomies')){
   foreach((array)wc_get_attribute_taxonomies() as $attribute){
    $taxonomy='pa_'.$attribute->attribute_name;
    if(!taxonomy_exists($taxonomy))continue;
    $values=wc_get_product_terms($id,$taxonomy,['fields'=>'names']);
    if(!is_wp_error($values)&&$values)$attributes[$attribute->attribute_label]=implode(', ',$values);
   }
  }
  $out[]=['id'=>$id,'name'=>get_the_title($id),'url'=>get_permalink($id),'image'=>get_the_post_thumbnail_url($id,'woocommerce_thumbnail')?:'','price'=>wp_strip_all_tags($p->get_price_html()),'sku'=>$p->get_sku(),'dimensions'=>$dimensions,'attributes'=>$attributes,'description'=>wp_trim_words(wp_strip_all_tags(get_post_field('post_excerpt',$id)?:get_post_field('post_content',$id)),24)];
 }
 wp_send_json_success($out);
}
add_action('wp_ajax_expedo_compare_data','expedo_compare_data');add_action('wp_ajax_nopriv_expedo_compare_data','expedo_compare_data');


/* Responsive homepage hero images: desktop and mobile are managed in Appearance > Customize. */
function expedo_customize_register($wp_customize){
 $wp_customize->add_section('expedo_home_hero',['title'=>'EXPEDO – Banner principal','priority'=>35]);
 foreach(['desktop'=>'Imagine banner desktop','mobile'=>'Imagine banner mobil'] as $key=>$label){
  $setting='expedo_hero_'.$key.'_image';
  $wp_customize->add_setting($setting,['default'=>0,'sanitize_callback'=>'absint']);
  $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize,$setting,['label'=>$label,'section'=>'expedo_home_hero','mime_type'=>'image']));
 }
}
add_action('customize_register','expedo_customize_register');
function expedo_hero_image_url($device='desktop'){
 $id=(int)get_theme_mod('expedo_hero_'.$device.'_image',0);
 return $id?wp_get_attachment_image_url($id,'full'):'';
}

/* Editorial archive controls: category and order filters for Inspirații. */
function expedo_inspiratii_query($query){
 if(is_admin()||!$query->is_main_query()||!$query->is_home())return;
 if(isset($_GET['expedo_order'])){
  $order=sanitize_key(wp_unslash($_GET['expedo_order']));
  $map=['newest'=>['date','DESC'],'oldest'=>['date','ASC'],'title_asc'=>['title','ASC'],'title_desc'=>['title','DESC']];
  if(isset($map[$order])){$query->set('orderby',$map[$order][0]);$query->set('order',$map[$order][1]);}
 }
}
add_action('pre_get_posts','expedo_inspiratii_query',40);

/** Create the editorial categories agreed for the Inspirații section. */
function expedo_seed_editorial_categories() {
    $editorial_categories = [
        'Idei de amenajare' => 'idei-de-amenajare',
        'Ghiduri de cumpărare' => 'ghiduri-de-cumparare',
        'Materiale și finisaje' => 'materiale-si-finisaje',
        'Sfaturi practice' => 'sfaturi-practice',
        'Tendințe în mobilier' => 'tendinte-in-mobilier',
    ];
    foreach ($editorial_categories as $name => $slug) {
        if (!get_term_by('slug', $slug, 'category')) {
            wp_insert_term($name, 'category', ['slug' => $slug]);
        }
    }
}
add_action('init', 'expedo_seed_editorial_categories', 25);

/* Partner enquiry form. */
function expedo_partner_form(){if($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['expedo_partner_submit'])){if(!isset($_POST['expedo_partner_nonce'])||!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['expedo_partner_nonce'])),'expedo_partner'))return '<p class="form-error">Sesiune expirată. Reîncarcă pagina.</p>';$name=sanitize_text_field(wp_unslash($_POST['name']??''));$company=sanitize_text_field(wp_unslash($_POST['company']??''));$email=sanitize_email(wp_unslash($_POST['email']??''));$message=sanitize_textarea_field(wp_unslash($_POST['message']??''));if(!$name||!is_email($email)||!$message)return '<p class="form-error">Completează numele, un e-mail valid și mesajul.</p>';$to=get_option('admin_email');$sent=wp_mail($to,'Solicitare parteneriat EXPEDO',"Nume: ".$name."\nCompanie: ".$company."\nEmail: ".$email."\n\n".$message,['Reply-To: '.$name.' <'.$email.'>']);return $sent?'<p class="form-success">Solicitarea a fost trimisă. Îți vom răspunde după verificare.</p>':'<p class="form-error">Mesajul nu a putut fi trimis. Încearcă din nou sau contactează administratorul site-ului.</p>';}
 ob_start();?><form method="post" class="expedo-form"><?php wp_nonce_field('expedo_partner','expedo_partner_nonce');?><label>Nume*<input required name="name"></label><label>Companie<input name="company"></label><label>E-mail*<input required type="email" name="email"></label><label>Mesaj*<textarea required name="message" rows="5"></textarea></label><button class="button" name="expedo_partner_submit" value="1">Trimite solicitarea</button></form><?php return ob_get_clean();}
add_shortcode('expedo_partner_form','expedo_partner_form');

/* Page templates by slug: usable without pre-existing page content. */
function expedo_page_template($template){if(is_page()){ $slug=get_post_field('post_name',get_queried_object_id());$map=['magazine-partenere'=>'templates/page-magazine.php','compara-produse'=>'templates/page-compare.php','devino-partener'=>'templates/page-partner.php','contact'=>'templates/page-contact.php','politica-de-cookies'=>'templates/page-cookies.php'];if(isset($map[$slug])){$file=get_template_directory().'/'.$map[$slug];if(file_exists($file))return $file;}}return $template;}
add_filter('template_include','expedo_page_template',99);

/* SEO metadata for product pages, unless an SEO plugin already owns metadata. */
function expedo_product_seo_description(){if(!is_product()||defined('WPSEO_VERSION')||class_exists('RankMath'))return;$desc=get_the_excerpt();if(!$desc)$desc=wp_trim_words(wp_strip_all_tags(get_post_field('post_content',get_queried_object_id())),28,'');if($desc)echo '<meta name="description" content="'.esc_attr(wp_strip_all_tags($desc)).'">'.PHP_EOL;}
add_action('wp_head','expedo_product_seo_description',3);

function expedo_store_url_box(){add_meta_box('expedo_store_url','Link către magazin','expedo_store_url_html','magazin_partener','side');}
add_action('add_meta_boxes','expedo_store_url_box');
function expedo_store_url_html($post){wp_nonce_field('expedo_store_url_save','expedo_store_url_nonce');$url=get_post_meta($post->ID,'_expedo_store_url',true);echo '<input type="url" name="expedo_store_url" value="'.esc_attr($url).'" style="width:100%" placeholder="https://...">';}
function expedo_store_url_save($id){if(!isset($_POST['expedo_store_url_nonce'])||!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['expedo_store_url_nonce'])),'expedo_store_url_save')||(defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE)||!current_user_can('edit_post',$id))return;update_post_meta($id,'_expedo_store_url',esc_url_raw(wp_unslash($_POST['expedo_store_url']??'')));}
add_action('save_post_magazin_partener','expedo_store_url_save');

/* Create the pages that use dedicated theme layouts if they are missing. */
function expedo_ensure_theme_pages(){
 $pages=['magazine-partenere'=>'Magazine partenere','compara-produse'=>'Compară produse','devino-partener'=>'Devino partener','contact'=>'Contact','politica-de-cookies'=>'Politica de cookies','inspiratii'=>'Inspirații'];
 foreach($pages as $slug=>$title){
  $existing=get_page_by_path($slug,OBJECT,'page');
  if(!$existing){
   $created=wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>$title,'post_name'=>$slug,'post_content'=>'']);
   if($slug==='inspiratii'&&!is_wp_error($created)&&$created) $existing=get_post($created);
  }
  // The comparison page is a public utility required by the product-comparison feature.
  if($slug==='compara-produse'&&$existing&&$existing->post_status!=='publish'){
   wp_update_post(['ID'=>$existing->ID,'post_status'=>'publish']);
  }
  if($slug==='inspiratii'&&!get_option('page_for_posts')&&$existing){
   update_option('page_for_posts',(int)$existing->ID);
  }
 }
}
add_action('init','expedo_ensure_theme_pages',30);

/* Default global WooCommerce attributes for the catalogue filters. */
function expedo_ensure_filter_attributes(){
 if(!function_exists('wc_get_attribute_taxonomies')||!function_exists('wc_create_attribute'))return;
 $existing=wc_get_attribute_taxonomies();
 $names=[];
 foreach((array)$existing as $attribute)$names[]=$attribute->attribute_name;
 foreach(['culoare'=>'Culoare','material'=>'Material'] as $slug=>$label){
  if(!in_array($slug,$names,true)){
   wc_create_attribute(['name'=>$label,'slug'=>$slug,'type'=>'select','order_by'=>'menu_order','has_archives'=>false]);
  }
 }
}
add_action('init','expedo_ensure_filter_attributes',1);
function expedo_seed_filter_attribute_terms(){
 $defaults=[
  'pa_culoare'=>['Alb','Negru','Gri','Bej','Maro','Verde','Albastru','Roșu'],
  'pa_material'=>['Lemn masiv','PAL melaminat','MDF','Metal','Sticlă','Țesătură','Catifea','Piele ecologică']
 ];
 foreach($defaults as $taxonomy=>$labels){
  if(!taxonomy_exists($taxonomy))continue;
  foreach($labels as $label)if(!term_exists($label,$taxonomy))wp_insert_term($label,$taxonomy);
 }
}
add_action('init','expedo_seed_filter_attribute_terms',40);
