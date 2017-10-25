<div class="wrap">

    <?php if ( isset( $title ) ) :?>
        <h1>
            <?php if ( isset( $icon ) ) :?>
                <?php echo $icon;?>
            <?php endif;?>

            <?php echo esc_html( $title ); ?>
        </h1>

    <?php endif; ?>

    <?php $this->load_view( 'pages/tabs' );?>

    <div class="exc-panel exc-wp-skin">
        <?php $this->form->get_html( $_config, $_active_form ); ?>
        <?php $this->form->get_form_settings( $_name, array( '_active_form' => $_active_form ) );?>
    </div>
</div>