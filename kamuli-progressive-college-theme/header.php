<!doctype html><html <?php language_attributes(); ?>><head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><?php wp_head(); ?></head><body <?php body_class(); ?>><?php wp_body_open(); ?>
<a class="screen-reader-text" href="#content">Skip to content</a>
<header class="site-header"><div class="header-inner"><div class="site-branding">
<?php if (has_custom_logo()) { the_custom_logo(); } else { ?><img class="site-logo" src="<?php echo kpc_image('badge.webp'); ?>" alt="Kamuli Progressive College badge"><?php } ?>
<div class="brand-copy"><p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></p><p class="site-description">Building knowledge, character and practical skills for the future</p></div></div></div></header>
<nav class="main-navigation" aria-label="Main navigation"><div class="nav-inner"><button class="menu-toggle" aria-expanded="false" aria-controls="primary-menu">Menu ☰</button><?php wp_nav_menu(array('theme_location'=>'primary','menu_id'=>'primary-menu','container'=>false,'fallback_cb'=>'wp_page_menu')); ?></div></nav>
