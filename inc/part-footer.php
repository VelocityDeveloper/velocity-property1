<footer class="site-footer text-center bg-dark text-white" id="colophon">
    <div class="site-info container py-5">
        <div class="row text-left text-start velocity-widget">
            <?php if ( is_active_sidebar( 'footer-1' ) ) { ?>
                <div class="col-md">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
            <?php } ?>
            <?php if ( is_active_sidebar( 'footer-2' ) ) { ?>
                <div class="col-md">
                    <?php dynamic_sidebar('footer-2'); ?>
                </div>
            <?php } ?>
            <?php if ( is_active_sidebar( 'footer-3' ) ) { ?>
                <div class="col-md">
                    <?php dynamic_sidebar('footer-3'); ?>
                </div>
            <?php } ?>
        </div>
    </div>
    
    <div class="py-4 bg-black">
        <small class="text-white">
            Copyright © <?php echo date("Y"); ?> <?php echo get_bloginfo('name'); ?>. All Rights Reserved.
        </small>
        <br>
        <small class="opacity-25" style="font-size: .7rem;">
            Design by <a class="text-white" href="https://velocitydeveloper.com" target="_blank" rel="noopener noreferrer"> Velocity Developer </a>
        </small>
    </div>
</footer>