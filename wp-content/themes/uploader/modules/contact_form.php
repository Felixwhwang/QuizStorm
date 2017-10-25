<form class="form-horizontal clearfix" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] );?>" method="post">

    <?php $this->form->display_messages( $config['_name'], 'success_msgs' );?>

    <?php
    $this->form->load_settings('widgets_contact')->get_html( $config['_config'] );?>
    <?php $this->form->get_form_settings('widgets_contact');?>

    <div class="form-group">
        <div class="col-sm-12">
            <button class="btn btn-primary btn-lg" type="submit">
                <i class="fa fa-paper-plane-o"></i><?php echo _x('Send Message', 'extracoding uploader contact form button', 'exc-uploader-theme');?>
            </button>
        </div>
    </div>
</form>