<div class="exc-dynamic-row-container">
    <?php
    foreach ( $_config as $key => $v ):

        // @TODO: remove dynamic_key and change the tmpl_id to group_name
        $dynamic_key = $_prefix . '-' . $key;
        $tmpl_id = exc_kv( $_settings, array( $key, 'tmpl_id' ), $dynamic_key );

        $template = exc_kv( $_settings, array( $key, 'template'), 'dynamic_row_content' );

        $label = exc_kv( $_settings, array( $key, 'label' ) );
        $columns = exc_kv( $_settings, array( $key, 'columns' ), 10 );

        $toolbar = array_flip( ( array ) exc_kv( $_settings, array( $key, 'toolbar' ) ) );

        $btn_text = exc_kv( $_settings, array( $key, 'btn_text' ), _x('Add row', 'extracoding dynamic row button text', 'exc-framework' ) );

        $this->html->js_template(
                $tmpl_id . '_tmpl',
                $this->load_view(
                    $template,
                    array(
                        'dynamic_key'  => &$dynamic_key,
                        'tmpl_id'      => &$tmpl_id,
                        'label'        => &$label,
                        'columns'      => &$columns,
                        'toolbar'      => &$toolbar,
                        'btn_text'     => &$btn_text,
                        '_settings'    => &$_settings,
                        '_config'      => &$_config,
                        'key'          => &$key,
                        'v'            => &$v
                    ), TRUE
                )
            );?>

        <?php if ( $label ) :?>

            <div class="form-group">
                <label class="col-sm-2" for="<?php echo esc_attr( $label );?>"><?php echo $label;?></label>
                <div class="col-sm-<?php echo $columns;?>">

        <?php endif;?>

            <?php
            $default_title = exc_kv( $_settings, 'default_title', _x('[TITLE IS MISSING]', 'extracoding dynamic row missing title', 'exc-framework' ) );
            $name = exc_kv( $_settings, 'name', exc_to_text( $key ) );?>

            <div id="<?php echo $tmpl_id;?>" class="panel-group exc-form-rows" data-action="exc-form-rows" data-default-title="<?php echo esc_attr( $default_title );?>">
                <div class="exc-add-row exc-row-controls" data-controls="<?php echo $tmpl_id;?>">
                    <b> <?php
                        printf(
                            __( 'The %s Section is Empty, %s', 'exc-framework' ),
                            $name,
                            '<a data-opt-name="add_row" href="#">' . __( 'Click Here to Add New', 'exc-framework' ) . '</a>'
                        );?>
                    </b>
                </div>
            </div>

            <div class="exc-row-controls hide-on-load" data-controls="<?php echo $tmpl_id;?>" data-toolbar="<?php echo $tmpl_id;?>" data-autostart="1">
                <a href="#" class="btn btn-default" data-opt-name="add_row">
                    <i class="fa fa-plus"></i>
                    <?php _e('Add New', 'exc-framework');?>
                </a>
            </div>

        <?php if ( $label ) :?>
            </div>
        </div>
        <?php endif;?>

    <?php endforeach;?>
</div>