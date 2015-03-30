<div class="box-heading"><?php echo $heading_title; ?></div>
<div class="list-group">
	<?php foreach ($items as $smenu) { ?>
		<?php if ($smenu['active']) { ?>
		<a href="<?php echo $smenu['href']; ?>" class="list-group-item active" title="<?php echo $smenu['title']; ?>"><?php echo $smenu['name']; ?></a>
		<?php } else { ?>
		<a href="<?php echo $smenu['href']; ?>" class="list-group-item" title="<?php echo $smenu['title']; ?>"><?php echo $smenu['name']; ?></a>
		<?php } ?>
		<?php if ($smenu['children']) { ?>
			<?php foreach ($smenu['children'] as $child) { ?>
				<?php if ($smenu['active']) { ?>
				<a href="<?php echo $child['href']; ?>" class="list-group-item active" title="<?php echo $child['title']; ?>">&nbsp;&nbsp;&nbsp;- <?php echo $child['name']; ?></a>
				<?php } else { ?>
				<a href="<?php echo $child['href']; ?>" class="list-group-item" title="<?php echo $child['title']; ?>">&nbsp;&nbsp;&nbsp;- <?php echo $child['name']; ?></a>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	<?php } ?>
</div>