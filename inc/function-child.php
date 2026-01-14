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

if (!function_exists('velocitychild_get_services_repeater_fields')) {
	function velocitychild_get_services_repeater_fields()
	{
		return [
			'service_title' => [
				'type'    => 'text',
				'label'   => __('Judul Layanan', 'justg'),
				'default' => '',
			],
			'service_link' => [
				'type'        => 'url',
				'label'       => __('Link Layanan', 'justg'),
				'default'     => '',
				'description' => __('URL menuju halaman layanan.', 'justg'),
			],
			'service_image' => [
				'type'        => 'image',
				'label'       => __('Gambar Layanan', 'justg'),
				'default'     => '',
				'description' => __('Pilih gambar dari Media Library.', 'justg'),
			],
		];
	}
}

if (!class_exists('WP_Customize_Control') && file_exists(ABSPATH . WPINC . '/class-wp-customize-control.php')) {
	require_once ABSPATH . WPINC . '/class-wp-customize-control.php';
}

if (!class_exists('Velocitychild_Repeater_Control') && class_exists('WP_Customize_Control')) {
	class Velocitychild_Repeater_Control extends WP_Customize_Control {
		public $type = 'velocity_repeater';
		public $fields = [];

		public function __construct($manager, $id, $args = [], $options = [])
		{
			if (isset($args['fields'])) {
				$this->fields = (array) $args['fields'];
				unset($args['fields']);
			}
			parent::__construct($manager, $id, $args);
		}

		protected function render_content()
		{
			if (empty($this->fields)) {
				return;
			}

			$value = $this->value();
			if (is_string($value)) {
				$decoded = json_decode($value, true);
				$value = (json_last_error() === JSON_ERROR_NONE) ? $decoded : [];
			}

			if (!is_array($value)) {
				$value = [];
			}

			$encoded_value = wp_json_encode($value);
			if (empty($encoded_value)) {
				$encoded_value = '[]';
			}
			?>
			<div class="velocity-repeater-control">
				<?php if (!empty($this->label)) : ?>
					<span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
				<?php endif; ?>
				<?php if (!empty($this->description)) : ?>
					<p class="description"><?php echo wp_kses_post($this->description); ?></p>
				<?php endif; ?>

				<div class="velocity-repeater" data-fields="<?php echo esc_attr(wp_json_encode($this->fields)); ?>" data-default-label="<?php echo esc_attr(__('Layanan', 'justg')); ?>">
					<input type="hidden" class="velocity-repeater-store" <?php $this->link(); ?> value="<?php echo esc_attr($encoded_value); ?>">
					<div class="velocity-repeater-items">
						<?php
						if (!empty($value)) {
							foreach ($value as $item) {
								echo $this->get_single_item_markup($item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
						}
						?>
					</div>
					<button type="button" class="button button-primary velocity-repeater-add"><?php esc_html_e('Tambah Layanan', 'justg'); ?></button>
					<script type="text/html" class="velocity-repeater-template">
						<?php echo $this->get_single_item_markup([]); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</script>
				</div>
			</div>
			<?php
		}

		private function get_single_item_markup($item_values = [])
		{
			ob_start();
			$title   = isset($item_values['service_title']) ? $item_values['service_title'] : '';
			$summary = $title ? $title : __('Layanan', 'justg');
			?>
			<div class="velocity-repeater-item">
				<button type="button" class="velocity-repeater-toggle" aria-expanded="true">
					<span class="velocity-repeater-item-label"><?php echo esc_html($summary); ?></span>
					<span class="velocity-repeater-toggle-icon" aria-hidden="true"></span>
				</button>
				<div class="velocity-repeater-item-body">
					<?php foreach ($this->fields as $field_key => $field) :
						$field_type    = isset($field['type']) ? $field['type'] : 'text';
						$field_label   = isset($field['label']) ? $field['label'] : '';
						$field_value   = isset($item_values[$field_key]) ? $item_values[$field_key] : '';
						$field_default = isset($field['default']) ? $field['default'] : '';
						$field_desc    = isset($field['description']) ? $field['description'] : '';
						$is_summary    = ('service_title' === $field_key);
						?>
						<label class="velocity-repeater-field">
							<span class="velocity-repeater-field-label"><?php echo esc_html($field_label); ?></span>
							<?php if ('textarea' === $field_type) : ?>
								<textarea data-field="<?php echo esc_attr($field_key); ?>" data-default="<?php echo esc_attr($field_default); ?>" <?php echo $is_summary ? 'data-summary-field="true"' : ''; ?>><?php echo esc_textarea($field_value); ?></textarea>
							<?php elseif ('image' === $field_type) : ?>
								<div class="velocity-repeater-media">
									<input type="hidden" data-field="<?php echo esc_attr($field_key); ?>" data-default="<?php echo esc_attr($field_default); ?>" value="<?php echo esc_attr($field_value); ?>">
									<div class="velocity-repeater-media-preview">
										<?php if (!empty($field_value)) : ?>
											<img src="<?php echo esc_url($field_value); ?>" alt="">
										<?php else : ?>
											<span><?php esc_html_e('Belum ada gambar', 'justg'); ?></span>
										<?php endif; ?>
									</div>
									<div class="velocity-repeater-media-actions">
										<button type="button" class="button velocity-repeater-upload"><?php esc_html_e('Pilih Gambar', 'justg'); ?></button>
										<button type="button" class="button velocity-repeater-remove-image"><?php esc_html_e('Hapus', 'justg'); ?></button>
									</div>
								</div>
							<?php else : ?>
								<input type="<?php echo esc_attr($field_type); ?>" data-field="<?php echo esc_attr($field_key); ?>" data-default="<?php echo esc_attr($field_default); ?>" value="<?php echo esc_attr($field_value); ?>" <?php echo $is_summary ? 'data-summary-field="true"' : ''; ?>>
							<?php endif; ?>
							<?php if (!empty($field_desc)) : ?>
								<span class="description customize-control-description"><?php echo esc_html($field_desc); ?></span>
							<?php endif; ?>
						</label>
					<?php endforeach; ?>
					<div class="velocity-repeater-actions">
						<button type="button" class="button velocity-repeater-clone"><?php esc_html_e('Clone', 'justg'); ?></button>
						<button type="button" class="button button-secondary velocity-repeater-remove"><?php esc_html_e('Hapus', 'justg'); ?></button>
					</div>
				</div>
			</div>
			<?php
			return ob_get_clean();
		}
	}
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
	$wp_customize->add_setting('services_list', [
		'type'              => 'theme_mod',
		'default'           => [],
		'sanitize_callback' => 'velocitychild_sanitize_services_list',
	]);
	$wp_customize->add_control(new Velocitychild_Repeater_Control($wp_customize, 'services_list', [
		'label'       => __('Daftar Layanan', 'justg'),
		'description' => __('Tambah, edit, clone, atau hapus layanan.', 'justg'),
		'section'     => 'section_layanan',
		'priority'    => 20,
		'fields'      => velocitychild_get_services_repeater_fields(),
	]));

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

function velocitychild_sanitize_services_list($value)
{
	if (is_string($value)) {
		$decoded = json_decode($value, true);
		if (json_last_error() === JSON_ERROR_NONE) {
			$value = $decoded;
		}
	}

	if (!is_array($value)) {
		return [];
	}

	$fields = velocitychild_get_services_repeater_fields();
	$clean  = [];

	foreach ($value as $item) {
		if (!is_array($item)) {
			continue;
		}

		$clean_item = [];
		$is_empty   = true;

		foreach ($fields as $field_key => $field) {
			$field_value = isset($item[$field_key]) ? $item[$field_key] : '';

			switch ($field_key) {
				case 'service_link':
				case 'service_image':
					$field_value = esc_url_raw($field_value);
					break;
				default:
					$field_value = sanitize_text_field($field_value);
					break;
			}

			if ('' === $field_value && !empty($field['default'])) {
				$field_value = $field['default'];
			}

			if (!empty($field_value)) {
				$is_empty = false;
			}

			$clean_item[$field_key] = $field_value;
		}

		if (!$is_empty) {
			$clean[] = $clean_item;
		}
	}

	return $clean;
}

function velocitychild_customize_controls_assets()
{
	$theme   = wp_get_theme();
	$version = $theme ? $theme->get('Version') : '1.0.0';

	wp_enqueue_media();

	wp_enqueue_style(
		'velocitychild-customizer-repeater',
		get_stylesheet_directory_uri() . '/css/customizer-repeater.css',
		[],
		$version
	);

	wp_enqueue_script(
		'velocitychild-customizer-repeater',
		get_stylesheet_directory_uri() . '/js/customizer-repeater.js',
		['customize-controls', 'jquery'],
		$version,
		true
	);
}
add_action('customize_controls_enqueue_scripts', 'velocitychild_customize_controls_assets');


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
