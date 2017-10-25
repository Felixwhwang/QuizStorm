<form method="post" action="">

<?php echo $this->validation->error('form_error');?>
<p><?php _e('In order to follow this user please enter following details.', 'exc-uploader-theme');?></p>

<input type="text" name="follower_name" class="form-control" placeholder="<?php _e('Your Name', 'exc-uploader-theme');?>" value="<?php echo $this->validation->set_value('follower_name');?>" required />

<?php echo $this->validation->error('follower_name');?>

<input type="email" name="follower_email" class="form-control" placeholder="<?php _e('Email Address', 'exc-uploader-theme');?>" value="<?php echo $this->validation->set_value('follower_email');?>" required />

<?php echo $this->validation->error('follower_email');?>

<button class="btn btn-blue">
	<i></i>
	<?php _e('Subscribe', 'exc-uploader-theme');?>
</button>
</form>