<?php foreach( $_config as $key => $v ):

$dynamic_key = $_prefix . '-' . $key;

$tmpl_id = exc_kv( $_settings, array( $key, 'tmpl_id' ), $dynamic_key );

$label = exc_kv( $_settings, array( $key, 'label' ) );
$columns = exc_kv( $_settings, array( $key, 'columns' ), 10 );

$btn_text = exc_kv( $_settings, array( $key, 'btn_text' ), _x('Add row', 'extracoding field set button text', 'exc-framework' ) );?>

<script type="text/html" id="<?php echo $tmpl_id;?>_tmpl">
    <!-- FIELDSET RULE -->
    <div class="exc-fieldset-rule exc-dynamic-row" data-row-id="{{{ i }}}">
        <div class="exc-fieldset-icon exc-row-controls">
            <a href="#" class="exc-rule-sort exc-rule-sort exc-move" data-opt-name="delete_row" tabindex="-1"><i class="fa fa-th"></i></a>
            <a href="#" class="exc-rule-remove exc-rule-remove exc-delete" data-opt-name="delete_row" tabindex="-1"><i class="fa fa-minus"></i></a>
        </div>
        <div class="exc-fieldset-controls">

            <div class="row">
                <?php $this->form->get_html( $_config, $key, $dynamic_key );?>
            </div>
        </div>
    </div>
</script>

<?php

if ( $label ) :?>

<div class="form-group">
    <label class="col-sm-2" for="<?php echo esc_attr( $label );?>"><?php echo $label;?></label>
        <div class="col-sm-<?php echo $columns;?>">

<?php endif;?>
            <div class="exc-fieldset exc-form-rows" id="<?php echo $tmpl_id;?>" data-autostart="1">
                <!-- ADD FIELDSET -->
                <?php if ( $head = exc_kv( $_settings, array( $key, 'head' ) ) ) :?>
                    <div class="exc-fieldset-header exc-fieldset-rule">
                        <div class="exc-fieldset-controls">
                            <div class="row">
                                <?php echo $head;?>
                            </div>
                        </div>
                    </div>
                <?php endif;?>

                <div class="exc-add-fieldset exc-add-btn">
                    <div class="exc-row-controls" data-controls="<?php echo $tmpl_id;?>">
                        <a class="btn btn-default" data-opt-name="add_row" href="#">
                            <i class="fa fa-plus"></i> <?php echo $btn_text;?>
                        </a>
                    </div>
                </div>
            </div>

<?php if ( $label ) :?>
    </div>
</div>
<?php endif;?>

<?php endforeach;?>