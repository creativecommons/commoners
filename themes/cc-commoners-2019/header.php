<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php wp_title('|') ?></title>
	<?php wp_head(); ?>
</head>
<?php 
	//obtener los settings del theme (settings.php)
	global $_set;
	$settings = $_set->settings;
 ?>
<body <?php body_class(); ?>>
<!-- MENU MOBILE -->
<header class="mobile-header show-for-small-only">
        <div class="grid-container">
            <div class="grid-x align-justify">
                <div class="cell small-5">
                    <a href="<?php echo site_url(); ?>" class="logo"><h1>CC Network</h1></a>
                </div>
                <div class="cell small-3 mobile-buttons">
                    <a href="#" class="open-mobile-menu"><span class="dashicons dashicons-menu"></span></a>
                </div>
            </div>
        </div>
    </header>
    <div class="menu-mobile-container hide">
        <a class="close" href="#">
            <button class="close-button" aria-label="Close alert" type="button" data-close>
                <span aria-hidden="true">&times;</span>
            </button>
        </a>
        <nav class="mobile-navigation">
        <?php 
            $args = array(
                'theme_location' => 'main-menu-mobile',
                'container' => '',
                'depth' => 2,
                'items_wrap' => '<ul id = "%1$s" class = "menu vertical %2$s">%3$s</ul>'
            );

            wp_nav_menu($args);
            ?>
        </nav>
    </div>
<header class="main-header">
    <?php 
    if ( is_user_logged_in() ) {
        $current_user = get_user_by( 'ID', get_current_user_id());
        $member_or_applicant = ( bp_commoners::current_user_is_accepted() )? 'member' : 'applicant';
        $individual_or_institutional = (ccgn_member_is_individual( get_current_user_id() )) ? 'Individual' : 'Institutional';
        $user_type = ' ( '.$individual_or_institutional.' '.$member_or_applicant.' ) ';
        echo '<div class="status-bar">';
            echo '<div class="grid-container">';
                echo '<div class="grid-x align-justify">';
                    echo '<div class="cell large-4 align-self-middle">';
                        echo '<i class="ion-chevron-right"></i> ';
                        echo "Logged in as: $current_user->display_name $user_type";
                    echo '</div>';
                    echo '<div class="cell large-2 align-self-middle">';
                        echo '<nav class="status-menu">';
                            echo '<ul class="menu align-right">';
                                echo '<li><a href="'. bp_loggedin_user_domain() . 'profile/edit/">Edit profile</a></li>';
                                echo '<li><a href="'.wp_logout_url().'"> <i class="ion-log-out"></i> Log out</a></li>';
                            echo '</ul>';
                        echo '</nav>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }
    ?>
    <div class="grid-container gradient-yellow">
        <div class="grid-x grid-padding-x navigation hide-for-small-only">
            <div class="cell large-3 columns logo">
                <a href="<?php bloginfo('url') ?>"><h1 class="site-title"><?php bloginfo('name') ?></h1><span class="tagline">Global Network</span></a>
            </div>
            <div class="cell large-9 columns nav">
                <nav class="aux-navigation">
                        <?php 
                        $args = array(
                            'theme_location' => 'auxiliar',
                            'container' => '',
                            'fallback_cb' => false,
                            'items_wrap' => '<ul id = "%1$s" class = "menu align-right %2$s">%3$s</ul>'
                            );

                        wp_nav_menu( $args );
                        ?>
                </nav>
                <nav class="main-navigation">
                        <?php 
                        $args = array(
                            'theme_location' => 'main-menu',
                            'container' => '',
                            'fallback_cb' => false,
                            'items_wrap' => '<ul id = "%1$s" class = "menu %2$s">%3$s</ul>'
                            );

                        wp_nav_menu( $args );
                        ?>
                </nav>
            </div>
        </div>
    </div>
</header>