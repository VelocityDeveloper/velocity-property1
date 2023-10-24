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


function vel_meta_box_callback( $post ) {

	wp_nonce_field( 'vel_metabox', 'property_meta_box_nonce' );

	$harga = get_post_meta( $post->ID, 'harga', true );
	$lokasi = get_post_meta( $post->ID, 'lokasi', true );
	$agen = get_post_meta( $post->ID, 'agen', true );
	$telepon_agen = get_post_meta( $post->ID, 'telepon_agen', true );
	$luas_tanah = get_post_meta( $post->ID, 'luas_tanah', true );
	$luas_bangunan = get_post_meta( $post->ID, 'luas_bangunan', true );
	$jumlah_lantai = get_post_meta( $post->ID, 'jumlah_lantai', true );
	$kamar_tidur = get_post_meta( $post->ID, 'kamar_tidur', true );
	$kamar_mandi = get_post_meta( $post->ID, 'kamar_mandi', true );
	$perabotan = get_post_meta( $post->ID, 'perabotan', true );
	$kondisi_property = get_post_meta( $post->ID, 'kondisi_property', true );
	$garasi = get_post_meta( $post->ID, 'garasi', true );
	$jalur_telepon = get_post_meta( $post->ID, 'jalur_telepon', true );
	$sertifikat = get_post_meta( $post->ID, 'sertifikat', true );
	$daya_listrik = get_post_meta( $post->ID, 'daya_listrik', true );
	$status = get_post_meta( $post->ID, 'status', true );

	echo '<table class="form-table" role="presentation">';
	echo '<tbody>';

	echo '<tr>';
	echo '<th><label>Status Properti</label></th>';
	echo '<td><input type="text" name="status" value="'.esc_attr($status).'" size="25" /></td>';
	echo '</tr>';
    
	echo '<tr>';
	echo '<th><label>Harga</label></th>';
	echo '<td><input type="number" name="harga" value="'.esc_attr($harga).'" size="25" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th><label>Lokasi Lengkap</label></th>';
	echo '<td><input type="text" name="lokasi" value="'.esc_attr($lokasi).'" size="25" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th><label>Nama Agen</label></th>';
	echo '<td><input type="text" name="agen" value="'.esc_attr($agen).'" size="25" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th><label>Telepon Agen</label></th>';
	echo '<td><input type="text" name="telepon_agen" value="'.esc_attr($telepon_agen).'" size="25" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th><label>Luas Tanah</label></th>';
	echo '<td><input type="text" name="luas_tanah" value="'.esc_attr($luas_tanah).'" size="25" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th><label>Luas Bangunan</label></th>';
	echo '<td><input type="text" name="luas_bangunan" value="'.esc_attr($luas_bangunan).'" size="25" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th><label>Jumlah Lantai</label></th>';
	echo '<td><input type="text" name="jumlah_lantai" value="'.esc_attr($jumlah_lantai).'" size="25" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th><label>Kamar Tidur</label></th>';
	echo '<td><input type="text" name="kamar_tidur" value="'.esc_attr($kamar_tidur).'" size="25" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th><label>Kamar Mandi</label></th>';
	echo '<td><input type="text" name="kamar_mandi" value="'.esc_attr($kamar_mandi).'" size="25" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th><label>Perabotan</label></th>';
	echo '<td><input type="text" name="perabotan" value="'.esc_attr($perabotan).'" size="25" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th><label>Kondisi Properti</label></th>';
	echo '<td><input type="text" name="kondisi_property" value="'.esc_attr($kondisi_property).'" size="25" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th><label>Garasi</label></th>';
	echo '<td><input type="text" name="garasi" value="'.esc_attr($garasi).'" size="25" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th><label>Jalur Telepon</label></th>';
	echo '<td><input type="text" name="jalur_telepon" value="'.esc_attr($jalur_telepon).'" size="25" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th><label>Sertifikat</label></th>';
	echo '<td><input type="text" name="sertifikat" value="'.esc_attr($sertifikat).'" size="25" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th><label>Daya Listrik</label></th>';
	echo '<td><input type="text" name="daya_listrik" value="'.esc_attr($daya_listrik).'" size="25" /></td>';
	echo '</tr>';

	echo '</tbody></table>';

}


