<?php defined( 'ABSPATH' ) or die( 'restricted access.' );

$_exc_uploader 		= eXc_theme_instance();
$footer_settings 	= get_option('mf_footer_settings');
$footer_class 		= ( exc_kv( $footer_settings, 'is_sticky' ) == 'on') ? 'footer-fixed' : ''; ?>

<?php if ( $footer_class ) :?>
<div class="footer-space"></div>
<?php endif; ?>

<footer class="footer <?php echo esc_attr( $footer_class ); ?>">
	<div class="<?php mf_container_class();?>">
		
		<!-- Footer Menu -->
		<?php
			wp_nav_menu(
				array(
					'menu_class' 	=> 'footer-menu clearfix',
					'depth'			=> 1,
					'container'		=> 'ul',
					'theme_location'=> 'footer-menu'
				)
			);
		?>

		<div class="footer-menu-right">

			<p class="copy-right"><?php exc_kv( (array) $footer_settings, 'copyright',
										__('Copyright ' . date('Y') . ' All rights reserved - Designed By Themebazaar', 'exc-uploader-theme'), true);?></p>

		</div>
	</div>
</footer>

</div>

<div id="exc-mf-frame"></div>

<?php get_template_part( 'modules/templates/dialogs' );?>
<?php wp_footer();?>
</body>
</html>