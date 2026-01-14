<?php

// Register Custom Post Type & Taxonomy
add_action('init', 'velocity_admin_init');
function velocity_admin_init() {
    register_post_type('property', array(
        'labels' => array(
            'name' => 'Listing',
            'singular_name' => 'property',
        ),
        'rewrite' => array(
            'slug' => 'listing',
        ),
        'menu_icon' => 'dashicons-admin-multisite',
        'public' => true,
        'has_archive' => true,
        'taxonomies' => array('property-category','property-location'),
        'supports' => array(
            'title',
            'editor',
            'thumbnail',
        ),
    ));
	register_taxonomy(
        'property-category',
        'property',
        array(
            'label' => __( 'Property Categories' ),
            'hierarchical' => true,
            'show_admin_column' => true,
        )
    );
	register_taxonomy(
        'property-location',
        'property',
        array(
            'label' => __( 'Property Locations' ),
            'hierarchical' => true,
            'show_admin_column' => true,
        )
    );
}




// custom property meta box
function velocity_custom_meta_box() {
	$screens = array( 'property' );
	foreach ( $screens as $screen ) {
		add_meta_box(
			'property_section',
			__( 'Property Detail', 'velpropertydetail' ),
			'vel_meta_box_callback',
			$screen
		);
	}
}
add_action( 'add_meta_boxes', 'velocity_custom_meta_box' );


// ===== Metabox Callback =====
function vel_meta_box_callback( $post ) {
	wp_nonce_field( 'vel_metabox', 'property_meta_box_nonce' );

	// Ambil semua meta yang diperlukan sekali saja
	$meta = array(
		'status'          => get_post_meta( $post->ID, 'status', true ),
		'harga'           => get_post_meta( $post->ID, 'harga', true ),
		'lokasi'          => get_post_meta( $post->ID, 'lokasi', true ),
		'agen'            => get_post_meta( $post->ID, 'agen', true ),
		'telepon_agen'    => get_post_meta( $post->ID, 'telepon_agen', true ),
		'luas_tanah'      => get_post_meta( $post->ID, 'luas_tanah', true ),
		'luas_bangunan'   => get_post_meta( $post->ID, 'luas_bangunan', true ),
		'jumlah_lantai'   => get_post_meta( $post->ID, 'jumlah_lantai', true ),
		'kamar_tidur'     => get_post_meta( $post->ID, 'kamar_tidur', true ),
		'kamar_mandi'     => get_post_meta( $post->ID, 'kamar_mandi', true ),
		'perabotan'       => get_post_meta( $post->ID, 'perabotan', true ),
		'kondisi_property'=> get_post_meta( $post->ID, 'kondisi_property', true ),
		'garasi'          => get_post_meta( $post->ID, 'garasi', true ),
		'jalur_telepon'   => get_post_meta( $post->ID, 'jalur_telepon', true ),
		'sertifikat'      => get_post_meta( $post->ID, 'sertifikat', true ),
		'daya_listrik'    => get_post_meta( $post->ID, 'daya_listrik', true ),
	);

	echo '<table class="form-table" role="presentation"><tbody>';

	// Status Properti (select)
	echo '<tr><th><label for="status">Status Properti</label></th><td>';
	echo '<select name="status" id="status">';
	$opts = array( 'Dijual', 'Disewakan' );
	$current = $meta['status'] ?: 'Dijual';
	foreach ( $opts as $opt ) {
		printf(
			'<option value="%1$s" %2$s>%1$s</option>',
			esc_attr( $opt ),
			selected( $current, $opt, false )
		);
	}
	echo '</select></td></tr>';

	// Helper utk render input row full width
	$render_row = function( $key, $label, $type = 'text', $attrs = '' ) use ( $meta ) {
		printf(
			'<tr><th><label for="%1$s">%2$s</label></th>
			<td><input type="%3$s" id="%1$s" name="%1$s" value="%4$s" style="max-width:300px;width:100%%" %5$s /></td></tr>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_attr( $type ),
			esc_attr( $meta[ $key ] ),
			$attrs
		);
	};

	// Semua field full width
	$render_row( 'harga',           'Harga',            'number', 'min="0" step="1"' );
	$render_row( 'lokasi',          'Lokasi Lengkap',   'text' );
	$render_row( 'agen',            'Nama Agen',        'text' );
	$render_row( 'telepon_agen',    'Telepon Agen',     'tel',    'placeholder="+62..."' );
	$render_row( 'luas_tanah',      'Luas Tanah (m²)',  'number', 'min="0" step="0.01"' );
	$render_row( 'luas_bangunan',   'Luas Bangunan (m²)','number','min="0" step="0.01"' );
	$render_row( 'jumlah_lantai',   'Jumlah Lantai',    'number', 'min="0" step="1"' );
	$render_row( 'kamar_tidur',     'Kamar Tidur',      'number', 'min="0" step="1"' );
	$render_row( 'kamar_mandi',     'Kamar Mandi',      'number', 'min="0" step="1"' );
	$render_row( 'perabotan',       'Perabotan',        'text',   'placeholder="Full/Partial/None"' );
	$render_row( 'kondisi_property','Kondisi Properti', 'text',   'placeholder="Baru/Second/Renovasi"' );
	$render_row( 'garasi',          'Garasi (mobil)',   'number', 'min="0" step="1"' );
	$render_row( 'jalur_telepon',   'Jalur Telepon',    'text',   'placeholder="Ada/Tidak Ada"' );
	$render_row( 'sertifikat',      'Sertifikat',       'text',   'placeholder="SHM/HGB/PPJB/dll."' );
	$render_row( 'daya_listrik',    'Daya Listrik (VA)','number', 'min="0" step="1"' );

	echo '</tbody></table>';
}


