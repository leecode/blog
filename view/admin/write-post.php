<?php
  //$app_root = 'http://' . $_SERVER['HTTP_HOST'] . '/meblog';
  $admin_root_dir = dirname(__FILE__);
  include_once $admin_root_dir . '/header.php';
  include_once $admin_root_dir . '/menu.php';
?>
<script type="text/javascript" src="../view/admin/js/ckeditor/ckeditor.js"></script>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Project name</a>
    </div>
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</div>

<div class="container-fluid">
  <div class="row">
      <div class="col-md-8">
        <form id="blog_form" class="form-horizontal" action="./index.php" method="post">
          <input type="hidden" name="controller" value="contents"/>
          <input type="hidden" name="action" value="add" />
          <input type="hidden" name="cid" value="<?php echo $content_item['cid'];?>" />
          <input type="hidden" id="category-ids" name="categories" value="" />
          <h3>撰写文章</h3>
          <input type="text" id="title" class="form-control" placeholder="文章标题" name="title" 
            value="<?php echo $content_item['title'];?>"/>
          <br>
          <textarea id="content" rows="3" class="form-control" name="text"><?php echo $content_item['text']?></textarea>
          <br>
          <input type="button" id="draft-btn" class="btn" value="保存草稿" />
          <input type="button" id="pub-btn" class="btn btn-success" value="发布文章" />
        </form>
      </div>
      <div class="col-md-4">
        <section class="category-option">
          <h3>分类</h3>
          <ul>
            <?php
              // Category list.
              foreach ($metas_list as $metas_item) {
                $checked = '';
                if(in_array($metas_item['mid'], $relationships)) {
                  $checked = 'checked';
                }
            ?>
            <li>
              <input type="checkbox" id="category-<?php echo $metas_item['mid'];?>" 
                     class="post-category" name="category" value="<?php echo $metas_item['mid'];?>" <?php echo $checked?> />
              <label for="category-<?php echo $metas_item['mid'];?>"><?php echo $metas_item['name'];?></label>
            </li>
            <?php
              }
            ?>
          </ul>
        </section>
        <section class="post-option">
          <label>标签</label>
          <p>
            <ul class="token-input-list">
              
              <li class="token-input-input-token">
                <input type="text" id="token-input-tags" style="outline:none;">
              </li>
            </ul>
          </p>
        </section>
      </div>
  </div>
</div><!-- /.container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="../view/admin/js/jquery-1.11.js"></script>
<script src="../view/admin/js/bootstrap.min.js"></script>
<script type="text/javascript">
	$(function() {
		CKEDITOR.replace('content');

    $('#pub-btn').click(function() {
      // 得到所有选中的分类
      var selected_categories = [];
      $('input[id^="category-"]').each(function() {
        if(this.checked) {
          selected_categories.push(this.value);
        }
      });

      var category_id_str = selected_categories.join(',');
      $('#category-ids').val(category_id_str);

      $('form#blog_form').submit();
    });

    $('#token-input-tags').blur(function() {
      var tagValue = this.value;
      if(tagValue.length > 0) {
        // tag有值
        /*<li class="token-input-token"><p>fasdfa</p><span class="token-input-delete-token">×</span></li>*/
        $('<li class="token-input-token"><p>' + tagValue + 
          '</p><span class="token-input-delete-token">&times;</span></li>').insertBefore($(this)).click(function() {
            $(this).parent().remove();
          });
        this.value = '';
      }
    });
	});
</script>