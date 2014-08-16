blog
====
一个简单的博客程序，底层MVC框架受网上的一篇<a href="http://www.cnblogs.com/shishd/p/3532922.html">博文</a>启发（或者说基本完全是照抄）。
目前还有很多问题。
Basic:
====
<ul>
<li>1.所有的请求都会经由根目录下的index.php来进行处理和转发（参见.htaccess）。</li>
<li>2.system/lib/lib_router负责对请求的路由和参数设置。</li>
<li>3.TBC...</li>
</ul>

Basic Operation :
====
1.文章
<ul>
  <li>增加：admin/index.php?controller=contents&action=write_post</li>
  <li>编辑：admin/index.php?controller=contents&action=write_post&cid=xxx</li>
  <li>列表页面: admin/index.php?controller=contents&action=show</li>
  </ul>
2.TBC...


