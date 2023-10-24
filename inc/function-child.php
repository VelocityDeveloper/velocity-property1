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

	if (class_exists('Kirki')) :

		Kirki::add_panel('panel_velocity', [
			'priority'    => 10,
			'title'       => esc_html__('Velocity Theme', 'justg'),
			'description' => esc_html__('', 'justg'),
		]);

		// section title_tagline
		Kirki::add_section('title_tagline', [
			'panel'    => 'panel_velocity',
			'title'    => __('Site Identity', 'justg'),
			'priority' => 10,
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'image',
			'settings'    => 'home_header',
			'label'       => __('Home Header', 'kirki'),
			'description' => esc_html__('', 'kirki'),
			'section'     => 'header_image',
			'priority' => 1,
		]);


		///Section Layanan
		Kirki::add_section('section_layanan', [
			'panel'    => 'panel_velocity',
			'title'    => __('Layanan (Halaman Depan)', 'justg'),
			'priority' => 11,
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'text',
			'settings'    => 'judul_layanan',
			'label'       => __('Judul Layanan', 'kirki'),
			'section'     => 'section_layanan',
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'text',
			'settings'    => 'subjudul_layanan',
			'label'       => __('Sub Judul Layanan', 'kirki'),
			'section'     => 'section_layanan',
		]);
		for ($x = 1; $x <= 4; $x++) {
			Kirki::add_field('justg_config', [
				'type'        => 'text',
				'settings'    => 'layanan'.$x,
				'label'       => __('Layanan '.$x, 'kirki'),
				'description' => esc_html__('Judul layanan '.$x, 'kirki'),
				'section'     => 'section_layanan',
			]);
			Kirki::add_field('justg_config', [
				'type'        => 'url',
				'settings'    => 'urllayanan'.$x,
				'label'       => __('Link Layanan '.$x, 'kirki'),
				'section'     => 'section_layanan',
			]);
			Kirki::add_field('justg_config', [
				'type'        => 'image',
				'settings'    => 'gambarlayanan'.$x,
				'label'       => __('Gambar Layanan '.$x, 'kirki'),
				'description' => esc_html__('', 'kirki'),
				'section'     => 'section_layanan',
			]);
		}


		///Section Properti
		Kirki::add_section('section_properti', [
			'panel'    => 'panel_velocity',
			'title'    => __('Properti (Halaman Depan)', 'justg'),
			'priority' => 12,
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'text',
			'settings'    => 'judul_properti',
			'label'       => __('Judul Properti', 'kirki'),
			'section'     => 'section_properti',
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'text',
			'settings'    => 'subjudul_properti',
			'label'       => __('Sub Judul Properti', 'kirki'),
			'section'     => 'section_properti',
		]);


		///Section Artikel
		Kirki::add_section('section_artikel', [
			'panel'    => 'panel_velocity',
			'title'    => __('Artikel (Halaman Depan)', 'justg'),
			'priority' => 12,
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'text',
			'settings'    => 'judul_artikel',
			'label'       => __('Judul Artikel', 'kirki'),
			'section'     => 'section_artikel',
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'text',
			'settings'    => 'subjudul_artikel',
			'label'       => __('Sub Judul Artikel', 'kirki'),
			'section'     => 'section_artikel',
		]);		
		$categories = Kirki_Helper::get_terms('category');
		$categories[''] = 'Semua Kategori';
		unset($categories[1]);
		Kirki::add_field('justg_config', [
			'type'        => 'select',
			'settings'    => 'artikel_cat',
			'label'       => __('Kategori Artikel', 'kirki'),
			'section'     => 'section_artikel',
			'choices'     => $categories,
		]);



		///Section Color
		Kirki::add_section('section_colorvelocity', [
			'panel'    => 'panel_velocity',
			'title'    => __('Color & Background', 'justg'),
			'priority' => 10,
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'color',
			'settings'    => 'color_theme',
			'label'       => __('Theme Color', 'kirki'),
			'description' => esc_html__('', 'kirki'),
			'section'     => 'section_colorvelocity',
			'default'     => '#176cb7',
			'transport'   => 'auto',
			'output'      => [
				[
					'element'   => ':root',
					'property'  => '--color-theme',
				],
				[
					'element'   => ':root',
					'property'  => '--bs-primary',
				],
				[
					'element'   => '.border-color-theme',
					'property'  => '--bs-border-color',
				]
			],
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'background',
			'settings'    => 'background_themewebsite',
			'label'       => __('Website Background', 'kirki'),
			'description' => esc_html__('', 'kirki'),
			'section'     => 'section_colorvelocity',
			'default'     => [
				'background-color'      => 'rgba(255,255,255)',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'cover',
				'background-attachment' => 'scroll',
			],
			'transport'   => 'auto',
			'output'      => [
				[
					'element'   => ':root[data-bs-theme=light] body',
				],
				[
					'element'   => 'body',
				],
			],
		]);

		// remove panel in customizer 
		Kirki::remove_panel('global_panel');
		Kirki::remove_panel('panel_header');
		Kirki::remove_panel('panel_footer');
		Kirki::remove_panel('panel_antispam');

	endif;

	//remove action from Parent Theme
	remove_action('justg_header', 'justg_header_menu');
	remove_action('justg_do_footer', 'justg_the_footer_open');
	remove_action('justg_do_footer', 'justg_the_footer_content');
	remove_action('justg_do_footer', 'justg_the_footer_close');
	remove_theme_support('widgets-block-editor');
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
