<div class="exc-wrapper <?php mf_container_class();?> form-horizontal">
	<div class="row">
		<div class="exc-content col-sm-12">
			<?php $this->form->get_html($_config);?>
		</div>
	</div>
</div>

<?php
/** Dynamic JS and CSS is the file to reuse the same code again and again */
//$this->html->dynamic_js($type, array());?>