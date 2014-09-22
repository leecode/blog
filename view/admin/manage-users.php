<?php
	include_once 'header.php';
	include_once 'menu.php';
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-9 col-sm-offset-1 col-md-10 col-md-offset-1">
			<h2 class="page_title">管理用户<a href="index.php?controller=user&action=user">新增用户</a></h2>
			<div class="row">
				<div class="col-md-7">
					<form action="" method="get">
						<label for="operate">
			            <input type="checkbox" class="table-select-all" id="check_all"/>
			            </label>
			            <div class="btn-group">
			              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">选中项<span class="caret"></span></button>
			              <ul class="dropdown-menu" role="menu">
			                <li><a href="#" onclick="delete_users();">删除</a></li>
			              </ul>
			            </div>
					</form>
				</div>
				<div class="col-md-4">
		          <div class="search">
		            <form id="search_form" action="" method="get">
		              <input type="hidden" name="controller" value="user" />
		              <input type="hidden" name="page" value="<?php echo $page;?>" />
		              <input type="hidden" name="page_size" value="<?php echo $page_size;?>" />
		              <input type="text" placeholder="请输入关键字" name="q" value="<?php echo urldecode($q);?>">
		              <button type="submit" class="btn btn-default" id="search_btn" onclick="searchUsers();">筛选</button>
		            </form>
		          </div>
		        </div>
			</div>
			<?php
				$url =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        		$list_page_url = substr($url, 0, strpos($url, '?'));
			?>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<th>#</th>
						<th>用户名</th>
						<th>昵称</th>
						<th>电子邮件</th>
					</thead>
					<tbody>
						<?php
							foreach ($users as $user) {
						?>
						<tr>
			              <td><input type="checkbox" class="user_id" name="uid" value="<?php echo $user['uid'];?>"/><span></span><a href="#"><?php echo $user['comment_count'];?></a></td>
			              <td><a href="<?php echo $list_page_url . '?controller=user&action=user&uid=' . $user['uid'];?>"><?php echo $user['name']; ?></a></td>
			              <td><?php echo $user['screenName']; ?></td>
			              <td><?php echo $user['mail']; ?></td>
			            </tr>
			            <?php
			            	}
			            ?>
					</tbody>
				</table>
			</div>
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
                    echo '<li><a href="'. $list_page_url .'?controller=user&page=1&page_size=' . $page_size .'">«</a></li>';    
                }
                
                // 输出中间页
                for($pn = $pagination_left; $pn <= $pagination_right; $pn++) {
                    if($pn == $page) {
                        echo '<li class="active"><a href="#">' . $pn .'</a></li>';                            
                    } else {
                        echo '<li><a href="'. $list_page_url .'?controller=user&page=' . $pn . '&page_size=' . $page_size .'">' . $pn .'</a></li>';                            
                    }
                }

                // 输出下一页
                if($page == $page_count) {
                    echo '<li class="disabled"><a href="#">»</a></li>';
                } else {
                    echo '<li><a href="' . $list_page_url . '?controller=user&page=' . $page_count . '&page_size=' . $page_size .'">»</a></li>';
                }
            ?>
        </ul>
		</div>
	</div>
</div>
<?php
	include_once 'footer.php';
?>
<script type="text/javascript">
	$(function() {
		$('#check_all').click(function() {
		  if(this.checked) {
	        checkAllUsers(true);  
	      } else {
	        checkAllUsers(false);
	      }
		});
	});

	function checkAllUsers(value) {
		$('input.user_id').each(function() {
			this.checked = value;
		});
	}

	function delete_users() {
		var uidArray = [];
		$('input.user_id').each(function() {
			if(this.checked) {
				uidArray.push(this.value);	
			}
		});

		var uidsStr = uidArray.join(',');
		if('' == uidsStr) {
	      alert('请选择要删除的用户');
	      return;
	    }

	    if(!confirm('确定要删除选择的用户么？')) {
	      return;
	    }

	    var params = {};
	    params.controller = 'user';
	    params.action = 'delete';
	    params.uids = uidsStr;

	    $.post('index.php', params, function(response) {
	      if(response.success) {
	        window.location = ''; // 刷新当前页面
	      }
	    }, 'json');
	}

	function searchUsers() {
		$('input[name="page"]', $('#search_btn').parent()).val(1);
	}
</script>