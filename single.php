<?php get_header(); ?>
<main class="site-shell" id="content">
<?php while(have_posts()):the_post(); ?>
<?php expedo_render_breadcrumb([ ['label'=>'Inspirații','url'=>get_permalink(get_option('page_for_posts')) ?: home_url('/inspiratii/')], ['label'=>get_the_title()] ]); ?>
<a class="back-to-articles" href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>">← Înapoi la inspirații</a>
<article <?php post_class('expedo-article'); ?>><header class="article-header"><div class="eyebrow">INSPIRAȚII EXPEDO</div><h1><?php the_title(); ?></h1></header>
<div class="article-intro"><?php if(has_post_thumbnail()): ?><figure class="article-featured-image"><?php the_post_thumbnail('large'); ?></figure><?php endif; ?><div class="article-content section"><?php the_content(); ?></div></div></article>
<?php endwhile; ?></main><?php get_footer(); ?>
