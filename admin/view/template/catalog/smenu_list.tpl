<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">


      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
              <th width="1"><input type="radio" name="onhead" class="onhead" value="0"></th>
              <th class="left"><?php if ($sort == 'name') { ?>
                <a href="<?php echo $sort_text; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_text; ?>"><?php echo $column_name; ?></a>
                <?php } ?>
              </th>
              <th class="right"><?php echo $column_action; ?></th>
            </tr>
          </thead>
          <tbody>
            <?php if ($smenus) { ?>
            <?php foreach ($smenus as $smenu) { ?>
            <tr>
              <td style="text-align: center;">
                <input type="checkbox" name="selected[]" value="<?php echo $smenu['smenu_id']; ?>" />
              </td>
              <td>
              <?php if ($smenu['header']) { ?>
                <input type="radio" name="onhead" class="onhead" CHECKED value="<?php echo $smenu['smenu_id']; ?>">
              <?php } else { ?>
                <input type="radio" name="onhead" class="onhead" value="<?php echo $smenu['smenu_id']; ?>">
              <?php } ?>
              </td>
              <td class="left"><?php echo $smenu['name']; ?></td>
              <td class="right"><?php foreach ($smenu['action'] as $action) { ?>
                <a href="<?php echo $action['href']; ?>" data-toggle="tooltip" title="<?php echo $action['text']; ?>" class="btn btn-primary" data-original-title="<?php echo $action['text']; ?>"><i class="fa fa-pencil"></i></a>
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
  $('.onhead').change(function(){
    var datas = "&menuid=" + $(this).val();
      $.ajax({
        type: "POST",
        url: "index.php?route=catalog/smenu/setheader&token=<?php echo $token; ?>",
        cache: false,
        data: datas,
        success: function(html){
          var jsonData = JSON.parse(html);
          if (jsonData['error']==0)
            alert('ok');
        },
      });
  })
})
</script>
<?php echo $footer; ?>