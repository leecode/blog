<?php
  //$app_root = 'http://' . $_SERVER['HTTP_HOST'] . '/meblog';
  $admin_root_dir = dirname(__FILE__);
  include_once $admin_root_dir . '/header.php';
  include_once $admin_root_dir . '/menu.php';
?>
<div class="container-fluid">
  <div class="row">

    <div class="col-sm-9 col-sm-offset-1 col-md-10 col-md-offset-1">
      <h2 class="page_title">管理文章<a href="index.php?controller=contents&action=write_post">新增文章</a></h2>
      <div class="row">
        <div class="col-md-7">
          <form action="" method="get">
            <label for="operate">
              <input type="checkbox" class="table-select-all" id="check_all"/>
            </label>
            <div class="btn-group">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">选中项<span class="caret"></span></button>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#" onclick="delete_contents();">Delete</a></li>
                <li><a href="#" onclick="publish_contents();">Publish</a></li>
              </ul>
            </div>
          </form>
        </div>
        <div class="col-md-4">
          <div class="search">
            <form id="search_form" action="" method="get">
              <input type="hidden" name="controller" value="contents" />
              <input type="hidden" name="action" value="show" />
              <input type="hidden" name="page" value="<?php echo $page;?>" />
              <input type="hidden" name="page_size" value="<?php echo $page_size;?>" />
              <input type="text" placeholder="请输入关键字" name="q" value="<?php echo urldecode($q);?>">
              <select name="category">
                <option>所有分类</option>
                <?php
                  foreach ($metas_list as $meta_item) {
                    $selected = '';
                    if($meta_item['mid'] == $category) {
                      $selected = 'selected';
                    }
                ?>
                <option name="category" value="<?php echo $meta_item['mid'];?>" <?php echo $selected;?>><?php echo $meta_item['name'];?></option>
                <?php
                  }
                ?>
              </select>
              <button type="submit" class="btn btn-default" id="search_btn" onclick="searchPosts();">筛选</button>
            </form>
          </div>
        </div>
      </div>
      <!-- Blog table -->
      <?php
        $url =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $list_page_url = substr($url, 0, strpos($url, '?'));

        if(is_array($contents_list) && 0 != count($contents_list)) {
      ?>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>ID</th>
              <th>标题</th>
              <th>作者</th>
              <th>更新时间</th>
            </tr>
          </thead>
          <tbody>
            <?php
              foreach ($contents_list as $contents_item) {
            ?>
            <tr>
              <td><input type="checkbox" class="content_id" name="cid" value="<?php echo $contents_item['cid'];?>"/><span></span><a href="#"><?php echo $contents_item['comment_count'];?></a></td>
              <td><?php echo $contents_item['cid']; ?></td>
              <td><a href="<?php echo $list_page_url . '?controller=contents&action=write_post&cid=' . $contents_item['cid'];?>"><?php echo $contents_item['title']; ?></a></td>
              <!-- <td><?php echo $contents_item['author_id']; ?></td> -->
              <td><a href="index.php?controller=user&action=user&uid=<?php echo $contents_item['author']['uid'];?>"><?php echo $contents_item['author']['name'];?></a></td>
              <td><?php echo $contents_item['created']; ?></td>
            </tr>
            <?php
              }
            ?>
          </tbody>
        </table>
        <ul class="pagination">
            <?php
                // 1 2 3 4 5
                // 6 7 8 9 10
                $temp = $total % $page_size;
                $page_count = intval(($temp == 0 ? ($total / $page_size) : ($total / $page_size + 1)));

                $pagination_right = ($page / $page_size + 1) * $page_size;
                $pagination_right = $pagination_right < $page_count ? $pagination_right : $page_count;
                $pagination_left = intval(($page / $page_size)) * $page_size + 1;

                // 输出前一页
                if($page == 1) {
                    echo '<li class="disabled"><a href="#">«</a></li>';
                } else {
                    echo '<li><a href="'. $list_page_url .'?controller=contents&action=show&page=1&page_size=' . $page_size .'">«</a></li>';    
                }
                
                // 输出中间页
                for($pn = $pagination_left; $pn <= $pagination_right; $pn++) {
                    if($pn == $page) {
                        echo '<li class="active"><a href="#">' . $pn .'</a></li>';                            
                    } else {
                        echo '<li><a href="'. $list_page_url .'?controller=contents&action=show&page=' . $pn . '&page_size=' . $page_size .'">' . $pn .'</a></li>';                            
                    }
                }

                // 输出下一页
                if($page == $page_count) {
                    echo '<li class="disabled"><a href="#">»</a></li>';
                } else {
                    echo '<li><a href="' . $list_page_url . '?controller=contents&action=show&page=' . $page_count . '&page_size=' . $page_size .'">»</a></li>';
                }
            ?>
        </ul>
      </div>
      <?php
        } else {
      ?>
      SHIT
      <?php
        }
      ?>
    </div>
  </div>
</div>
    <!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<?php
  include_once $admin_root_dir . '/footer.php';
?>
<script type="text/javascript">
  $(function() {
    $('#check_all').click(function() {
      if(this.checked) {
        checkAllContents(true);  
      } else {
        checkAllContents(false);
      }
    });
  });

  function checkAllContents(value) {
    $('input.content_id').each(function() {
      this.checked = value;
    })
  }

  function getSelectedContentIds() {
    var contentIdsArray = [];
    $('input.content_id').each(function() {
      if(this.checked) {
        contentIdsArray.push($(this).val());
      }
    });

    var contentIdsStr = contentIdsArray.join(',');
    
    return contentIdsStr;
  }

  function delete_contents() {
    var idStr = getSelectedContentIds();
    if('' == idStr) {
      alert('请选择要操作的POST');
      return;
    }

    if(!confirm('确定要进行操作么？')) {
      return;
    }

    var params = {};
    params.controller = 'contents';
    params.action = 'delete';
    params.cids = idStr;

    $.post('index.php', params, function(response) {
      if(response.success) {
        window.location = ''; // 刷新当前页面
      }
    }, 'json');
  }

  function publish_contents() {

  }

  // 当点击搜索的时候，将页号置为1.
  function searchPosts() {
    $('input[name="page"]', $('#search_btn').parent()).val(1);
  }
</script>