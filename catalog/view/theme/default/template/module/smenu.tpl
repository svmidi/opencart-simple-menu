<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-gallery row">
		<ul class="box-category">
		  <?php foreach ($smenus as $smenu) { ?>
		  <li>
		    <?php if ($_SERVER['QUERY_STRING'] == $smenu['link']) { ?>
		    <a href="<?php echo $smenu['link']; ?>" class="active" title="<?php echo $smenu['title']; ?>"><?php echo $smenu['text']; ?></a>
		    <?php } else { ?>
		    <a href="<?php echo $smenu['link']; ?>" title="<?php echo $smenu['title']; ?>"><?php echo $smenu['text']; ?></a>
		    <?php } ?>
		    <?php if ($smenu['children']) { ?>
		    <ul>
		      <?php foreach ($smenu['children'] as $child) { ?>
		      <li>
		        <?php if ($_SERVER['QUERY_STRING'] == $child['link']) { ?>
		        <a href="<?php echo $child['link']; ?>" class="active" title="<?php echo $child['title']; ?>"> - <?php echo $child['text']; ?></a>
		        <?php } else { ?>
		        <a href="<?php echo $child['link']; ?>" title="<?php echo $child['title']; ?>"> - <?php echo $child['text']; ?></a>
		        <?php } ?>
		      </li>
		      <?php } ?>
		    </ul>
		    <?php } ?>
		  </li>
		  <?php } ?>
		</ul>
	</div>
  </div>
</div>