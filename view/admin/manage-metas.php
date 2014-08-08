<?php
  $admin_root_dir = dirname(__FILE__);
  include_once $admin_root_dir . '/header.php';
  include_once $admin_root_dir . '/menu.php';
?>
<link rel="stylesheet" type="text/css" href="../view/admin/css/app/metas.css">
<div class="container-fluid">
  <div class="row">
      <div class="col-md-12">
        <ul class="option-tabs">
            <li role="presentation" <?php if(!isset($type) || 'category' == $type) {?>class="active"<?php } ?>><a href="index.php?controller=metas&action=show">分类</a></li>
            <li role="presentation" <?php if(isset($type) && 'tag' == $type) {?> class="active" <?php } ?>><a href="index.php?controller=metas&action=show&type=tag">标签</a></li>
        </ul>
      </div>
      <div class="col-md-12 col-md-8">

        <form action="" method="get">
            <label for="operate">
              <input type="checkbox" class="table-select-all" id="check_all"/>
            </label>
            <div class="btn-group">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">选中项<span class="caret"></span></button>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#" onclick="delete_metas();">Delete</a></li>
              </ul>
            </div>
          </form>
        <?php
          if(!isset($type) || 'category' == $type) {
        ?>
        <div class="table-response">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>名称</th>
                <th>文章数</th>
              </tr>
            </thead>
            <tbody>
                <!-- meta list-->
                <?php
                  foreach ($metas_list as $metas_item) {
                ?>
                <tr>
                  <td><input type="checkbox" class="metas_id" name="mid" value="<?php echo $metas_item['mid'];?>"/></td>
                  <td><a href="index.php?controller=metas&action=show&mid=<?php echo $metas_item['mid'];?>"><?php echo $metas_item['name'];?></a></td>
                  <td><?php echo $metas_item['post_count'];?></td>
                </tr>
                <?php
                  }
                ?>
            </tbody>
          </table>
        </div>
        <?php } elseif(isset($type) && 'tag' == $type) { ?>
        <ul class="typecho-list-notable tag-list clearfix">
          <?php
            foreach($metas_list as $metas_item) {
          ?>
          <li class="size-5" id="tag-<?php echo $metas_item['mid'];?>">
            <input type="checkbox" value="<?php echo $metas_item['mid'];?>" name="mid">
            <span onclick="tag_clicked(<?php echo $metas_item['mid'];?>);" rel="index.php?controller=metas&action=show&type=tag&mid=<?php echo $metas_item['mid'];?>"><?php echo $metas_item['name'];?></span>
            <a class="tag-edit-link" href="index.php?controller=metas&action=show&type=tag&mid=<?php echo $metas_item['mid'];?>">
              <i class="glyphicon glyphicon-pencil"></i>
            </a>
          </li>
          <?php 
            }
          ?>
        </ul>
        <?php } ?>
      </div>
      <div class="col-md-12 col-md-3">
        <form class="form-horizontal" action="index.php" id="meta-form" method="post">
          <input type="hidden" name="controller" value="metas" />
          <input type="hidden" name="action" value="add" />
          <input type="hidden" name="type" value="<?php if(isset($type) && !empty($type)) { echo $type; } else { echo 'category'; }?>" />
          <input type="hidden" name="meta_order" value="0" />
          <input type="hidden" name="mid" value="<?php echo $metas_to_update['mid'];?>">
          <div class="form-group">
            <label for="meta-type">分类名称*</label>
            <input type="text" class="form-control" name="name" value="<?php echo $metas_to_update['name'];?>" placeholder="分类名称" />
          </div>
          <div class="form-group">
            <label for="meta-desc">分类介绍</label>
            <!-- <input type="text" class="form-control"  name="description" placeholder="分类介绍" /> -->
            <textarea class="form-control" name="description" placeholder="分类介绍"><?php echo $metas_to_update['description'];?></textarea>
          </div>
          <div class="form-group">
            <input type="button" id="meta-save-btn" class="btn btn-success" value="保存" />
          </div>
        </form>
      </div>
  </div>
</div><!-- /.container -->

<?php
	include_once $admin_root_dir . '/footer.php';	// load javascripts.
?>

<script type="text/javascript">
  $(function() {
    $('#meta-save-btn').click(function() {
      $meta_form = $('#meta-form');
      $meta_form.submit();
    });

    $('#check_all').click(function() {
      if(this.checked) {
        selectAllMetas(true);  
      } else {
        selectAllMetas(false);  
      }
    });
  });

  function selectAllMetas(value) {
    $('.metas_id').each(function() {
      this.checked = value;
    });
  }

  function get_selected_metas() {
    var metasIdArray = [];
    $('.metas_id').each(function() {
      if(this.checked) {
        metasIdArray.push(this.value);
      }
    });

    var metasIdsStr = metasIdArray.join(',');
    return metasIdsStr;
  }

  function delete_metas() {
    var metaIdsStr = get_selected_metas();
    if('' == metaIdsStr) {
      alert('请选择要删除的分类!');
      return;
    }

    if(!confirm('确定要删除所选择的分类吗？')) {
      return;
    }

    var params = {};
    params.controller = 'metas';
    params.action = 'delete';
    params.mids = metaIdsStr;

    $.post('index.php', params, function(response) {
      if(response.success) {
        window.location = ''; // 刷新当前页面  
      }
      
      console.log(response);
    }, 'json');
  }

  function tag_clicked(spanObjId) {
    var $liElemObj = $('#tag-' + spanObjId);
    var $inputObj = $('input', $liElemObj);
    var val = !$inputObj.attr('checked');

    $inputObj.attr('checked', val);
    if(val) {
      $liElemObj.addClass('checked');
    } else {
      $liElemObj.removeClass('checked');
    }
  }
</script>