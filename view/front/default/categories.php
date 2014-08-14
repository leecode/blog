<div class="sidebar-module">
  <h4>分类</h4>
  <ol class="list-unstyled">
    <?php
      foreach ($categories as $cate) {
    ?>
      <li><a href="index.php?category=<?php echo $cate['mid'];?>"><?php echo $cate['name'];?></a>(<?php echo $cate['count'];?>)</li>
    <?php 
      }
    ?>
  </ol>
</div>