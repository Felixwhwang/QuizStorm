<?php foreach($_config as $k=>$v): ?>

    <?php if(isset($_settings[$k])):?>
        <?php if(isset($_settings[$k]['heading'])):?>
        <h4><?php echo $_settings[$k]['heading'];?></h4>
        <?php endif;?>

        <?php if(isset($_settings[$k]['description'])):?>
        <p><?php echo $_settings[$k]['description'];?></p>
        <?php endif;?>
    <?php endif;?>

    <?php $this->form->get_html($_config, $k, $_prefix.'-'.$k);?>
    <?php $this->form->get_form_settings( $_name, array('_active_form' => $_prefix . '-' . $k ) );?>
<?php endforeach;?>