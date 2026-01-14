<?php

/**
 * Post rendering content according to caller of get_template_part
 *
 * @package justg
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$status = get_post_meta( $post->ID, 'status', true );
$lokasi = get_post_meta( $post->ID, 'lokasi', true );
$agen = get_post_meta( $post->ID, 'agen', true );
if (empty($agen)) {
    $agen = get_theme_mod('property_default_agent', '');
}
$telepon_agen = get_post_meta( $post->ID, 'telepon_agen', true );
if (empty($telepon_agen)) {
    $telepon_agen = get_theme_mod('property_default_agent_phone', '');
}
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
?>

<article <?php post_class('block-primary mb-4'); ?> id="post-<?php the_ID(); ?>">

    <header class="entry-header">
        <?php do_action('justg_before_title');
        the_title('<h1 class="entry-title">', '</h1>'); ?>
        <div class="entry-meta mb-2">
            <?php justg_posted_on(); ?>
        </div><!-- .entry-meta -->
    </header><!-- .entry-header -->

    <div class="entry-content">

        <div class="border bg-white mb-3">
                <div class="row bg-light m-0 py-2">
                    <div class="col-md-8 h6 mb-2 mb-md-0">
                        <?php $args = array(
                            'orderby' => 'term_order',                          
                        );
                            $property_location = wp_get_object_terms( $post->ID,  'property-location', $args );
                            //echo '<pre>'.print_r($property_location,1).'</pre>'; 
                            if ( ! empty( $property_location ) ) {
                                if ( ! is_wp_error( $property_location ) ) {
                                    echo '<div class="property-location">';
                                        foreach( array_reverse($property_location) as $term ) {
                                            echo '<span><a  class="text-dark" href="' . esc_url( get_term_link( $term->slug, 'property-location' ) ) . '">' . esc_html( $term->name ) . '</a></span>'; 
                                        }
                                    echo '</div>';
                                }
                            }
                        ?>
                    </div>
                    <div class="col-md-4 h6 mb-0 text-md-end">
                        <?php echo velocity_harga(); ?>
                    </div>
                </div>
                <div class="row m-0 py-3">
                    <div class="col-12 mb-2">
                        <?php if(has_post_thumbnail()) { ?>
                            <img class="w-100" src="<?php the_post_thumbnail_url( 'full' ); ?> " />
                        <?php } ?>
                    </div>
                    <div class="col-12">
                        <small>Tayang sejak <?php the_time('d-m-Y'); ?> | Dilihat sebanyak <?php echo get_post_meta(get_the_ID(),'hit',true);?> kali</small>
                        <?php if($agen || $telepon_agen){ ?>
                            <div class="bg-agen p-3 text-dark my-2">
                                <?php if(!empty($agen)){ ?>
                                    <strong class="d-block mb-2"><?php echo $agen;?></strong>
                                <?php } if(!empty($telepon_agen)){ ?>
                                    <div class="rounded-0 py-2 px-4 bg-primary text-white d-inline-block">
                                        <?php echo $telepon_agen;?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-6 pr-1">
                                <?php if(!empty($status)){ ?>
                                    <div class="mb-2"><strong>Status Properti:</strong> <?php echo $status; ?></div>
                                <?php } ?>
                                <?php if(!empty($luas_tanah)){ ?>
                                    <div class="mb-2"><strong>Luas Tanah:</strong> <?php echo $luas_tanah; ?></div>
                                <?php } ?>
                                <?php if(!empty($luas_bangunan)){ ?>
                                    <div class="mb-2"><strong>Luas Bangunan:</strong> <?php echo $luas_bangunan; ?></div>
                                <?php } ?>
                                <?php if(!empty($jumlah_lantai)){ ?>
                                    <div class="mb-2"><strong>Luas Lantai:</strong> <?php echo $jumlah_lantai; ?></div>
                                <?php } ?>
                                <?php if(!empty($kamar_tidur)){ ?>
                                    <div class="mb-2"><strong>Kamar Tidur:</strong> <?php echo $kamar_tidur; ?></div>
                                <?php } ?>
                                <?php if(!empty($kamar_mandi)){ ?>
                                    <div class="mb-2"><strong>Kamar Mandi:</strong> <?php echo $kamar_mandi; ?></div>
                                <?php } ?>
                                <?php if(!empty($perabotan)){ ?>
                                    <div class="mb-2"><strong>Perabotan:</strong> <?php echo $perabotan; ?></div>
                                <?php } ?>
                            </div>
                            <div class="col-6 pl-1">
                                <?php if(!empty($kondisi_property)){ ?>
                                    <div class="mb-2"><strong>Kondisi Properti:</strong> <?php echo $kondisi_property; ?></div>
                                <?php } ?>
                                <?php if(!empty($garasi)){ ?>
                                    <div class="mb-2"><strong>Garasi:</strong> <?php echo $garasi; ?></div>
                                <?php } ?>
                                <?php if(!empty($jalur_telepon)){ ?>
                                    <div class="mb-2"><strong>Jalur Telepon:</strong> <?php echo $jalur_telepon; ?></div>
                                <?php } ?>
                                <?php if(!empty($sertifikat)){ ?>
                                    <div class="mb-2"><strong>Sertifikat:</strong> <?php echo $sertifikat; ?></div>
                                <?php } ?>
                                <?php if(!empty($daya_listrik)){ ?>
                                    <div class="mb-2"><strong>Daya Listrik:</strong> <?php echo $daya_listrik; ?></div>
                                <?php } ?>
                                <?php if(!empty($lokasi)){ ?>
                                    <div class="mb-2"><strong>Lokasi Lengkap:</strong> <?php echo $lokasi; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <hr>
                        <?php echo get_the_content();?>
                    </div>
                </div>
            </div>

</div>

</article><!-- #post-## -->
