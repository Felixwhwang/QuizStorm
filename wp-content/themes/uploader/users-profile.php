<?php defined( 'ABSPATH' ) or die( 'restricted access.' );

// User data
// @TODO: add condition to check if user has permission to edit profile
@extract( exc_get_user_data( ) );

if ( ! isset( $user_data ) || empty( $user_data ) )
{
    get_template_part( '404' );
    exit;
}

// Load Layout Structure
$layout = exc_layout_structure( 'users' );

// Load Header
get_header( $layout['header'] ); ?>

<main role="main">
    <div class="<?php mf_container_class();?> user-dashboard">
        <div class="row">

            <!-- Sidebar -->
            <div class="col-sm-3 sidebar-dashboard">
                <?php exc_load_template('sidebar-dashboard', array('user_id' => $user_data->ID, 'user_info' => $user_data, 'user_meta' => $user_meta ) ); ?>
                <?php //get_sidebar('dashboard');?>
            </div>

            <div class="dashboard col-sm-9">

                <!-- Dashboard Navigation -->
                <?php get_template_part('modules/dashboard-menu');?>

                <div class="dashboard-content">
                    <table class="table table-condensed" id="exc-editable" data-security="<?php echo wp_create_nonce('exc-edit-profile');?>" data-action="exc_edit_profile">
                        <thead>
                            <tr>
                                <th><?php _e('Personal Details', 'exc-uploader-theme');?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="35%"><?php _e('First Name', 'exc-uploader-theme');?></td>
                                <td width="65%">
                                    <a id="user_first_name" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>" data-title="<?php esc_attr_e('Enter firtname', 'exc-uploader-theme');?>" data-placement="right" href="#" class="editable editable-click">
                                        <?php echo exc_kv( $user_meta, 'first_name' );?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e('Last Name', 'exc-uploader-theme');?></td>
                                <td>
                                    <a id="user_last_name" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>" href="#" class="editable editable-click editable-empty">
                                        <?php echo exc_kv( $user_meta, 'last_name' );?>
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td><?php _e('DOB', 'exc-uploader-theme');?></td>
                                <td>
                                    <a href="#" id="user_dob" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>">
                                        <?php echo exc_kv($user_meta, 'user_dob');?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e('Gender', 'exc-uploader-theme');?></td>
                                <td>
                                    <a href="#" id="user_gender" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>">
                                        <?php echo exc_kv($user_meta, 'user_gender');?>
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td><?php _e('About me', 'exc-uploader-theme');?></td>
                                <td>
                                    <a href="#" id="user_about_us" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>" class="exc-editable"><?php echo esc_textarea( exc_kv($user_meta, 'description') );?></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th width="35%"><?php _e('Change Password', 'exc-uploader-theme');?></th>
                                <th width="65%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php _e('Password', 'exc-uploader-theme');?></td>
                                <td>
                                    <a href="#" id="user_password" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>"><?php echo str_repeat('x', 6 ); ?></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th><?php _e('Contact Details', 'exc-uploader-theme');?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="35%"><?php _e('Email Address', 'exc-uploader-theme');?></td>
                                <td width="65%">
                                    <a href="#" id="user_email" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>">
                                        <?php echo $user_data->user_email;?>
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td><?php _e('Notification Email Address', 'exc-uploader-theme');?></td>
                                <td>
                                    <a href="#" id="user_notify_email" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>">
                                        <?php echo exc_kv($user_meta, 'notification_email');?>
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td><?php _e('Location', 'exc-uploader-theme');?></td>
                                <td>
                                    <a href="#" id="user_address" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>"><?php echo exc_kv( $user_meta, 'address' );?></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th width="35%"><?php _e('Public Contact Details', 'exc-uploader-theme');?></th>
                                <th width="65%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="35%"><?php _e('Email Address', 'exc-uploader-theme');?></td>
                                <td width="65%">
                                    <a href="#" id="user_public_email" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>">
                                        <?php echo exc_kv($user_meta, 'public_email'); ?>
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td width="35%"><?php _e('Skype ID', 'exc-uploader-theme');?></td>
                                <td width="65%">
                                    <a href="#" id="user_skype_id" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>">
                                        <?php echo exc_kv($user_meta, 'skype_id'); ?>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th width="35%"><?php _e('Social Network Links', 'exc-uploader-theme');?></th>
                                <th width="65%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="35%"><i class="fa fa-facebook"></i> <?php _e('Facebook', 'exc-uploader-theme');?></td>
                                <td width="65%">
                                    <a href="#" id="user_facebook" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>">
                                        <?php echo esc_url( exc_kv( $user_meta, 'facebook' ) ); ?>
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td width="35%"><i class="fa fa-twitter"></i> <?php _e('Twitter', 'exc-uploader-theme');?></td>
                                <td width="65%">
                                    <a href="#" id="user_twitter" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>">
                                        <?php echo esc_url( exc_kv( $user_meta, 'twitter' ) ); ?>
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td width="35%"><i class="fa fa-google-plus"></i> <?php _e('Google+', 'exc-uploader-theme');?></td>
                                <td width="65%">
                                    <a href="#" id="user_google-plus" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>">
                                        <?php echo esc_url( exc_kv( $user_meta, 'google-plus' ) ); ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td width="35%"><i class="fa fa-instagram"></i> <?php _e('Instagram', 'exc-uploader-theme');?></td>
                                <td width="65%">
                                    <a href="#" id="user_instagram" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>">
                                        <?php echo esc_url( exc_kv( $user_meta, 'instagram' ) ); ?>
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td width="35%"><i class="fa fa-youtube"></i> <?php _e('Youtube', 'exc-uploader-theme');?></td>
                                <td width="65%">
                                    <a href="#" id="user_youtube" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>">
                                        <?php echo esc_url( exc_kv( $user_meta, 'youtube' ) ); ?>
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td width="35%"><i class="fa fa-vimeo-square"></i> <?php _e('Vimeo', 'exc-uploader-theme');?></td>
                                <td width="65%">
                                    <a href="#" id="user_vimeo" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>">
                                        <?php echo esc_url( exc_kv( $user_meta, 'vimeo' ) ); ?>
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td width="35%"><i class="fa fa-soundcloud"></i> <?php _e('Soundcloud', 'exc-uploader-theme');?></td>
                                <td width="65%">
                                    <a href="#" id="user_soundcloud" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>">
                                        <?php echo esc_url( exc_kv( $user_meta, 'soundcloud' ) ); ?>
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td width="35%"><i class="fa fa-flickr"></i> <?php _e('Flickr', 'exc-uploader-theme');?></td>
                                <td width="65%">
                                    <a href="#" id="user_flickr" data-emptytext="<?php esc_attr_e("Empty", "exc-uploader-theme");?>">
                                        <?php echo esc_url( exc_kv( $user_meta, 'flickr' ) ); ?>
                                    </a>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>
        </div><!--/row-->

    </div><!--/.container-->
</main>

<?php get_footer();?>