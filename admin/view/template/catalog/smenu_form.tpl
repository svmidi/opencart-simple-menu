<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button id="save_data" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
			</div>
			<div class="panel-body">



<style type="text/css">
.mjs-nestedSortable-error {
	background: #fbe3e4;
	border-color: transparent;
}
#tree {
	width: 550px;
	margin: 0;
}
ol {
	max-width: 100%;
	padding-left: 25px;
}
ol.sortable,ol.sortable ol {
	list-style-type: none;
}
.sortable li div {
	cursor: move;
	margin: 0;
	padding: 3px;
}
.sorted-border {
	border: 1px solid #d4d4d4;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	border-color: #D4D4D4 #D4D4D4 #BCBCBC;
	margin: 10px 0;
}
li.mjs-nestedSortable-collapsed.mjs-nestedSortable-hovering div {
	border-color: #999;
}
.sortable li.mjs-nestedSortable-collapsed > ol {
	display: none;
}
.sortable li.mjs-nestedSortable-branch > div > .disclose {
	display: inline-block;
}
li.mjs-nestedSortable-leaf {
  margin: 5px 0;
  border: 1px solid #D4D4D4;
}
.sortable span.ui-icon {
	display: inline-block;
	margin: 0;
	padding: 0;
}
.menuDiv {
	background: #EBEBEB;
}
.menuEdit {
	background: #FFF;
	display: none;
}
.menuEdit-open {
	background: #FFF;
}
.itemTitle {
	vertical-align: middle;
	cursor: pointer;
}
.deleteMenu {

}
</style>


