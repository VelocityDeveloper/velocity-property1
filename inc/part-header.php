<nav id="main-navi" class="navbar navbar-expand-md d-block navbar-light pb-0 px-0 pt-0" aria-labelledby="main-nav-label">

    <h2 id="main-nav-label" class="screen-reader-text">
        <?php esc_html_e('Main Navigation', 'justg'); ?>
    </h2>

    <div class="velocity-menu">
        <div class="container">

        <div class="row align-items-center m-0 py-2">
        <div class="col-9 col-md-3 px-0">
            <?php if (has_custom_logo()) {
                echo '<a href="'.get_home_url().'">';
                    echo get_custom_logo();
                echo '</a>';
            } ?>
        </div>
        <div class="col-3 col-md-9 px-0">

            <div class="offcanvas offcanvas-start" tabindex="-1" id="navbarNavOffcanvas">

                <div class="offcanvas-header justify-content-end">
                    <button type="button" class="btn-close btn-close-dark text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div><!-- .offcancas-header -->

                <!-- The WordPress Menu goes here -->
                <?php
                wp_nav_menu(
                    array(
                        'theme_location'  => 'primary',
                        'container_class' => 'offcanvas-body',
                        'container_id'    => '',
                        'menu_class'      => 'navbar-nav navbar-light justify-content-end flex-md-wrap flex-grow-1',
                        'fallback_cb'     => '',
                        'menu_id'         => 'primary-menu',
                        'depth'           => 4,
                        'walker'          => new justg_WP_Bootstrap_Navwalker(),
                    )
                ); ?>

            </div><!-- .offcanvas -->

            <div class="menu-header d-md-none position-relative text-end" data-bs-theme="dark">
                <button class="navbar-toggler bg-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarNavOffcanvas" aria-controls="navbarNavOffcanvas" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation', 'justg'); ?>">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </div>
    </div>

</nav><!-- .site-navigation -->


<div class="text-center position-relative">
    <?php $home_header = velocitytheme_option('home_header', ''); ?>
    <?php if (is_front_page()){ ?>
        <?php if ($home_header){ ?>
            <div class="mb-md-5">
                <img class="d-block w-100" src="<?php echo $home_header; ?>">
            </div>
        <?php } ?>
    <?php } else { ?>
        <?php if(has_header_image()) {
            echo '<div class="velocity-page-header mb-5" style="background-image:url('.esc_url(get_header_image()).')"></div>';
        } ?>
        <div class="velocity-page-title">
            <div class="container">
                <?php echo velocity_title(); ?>
            </div>
        </div>
    <?php } ?>
</div>

