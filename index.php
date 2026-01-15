<?php

/**
 * Template Name: Home Template
 *
 * Template for displaying a page just with the header and footer area and a "naked" content area in between.
 * Good for landingpages and other types of pages where you want to add a lot of custom markup.
 *
 * @package justg
 */

get_header();
?>

<div class="wrapper" id="page-wrapper">


    <?php 
    $judul_layanan = velocitytheme_option('judul_layanan', '');
    $subjudul_layanan = velocitytheme_option('subjudul_layanan', '');
    echo '<div class="text-center my-3 services-section">';
        if($judul_layanan){
            echo '<h2 class="w-100 text-center mt-5 mb-2 fs-3">'.$judul_layanan.'</h2>';
        } if($subjudul_layanan){
            echo '<p class="w-100 text-muted mb-4">'.$subjudul_layanan.'</p>';
        }
        $services = get_theme_mod('services_list', []);
        if (is_string($services)) {
            $decoded_services = json_decode($services, true);
            $services = (json_last_error() === JSON_ERROR_NONE) ? $decoded_services : [];
        }
        if (!is_array($services)) {
            $services = [];
        }
        if (empty($services)) {
            $legacy_services = [];
            for ($x = 1; $x <= 4; $x++) {
                $legacy_title = velocitytheme_option('layanan'.$x, '');
                $legacy_link = velocitytheme_option('urllayanan'.$x, '');
                $legacy_image = velocitytheme_option('gambarlayanan'.$x, '');
                if ($legacy_title || $legacy_link || $legacy_image) {
                    $legacy_services[] = [
                        'service_title' => $legacy_title,
                        'service_link' => $legacy_link,
                        'service_image' => $legacy_image,
                    ];
                }
            }
            $services = $legacy_services;
        }

        if (!empty($services)) {
            $service_count = count($services);
            if ($service_count <= 1) {
                $cols_md = 1;
                $cols_lg = 1;
                $cols_xxl = 1;
            } elseif ($service_count === 2) {
                $cols_md = 2;
                $cols_lg = 2;
                $cols_xxl = 2;
            } elseif ($service_count === 3) {
                $cols_md = 3;
                $cols_lg = 3;
                $cols_xxl = 3;
            } elseif ($service_count === 4) {
                $cols_md = 4;
                $cols_lg = 4;
                $cols_xxl = 4;
            } elseif ($service_count === 5) {
                $cols_md = 4;
                $cols_lg = 4;
                $cols_xxl = 5;
            } elseif ($service_count === 6) {
                $cols_md = 3;
                $cols_lg = 3;
                $cols_xxl = 3;
            } elseif ($service_count === 7) {
                $cols_md = 3;
                $cols_lg = 4;
                $cols_xxl = 4;
            } elseif ($service_count === 8) {
                $cols_md = 4;
                $cols_lg = 4;
                $cols_xxl = 4;
            } elseif ($service_count === 9) {
                $cols_md = 3;
                $cols_lg = 3;
                $cols_xxl = 5;
            } else {
                $cols_md = 4;
                $cols_lg = 4;
                $cols_xxl = 5;
            }

            echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-'.$cols_md.' row-cols-lg-'.$cols_lg.' row-cols-xxl-'.$cols_xxl.' g-3 justify-content-center">';
            foreach ($services as $service) {
                $service_title = isset($service['service_title']) ? $service['service_title'] : '';
                $service_desc = isset($service['service_desc']) ? $service['service_desc'] : '';
                $service_link = isset($service['service_link']) ? $service['service_link'] : '';
                $service_image = isset($service['service_image']) ? $service['service_image'] : '';

            echo '<div class="col">';
                echo '<div class="card-layanan h-100">';
                    echo '<div class="img-layanan ratio ratio-1x1 mx-auto">';
                        if (!empty($service_image)) {
                            echo '<img class="img-fluid rounded-circle object-fit-cover" src="'.esc_url($service_image).'" alt="">';
                        }
                    echo '</div>';
                    echo '<h3 class="fs-6">';
                        if (!empty($service_link)) {
                            echo '<a href="'.esc_url($service_link).'">';
                        }
                        echo '<strong class="text-dark">'.esc_html($service_title).'</strong>';
                        if (!empty($service_link)) {
                            echo '</a>';
                        }
                    echo '</h3>';
                    if (!empty($service_desc)) {
                        echo '<p class="text-muted lh-custom mb-0">'.esc_html($service_desc).'</p>';
                    }
                echo '</div>';
            echo '</div>';
        }
            echo '</div>';
        }
    echo '</div>';
    ?>


    <?php $args = array(
        'posts_per_page' => 3,
        'showposts' => 3,
        'post_type' => 'property',
    );
    $wp_query = new WP_Query($args); 
    global $product_metabox;
    if($wp_query->have_posts ()): ?>
    <div class="text-center mb-3 mt-5 ">
    <h3 class="w-100 text-center mt-5 mb-1 d-inline-block"><?php echo velocitytheme_option('judul_properti', ''); ?></h3>
    <p class="w-100 text-center mb-3 d-inline-block text-secondary"><?php echo velocitytheme_option('subjudul_properti', ''); ?></p>
    </div>
    <div class="text-center row property">
    <?php while($wp_query->have_posts()): $wp_query->the_post(); ?>
    <div class="col-sm-4 mb-5 ">
        <?php get_template_part('property', 'loop');?>
    </div>
    <?php endwhile; ?>
    </div>
    <?php endif; ?>
    <?php wp_reset_query(); ?>



    <?php $args = array(
        'posts_per_page' => 3,
        'showposts' => 3,
        'post_type' => 'post',
        'cat' => velocitytheme_option('artikel_cat', ''),
    );
    $wp_query = new WP_Query($args); 
    global $product_metabox;
    if($wp_query->have_posts ()): ?>
    <div class="text-center my-3">
        <h3 class="w-100 text-center mt-5 mb-1 d-inline-block"><?php echo velocitytheme_option('judul_artikel', ''); ?></h3>
        <p class="w-100 text-center mb-3 d-inline-block text-secondary"><?php echo velocitytheme_option('subjudul_artikel', ''); ?></p>
    </div>
    <div class="text-center row mb-5">
    <?php while($wp_query->have_posts()): $wp_query->the_post();
    $content = get_the_content();
    $trimmed_content = wp_trim_words($content,20);
    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
    if (!$thumbnail_url) {
        $thumbnail_url = get_stylesheet_directory_uri() . '/img/no-image.webp';
    } ?>
    <div class="col-sm-4">
        <div class="card w-100 text-start rounded-0 border-0 bg-transparent">
        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
            <div class="ratio ratio-4x3">
            <img class="home-bottom-post-thumb rounded-0 img-fluid" 
                src="<?php echo esc_url($thumbnail_url); ?>" 
                alt="<?php the_title_attribute(); ?>">
            </div>
        </a>
        <div class="card-body px-0">
            <div class="text-dark"><?php echo get_the_date(); ?></div>
            <h5 class="card-title mb-2" style="line-height: 1.4;">
                <a class="text-dark" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                <?php the_title(); ?>
                </a>
            </h5>
            <p class="card-text text-secondary"><?php echo $trimmed_content; ?></p>
            <div class="mt-3">By <?php echo get_the_author_meta('display_name'); ?></div>
        </div>
        </div>
    </div>
    <?php endwhile; ?>
    </div>
    <?php endif; ?>
    <?php wp_reset_query(); ?>



</div><!-- #page-wrapper -->

<?php
get_footer();
