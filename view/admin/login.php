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

      <form id="login-form" class="form-signin" action="#" method="POST">
        <input type="hidden" name="controller" value="login" />
        <input type="hidden" name="action" value="do_login" />
        <h2 class="form-signin-heading">登录</h2>
        <div>
          <input type="text" class="input-block-level form-control" name="username" placeholder="用户名" value="<?php echo $username;?>">
        </div>
        <div>
          <input type="password" class="input-block-level form-control" name="password" placeholder="密码" value="<?php echo $password;?>">
        </div>
        <label class="checkbox">
          <input type="checkbox" name="remember_me" value="remember-me"> 记住我
        </label>
        <button class="btn btn-large btn-primary" name="submit" type="button" id="login_btn">登录</button>
      </form>
    </div>
    <?php
      include_once 'footer.php';
    ?>
    <script type="text/javascript">
      $(function() {
        $('#login_btn').click(function() {
          clearErrorMsg('username');
          clearErrorMsg('password');

          var url = 'index.php';

          $.post(url, $('#login-form').serialize(), function(resoponse) {
            if(resoponse.success) {
              window.location = resoponse.url;
              return;
            } else {
              var elementName = resoponse.elementName;
              var errorMsg = resoponse.msg;

              showErrorMsg(elementName, errorMsg);
            }
          }, 'json');

          $('#login-form input').focus(function() {
            clearErrorMsg(this.name);
          });
        });

        function showErrorMsg(elemName, errorMsg) {
          var selector = 'input[name="' + elemName + '"]';
          var $parentDiv = $($(selector)[0]).parent();

          var msgElem = '<span for="' + elemName + '" class="help-block">' + errorMsg + '</span>';
          $parentDiv.append(msgElem);
          $parentDiv.addClass('has-error');
        }

        function clearErrorMsg(elemName) {
          var selector = 'input[name="' + elemName + '"]';
          var $parentDiv = $($(selector)[0]).parent();

          $($parentDiv.find('span')[0]).remove();
          $parentDiv.removeClass('has-error');
        }

      });
    </script>
  </body>