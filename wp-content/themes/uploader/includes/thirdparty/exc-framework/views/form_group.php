<?php defined('ABSPATH') OR die('restricted access');

$key = key( $_config );

$label = exc_kv( $_settings, array( $key, 'label' ) );
$columns = exc_kv( $_settings, array( $key, 'columns' ), 6 ); ?>

<?php if ( $label ) :?>

<div class="form-group">
	<label class="col-sm-2" for="<?php echo esc_attr( $label );?>"><?php echo $label;?></label>
		<div class="col-sm-<?php echo $columns;?>">

<?php endif;?>

		<div class="input-group">
			<?php foreach ( $_config as $k => $v ) :

			$this->form->get_html( $_config, $k, $_prefix . '-' . $k );

			endforeach;?>
		</div>

<?php if ( $label ) :?>
	</div>
</div>
<?php endif;?>