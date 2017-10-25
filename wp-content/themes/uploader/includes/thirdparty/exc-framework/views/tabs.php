<div class="exc-tabs">
    <ul role="tablist" class="nav nav-tabs" id="<?php echo esc_attr( $_prefix );?>">
        <?php

        $active = 'class="active"';
        foreach ( $_config as $k => $v ):
            $label = exc_kv( (array) $_settings, $k . '/label', exc_to_text( $k ) ); ?>

        <li <?php echo $active;?>><a data-toggle="tab" role="tab" href="#tab-<?php echo $_prefix . '-' . $k;?>"><?php echo $label;?></a></li>

        <?php
        $active = '';
        endforeach;?>
    </ul>

    <div class="tab-content" id="<?php echo $_prefix;?>Content">

        <?php
        $active = ' in active';
        foreach($_config as $k=>$v):?>

        <div id="tab-<?php echo $_prefix . '-' . $k;?>" class="tab-pane fade <?php echo $active;?>">
            <?php $this->form->get_html($_config, $k, $_prefix . '-' . $k);?>
        </div>

        <?php
        $active = '';
        endforeach;?>

    </div>
</div>
