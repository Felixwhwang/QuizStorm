<?php foreach( $_menu_settings as $k => $v ) :

		$active_class = ( $_active_form == $k ) ? 'active' : '';?>

<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">
		
			<?php if ( isset( $v['menu_child'] ) ) :?>
			<a data-toggle="collapse" data-parent="#accordion" href="#<?php echo esc_attr( $v['menu_slug'] );?>" class="<?php echo esc_attr( $active_class );?>">
				<span class="<?php echo esc_attr( $v['menu_icon'] );?>"></span> <?php echo $v['menu_name'];?>
			</a>
			<?php
			else: ?>
			<a href="<?php echo esc_url( $v['menu_link'] );?>" class="<?php echo esc_attr( $active_class );?>">
				<span class="<?php echo esc_attr( $v['menu_icon'] );?>"></span> <?php echo $v['menu_name'];?>
			</a>
			<?php
			endif;?>
		</h4>
	</div>
	
	<?php if ( isset( $v['menu_child'] ) ) :?>
	<div id="<?php echo esc_attr( $v['menu_slug'] );?>" class="panel-collapse collapse">
		<div class="panel-body">
			<ul>
				<?php foreach ( $v['menu_child'] as $child_key => $child_value ) :?>
				<li>
					<a href="<?php echo esc_url( $child_value['menu_link'] );?>"><?php echo $child_value['menu_name'];?></a>
				</li>
				<?php endforeach;?>
			</ul>
		</div>
	</div>
	<?php endif;?>
</div>
<?php endforeach;?>