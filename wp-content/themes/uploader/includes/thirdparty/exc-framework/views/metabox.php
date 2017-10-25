<div class="exc-panel">	
	<div class="form-horizontal">

		<?php

			$settings = array();

			if ( ! empty( $_config['_settings'] ) )
			{
				$settings = $_config['_settings'];
				unset( $_config['_settings'] );
			}

			foreach ( $_config as $_config_key => $_config_block ) : ?>
				<?php
					$this->load_view(
						'options_block',
						array(
							'_config'		=> $_config_block,
							'_settings'		=> $settings,
							'_prefix'		=> $_config_key,
							'parent_key'	=> ''
						)
					);?>
			<?php endforeach;?>
		<?php //$this->form->get_html( $_config, $_active_form );?>
	</div>
</div>