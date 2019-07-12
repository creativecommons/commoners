<?php if ( is_active_sidebar( 'home-not-logged-first' ) ): ?>
    <section class="sidebar sidebar-first sidebar-move-up">
        <div class="grid-container">
            <div class="grid-x grid-padding-x">
                <?php dynamic_sidebar( 'home-not-logged-first' ) ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php if ( is_active_sidebar( 'home-not-logged-second' ) ): ?>
    <section class="sidebar sidebar-second inner-space">
        <div class="grid-container">
            <div class="grid-x">
                <?php dynamic_sidebar( 'home-not-logged-second' ) ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php if ( is_active_sidebar( 'home-not-logged-third' ) ): ?>
    <section class="sidebar sidebar-third">
        <div class="grid-container">
            <div class="grid-x">
                <?php dynamic_sidebar( 'home-not-logged-third' ) ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php if ( is_active_sidebar( 'home-not-logged-fourth' ) ): ?>
    <section class="sidebar sidebar-fourth background-lighter-gray inner-space">
        <div class="grid-container">
            <div class="grid-x">
                <?php dynamic_sidebar( 'home-not-logged-fourth' ) ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php if ( is_active_sidebar( 'home-not-logged-fifth' ) ): ?>
    <section class="sidebar sidebar-fifth background-gray-dark inner-space">
        <div class="grid-container">
            <div class="grid-x">
                <?php dynamic_sidebar( 'home-not-logged-fifth' ) ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php if ( is_active_sidebar( 'home-not-logged-sixth' ) ): ?>
    <section class="sidebar sidebar-sixth inner-space">
        <div class="grid-container">
            <div class="grid-x widget blog-entries">
                <?php dynamic_sidebar( 'home-not-logged-sixth' ) ?>
            </div>
        </div>
    </section>
<?php endif; ?>