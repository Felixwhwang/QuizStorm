<h2 class="nav-tab-wrapper">

    <?php

    foreach( $_menu_settings as $menu_index => $menu ) :
        $menu_class = ( $_active_menu == $menu_index ) ? 'nav-tab nav-tab-active' : 'nav-tab';?>

        <a class="<?php echo esc_attr( $menu_class );?>" href="<?php echo esc_url( $menu['menu_link'] );?>">

        	<?php if ( ! empty( $menu['menu_icon'] ) ) :?>
        		<i class="<?php echo $menu['menu_icon'];?>"></i>
        	<?php endif;?>

        	<?php echo $menu['menu_name'];?>
        </a>
    <?php endforeach;?>
</h2>