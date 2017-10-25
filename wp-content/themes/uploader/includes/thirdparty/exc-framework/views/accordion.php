<div class="exc-accordion">
	<div id="<?php echo $_prefix;?>" class="panel-group">
	
		<?php
		$class = '';
		
		foreach($_config as $k=>$v):?>
		
		<div class="panel">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a <?php echo $class;?> data-toggle="collapse" data-parent="#accordion" href="#accordion-<?php echo $k;?>">
						<?php echo exc_to_text($k);?><i class="fa fa-chevron-down pull-right"></i>
					</a>
				</h4>
			</div>
			<div id="accordion-<?php echo $k;?>" class="panel-collapse collapse<?php echo ($class) ? '' : ' in';?>">
				<div class="panel-body"><?php $this->form->get_html($_config, $k, $_prefix.'-'.$k);?></div>
			</div>
		</div>
	
		
		<?php
		$class = 'class="collapsed"';
		endforeach;?>
	</div>
</div>