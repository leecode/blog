<?php
  //$app_root = 'http://' . $_SERVER['HTTP_HOST'] . '/meblog';
  $admin_root_dir = dirname(__FILE__);
  include_once $admin_root_dir . '/header.php';
  include_once $admin_root_dir . '/menu.php';
?>
<div class="container-fluid">
  <div class="row">

    <div class="col-sm-9 col-sm-offset-1 col-md-10 col-md-offset-1">
      <h2 class="page_title">管理评论</h2>
      <div class="row">
        <div class="col-md-7">
          <form action="" method="get">
            <label for="operate">
              <input type="checkbox" class="table-select-all" id="check_all"/>
            </label>
            <div class="btn-group">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">选中项<span class="caret"></span></button>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#" onclick="delete_comments();">Delete</a></li>
              </ul>
            </div>
          </form>
        </div>
        <div class="col-md-4">
          <div class="search">
            <form id="search_form" action="" method="get">
              <input type="hidden" name="controller" value="comments" />
              <input type="hidden" name="page" value="<?php echo $page;?>" />
              <input type="hidden" name="page_size" value="<?php echo $page_size;?>" />
              <input type="text" placeholder="请输入关键字" name="q" value="<?php echo $q;?>">
              <button type="submit" class="btn btn-default" id="search_btn" onclick="searchPosts();">筛选</button>
            </form>
          </div>
        </div>
      </div>
      <!-- Blog table -->
      <?php
        $url =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $list_page_url = substr($url, 0, strpos($url, '?'));

        if(is_array($comments) && 0 != count($comments)) {
      ?>
      <div class="table-responsive">
        <table class="table table-striped">
          <colgroup>
            <col width="3%">
            <col width="6%">
            <col width="20%">
            <col width="71%">
          </colgroup>
          <thead>
            <tr>
              <th>#</th>
              <th>作者</th>
              <th> </th>
              <th>内容</th>
            </tr>
          </thead>
          <tbody>
            <?php
              foreach ($comments as $comment_item) {
            ?>
            <tr>
              <input type="hidden" name="cid" value="<?php echo $comment_item['cid'];?>" />
              <input type="hidden" name="parent" value="<?php echo $comment_item['coid'];?>" />
              <td valign="top">
                  <input type="checkbox" value="<?php echo $comment_item['coid'];?>" name="coid">
              </td>
              <td valign="top">
                  <div class="comment-avatar">
                      <img class="avatar" alt="admin" width="40" height="40">
                  </div>
              </td>
              <td valign="top" class="comment-head">
                  <div class="comment-meta">
                      <strong class="comment-author">
                        <a href="http://www.typecho.org" rel="external nofollow" target="_blank">admin</a>
                      </strong><br>
                      <span>
                        <a href="mailto:xiaoliang.leecode@gmail.com" target="_blank">xiaoliang.leecode@gmail.com</a>
                      </span><br>
                      <span>127.0.0.1</span>
                  </div>
              </td>
              <td valign="top" class="comment-body">
                  <div class="comment-date">
                    <?php echo Commons::timeToDate($comment_item['created'], 'F j, Y H:m'); ?> 于 
                    <a href="http://www.leecode.io:8888/typecho/index.php/archives/6/comment-page-2#comment-27" target="_blank"><?php echo $comment_item['post_title'];?></a>
                  </div>
                  <div class="comment-content">
                      <p><?php echo $comment_item['text'];?></p>                                
                  </div> 
                  <div class="comment-action hidden-by-mouse">
                      <a class="operate-reply" href="javaqscript:">回复</a>
                      <a class="operate-delete" href="javascript:" onclick="delete_one_comment(<?php echo $comment_item['coid'];?>);">删除</a>
                  </div>
              </td>
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
                    echo '<li><a href="'. $list_page_url .'?controller=comments&page=1&page_size=' . $page_size .'">«</a></li>';    
                }
                
                // 输出中间页
                for($pn = $pagination_left; $pn <= $pagination_right; $pn++) {
                    if($pn == $page) {
                        echo '<li class="active"><a href="#">' . $pn .'</a></li>';                            
                    } else {
                        echo '<li><a href="'. $list_page_url .'?controller=comments&page=' . $pn . '&page_size=' . $page_size .'">' . $pn .'</a></li>';                            
                    }
                }

                // 输出下一页
                if($page == $page_count) {
                    echo '<li class="disabled"><a href="#">»</a></li>';
                } else {
                    echo '<li><a href="' . $list_page_url . '?controller=comments&page=' . $page_count . '&page_size=' . $page_size .'">»</a></li>';
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
        checkAllComments(true);  
      } else {
        checkAllComments(false);
      }
    });

    $('.table tr').each(function() {
      $(this).click(function() {
        checkOneCommentRow(this);
      });
    });

    // 点击“回复”按钮
    $('.operate-reply').click(function() {
      event.stopPropagation();
      var $td = $(this).parents('td');
      var $t = $(this);
      var action_url = 'index.php';

      // 创建一个回复评论用的form，并将其添加到评论的下方
      // 如果此行已经创建了form，则将已将创建的移除。
      if($('.comment-reply', $td).length > 0) {
        $('.comment-reply', $td).remove();
      } else {
        var $form = $('<form method="post" action="' 
                    + action_url + '" class="comment-reply">'
                    + '<div class="form-group"><textarea id="text" name="text" class="w-90" row="3"></textarea></div>'
                    + '<div class="form-group"><input type="submit" class="comment-confirm btn btn-success" value="回复"/>&nbsp;<input type="button" class="comment-cancel btn" value="取消"/></div>'
                    ).insertBefore($('.comment-action', $td));

        // 添加取消按钮响应事件。
        $('.comment-cancel', $form).click(function() {
          $(this).parents('.comment-reply').remove();
        });

        var $textarea = $('textarea', $form).focus();

        // 表单提交。
        $form.submit(function() {
          var $that = $(this);
          var $tr = $that.parents('tr');
          // 创建“回复”元素
          var $reply = $('<div class="comment-reply-content"></div>').insertAfter($('.comment-content', $tr));
          $reply.html('<p>' + $textarea.val() + '</p>');

          var params = {};
          params.action = 'reply';
          params.controller = 'comments';
          params.cid = $('input[name="cid"]', $tr)[0].value;
          params.parent = $('input[name="parent"]', $tr)[0].value;
          params.text = $textarea.val();

          $.post(action_url, params, function(response) {
            if(response.success) {
              $reply.fadeOut();
              $reply.fadeIn();              
            }
          }, 'json');

          $that.remove();
          return false;
        });
      }
    });
  });

  /**
   * 选中/取消选中当前页所有的评论。
   */
  function checkAllComments(value) {
    $('input[name="coid"]').each(function() {
      this.checked = value;
    });

    $('.table tr').each(function() {
      if(value) {
        $(this).addClass('checked');
      } else {
        $(this).removeClass('checked');
      }
    });
  }

  /**
   * 选中某一个评论
   */
  function checkOneCommentRow(row) {
    $(row).toggleClass('checked');
    $coidCheckbox = $("input[name='coid']", $(row));

    $("input[name='coid']", $(row)).each(function(event) {
      if($(row).hasClass('checked')) {
        this.checked = true;  
      } else {
        this.checked = false;
      }
      
    });


  }

  /**
   * 得到选中的评论，用于批量删除。
   */
  function getSelectedCommentIds() {
    var coIdsArray = [];
    $('input[name="coid"]').each(function() {
      if(this.checked) {
        coIdsArray.push($(this).val());
      }
    });

    var coIdsStr = coIdsArray.join(',');
    
    return coIdsStr;
  }

  /**
   * 批量删除评论
   */
  function delete_comments() {
    var idStr = getSelectedCommentIds();
    if('' == idStr) {
      alert('请选择要操作的评论！');
      return;
    }

    if(!confirm('确定要进行操作么？')) {
      return;
    }

    var params = {};
    params.controller = 'comments';
    params.action = 'delete';
    params.coids = idStr;

    $.post('index.php', params, function(response) {
      if(response.success) {
        window.location = ''; // 刷新当前页面
      }
    }, 'json');
  }

  /**
   * 通过“删除”超链接删除评论
   */
  function delete_one_comment(comment_id) {
    event.stopPropagation();
    if('' == comment_id) {
      alert('请选择要删除的评论！');
      return;
    }

    var params = {};
    params.controller = 'comments';
    params.action = 'delete';
    params.coids = comment_id;

    $.post('index.php', params, function(response) {
      if(response.success) {
        window.location = '';
      }
    }, 'json');
  }

  // 当点击搜索的时候，将页号置为1.
  function searchComments() {
    $('input[name="page"]', $('#search_btn').parent()).val(1);
  }
</script>