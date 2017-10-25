<div class="dropdown">
	<button class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown"> Select <?php echo $label;?> <span class="caret"></span> </button>
	<?php echo $markup;?>
</div>

<?php if($help):?>
<span class="help-block"><?php echo $help;?></span>
<?php endif;?>