<script>
		$().ready(function(){
			var ns = $('ol.sortable').nestedSortable({
				forcePlaceholderSize: true,
				handle: 'div',
				helper:	'clone',
				items: 'li',
				opacity: .6,
				placeholder: 'placeholder',
				revert: 250,
				tabSize: 25,
				tolerance: 'pointer',
				toleranceElement: '> div',
				maxLevels: 2,
				isTree: true,
				expandOnHover: 700,
				startCollapsed: false
			});
			
			$(document).on('click', '.disclose',function() {
				$(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
				$(this).toggleClass('ui-icon-plusthick').toggleClass('ui-icon-minusthick');
			});
			
			$(document).on ('click','.expandEditor', function(){
				var id = $(this).attr('data-id');
				$('#menuEdit'+id).toggle();
				$(this).toggleClass('ui-icon-triangle-1-n').toggleClass('ui-icon-triangle-1-s');
			});
			
			$(document).on( "click",'.deleteMenu', function() {
				var id = $(this).attr('data-id');
				var datas = "&id=" + id;
				alert(id)
				$.ajax({
					type: "POST",
					url: "index.php?route=catalog/smenu/deleteitem&token=<?php echo $token; ?>",
					cache: false,
					data: datas,
					success: function(html){
						alert(html);
						var jsonData = JSON.parse(html);
						alert(jsonData['error']);
						if (jsonData['error']==1)
							alert('error'+jsonData['text']);
						else
						{
							$('#menuItem_'+id).remove();
						}
					},
				});
			});

			$('#save_data').click(function(){
				serialized = $('ol.sortable').nestedSortable('serialize');
				var action = $("#form").attr("action");
				$("#form").attr("action", action+'&'+serialized);
				$('#form').submit();
			})



			$('#type').change(function() {
				var id = $(this).val();
				var datas = "&id=" + id;
				$.ajax({
					type: "POST",
					url: "index.php?route=catalog/smenu/getType&token=<?php echo $token; ?>",
					cache: false,
					data: datas,
					success: function(html){
						var jsonData = JSON.parse(html);
						$('#level2').html(jsonData.result);
					},
				});

			});

			$(document).on('click', '.select-btn', function(){
				$('#myModalLabel').html($(this).data('id'));
			});

			$('#save-type').click(function(){
				var id=$('#myModalLabel').text();
				$('#type-'+id).val($('#type').val());
				if ($('#type').val()==5)
				{
					$('#type-id-'+id).val('');
					$('#type-name-'+id).val($('#end').val());
					$('#itemTitle-'+id).text($('#end').val());
				} else {
					$('#type-id-'+id).val($('#end').val());
					$('#type-name-'+id).val($('#end').find(":selected").text());
					$('#itemTitle-'+id).text($('#end').find(":selected").text());
				}
				$('#myModal').modal('hide');
			});


		});			
	
		function dump(arr,level) {
			var dumped_text = "";
			if(!level) level = 0;
	
			//The padding given at the beginning of the line.
			var level_padding = "";
			for(var j=0;j<level+1;j++) level_padding += "    ";
	
			if(typeof(arr) == 'object') { //Array/Hashes/Objects
				for(var item in arr) {
					var value = arr[item];
	
					if(typeof(value) == 'object') { //If it is an array,
						dumped_text += level_padding + "'" + item + "' ...\n";
						dumped_text += dump(value,level+1);
					} else {
						dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
					}
				}
			} else { //Strings/Chars/Numbers etc.
				dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
			}
			return dumped_text;
		}
	</script>



<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
 <div class="form-group required">
	<label class="col-sm-2 control-label" for="name"><?php echo $entry_name; ?></label>
		<div class="col-sm-10">
			<input type="text" name="name" value="<?php echo $name; ?>" size="100" class="form-control" />
			<?php if ($error_name) { ?>
			<div class="text-danger"><?php echo $error_name; ?></div>
			<?php } ?>
		</div>
	</div>

<ol class="sortable ui-sortable mjs-nestedSortable-branch mjs-nestedSortable-expanded" id="mainTree">
<?php $image_row = 1; $link_name=""; $link_title=''; $link_url='';?>
<?php foreach ($tree as $smenu_item) { 
	$image_row = $smenu_item['item_id']; ?>

	<?php foreach ($languages as $language) {
		$link_name.='<div class="input-group">
		<span class="input-group-addon">
			<img src="view/image/flags/'. $language["image"].'" title="'. $language['name'].'" />
		</span>
		<input type="text" class="form-control" placeholder="'.$column_name.'" name="smenu_item['.$image_row.'][smenu_item_description]['. $language['language_id'].'][text]" value="';
		$link_name.= isset($smenu_item['description'][$language['language_id']]) ? $smenu_item['description'][$language['language_id']]['text'] : '';
		$link_name.='" />';
		if (isset($error_smenu_item[$image_row][$language['language_id']])) {
			$link_name.='<span class="error">'.$error_smenu_item[$image_row][$language['language_id']].'</span>';
			}
		$link_name.='</div>';
		$link_title.='<div class="input-group">
			<span class="input-group-addon">
				<img src="view/image/flags/'. $language["image"].'" title="'. $language['name'].'" />
			</span>
			<input type="text" class="form-control" placeholder="'.$column_title.'" name="smenu_item['.$image_row.'][smenu_item_description]['. $language['language_id'].'][title]" value="';
		$link_title.= isset($smenu_item['description'][$language['language_id']]) ? $smenu_item['description'][$language['language_id']]['title'] : '';
		$link_title.='" />';
		if (isset($error_smenu_item[$image_row][$language['language_id']])) {
			$link_title.='<span class="error">'.$error_smenu_item[$image_row][$language['language_id']].'</span>';
		}
		$link_title.='</div>';

	 } ?>


	 <?php 
	 if ($smenu_item['childs']) {
	 	$children=''; $link_name_child=''; $link_title_child='';$link_url_child='';
		foreach ($smenu_item['childs'] as $child) {
		
		 foreach ($languages as $language) {
			$link_name_child.='<div class="input-group">
			<span class="input-group-addon">
				<img src="view/image/flags/'. $language["image"].'" title="'. $language['name'].'" />
			</span>
			<input type="text" class="form-control" placeholder="'.$column_name.'" name="smenu_item['.$child['item_id'].'][smenu_item_description]['. $language['language_id'].'][text]" value="';
			$link_name_child.= isset($child['description'][$language['language_id']]) ? $child['description'][$language['language_id']]['text'] : '';
			$link_name_child.='" />';
			if (isset($error_smenu_item[$child['item_id']][$language['language_id']])) {
				$link_name_child.='<span class="error">'.$error_smenu_item[$child['item_id']][$language['language_id']].'</span>';
				}
			$link_name_child.='</div>';
			$link_title_child.='<div class="input-group">
				<span class="input-group-addon">
					<img src="view/image/flags/'. $language["image"].'" title="'. $language['name'].'" />
				</span>
				<input type="text" class="form-control" placeholder="'.$column_title.'" name="smenu_item['.$child['item_id'].'][smenu_item_description]['. $language['language_id'].'][title]" value="';
			$link_title_child.= isset($child['description'][$language['language_id']]) ? $child['description'][$language['language_id']]['title'] : '';
			$link_title_child.='" />';
			if (isset($error_smenu_item[$child['item_id']][$language['language_id']])) {
				$link_title_child.='<span class="error">'.$error_smenu_item[$child['item_id']][$language['language_id']].'</span>';
			}
			$link_title_child.='</div>';


		 }

		$children.= '
		<li style="display: list-item;" class="mjs-nestedSortable-leaf" id="menuItem_'.$child['item_id'].'">
			<div class="menuDiv">
				<div class="btn-group" role="group" aria-label="...">
					<button type="button" class="disclose btn btn-default" title="'.$button_showchilds.'"><span class="fa fa-outdent"></span></button>
					<button type="button" data-id="'.$child['item_id'].'" class="expandEditor btn btn-default" title="'.$button_showform.'"><span class="fa fa-eye-slash"></span></button>
					<button type="button" data-id="'.$child['item_id'].'" class="deleteMenu btn btn-danger" title="'.$button_remove.'"><span class="fa fa-minus-circle"></span></button>
				</div>
				<span data-id="'.$child['item_id'].'" class="itemTitle" id="itemTitle-'.$child['item_id'].'">'.$child['type_name'].'</span>
				<div id="menuEdit'.$child['item_id'].'" class="menuEdit">
					<div class="row">
						<div class="col-md-4">'.$link_name_child.'</div>
						<div class="col-md-4">'.$link_title_child.'</div>
						<div class="col-md-4">
						<input type="hidden" name="smenu_item['.$child['item_id'].'][type]" id="type-'.$child['item_id'].'" value="'.$child['type'].'">
						<input type="hidden" name="smenu_item['.$child['item_id'].'][type-id]" id="type-id-'.$child['item_id'].'" value="'.$child['type_id'].'">
						<div class="input-group">
							<input type="text" name="smenu_item['.$child['item_id'].'][type-name]" id="type-name-'.$child['item_id'].'" value="'.$child['type_name'].'" class="form-control" readonly>
							<span class="input-group-btn">
								<button type="button" class="btn btn-primary select-btn" data-toggle="modal" data-target="#myModal" data-id="'.$child['item_id'].'">
									<span class="fa fa-navicon"></span>
								</button>
							</span>
						</div>
					</div>
				</div>
			</div>
		</li>';
		$link_name_child=''; $link_title_child=''; $link_url_child='';
		}

		} ?>

	<li style="display: list-item;" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded sorted-border" id="menuItem_<?php echo $image_row; ?>">
		<div class="menuDiv">
			<div class="btn-group" role="group">
				<button type="button" class="disclose btn btn-default" title="<?php echo $button_showchilds; ?>"><span class="fa fa-outdent"></span></button>
				<button type="button" data-id="<?php echo $image_row; ?>" class="expandEditor btn btn-default" title="<?php echo $button_showform; ?>"><span class="fa fa-eye-slash"></span></button>
				<button type="button" data-id="<?php echo $image_row; ?>" class="deleteMenu btn btn-danger" title="<?php echo $button_remove; ?>"><span class="fa fa-minus-circle"></span></button>
			</div>
			<span>
				<span data-id="<?php echo $image_row; ?>" class="itemTitle" id="itemTitle-<?php echo $image_row; ?>"><?php echo $smenu_item['type_name']; ?></span>
			</span>
			<div id="menuEdit<?php echo $image_row; ?>" class="menuEdit ui-icon-triangle-1-n">
				<div class="row">
					<div class="col-md-4"><?php echo $link_name; ?></div>
					<div class="col-md-4"><?php echo $link_title; ?></div>
					<div class="col-md-4">
						<input type="hidden" name="smenu_item[<?php echo $image_row; ?>][type]" id="type-<?php echo $image_row; ?>" value="<?php echo $smenu_item['type']; ?>">
						<input type="hidden" name="smenu_item[<?php echo $image_row; ?>][type-id]" id="type-id-<?php echo $image_row; ?>" value="<?php echo $smenu_item['type_id']; ?>">

						<div class="input-group">
							<input type="text" class="form-control" readonly name="smenu_item[<?php echo $image_row; ?>][type-name]" id="type-name-<?php echo $image_row; ?>" value="<?php echo $smenu_item['type_name']; ?>">
							<span class="input-group-btn">
								<button type="button" class="btn btn-primary select-btn" data-toggle="modal" data-target="#myModal" data-id="<?php echo $image_row; ?>">
									<span class="fa fa-navicon"></span>
								</button>
							</span>
						</div>
						

					</div>
				</div>
				<?php $link_name=''; $link_title=''; $link_url='';?>
			</div>
		</div>
		<?php if ($smenu_item['childs']) { ?>
			<ol><?php echo $children; ?></ol>
		 <?php } ?>
	</li>
<?php //$image_row++; 
} ?>

</ol>

<button type="button" onclick="addItemTree();" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $button_add; ?>" title="<?php echo $button_add; ?>">
	<i class="fa fa-plus-circle"></i>
</button>

				
					
				</form>
			</div> 
		</div>
	</div>
</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="<?php echo $button_cancel; ?>"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo $text_modal; ?> (<span  id="myModalLabel"></span>)</h4>
			</div>
			<div class="modal-body">
				<select id="type" class="form-control">
					<option value="0"><?php echo $option_type; ?></option>
					<option value="1"><?php echo $option_article; ?></option>
					<option value="2"><?php echo $option_category; ?></option>
					<option value="3"><?php echo $option_product; ?></option>
					<option value="4">SIGallery</option>
					<option value="5">URL</option>
					<option value="6"><?php echo $option_system; ?></option>
				</select>
				<div id="level2">
					
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $button_cancel; ?></button>
				<button type="button" class="btn btn-primary" id="save-type"><?php echo $button_save; ?></button>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript"><!--
var image_row = <?php echo $last; $image_row=$last; ?>;

function addItemTree() {
	html  = '<li style="display: list-item;" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded sorted-border" id="menuItem_' + image_row + '">';
	html += '<div class="menuDiv">';
	html += '<div class="btn-group" role="group">';
	html += '<button type="button" class="disclose btn btn-default" title="<?php echo $button_showchilds; ?>"><span class="fa fa-outdent"></span></button>';
	html += '<button type="button" data-id="'+image_row+'" class="expandEditor btn btn-default" title="<?php echo $button_showform; ?>"><span class="fa fa-eye-slash"></span></button>';
	html += '<button type="button" data-id="'+image_row+'" class="deleteMenu btn btn-danger" title="<?php echo $button_remove; ?>"><span class="fa fa-minus-circle"></span></button>';
	html += '</div>';
	html += '	<span data-id="' + image_row + '" class="itemTitle" id="itemTitle-'+image_row+'"></span>';
	html += '<div id="menuEdit' + image_row + '" class="menuEdit-open">';
	html += '	<div class="row"><div class="col-md-4">';
	<?php foreach ($languages as $language) { ?>
		html += '<div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span><input type="text" class="form-control" placeholder="<?php echo $column_name; ?>" name="smenu_item[' + image_row + '][smenu_item_description][<?php echo $language['language_id']; ?>][text]" value="" /></div>';
	<?php } ?>
	html += '</div>';
	html += '<div class="col-md-4">';
	<?php foreach ($languages as $language) { ?>
		html += '<div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span><input type="text" class="form-control" placeholder="<?php echo $column_title; ?>" name="smenu_item[' + image_row + '][smenu_item_description][<?php echo $language['language_id']; ?>][title]" value="" /></div>';
	<?php } ?>
	html += '</div>';
	html += '<div class="col-md-4">';
	html += '<input type="hidden" name="smenu_item['+image_row+'][type]" id="type-<?php echo $image_row; ?>">';
	html += '<input type="hidden" name="smenu_item['+image_row+'][type-id]" id="type-id-<?php echo $image_row; ?>">';

	html += '<div class="input-group">';
	html += '<input type="text" class="form-control" readonly name="smenu_item['+image_row+'][type-name]" id="type-name-'+image_row+'" value="">';
	html += '<span class="input-group-btn">';
	html += '<button type="button" class="btn btn-primary select-btn" data-toggle="modal" data-target="#myModal" data-id="'+image_row+'">';
	html += '<span class="fa fa-navicon"></span>';
	html += '</button>';
	html += '</span>';
	html += '</div>';
	html += '</div></div>';	
	html += '</div>';
	html += '</li>'; 
	$('#mainTree').append(html);
	image_row++;
}

//--></script>
<?php echo $footer; ?>