<article <?php post_class('velocity-post'); ?> id="post-<?php the_ID(); ?>">

    <div class="mb-3 pb-3">
        <?php if(has_post_thumbnail($post->ID)){ ?>
            <div class="w-100">
                <a class="text-white text-uppercase" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                    <?php echo get_the_post_thumbnail( $post->ID,'full',array('class'=>'w-100')); ?>
                </a>
            </div>
        <?php } ?>
        <div class="p-4 text-secondary">
        <div class="archive-header">
            <h6 class="mb-2"><a class="text-white text-uppercase" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h6>
            <div class="text-white"><?php echo get_the_date(); ?></div>
        </div>
        <div class="mb-3"><?php $content = get_the_content();
        $trimmed_content = wp_trim_words($content,35);
        echo $trimmed_content; ?></div>
        <a class="rounded-0 px-4 py-2 btn btn-sm bg-theme" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">Read More</a>
        </div>
    </div>

</article>