<?php if ( is_active_sidebar( 'home-logged-active-first' ) ): ?>
    <section class="sidebar sidebar-first sidebar-move-up tiny-space">
        <div class="grid-container">
            <div class="grid-x align-center">
                <?php dynamic_sidebar( 'home-logged-active-first' ) ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php if ( is_active_sidebar( 'home-logged-active-second' ) ): ?>
    <section class="sidebar sidebar-second inner-space">
        <div class="grid-container">
            <div class="grid-x">
                <?php dynamic_sidebar( 'home-logged-active-second' ) ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php if ( is_active_sidebar( 'home-logged-active-third' ) ): ?>
    <section class="sidebar sidebar-third inner-space">
        <div class="grid-container">
            <div class="grid-x">
                <?php dynamic_sidebar( 'home-logged-active-third' ) ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php if ( is_active_sidebar( 'home-logged-active-fourth' ) ): ?>
    <section class="sidebar sidebar-fourth background-lighter-gray inner-space">
        <div class="grid-container">
            <?php dynamic_sidebar( 'home-logged-active-fourth' ) ?>
        </div>
    </section>
<?php endif; ?>
<?php if ( is_active_sidebar( 'home-logged-active-fifth' ) ): ?>
    <section class="sidebar sidebar-fifth background-gray-dark inner-space">
        <div class="grid-container">
            <div class="grid-x">
                <?php dynamic_sidebar( 'home-logged-active-fifth' ) ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php if ( is_active_sidebar( 'home-logged-active-sixth' ) ): ?>
    <section class="sidebar sidebar-sixth inner-space">
        <div class="grid-container">
            <div class="grid-x widget blog-entries">
                <?php dynamic_sidebar( 'home-logged-active-sixth' ) ?>
            </div>
        </div>
    </section>
<?php endif; ?>