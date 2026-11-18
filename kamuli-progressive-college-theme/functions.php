<?php
if (!defined('ABSPATH')) { exit; }
function kpc_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array('height'=>180,'width'=>180,'flex-height'=>true,'flex-width'=>true));
    add_theme_support('html5', array('search-form','comment-form','comment-list','gallery','caption','style','script'));
    add_theme_support('align-wide');
    register_nav_menus(array('primary'=>__('Main Menu','kamuli-progressive-college')));
}
add_action('after_setup_theme','kpc_theme_setup');
function kpc_assets() {
    wp_enqueue_style('kpc-style', get_stylesheet_uri(), array(), '1.0.0');
    wp_enqueue_script('kpc-navigation', get_template_directory_uri().'/assets/js/navigation.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts','kpc_assets');
function kpc_image($filename) { return esc_url(get_template_directory_uri().'/assets/images/'.rawurlencode($filename)); }
function kpc_default_pages() {
    $pages = array(
        'home' => array('title'=>'Home','content'=>'Welcome to Kamuli Progressive College.','template'=>'front-page.php'),
        'about-us' => array('title'=>'About Us','content'=>'<h2>Our Background</h2><p>Kamuli Progressive College is a secondary school established to provide accessible, quality and holistic education. The school promotes academic achievement together with discipline, creativity, practical skills and community responsibility.</p><h2>Our Mission</h2><p>To provide quality education that equips students with knowledge, skills, and values necessary for personal development and societal contribution.</p><h2>Our Vision</h2><p>To be a leading educational institution that fosters excellence, innovation, and integrity in learning.</p><h2>Our Core Values</h2><ul><li>Integrity</li><li>Excellence</li><li>Innovation</li><li>Community Engagement</li><li>Respect for Diversity</li></ul><h2>Programmes</h2><p>Our learning opportunities include the S.1–S.6 curriculum, computer studies, internet skills, practical technology training, sports, clubs, leadership, debate and talent development.</p>','template'=>'default'),
        'our-services' => array('title'=>'Our Services','content'=>'<h2>Our Services</h2><p>Kamuli Progressive College offers a variety of services to our students to mould them into all-round citizens who are globally competitive and ready to contribute to national development.</p><h3>Academic Services</h3><p>We follow the UNEB curriculum at both O-Level and A-Level.</p><h3>Debate and Public Speaking</h3><p>Students participate in debate championships and public-speaking activities to develop confidence and communication skills.</p><h3>Co-curricular Activities</h3><p>Activities include Music, Dance and Drama (MDD), football, basketball, swimming, table tennis and hockey.</p>','template'=>'default'),
        'gallery' => array('title'=>'Gallery','content'=>'<p>Explore moments and facilities at Kamuli Progressive College.</p>[kpc_gallery]','template'=>'default'),
        'contact-us' => array('title'=>'Contact Us','content'=>'<p>Get in touch with Kamuli Progressive College or send an enquiry using the form below.</p>[kpc_contact_form]','template'=>'default')
    );
    $ids = array();
    foreach ($pages as $slug=>$page) {
        $existing = get_page_by_path($slug);
        if ($existing) { $ids[$slug] = $existing->ID; continue; }
        $id = wp_insert_post(array('post_title'=>$page['title'],'post_name'=>$slug,'post_content'=>$page['content'],'post_status'=>'publish','post_type'=>'page'));
        if (!is_wp_error($id) && $id) $ids[$slug] = $id;
    }
    if (!empty($ids['home'])) {
        update_option('show_on_front','page');
        update_option('page_on_front',$ids['home']);
    }
    $menu = wp_get_nav_menu_object('KPC Main Menu');
    if (!$menu) {
        $menu_id = wp_create_nav_menu('KPC Main Menu');
        if (!is_wp_error($menu_id)) {
            foreach (array('home','about-us','our-services','gallery','contact-us') as $slug) {
                if (!empty($ids[$slug])) wp_update_nav_menu_item($menu_id,0,array('menu-item-title'=>get_the_title($ids[$slug]),'menu-item-object'=>'page','menu-item-object-id'=>$ids[$slug],'menu-item-type'=>'post_type','menu-item-status'=>'publish'));
            }
            $locations = get_theme_mod('nav_menu_locations',array()); $locations['primary']=$menu_id; set_theme_mod('nav_menu_locations',$locations);
        }
    }
}
add_action('after_switch_theme','kpc_default_pages');
function kpc_gallery_shortcode() {
    $items = array(
        array('indoor.jpeg','Indoor Games'),array('ttennis.jpeg','Table Tennis'),array('sports.jpeg','Basketball'),array('images.jpg','Computer Lab'),array('MDD.jpg','Music, Dance and Drama'),array('our dormitory.jpg','Dormitory'),array('school bus.jpg','School Bus'),array('our sickbay.jpg','Sick Bay'),array('student body.jpg','Student Life'),array('lapic.jpg','ICT Learning'),array('tropic.jpg','Talent Development'),array('badge.webp','College Badge')
    );
    $out = '<div class="gallery-grid">';
    foreach ($items as $item) { $src=kpc_image($item[0]); $alt=esc_attr($item[1]); $out .= '<figure class="gallery-item"><a href="'.$src.'" target="_blank" rel="noopener"><img loading="lazy" src="'.$src.'" alt="'.$alt.'"></a><figcaption>'.esc_html($item[1]).'</figcaption></figure>'; }
    return $out.'</div>';
}
add_shortcode('kpc_gallery','kpc_gallery_shortcode');
function kpc_contact_shortcode() {
    $sent = isset($_GET['kpc_sent']) && $_GET['kpc_sent']==='1';
    ob_start(); ?>
    <div class="contact-form"><h2>Send an Enquiry</h2><?php if ($sent): ?><p class="notice">Thank you. Your message has been sent successfully.</p><?php endif; ?>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
      <input type="hidden" name="action" value="kpc_contact"><input type="hidden" name="redirect_to" value="<?php echo esc_url(get_permalink()); ?>"><?php wp_nonce_field('kpc_contact_form','kpc_nonce'); ?>
      <div class="kpc-form-row"><label for="kpc-name">Your Name</label><input id="kpc-name" name="name" type="text" required maxlength="100"></div>
      <div class="kpc-form-row"><label for="kpc-email">Email Address</label><input id="kpc-email" name="email" type="email" required maxlength="150"></div>
      <div class="kpc-form-row"><label for="kpc-phone">Telephone (optional)</label><input id="kpc-phone" name="phone" type="tel" maxlength="40"></div>
      <div class="kpc-form-row"><label for="kpc-subject">Subject / Class (optional)</label><input id="kpc-subject" name="subject" type="text" maxlength="120"></div>
      <div class="kpc-form-row"><label for="kpc-message">Message / Enquiry</label><textarea id="kpc-message" name="message" required maxlength="4000"></textarea></div>
      <button class="kpc-submit" type="submit">Send Message</button>
    </form></div><?php return ob_get_clean();
}
add_shortcode('kpc_contact_form','kpc_contact_shortcode');
function kpc_handle_contact() {
    if (!isset($_POST['kpc_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['kpc_nonce'])),'kpc_contact_form')) wp_die('Security check failed. Please go back and try again.');
    $name=sanitize_text_field(wp_unslash($_POST['name'] ?? '')); $email=sanitize_email(wp_unslash($_POST['email'] ?? '')); $phone=sanitize_text_field(wp_unslash($_POST['phone'] ?? '')); $subject=sanitize_text_field(wp_unslash($_POST['subject'] ?? '')); $message=sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));
    if (!$name || !is_email($email) || !$message) wp_die('Please provide a valid name, email address and message.');
    $to=get_option('admin_email'); $mail_subject='Website enquiry: '.($subject ? $subject : 'New message');
    $body="Name: $name\nEmail: $email\nTelephone: $phone\nSubject/Class: $subject\n\nMessage:\n$message";
    wp_mail($to,$mail_subject,$body,array('Reply-To: '.$name.' <'.$email.'>'));
    $redirect=esc_url_raw(wp_unslash($_POST['redirect_to'] ?? home_url('/contact-us/'))); wp_safe_redirect(add_query_arg('kpc_sent','1',$redirect)); exit;
}
add_action('admin_post_nopriv_kpc_contact','kpc_handle_contact'); add_action('admin_post_kpc_contact','kpc_handle_contact');