// ===== Save Handler (Optimized) =====
function vel_metabox( $post_id ) {
	// Nonce
	if ( ! isset( $_POST['property_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['property_meta_box_nonce'], 'vel_metabox' ) ) return;

	// Autosave / Revisions
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( wp_is_post_revision( $post_id ) ) return;

	// Capability
	if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) return;
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	}

	// Whitelist & tipe sanitasi
	$fields = array(
		'status'            => 'enum',   // Dijual/Disewakan
		'harga'             => 'int',
		'lokasi'            => 'text',
		'agen'              => 'text',
		'telepon_agen'      => 'tel',
		'luas_tanah'        => 'float',
		'luas_bangunan'     => 'float',
		'jumlah_lantai'     => 'int',
		'kamar_tidur'       => 'int',
		'kamar_mandi'       => 'int',
		'perabotan'         => 'text',
		'kondisi_property'  => 'text',
		'garasi'            => 'int',
		'jalur_telepon'     => 'text',
	'sertifikat'        => 'text',
		'daya_listrik'      => 'int',
	);

	$allowed_status = array( 'Dijual', 'Disewakan' );

	foreach ( $fields as $key => $type ) {
		if ( ! isset( $_POST[ $key ] ) ) continue;

		$raw = wp_unslash( $_POST[ $key ] );
		switch ( $type ) {
			case 'int':
				$val = intval( $raw );
				break;
			case 'float':
				$val = floatval( str_replace( ',', '.', $raw ) );
				break;
			case 'tel':
				// Hanya angka, spasi, +, -, (, )
				$val = preg_replace( '/[^0-9\+\-\s\(\)]/', '', $raw );
				$val = substr( $val, 0, 32 );
				break;
			case 'enum':
				$val = in_array( $raw, $allowed_status, true ) ? $raw : 'Dijual';
				break;
			default: // text
				$val = sanitize_text_field( $raw );
		}

		update_post_meta( $post_id, $key, $val );
	}
}
add_action( 'save_post', 'vel_metabox' );




function velocity_harga($postid = null){
    global $post;
    if(empty($postid)){
        $post_id = $post->ID;
    } else {
        $post_id = $postid;
    }
    $price = get_post_meta($post_id,'harga',true);
    $harga = preg_replace('/[^0-9]/', '', $price);
    if(!empty($price)){
        $html = 'Rp '.number_format( $harga ,0 , ',','.' );
    } else {
        $html = 'Hubungi Admin';
    }
    
    return $html;
}



// Update jumlah pengunjung per postingan (tanpa WP-Statistics)
function velocity_allpage_simple() {
    if ( is_admin() || wp_doing_ajax() || is_feed() ) return;
    if ( !is_singular() ) return;

    global $post;
    if ( empty($post) || empty($post->ID) ) return;

    $postID    = (int) $post->ID;
    $count_key = 'hit';

    // Gunakan IP + postID sebagai kunci sementara
    $user_ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
    $key     = 'v_hit_' . md5($user_ip . 'x' . $postID);

    // Jika belum ada transient, tambahkan view + set transient (12 jam per IP/post)
    if ( false === get_transient($key) ) {
        set_transient($key, 1, 12 * HOUR_IN_SECONDS);

        $count = (int) get_post_meta($postID, $count_key, true);
        update_post_meta($postID, $count_key, $count + 1);
    }
}
if ( ! ( class_exists( 'Velocity_Addons_Statistic' ) && get_option( 'statistik_velocity', '1' ) === '1' ) ) {
    add_action( 'template_redirect', 'velocity_allpage_simple', 11 );
}



// [velocity-property]
function velocity_property($atts){
    ob_start();
    $atribut = shortcode_atts(array(
        'kategori' 	=> '', // pakai slug
        'lokasi' 	=> '', // pakai slug
        'show_image' 	=> 'yes',
        'jumlah' => 3
    ),$atts);
    $show_image = $atribut['show_image'];
    $args['posts_per_page'] = $atribut['jumlah'];
    $args['post_type'] = 'property';
    $kategori = $atribut['kategori'];
    $lokasi = $atribut['lokasi'];
    $taxquery = array();
    if ($kategori) {
        $taxquery[] = array(
            'taxonomy' => 'property-category',
            'field'    => 'slug',
            'terms'    => $kategori,
        );
    }
    if ($lokasi) {
        $taxquery[] = array(
            'taxonomy' => 'property-location',
            'field'    => 'slug',
            'terms'    => $lokasi,
        );
    }
    //if count taxquery more than 1, then set taxquery
    if(count($taxquery) > 1) {
        $taxquery['relation'] = 'AND';
    }
    if($taxquery) {
        $args['tax_query'] = $taxquery;
    }
    $wpex_query = new wp_query( $args );
    echo '<div class="velocity-property">';
    foreach( $wpex_query->posts as $post ) { setup_postdata( $post ); ?>
    <div class="border-bottom pb-2 mb-2 row mx-0 velocity-property-list">
        <?php if($show_image == 'yes'){ ?>
            <div class="col-3 ps-0">
                <div class="ratio ratio-1x1">
                    <?php if (has_post_thumbnail($post->ID)) { ?>
                        <?php echo get_the_post_thumbnail($post->ID, 'medium', array('class' => 'img-fluid')); ?>
                    <?php } else { ?>
                        <svg style="background-color: #ececec;width: 100%;height: 100%;" aria-hidden="true"></svg>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <div class="col px-0">
            <div class="mb-1"><a class="fw-bold text-dark" href="<?php echo get_the_permalink($post->ID); ?>"><?php echo get_the_title($post->ID); ?></a></div>
            <div class="text-dark"><?php echo velocity_harga($post->ID); ?></div>
        </div>
    </div>
    <?php }
    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('velocity-property', 'velocity_property');

