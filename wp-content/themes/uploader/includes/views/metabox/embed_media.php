<div class="exc-bootstrap">

	<div class="exc-embed-media form-horizontal">
		<div class="form-group">
			<h4><?php _e( 'Enter the URL and Embeding Code', 'exc-uploader-theme' );?></h4>
			<textarea id="exc-embed-code" class="form-control"></textarea>
			
			<span class="help-block">			
			  <strong><?php _e( 'Supported Hosts:', 'exc-uploader-theme' );?> </strong>
			  
			  <?php foreach($supported_hosts as $id=>$host):?>
			  <a target="_blank" href="<?php echo $host['url'];?>"><?php echo $host['name'];?></a><?php if( $id != ( count($supported_hosts) - 1) ) echo ', ';?>
			  <?php endforeach;?>
  			</span>
			
			<button class="btn btn-primary" id="exc-embed-btn"><i class="fa fa-gear"></i> <?php _e( 'Process', 'exc-uploader-theme' );?></button>
		</div>
	</div>
	
	<div id="exc-embed-body"></div>
</div>