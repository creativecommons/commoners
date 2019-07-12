<?php if ( is_active_sidebar( 'home-logged-inactive-first' ) ): ?>
    <section class="sidebar sidebar-first sidebar-move-up tiny-space">
        <div class="grid-container">
            <div class="grid-x align-center">
                <?php dynamic_sidebar( 'home-logged-inactive-first' ) ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php if ( is_active_sidebar( 'home-logged-inactive-second' ) ): ?>
    <section class="sidebar sidebar-second inner-space">
        <div class="grid-container">
            <div class="grid-x">
                <?php dynamic_sidebar( 'home-logged-inactive-second' ) ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php if ( is_active_sidebar( 'home-logged-inactive-third' ) ): ?>
    <section class="sidebar sidebar-third inner-space">
        <div class="grid-container">
            <div class="grid-x">
                <?php dynamic_sidebar( 'home-logged-inactive-third' ) ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php if ( is_active_sidebar( 'home-logged-inactive-fourth' ) ): ?>
    <section class="sidebar sidebar-fourth background-lighter-gray inner-space">
        <div class="grid-container">
            <div class="grid-x">
                <?php dynamic_sidebar( 'home-logged-inactive-fourth' ) ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php if ( is_active_sidebar( 'home-logged-inactive-fifth' ) ): ?>
    <section class="sidebar sidebar-fifth background-gray-dark inner-space">
        <div class="grid-container">
            <div class="grid-x">
                <?php dynamic_sidebar( 'home-logged-inactive-fifth' ) ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php if ( is_active_sidebar( 'home-logged-inactive-sixth' ) ): ?>
    <section class="sidebar sidebar-sixth inner-space">
        <div class="grid-container">
            <div class="grid-x widget blog-entries">
                <?php dynamic_sidebar( 'home-logged-inactive-sixth' ) ?>
            </div>
        </div>
    </section>
<?php endif; ?>