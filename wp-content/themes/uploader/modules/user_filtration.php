<div id="exc-users-filter">
	<ul class="filter-list">
		<!-- Media View -->
		<li>
			<div class="filter-item sort-by">
				<div class="btn-group">
					<button class="btn" id="all-fields" data-toggle="dropdown">
						<?php _e('Sort By', 'exc-uploader-theme'); ?>
					</button>
					<button class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<span class="caret"></span>
					</button>

					<ul role="menu" class="dropdown-menu exc-user-filter" data-name="sort_users">
						<li><a href="#" data-id="most-appriciated"><i class="fa fa-heart"></i><?php echo _x('Most appriciated', 'extracoding uploader user filter', 'exc-uploader-theme');?></a></li>
						<li><a href="#" data-id="most-viewed"><i class="fa fa-eye"></i><?php echo _x('Most Viewed', 'extracoding uploader user filter', 'exc-uploader-theme');?></a></li>
						<li><a href="#" data-id="most-discussed"><i class="fa fa-comment"></i><?php echo _x('Most Discussed', 'extracoding uploader user filter', 'exc-uploader-theme');?></a></li>
						<li><a href="#" data-id="most-recent"><i class="fa fa-clock-o"></i><?php echo _x('Most Recent', 'extracoding uploader user filter', 'exc-uploader-theme');?></a></li>
					</ul>
				</div>
			</div>
		</li>
		<li>
			<div class="filter-item sortby-order">
				<span class="exc-user-filter" data-name="order">
					<a class="ascending hide" href="#" data-id="asc"><i class="fa fa-long-arrow-up"></i> <span><?php echo esc_attr__('Ascending', 'exc-uploader-theme'); ?></span></a>
					<a class="descending" href="#" data-id="desc"><i class="fa fa-long-arrow-down"></i> <span><?php echo esc_attr__('Descending', 'exc-uploader-theme'); ?></span></a>
				</span>
			</div>
		</li>
	</ul>
</div>