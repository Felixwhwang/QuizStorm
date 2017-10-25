<div class="exc-panel">

    <div class="exc-panel-left">
        <div class="exc-panel-logo">
            <a href="http://www.extracoding.com/" target="_blank">
                <img src="<?php echo $this->system_url('views/images/logo.png');?>" alt="extracoding.com">
            </a>
        </div>

        <div class="exc-panel-nav">
            <div class="panel panel-group" id="main">
                <div class="panel-body">
                    <?php $this->load_view( 'theme_options/menu' );?>
                </div>
            </div>
        </div>
    </div>

    <div class="exc-panel-right">

        <?php if(isset($title)):?>

            <div class="exc-panel-header">
                <h2>
                    <?php echo ( isset($icon) ) ? $icon : '<span class="fa fa-gear"></span>';?><?php echo $title;?>
                </h2>
            </div>

        <?php endif;?>

        <div class="exc-panel-content">
            <?php
            $settings = array();

            if ( ! empty( $_config['_settings'] ) )
            {
                $settings = $_config['_settings'];
                unset( $_config['_settings'] );
            }

            foreach ( $_config as $_config_key => $_config_block ) {

                $this->load_view(
                    'options_block',
                    array(
                        '_config'       => $_config_block,
                        '_settings'     => $settings,
                        '_prefix'       => $_config_key,
                        'parent_key'    => ''
                    )
                );
            }?>

        </div>
    </div>

</div>