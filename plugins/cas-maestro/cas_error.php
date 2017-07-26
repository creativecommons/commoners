<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header(); ?>

<!-- _Content_Container_ -->
	<div id="primary" class="site-content">
		<div id="content" role="main">
				<header class="entry-header">
					<h1 class="entry-title">
						<?php echo __("Ooops, there was an error.",'CAS_Maestro')?>
					</h1>
				</header>
	           
				<div class="entry-content">
					<?php echo sprintf(__("The username %s isn't allowed to register in this website. If you think this is a mistake, please contact the <a href=\"mailto:%s\">website administrator</a>."),$username,get_option('admin_email'));?>
					<p><a href="/wp-login.php?action=logout">Log out</a> of CAS.</p>

				</div>
			</article>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>