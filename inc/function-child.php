<?php

/**
 * Fuction yang digunakan di theme ini.
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

add_action('after_setup_theme', 'velocitychild_theme_setup', 9);

function velocitychild_theme_setup()
{

	// Load justg_child_enqueue_parent_style after theme setup
	add_action('wp_enqueue_scripts', 'justg_child_enqueue_parent_style', 20);

	add_action('customize_register', 'velocitychild_customize_register');

	//remove action from Parent Theme
	remove_action('justg_header', 'justg_header_menu');
	remove_action('justg_do_footer', 'justg_the_footer_open');
	remove_action('justg_do_footer', 'justg_the_footer_content');
	remove_action('justg_do_footer', 'justg_the_footer_close');
	remove_theme_support('widgets-block-editor');
}

function velocitychild_customize_register($wp_customize)
{
	$wp_customize->add_panel('panel_velocity', [
		'priority'    => 10,
		'title'       => esc_html__('Velocity Theme', 'justg'),
		'description' => esc_html__('', 'justg'),
	]);

	$site_identity = $wp_customize->get_section('title_tagline');
	if ($site_identity) {
		$site_identity->panel = 'panel_velocity';
		$site_identity->priority = 10;
	}

	$header_image = $wp_customize->get_section('header_image');
	if ($header_image) {
		$header_image->panel = 'panel_velocity';
		$header_image->priority = 11;
	}

	$wp_customize->add_setting('home_header', [
		'type'              => 'theme_mod',
		'sanitize_callback' => 'esc_url_raw',
	]);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'home_header',
			[
				'label'    => __('Home Header', 'justg'),
				'section'  => 'header_image',
				'priority' => 1,
			]
		)
	);

	$wp_customize->add_section('section_layanan', [
		'panel'    => 'panel_velocity',
		'title'    => __('Layanan (Halaman Depan)', 'justg'),
		'priority' => 12,
	]);
	$wp_customize->add_setting('judul_layanan', [
		'type'              => 'theme_mod',
		'sanitize_callback' => 'sanitize_text_field',
	]);
	$wp_customize->add_control('judul_layanan', [
		'type'    => 'text',
		'label'   => __('Judul Layanan', 'justg'),
		'section' => 'section_layanan',
	]);
	$wp_customize->add_setting('subjudul_layanan', [
		'type'              => 'theme_mod',
		'sanitize_callback' => 'sanitize_text_field',
	]);
	$wp_customize->add_control('subjudul_layanan', [
		'type'    => 'text',
		'label'   => __('Sub Judul Layanan', 'justg'),
		'section' => 'section_layanan',
	]);
	for ($x = 1; $x <= 4; $x++) {
		$wp_customize->add_setting('layanan' . $x, [
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_text_field',
		]);
		$wp_customize->add_control('layanan' . $x, [
			'type'        => 'text',
			'label'       => __('Layanan ' . $x, 'justg'),
			'description' => esc_html__('Judul layanan ' . $x, 'justg'),
			'section'     => 'section_layanan',
		]);

		$wp_customize->add_setting('urllayanan' . $x, [
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		]);
		$wp_customize->add_control('urllayanan' . $x, [
			'type'    => 'url',
			'label'   => __('Link Layanan ' . $x, 'justg'),
			'section' => 'section_layanan',
		]);

		$wp_customize->add_setting('gambarlayanan' . $x, [
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		]);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'gambarlayanan' . $x,
				[
					'label'   => __('Gambar Layanan ' . $x, 'justg'),
					'section' => 'section_layanan',
				]
			)
		);
	}

	$wp_customize->add_section('section_properti', [
		'panel'    => 'panel_velocity',
		'title'    => __('Properti (Halaman Depan)', 'justg'),
		'priority' => 13,
	]);
	$wp_customize->add_setting('judul_properti', [
		'type'              => 'theme_mod',
		'sanitize_callback' => 'sanitize_text_field',
	]);
	$wp_customize->add_control('judul_properti', [
		'type'    => 'text',
		'label'   => __('Judul Properti', 'justg'),
		'section' => 'section_properti',
	]);
	$wp_customize->add_setting('subjudul_properti', [
		'type'              => 'theme_mod',
		'sanitize_callback' => 'sanitize_text_field',
	]);
	$wp_customize->add_control('subjudul_properti', [
		'type'    => 'text',
		'label'   => __('Sub Judul Properti', 'justg'),
		'section' => 'section_properti',
	]);

	$wp_customize->add_section('section_artikel', [
		'panel'    => 'panel_velocity',
		'title'    => __('Artikel (Halaman Depan)', 'justg'),
		'priority' => 14,
	]);
	$wp_customize->add_setting('judul_artikel', [
		'type'              => 'theme_mod',
		'sanitize_callback' => 'sanitize_text_field',
	]);
	$wp_customize->add_control('judul_artikel', [
		'type'    => 'text',
		'label'   => __('Judul Artikel', 'justg'),
		'section' => 'section_artikel',
	]);
	$wp_customize->add_setting('subjudul_artikel', [
		'type'              => 'theme_mod',
		'sanitize_callback' => 'sanitize_text_field',
	]);
	$wp_customize->add_control('subjudul_artikel', [
		'type'    => 'text',
		'label'   => __('Sub Judul Artikel', 'justg'),
		'section' => 'section_artikel',
	]);

	$categories = get_terms([
		'taxonomy'   => 'category',
		'hide_empty' => false,
	]);
	$category_choices = [
		'' => 'Semua Kategori',
	];
	if (!is_wp_error($categories)) {
		foreach ($categories as $category) {
			$category_choices[$category->term_id] = $category->name;
		}
	}
	unset($category_choices[1]);
	$wp_customize->add_setting('artikel_cat', [
		'type'              => 'theme_mod',
		'sanitize_callback' => 'velocitychild_sanitize_article_cat',
	]);
	$wp_customize->add_control('artikel_cat', [
		'type'    => 'select',
		'label'   => __('Kategori Artikel', 'justg'),
		'section' => 'section_artikel',
		'choices' => $category_choices,
	]);

	$wp_customize->add_section('section_property_defaults', [
		'panel'    => 'panel_velocity',
		'title'    => __('Properti (Default)', 'justg'),
		'priority' => 15,
	]);
	$wp_customize->add_setting('property_default_agent', [
		'type'              => 'theme_mod',
		'sanitize_callback' => 'sanitize_text_field',
	]);
	$wp_customize->add_control('property_default_agent', [
		'type'    => 'text',
		'label'   => __('Agen Default', 'justg'),
		'section' => 'section_property_defaults',
	]);
	$wp_customize->add_setting('property_default_agent_phone', [
		'type'              => 'theme_mod',
		'sanitize_callback' => 'sanitize_text_field',
	]);
	$wp_customize->add_control('property_default_agent_phone', [
		'type'    => 'text',
		'label'   => __('Kontak Agen Default', 'justg'),
		'section' => 'section_property_defaults',
	]);

	// remove panel in customizer
	$wp_customize->remove_panel('global_panel');
	$wp_customize->remove_panel('panel_header');
	$wp_customize->remove_panel('panel_footer');
	$wp_customize->remove_panel('panel_antispam');
}

function velocitychild_sanitize_article_cat($value)
{
	if ($value === '' || $value === null) {
		return '';
	}

	return absint($value);
}


///remove breadcrumbs
add_action('wp_head', function () {
	if (!is_single()) {
		remove_action('justg_before_title', 'justg_breadcrumb');
	}
});

if (!function_exists('justg_header_open')) {
	function justg_header_open()
	{
		echo '<header id="wrapper-header">';
		echo '<div id="wrapper-navbar" class="px-0" itemscope itemtype="http://schema.org/WebSite">';
	}
}
if (!function_exists('justg_header_close')) {
	function justg_header_close()
	{
		echo '</div>';
		echo '</header>';
	}
}

function velocity_footer_script() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
	echo '<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">';
}
add_action( 'wp_footer', 'velocity_footer_script' );


///add action builder part
add_action('justg_header', 'justg_header_berita');
function justg_header_berita()
{
	require_once(get_stylesheet_directory() . '/inc/part-header.php');
}
add_action('justg_do_footer', 'justg_footer_berita');
function justg_footer_berita()
{
	require_once(get_stylesheet_directory() . '/inc/part-footer.php');
}
add_action('justg_before_wrapper_content', 'justg_before_wrapper_content');
function justg_before_wrapper_content()
{
	echo '<div class="card rounded-0 border-0 container">';
}
add_action('justg_after_wrapper_content', 'justg_after_wrapper_content');
function justg_after_wrapper_content()
{
	echo '</div>';
}


// excerpt more
if ( ! function_exists( 'velocity_custom_excerpt_more' ) ) {
	function velocity_custom_excerpt_more( $more ) {
		return '...';
	}
}
add_filter( 'excerpt_more', 'velocity_custom_excerpt_more' );

// excerpt length
function velocity_excerpt_length($length){
	return 20;
}
add_filter('excerpt_length','velocity_excerpt_length');


//register widget
add_action('widgets_init', 'justg_widgets_init', 20);
if (!function_exists('justg_widgets_init')) {
	function justg_widgets_init()
	{
		$before_widget = '<aside id="%1$s" class="widget %2$s">';
		$after_widget = '</aside>';
		$before_title = '<h3 class="fs-5 widget-title position-relative text-uppercase mb-3"><span>';
		$after_title = '</span></h3>';
		register_sidebar(
			array(
				'name'          => __('Main Sidebar', 'justg'),
				'id'            => 'main-sidebar',
				'description'   => __('Main sidebar widget area', 'justg'),
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
				'show_in_rest'   => false,
			)
		);
		register_sidebar(
			array(
				'name'          => __('Footer 1', 'justg'),
				'id'            => 'footer-1',
				'description'   => __('Footer sidebar widget area', 'justg'),
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
				'show_in_rest'   => false,
			)
		);
		register_sidebar(
			array(
				'name'          => __('Footer 2', 'justg'),
				'id'            => 'footer-2',
				'description'   => __('Footer sidebar widget area', 'justg'),
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
				'show_in_rest'   => false,
			)
		);
		register_sidebar(
			array(
				'name'          => __('Footer 3', 'justg'),
				'id'            => 'footer-3',
				'description'   => __('Footer sidebar widget area', 'justg'),
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
				'show_in_rest'   => false,
			)
		);
	}
}
if (!function_exists('justg_right_sidebar_check')) {
	function justg_right_sidebar_check()
	{
		if (is_singular('fl-builder-template')) {
			return;
		}
		if (!is_active_sidebar('main-sidebar')) {
			return;
		}
		echo '<div class="right-sidebar velocity-widget widget-area px-md-0 col-sm-12 col-md-3 order-3" id="right-sidebar" role="complementary">';
		do_action('justg_before_main_sidebar');
		dynamic_sidebar('main-sidebar');
		do_action('justg_after_main_sidebar');
		echo '</div>';
	}
}


function velocity_title() {	
	if (is_single() || is_page()) {
		return the_title( '<h1 class="velocity-postheader velocity-judul">', '</h1>' );
	} elseif (is_category()) {
		return '<h1 class="velocity-postheader velocity-judul">' . single_cat_title('', false) . '</h1>';
		return category_description();
	} elseif (is_tag()) {
		return '<h1 class="velocity-postheader velocity-judul">' . single_tag_title('', false) . '</h1>';
	} elseif (is_day()) {
		return '<h1 class="velocity-postheader velocity-judul">' . sprintf(__('Daily Archives: <span>%s</span>', THEME_NS), get_the_date()) . '</h1>';
	} elseif (is_month()) {
		return '<h1 class="velocity-postheader velocity-judul">' . sprintf(__('Monthly Archives: <span>%s</span>', THEME_NS), get_the_date('F Y')) . '</h1>';
	} elseif (is_year()) {
		return '<h1 class="velocity-postheader velocity-judul">' . sprintf(__('Yearly Archives: <span>%s</span>', THEME_NS), get_the_date('Y')) . '</h1>';
	} elseif (is_tax()) {
		$object = get_queried_object();
		return '<h1 class="velocity-postheader velocity-judul">'.$object->name.'</h1>';
	} elseif (is_post_type_archive()) {
		$object = get_queried_object();
		return '<h1 class="velocity-postheader velocity-judul">'.$object->label.'</h1>';
	} elseif (is_author()) {
		//the_post();
		return '<h1 class="velocity-postheader velocity-judul">' . get_the_author() . '</h1>';
		//rewind_posts();
	} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
		return '<h1 class="velocity-postheader velocity-judul">' . __('Blog Archives', THEME_NS) . '</h1>';
	}
}
