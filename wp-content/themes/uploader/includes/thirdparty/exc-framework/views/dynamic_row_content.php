<?php defined('ABSPATH') OR die('restricted access');?>

<div class="panel exc-dynamic-row" data-row-id="{{{ i }}}">

    <div class="panel-heading">
        <ul class="exc-panel-heading-list pull-left">
            <li><span class="exc-count badge">{{{ count }}}</span></li>
            <li>
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#<?php echo $_prefix;?>" href="#<?php echo $_prefix;?>-{{{ i }}}" class="exc_row_title">{{{ title }}}</a>
                </h4>
            </li>
        </ul>

        <ul class="exc-panel-heading-list pull-right exc-row-controls" data-controls="<?php echo $tmpl_id;?>">

            <?php if ( isset( $toolbar['delete'] ) ) :?>
                <# if ( 'undefined' !== settings['delete'] && settings['delete'] !== false ) { #>
                <li class="exc-delete">
                    <a href="#" class="fa fa-times" data-opt-name="delete_row"></a>
                </li>
                <# } #>
            <?php endif;?>

            <?php if ( isset( $toolbar['settings'] ) ) :?>
                <# if ( 'undefined' !== settings['move'] && settings['move'] !== false ) { #>
                <li class="dropdown">
                    <span class="exc-settings" data-toggle="dropdown"><i class="fa fa-gear"></i></span>
                    <ul class="dropdown-menu">

                        <li><a href="#" data-opt-name="add_row_above"><?php _e('Add row above', 'exc-framework');?></a></li>
                        <li><a href="#" data-opt-name="add_row_below"><?php _e('Add row below', 'exc-framework');?></a></li>
                        <li class="divider"></li>
                        <li><a href="#" data-opt-name="move_to_top"><?php _e('Move to top', 'exc-framework');?></a></li>
                        <li><a href="#" data-opt-name="move_to_bottom"><?php _e('Move to bottom', 'exc-framework');?></a></li>

                        <?php if ( isset( $toolbar['delete'] ) ) :?>
                            <# if ( 'undefined' !== settings['delete'] && settings['delete'] !== false ) { #>
                            <li class="divider"></li>
                            <li><a href="#" data-opt-name="delete_row"><?php _e('Delete row', 'exc-framework');?></a></li>
                            <# } #>
                        <?php endif;?>
                    </ul>
                </li>
                <# } #>
            <?php endif;?>

            <?php if ( isset( $toolbar['move'] ) ) :?>
                <# if ( 'undefined' !== settings['move'] && settings['move'] !== false ) { #>
                <li class="exc-move">
                    <a href="#" class="fa fa-arrows"></a>
                </li>
                <# } #>
            <?php endif;?>

            <?php if ( isset( $toolbar['status'] ) ) :?>
            <# if ( 'undefined' !== settings['status'] && settings['status'] !== false ) { #>
            <li class="exc-status">
                <a href="#" class="fa fa-eye" data-opt-name="toggle_status" data-toggle-class="fa fa-eye-slash"></a>
            </li>
            <# } #>
            <?php endif;?>
        </ul>

        <div class="clearfix"></div>
    </div>

    <div id="<?php echo esc_attr( $_prefix );?>-{{{ i }}}" class="panel-collapse collapse">
        <div class="panel-body">
            <?php $this->form->get_html( $_config, $key, $dynamic_key );?>
        </div>
    </div>
</div>