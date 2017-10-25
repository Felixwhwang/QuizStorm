<?php defined('ABSPATH') or die('Restrcited Access!!');

$member_settings = get_option( 'mf_member_settings' );
$attachments = exc_kv( $member_settings, 'bg_attachments', array() );

$js_vars = array();
$urls = array();

// Take maximum 10 backgrounds
if ( ! empty( $attachments ) )
{
    $limit = ( count( $attachments ) > 10 ) ? 10 : count( $attachments );

    $limit = ( $limit ) ? $limit : 1;
    $data = ( array ) array_rand( $attachments, $limit );

    foreach ( $data as $key => $id )
    {
        if ( $url = wp_get_attachment_url( $attachments[ $id ] ) )
        {
            $js_vars[] = $url;
        }
    }
}?>

<script type="text/javascript">
    var exc_backgrounds = <?php echo json_encode( $js_vars ); ?>;
</script>

<script type="text/html" id="tmpl-exc-forgot-password">

    <form method="post" id="exc-forgot-password-form">
        <input type="text" name="email" class="form-control" placeholder="<?php esc_attr_e('Email Address', 'exc-uploader-theme');?>">

        <input type="hidden" name="security" value="<?php echo wp_create_nonce('exc-forgot-password');?>" />
        <input type="hidden" name="action" value="exc_forgot_password" />

        <button class="btn btn-primary btn-lg btn-block btn-reg" type="submit">
            <i class="fa fa-refresh"></i> <?php _e('Recover Password', 'exc-uploader-theme');?>
        </button>

        <p class="reg-subtitle">
            <?php printf( __('Go back to login page - %s', 'exc-uploader-theme'), '<a href="#login" class="click-here">' . __('Click here', 'exc-uploader-theme') . '</a>');?>
        </p>
    </form>
</script>

<?php if ( exc_theme_instance()->session->flashdata('allow_password_reset', true) ) :?>
<script type="text/html" id="tmpl-exc-reset-password">

    <form method="post" id="exc-reset-password-form">
        <input type="password" name="pwd" class="form-control" placeholder="<?php echo esc_attr__('Password', 'exc-uploader-theme');?>">
        <input type="password" name="confirm_pwd" class="form-control" placeholder="<?php echo esc_attr__('Confirm Password', 'exc-uploader-theme');?>">

        <input type="hidden" name="security" value="<?php echo wp_create_nonce('exc-reset-password');?>" />
        <input type="hidden" name="action" value="exc_reset_password" />
        <input type="hidden" name="redirect_to" value="<?php echo esc_url( site_url('wp-login.php') );?>" />
        <?php //$redirect_to = ( $rt = exc_theme_instance()->session->get_data( 'redirect_to', true ) ) ? esc_url( $rt ) : site_url(); ?>

        <button class="btn btn-primary btn-lg btn-block btn-reg" type="submit">
            <i class="fa fa-refresh"></i> <?php _e('Update Password', 'exc-uploader-theme');?>
        </button>

        <p class="reg-subtitle">
            <?php printf( __('Go back to login page - %s', 'exc-uploader-theme'), '<a href="#login" class="click-here">' . __('Click here', 'exc-uploader-theme') . '</a>');?>
        </p>
    </form>
</script>
<?php endif;?>

<script type="text/html" id="tmpl-exc-login">

    <?php if ( defined( 'WORDPRESS_SOCIAL_LOGIN_ABS_PATH' ) && get_option( 'wsl_settings_bouncer_authentication_enabled' ) != 2 ) :?>
        <?php do_action( 'wordpress_social_login' ); ?>
        <p class="reg-divider"><span><?php _e('OR', 'exc-uploader-theme');?></span></p>
    <?php endif;?>

    <?php $redirect_to = ( $rt = exc_theme_instance()->session->get_data( 'redirect_to', true ) ) ? esc_url( $rt ) : site_url(); ?>

    <form method="post" action="<?php echo esc_url( wp_login_url( $redirect_to ) );?>" id="exc-signin-form">
        <input type="text" placeholder="<?php echo esc_attr__('Email Address', 'exc-uploader-theme');?>" class="form-control" name="log">
        <input type="password" placeholder="<?php echo esc_attr__('Password', 'exc-uploader-theme');?>" class="form-control" name="pwd">
        <input type="hidden" name="security" value="<?php echo wp_create_nonce('exc-login');?>" />
        <input type="hidden" name="action" value="exc_login" />
        <input type="hidden" name="redirect_to" value="<?php echo esc_url( $redirect_to );?>" />

        <?php $btn_string = exc_kv( $member_settings, 'signin_btn', __('Sign In To Uploader', 'exc-uploader-theme') );?>
        <button type="submit" class="btn btn-primary btn-lg btn-block btn-reg"><i class="fa fa-sign-in"></i> <?php echo esc_html( $btn_string );?></button>

        <p class="reg-subtitle">
            <?php _e('Forgot your password?', 'exc-uploader-theme');?> <a class="click-here" href="#forgot_password"><?php _e('Click here', 'exc-uploader-theme');?></a>
            <?php _e('OR', 'exc-uploader-theme');?> <a href="#signup"><?php _e('Signup', 'exc-uploader-theme');?></a>
        </p>
    </form>
</script>

<script type="text/html" id="tmpl-exc-signup">

    <?php if ( defined( 'WORDPRESS_SOCIAL_LOGIN_ABS_PATH' ) && get_option( 'wsl_settings_bouncer_authentication_enabled' ) != 2 ) :?>
        <?php do_action( 'wordpress_social_login' ); ?>
        <p class="reg-divider"><span><?php _e('OR', 'exc-uploader-theme');?></span></p>
    <?php endif;?>

    <form id="exc-signup-form" method="post">
        <div class="name-field">
            <div class="input-field firstname-field">
                <input type="text" value="" placeholder="<?php echo esc_attr__('First name', 'exc-uploader-theme');?>" class="form-control" name="first_name">
            </div>
            <div class="input-field lastname-field">
                <input type="text" value="" placeholder="<?php echo esc_attr__('Last name', 'exc-uploader-theme');?>" class="form-control" name="last_name">
            </div>
        </div>

        <div class="input-field">
            <input type="text" value="" placeholder="<?php echo esc_attr__('Username', 'exc-uploader-theme');?>" class="form-control" name="user_login">
        </div>
        <div class="input-field">
            <input type="email" value="" placeholder="<?php echo esc_attr__('Email Address', 'exc-uploader-theme');?>" class="form-control" name="user_email">
        </div>
        <div class="input-field">
            <input type="password" value="" placeholder="<?php echo esc_attr__('Password', 'exc-uploader-theme');?>" class="form-control" name="user_pass">
        </div>

        <?php exc_theme_instance()->form->get_form_settings( 'members_signup', array( 'action' => 'exc_signup' ) );?>

        <button type="submit" class="btn btn-primary btn-lg btn-block btn-reg"><i class="fa fa-sign-out fa-rotate-270"></i><?php _e('Sign Up for new account', 'exc-uploader-theme');?></button>
        <p class="reg-subtitle">
            <?php printf( __('Already have an account? - %s', 'exc-uploader-theme'), '<a href="#login" class="click-here">' . __('Click here', 'exc-uploader-theme') . '</a>');?>
        </p>
    </form>

</script>