<?php $k = $_prefix;?>

<div class="exc-content-block" id="exc-block-<?php echo esc_attr( $k );?>">

    <?php if ( ! empty( $_settings[ $k ]['heading'] ) || ! empty( $_settings[$k]['description'] ) ):?>
    <div class="exc-content-header <?php echo ( empty( $_settings[ $k ]['heading'] ) ) ? 'no-heading' : '';?>">
        <?php if ( isset( $_settings[ $k ]['heading'] ) ) :?>
            <h4><?php echo $_settings[ $k ]['heading'];?></h4>
        <?php endif;?>

        <?php if ( isset( $_settings[$k]['description'] ) ) :?>
            <p><?php echo $_settings[$k]['description'];?></p>
        <?php endif;?>
    </div>
    <?php endif;?>

    <div class="exc-content-body">
        <?php if($is_form = exc_kv($_settings, array($k, 'show_controls'), true )) :?>
        <form role="form" class="form-horizontal exc-form" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
        <?php endif;?>

            <?php $this->form->get_html( $_config, '', $_prefix );?>

            <?php if( $is_form ): ?>
            <div class="form-submit-btn">
                <button class="btn btn-info exc-submit-btn" type="submit"><i class="fa fa-save"></i> <?php _e('Save Options', 'exc-framework');?></button>
            </div>
            <?php endif;?>

            <?php
            $form_args = array( '_active_form' => $_prefix );

            if ( $is_form && ! empty( $action ) )
            {
                $form_args['action'] = $action;
            }?>

            <?php $this->form->get_form_settings( $_name, $form_args );?>

        <?php if( $is_form ):?>
        </form>
        <?php endif;?>
    </div>
</div>