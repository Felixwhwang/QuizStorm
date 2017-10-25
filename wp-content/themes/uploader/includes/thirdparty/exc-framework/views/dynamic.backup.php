<?php
foreach ( $_config as $key => $v ):

	// @TODO: remove dynamic_key and change the tmpl_id to group_name
	$dynamic_key = $_prefix . '-' . $key;
	$tmpl_id = exc_kv( $_settings, array( $key, 'tmpl_id' ), $dynamic_key );

	$label = exc_kv( $_settings, array( $key, 'label' ) );
	$columns = exc_kv( $_settings, array( $key, 'columns' ), 10 );

	$toolbar = array_flip( ( array ) exc_kv( $_settings, array( $key, 'toolbar' ) ) );

	$btn_text = exc_kv( $_settings, array( $key, 'btn_text' ), _x('Add row', 'extracoding dynamic row button text', 'exc-framework' ) );?>

	<script type="text/html" id="<?php echo esc_attr( $tmpl_id );?>_tmpl">
		<div class="panel exc-dynamic-row" data-row-id="{{{ i }}}">

			<div class="panel-heading">
				<ul class="exc-panel-heading-list pull-left">
					<li><span class="exc-count badge">{{{ count }}}</span></li>
					<li>
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#<?php echo $_prefix;?>" href="#<?php echo $_prefix;?>-{{{ i }}}" class="exc_row_title">{{{ title }}}</a>
						</h4>
					</li>
				</ul>
				
				<ul class="exc-panel-heading-list pull-right exc-row-controls">
					
					<?php if ( isset( $toolbar['delete'] ) ) :?>
						<# if ( 'undefined' !== settings['delete'] && settings['delete'] !== false ) { #>
						<li class="exc-delete">
							<a href="#" class="fa fa-times" data-opt-name="delete_row"></a>
						</li>
						<# } #>
					<?php endif;?>

					<?php if ( isset( $toolbar['settings'] ) ) :?>
						<# if ( 'undefined' !== settings['move'] && settings['move'] !== false ) { #>
						<li class="dropdown">
							<span class="exc-settings" data-toggle="dropdown"><i class="fa fa-gear"></i></span>
							<ul class="dropdown-menu">

								<li><a href="#" data-opt-name="add_row_above"><?php _e('Add row above', 'exc-framework');?></a></li>
								<li><a href="#" data-opt-name="add_row_below"><?php _e('Add row below', 'exc-framework');?></a></li>
								<li class="divider"></li>
								<li><a href="#" data-opt-name="move_to_top"><?php _e('Move to top', 'exc-framework');?></a></li>
								<li><a href="#" data-opt-name="move_to_bottom"><?php _e('Move to bottom', 'exc-framework');?></a></li>

								<?php if ( isset( $toolbar['delete'] ) ) :?>
									<# if ( 'undefined' !== settings['delete'] && settings['delete'] !== false ) { #>
									<li class="divider"></li>
									<li><a href="#" data-opt-name="delete_row"><?php _e('Delete row', 'exc-framework');?></a></li>
									<# } #>
								<?php endif;?>
							</ul>
						</li>
						<# } #>
					<?php endif;?>

					<?php if ( isset( $toolbar['move'] ) ) :?>
						<# if ( 'undefined' !== settings['move'] && settings['move'] !== false ) { #>
						<li class="exc-move">
							<a href="#" class="fa fa-arrows"></a>
						</li>
						<# } #>
					<?php endif;?>

					<?php if ( isset( $toolbar['status'] ) ) :?>
					<# if ( 'undefined' !== settings['status'] && settings['status'] !== false ) { #>
					<li class="exc-status">
						<a href="#" class="fa fa-eye" data-opt-name="toggle_status" data-toggle-class="fa fa-eye-slash"></a>
					</li>
					<# } #>
					<?php endif;?>
				</ul>
				
				<div class="clearfix"></div>
			</div>
			
			<div id="<?php echo esc_attr( $_prefix );?>-{{{ i }}}" class="panel-collapse collapse">
				<div class="panel-body">
					<?php $this->form->get_html( $_config, $key, $dynamic_key );?>
				</div>
			</div>
		</div>
	</script>

	<?php if ( $label ) :?>

	<div class="form-group">
		<label class="col-sm-2" for="<?php echo esc_attr( $label );?>"><?php echo $label;?></label>
			<div class="col-sm-<?php echo $columns;?>">

	<?php endif;?>

		<?php
		$default_title = exc_kv( $_settings, 'default_title', _x('[TITLE IS MISSING]', 'extracoding dynamic row missing title', 'exc-framework' ) );
		$name = exc_kv( $_settings, 'name', exc_to_text( $key ) );?>

		<div id="<?php echo $tmpl_id;?>" class="panel-group exc-form-rows" data-name="<?php echo esc_attr( $name );?>" data-default-title="<?php echo esc_attr( $default_title );?>">
			<span class="exc-row-controls exc-add-btn"><a href="#" class="btn btn-default" data-opt-name="add_row">
				<i class="fa fa-plus"></i>
				<?php echo esc_html( $btn_text );?></a>
			</span>
		</div>

	<?php if ( $label ) :?>
		</div>
	</div>
	<?php endif;?>

<?php endforeach;?>