function vel_metabox( $post_id ) {

	if ( ! isset( $_POST['property_meta_box_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['property_meta_box_nonce'], 'vel_metabox' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}


	if ( ! isset( $_POST['status'] ) ) {
		return;
	}
	if ( ! isset( $_POST['harga'] ) ) {
		return;
	}
	if ( ! isset( $_POST['lokasi'] ) ) {
		return;
	}
	if ( ! isset( $_POST['agen'] ) ) {
		return;
	}
	if ( ! isset( $_POST['telepon_agen'] ) ) {
		return;
	}
	if ( ! isset( $_POST['luas_tanah'] ) ) {
		return;
	}
	if ( ! isset( $_POST['luas_bangunan'] ) ) {
		return;
	}
	if ( ! isset( $_POST['jumlah_lantai'] ) ) {
		return;
	}
	if ( ! isset( $_POST['kamar_tidur'] ) ) {
		return;
	}
	if ( ! isset( $_POST['kamar_mandi'] ) ) {
		return;
	}
	if ( ! isset( $_POST['perabotan'] ) ) {
		return;
	}
	if ( ! isset( $_POST['kondisi_property'] ) ) {
		return;
	}
	if ( ! isset( $_POST['garasi'] ) ) {
		return;
	}
	if ( ! isset( $_POST['jalur_telepon'] ) ) {
		return;
	}
	if ( ! isset( $_POST['sertifikat'] ) ) {
		return;
	}
	if ( ! isset( $_POST['daya_listrik'] ) ) {
		return;
	}


	// Update the meta field in the database.
	update_post_meta( $post_id, 'status', sanitize_text_field($_POST['status']));
	update_post_meta( $post_id, 'harga', sanitize_text_field($_POST['harga']));
	update_post_meta( $post_id, 'lokasi', sanitize_text_field($_POST['lokasi']));
	update_post_meta( $post_id, 'agen', sanitize_text_field($_POST['agen']));
	update_post_meta( $post_id, 'telepon_agen', sanitize_text_field($_POST['telepon_agen']));
	update_post_meta( $post_id, 'luas_tanah', sanitize_text_field($_POST['luas_tanah']));
	update_post_meta( $post_id, 'luas_bangunan', sanitize_text_field($_POST['luas_bangunan']));
	update_post_meta( $post_id, 'jumlah_lantai', sanitize_text_field($_POST['jumlah_lantai']));
	update_post_meta( $post_id, 'kamar_tidur', sanitize_text_field($_POST['kamar_tidur']));
	update_post_meta( $post_id, 'kamar_mandi', sanitize_text_field($_POST['kamar_mandi']));
	update_post_meta( $post_id, 'perabotan', sanitize_text_field($_POST['perabotan']));
	update_post_meta( $post_id, 'kondisi_property', sanitize_text_field($_POST['kondisi_property']));
	update_post_meta( $post_id, 'garasi', sanitize_text_field($_POST['garasi']));
	update_post_meta( $post_id, 'jalur_telepon', sanitize_text_field($_POST['jalur_telepon']));
	update_post_meta( $post_id, 'sertifikat', sanitize_text_field($_POST['sertifikat']));
	update_post_meta( $post_id, 'daya_listrik', sanitize_text_field($_POST['daya_listrik']));
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
    $html = 'Rp '.number_format( $harga ,0 , ',','.' );
    return $html;
}



// Update jumlah pengunjung dengan plugin WP-Statistics
function velocity_allpage() {
    global $wpdb,$post;
    $postID = $post->ID;
    $count_key = 'hit';
    if(empty($post))
    return false;
	if( function_exists( 'WP_Statistics' ) ) {
		$table_name = $wpdb->prefix . "statistics_pages";
		$results    = $wpdb->get_results("SELECT sum(count) as result_value FROM $table_name WHERE id = $postID");
		$count = $results?$results[0]->result_value:'0';
		if($count=='') {
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, '0');
		} else {
			update_post_meta($postID, $count_key, $count);
		}
	} else {
        $user_ip = $_SERVER['REMOTE_ADDR']; //retrieve the current IP address of the visitor
        $key = $user_ip . 'x' . $postID; //combine post ID & IP to form unique key
        $value = array($user_ip, $postID); // store post ID & IP as separate values (see note)
        $visited = get_transient($key); //get transient and store in variable

        //check to see if the Post ID/IP ($key) address is currently stored as a transient
        if ( false === ( $visited ) ) {

            //store the unique key, Post ID & IP address for 12 hours if it does not exist
           set_transient( $key, $value, 60*60*12 );

            // now run post views function
            $count = get_post_meta($postID, $count_key, true);
            if($count==''){
                $count = 0;
                delete_post_meta($postID, $count_key);
                add_post_meta($postID, $count_key, '0');
            }else{
                $count++;
                update_post_meta($postID, $count_key, $count);
            }
        }		
	}
}
add_action( 'wp', 'velocity_allpage' );



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
                <?php echo do_shortcode("[resize-thumbnail width='300' height='300' crop='false' upscale='true' post_id='".$post->ID."']"); ?>
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

