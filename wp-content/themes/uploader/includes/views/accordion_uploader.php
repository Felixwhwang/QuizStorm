<div class="advanced-field col-sm-12">
	<a class="btn-adv-settings" data-toggle="collapse" href="#advanced-fields"><i class="fa fa-plus"></i><?php esc_html_e('Advanced Settings', 'exc-uploader-theme');?></a>
	
	<div id="advanced-fields" class="collapse">
		<div class="row">
			<?php
			$class = '';
			
			foreach($_config as $k=>$v):?>

				<?php $this->form->get_html($_config, $k, $_prefix.'-'.$k);?>

			<?php

			endforeach;?>
		</div>
	</div>
</div>
