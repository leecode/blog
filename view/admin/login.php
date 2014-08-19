<?php
  include_once 'header.php';
?>
   <style type="text/css">
      /**
       *    设置登录表单的样式
       */
      .form-signin {
        max-width: 40%;
        padding: 19px 29px 29px;
        margin: 50px auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        display: block;
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
        width: 100%;
      }
    </style>
  <body>
     <div class="container">

      <form class="form-signin" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
        <input type="hidden" name="controller" value="login" />
        <input type="hidden" name="action" value="login" />
        <h2 class="form-signin-heading">登录</h2>
        <input type="text" class="input-block-level" name="username" placeholder="用户名" value="<?php echo $username;?>">
        <input type="password" class="input-block-level" name="password" placeholder="密码" value="<?php echo $password;?>">
        <label class="checkbox">
          <input type="checkbox" value="remember-me"> 记住我
        </label>
        <button class="btn btn-large btn-primary" name="submit" type="submit">登录</button>
      </form>
    </div>
    <?php
      include_once 'footer.php';
    ?>
  </body>