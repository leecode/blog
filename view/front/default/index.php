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

<!--       <div class="blog-header">
        <h1 class="blog-title">The Bootstrap Blog</h1>
        <p class="lead blog-description">The official example template of creating a blog with Bootstrap.</p>
      </div> -->

      <div class="row">

        <div class="col-sm-8 blog-main">
          <?php
            foreach($posts as $post_item) {
          ?>
          <article class="blog-post">
            <h2 class="blog-post-title"><?php echo $post_item['title'];?></h2>
            <ul class="blog-post-meta">
              <li><?php echo Commons::timeToDate($post_item['created']);?></li>
              <li>作者: Leecode</li>
              <li><a href="index.php?cid=<?php echo $post_item['cid'];?>&action=details">评论</a></li>
            </ul>
            <?php echo $post_item['text'];?>
          </article><!-- /.blog-post -->
          <?php
            }
          ?>
          <?php
            $url =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $page_url = substr($url, 0, strpos($url, '?'));

            if(empty($page) || 0 == $page) {
              $page = 1;
            }
          ?>
          <ul class="pager">
            <?php
              if(1 < $page) {
            ?>
            <li><a href="<?php echo 'index.php?page=' . ($page - 1);?>">Previous</a></li>
            <?php
              }
            ?>
            <?php
              if($page_count > $page) {
            ?>
            <li><a href="<?php echo 'index.php?page=' . ($page + 1);?>">Next</a></li>
            <?php
              }
            ?>
          </ul>

        </div><!-- /.blog-main -->

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

</body>
<?php
	include_once 'footer.php';
?>