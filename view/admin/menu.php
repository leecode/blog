<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">MeBlog</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">撰写<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="./index.php?controller=contents&action=write_post">文章</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">管理 <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="./index.php?controller=contents&action=show">文章</a></li>
                <li><a href="./index.php?controller=comments">评论</a></li>
                <li><a href="./index.php?controller=metas&action=show">分类和标签</a></li>
              </ul>
            </li>
            <li><a href="#">设置</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
</div>