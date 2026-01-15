<?php 
$content = get_the_content();
$trimmed_content = wp_trim_words($content,10);
?>

<div class="card h-100 w-100 text-start">
    <?php 
    $kondisi_property = get_post_meta(get_the_ID(), 'kondisi_property', true );
    if($kondisi_property){ ?>
        <div class="label-property"><?php echo $kondisi_property; ?></div>
    <?php } ?>
    
    <?php
    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
    if (!$thumbnail_url) {
        $thumbnail_url = get_stylesheet_directory_uri() . '/img/no-image.webp';
    }
    ?>
    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
        <div class="ratio ratio-4x3">
            <img class="card-img-top m-0 rounded-top img-fluid" 
                 src="<?php echo esc_url($thumbnail_url); ?>" 
                 alt="<?php the_title_attribute(); ?>">
        </div>
    </a>
    
    <div class="card-body">
        <h5 class="card-title fs-5">
            <a class="text-dark" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                <?php the_title(); ?>
            </a>
        </h5>
        <p class="card-text text-secondary"><?php echo $trimmed_content; ?></p>
        <div class="text-dark">Harga:</div>
        <h5 class="fs-5 text-info"><?php echo velocity_harga(); ?></h5>
    </div>
</div>
