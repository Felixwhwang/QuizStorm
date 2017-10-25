<div class="exc-panel exc-wp-skin">

    <?php if( isset( $title ) ) : ?>

        <div class="exc-panel-header">
            <h2>
                <?php if ( isset( $icon ) ) :?>
                    <?php echo $icon;?>
                <?php endif;?>

                <?php echo esc_html( $title ); ?>
            </h2>
        </div>

    <?php endif; ?>

    <?php $this->form->get_html( $_config, $_active_form ); ?>
    <?php $this->form->get_form_settings( $_name, array( '_active_form' => $_active_form ) );?>
</div>