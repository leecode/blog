<?php
	include_once 'header.php';
?>
<body>
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

    <div class="container">
    	<div class="row">

        <div class="col-sm-8 blog-main">
          <article class="blog-post">
            <h2 class="blog-post-title"><?php echo $post['title'];?></h2>
            <ul class="blog-post-meta">
              <li><?php echo Commons::timeToDate($post['created']);?></li>
              <li>作者: Leecode</li>
            </ul>
            <?php echo $post['text'];?>
          </article><!-- /.blog-post -->


          <?php
          	if(is_array($comments) && 0 < count($comments)) {
          ?>
	          <div class="comments">
	          	<h4>已有<?php echo $comments_count;?>条评论</h4>
	          	<ol class="comment-list">
	          <?php
		          foreach ($comments as $comment_item) {
                $comment_div_id = 'comment-' . $comment_item['coid'];
		      ?>
		      	<li id="<?php echo $comment_div_id?>" class="comment-body comment-pattern comment-by-author">
              <div class="comment-meta">
                <?php echo Commons::timeToDate($comment_item['created'], 'F j, Y H:m:s');?>
              </div>
              <div class="comment-content">
                <?php echo $comment_item['text'];?>
              </div>
              <div class="comment-reply">
                <a href="javascript:" onclick="reply_to_comment('<?php echo $comment_div_id;?>', <?php echo $comment_item['coid'];?>);">回复</a>
              </div>
            </li>
		      <?php
		          }
		      ?>
		  		</ol>
          <ul class="pagination">
            <?php
                $temp = $comments_count % $page_size;
                $page_count = intval(($temp == 0 ? ($comments_count / $page_size) : ($comments_count / $page_size + 1)));

                $pagination_right = ($page / $page_size + 1) * $page_size;
                $pagination_right = $pagination_right < $page_count ? $pagination_right : $page_count;
                $pagination_left = intval(($page / $page_size)) * $page_size + 1;

                // 输出前一页
                if($page == 1) {
                    echo '<li class="disabled"><a href="#">«</a></li>';
                } else {
                    echo '<li><a href="'. $list_page_url .'?action=details&cid=' . $post['cid'] . '&page=1&page_size=' . $page_size .'">«</a></li>';
                }
                
                // 输出中间页
                for($pn = $pagination_left; $pn <= $pagination_right; $pn++) {
                    if($pn == $page) {
                        echo '<li class="active"><a href="#">' . $pn .'</a></li>';                            
                    } else {
                        echo '<li><a href="'. $list_page_url .'?action=details&cid=' . $post['cid'] . '&page=' . $pn . '&page_size=' . $page_size .'">' . $pn .'</a></li>';                            
                    }
                }

                // 输出下一页
                if($page == $page_count) {
                    echo '<li class="disabled"><a href="#">»</a></li>';
                } else {
                    echo '<li><a href="' . $list_page_url . '?action=details&cid=' . $post['cid'] . '&page=' . $page_count . '&page_size=' . $page_size .'">»</a></li>';
                }
                ?>
          </ul>
	          </div>
          <?php
      		}
          ?>
          <form id="respond-form" class="respond" action="index.php" method="post">
          	<input type="hidden" name="action" value="comment" />
          	<input type="hidden" name="cid" value="<?php echo $cid;?>"/>
            <a id="cancel-comment-reply-link" href="#" onclick="return cancelReply();">取消回复</a>
          	<h4>添加新评论</h4>
          	<div class="form-group">
	          	<label for="comment" class="required">评论</label>
	          	<textarea name="text" rows="8" cols="50" style="width:100%;resize:vertical;"></textarea>
			      </div>

			      <div class="form-group">
            	<input type="submit" id="meta-save-btn" class="btn btn-success" value="保存" />
          	</div>
          </form>
          <div id="respond-form-holder"></div>
        </div><!-- /.blog-main -->
        <script type="text/javascript">
          function reply_to_comment(commentDivId, commentId) {
            var $commentDiv = $('#' + commentDivId);
            var $respondForm = $('#respond-form');
            var $parentInputField = $('#comment-parent');
            if($parentInputField.length == 0) {
              $parentInputField = $(createElement('input', {'type' : 'hidden', 'name' : 'parent', 'id' : 'comment-parent'}));
            }

            $parentInputField.val(commentId);
            $respondForm.append($parentInputField);

            $commentDiv.append($respondForm);

             $('#cancel-comment-reply-link').show();
            var textarea = $('textarea[name="text"]')[0];
            textarea.focus();

          }

          function cancelReply() {
            $('#respond-form-holder').parent().append($('#respond-form'));
            $('#cancel-comment-reply-link').hide();
            $('#comment-parent').remove();

            return false;
          }

          /**
           * 创建一个DOM元素
           */
          function createElement(tag, attr) {
            var elem = document.createElement(tag);

            for(var key in attr) {
              elem.setAttribute(key, attr[key]);
            }

            return elem;
          }
        </script>

        <div class="col-sm-3 col-sm-offset-1 blog-sidebar">
          <div class="sidebar-module sidebar-module-inset">
            <h4>About</h4>
            <p>Etiam porta <em>sem malesuada magna</em> mollis euismod. Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur.</p>
          </div>
          <div class="sidebar-module">
            <h4>Archives</h4>
            <ol class="list-unstyled">
              <li><a href="#">January 2014</a></li>
              <li><a href="#">December 2013</a></li>
              <li><a href="#">November 2013</a></li>
              <li><a href="#">October 2013</a></li>
              <li><a href="#">September 2013</a></li>
              <li><a href="#">August 2013</a></li>
              <li><a href="#">July 2013</a></li>
              <li><a href="#">June 2013</a></li>
              <li><a href="#">May 2013</a></li>
              <li><a href="#">April 2013</a></li>
              <li><a href="#">March 2013</a></li>
              <li><a href="#">February 2013</a></li>
            </ol>
          </div>
          <div class="sidebar-module">
            <h4>Elsewhere</h4>
            <ol class="list-unstyled">
              <li><a href="#">GitHub</a></li>
              <li><a href="#">Twitter</a></li>
              <li><a href="#">Facebook</a></li>
            </ol>
          </div>
        </div><!-- /.blog-sidebar -->

      </div><!-- /.row -->

    </div><!-- /.container -->

    <div class="blog-footer">
      <p>Blog template built for <a href="http://getbootstrap.com">Bootstrap</a> by <a href="https://twitter.com/mdo">@mdo</a>.</p>
      <p>
        <a href="#">Back to top</a>
      </p>
    </div>
    </div>
</body> 
<?php
  include_once 'footer.php';
?>