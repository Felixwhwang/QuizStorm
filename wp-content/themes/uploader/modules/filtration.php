<?php

$layout = ( isset( $layout ) ) ? $layout : exc_get_layout_settings();
$active_view = ( ( exc_kv( $layout, 'active_view' ) ) == 'list' ) ? 'list' : 'grid';

$allowed_post_types = get_allowed_post_types_array();?>

<div class="filter-bar filter">
    <div class="<?php mf_container_class();?>" id="exc-media-filter">
        <ul class="filter-list">

            <!-- Media Files Counter -->
            <li>
                <div class="filter-item media-files">
                    <span id="exc-media-count"></span>
                </div>
            </li>

            <!-- Media Fields -->

            <?php
            $parent_id = ( is_category() ) ? get_query_var('cat') : 0;
            $categories = get_categories( 'hide_empty=1&parent=' . $parent_id );?>

            <?php if ( ! empty( $categories ) ): ?>
            <li>
                <div class="filter-item media-categories">
                    <div class="btn-group">
                        <button class="btn" id="all-fields" data-toggle="dropdown">
                            <?php echo _x('All Categories', 'extracoding uploader media filter', 'exc-uploader-theme');?>
                        </button>
                        <button class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <span class="caret"></span>
                        </button>
                        <ul aria-labelledby="all-fields" role="menu" class="dropdown-menu exc-media-filter" data-name="cat">
                            <?php

                            $parent_id = ( is_category() ) ? get_query_var('cat') : 0;
                            foreach( $categories as $category) :?>
                            <li><a href="<?php echo esc_url( get_category_link( $category->term_id ) );?>" data-id="<?php echo esc_attr( $category->term_id );?>">
                                <?php echo $category->name;?></a>
                            </li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            </li>
            <?php endif;?>

            <!-- Media Type -->
            <li>
                <?php if ( ! empty( $layout['post_type'] ) && count( $allowed_post_types ) > 1 ) :?>
                <div class="filter-item media-types exc-media-filter autoload-only" data-name="post_type">
                    <ul class="show-first-count clearfix">
                        <li>
                            <a class="btn active" href="#" data-id="any"><?php echo _x('All', 'extracoding uploader media filter', 'exc-uploader-theme');?></a>
                        </li>

                        <?php
                        $no_of_post_types = count( $allowed_post_types ) - 1;
                        $i = 0;
                        foreach ( $allowed_post_types as $index => $post_type ) : ?>

                            <?php if ( $no_of_post_types > 2 && $i == 2 ) :?>
                            <li><a class="btn skip-filter" href="javascript:void(0);"><span><?php esc_html_e('More', 'exc-uploader-theme');?></span> <i class="fa fa-sort"></i></a>
                                <ul>
                            <?php endif;?>

                                <li>
                                    <a class="btn" href="#" data-id="<?php echo esc_attr( $post_type['type'] );?>"><i class="fa <?php echo esc_attr( $post_type['icon'] );?>"></i> <span><?php echo esc_html( $post_type['label'] );?>s</span></a>
                                </li>

                            <?php if ( $no_of_post_types > 2 && $i == $no_of_post_types ) :?>
                                </ul>
                            </li>
                            <?php endif;?>
                        <?php
                        ++$i;
                        endforeach;?>
                    </ul>
                </div>
                <?php endif;?>
            </li>
            <!-- Media View -->
            <li>
                <div class="filter-item media-views exc-media-filter autoload-only" data-name="active_view">
                    <a href="#" class="grid-view <?php echo ( $active_view == 'grid' ) ? 'active' : '' ;?>" data-id="grid">
                        <span class="top-left"></span>
                        <span class="top-right"></span>
                        <span class="bottom-left"></span>
                        <span class="bottom-right"></span>
                    </a>
                    <a href="#" class="list-view <?php echo ( $active_view == 'list' ) ? 'active' : '' ;?>" data-id="list">
                        <span></span>
                        <span></span>
                        <span></span>
                    </a>
                </div>
            </li>
            <!-- Media Search -->
            <li>
                <div class="filter-item media-search">
                    <form role="search" id="exc-media-search">
                        <div class="search">
                            <input type="text" name="s" placeholder="<?php esc_attr_e('Search any keyword', 'exc-uploader-theme');?>" class="form-control">
                            <button type="submit" class="btn"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>