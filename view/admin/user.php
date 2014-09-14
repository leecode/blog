<?php
	include_once 'header.php';
	include_once 'menu.php';
?>
<style type="text/css">
	.user-form .form-group {
		width: 50%;
	}

	p.description {
		margin: .5em 0 0;
	    color: #999;
	    font-size: .92857em;
	}

	label.required:after {
		content: " *";
		color: #C00;
	}
</style>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 col-md-9 col-md-offset-3">
				<form class="form-horizontal user-form" action="index.php" method="post">
					<input type="hidden" name="controller" value="user" />
					<input type="hidden" name="action" value="add" />
					<input type="hidden" name="uid" value="<?php echo $user['uid'];?>" />
					<div class="form-group">
						<label class="required">用户名</label>
						<input type="text" name="name" class="form-control" value="<?php echo $user['name'];?>"/>
						<p class="description">此用户名将作为用户登录时所用的名称.<br/>请不要与系统中现有的用户名重复.</p>
					</div>
					<div class="form-group">
						<label class="required">电子邮箱地址</label>
						<input type="text" name="mail" class="form-control" value="<?php echo $user['mail'];?>" />
						<p class="description">
							电子邮箱地址将作为此用户的主要联系方式.<br>
							请不要与系统中现有的电子邮箱地址重复.
						</p>
					</div>
					<div class="form-group">
						<label>用户昵称</label>
						<input type="text" name="screenName" class="form-control" value="<?php echo $user['screenName'];?>">
						<p class="description">
							用户昵称可以与用户名不同, 用于前台显示.<br/>如果你将此项留空, 将默认使用用户名.
						</p>
					</div>
					<div class="form-group">
						<label class="required">用户密码</label>
						<input type="password" name="password" class="form-control">
						<p class="description">
							为此用户分配一个密码.<br/>建议使用特殊字符与字母的混编样式,以增加系统安全性.
						</p>
					</div>
					<div class="form-group">
						<label class="required">确认密码</label>
						<input type="password" name="confirm" class="form-control">
						<p class="description">
							请确认你的密码, 与上面输入的密码保持一致.
						</p>
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-primary" value="增加用户">
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php
		include_once 'footer.php';
	?>
</body>
</html>