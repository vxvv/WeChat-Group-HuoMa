<!DOCTYPE html>
<html>
<head>
  <title>活码管理系统 - 创建渠道码</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
  <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <link rel="icon" href="../images/xiaotubiao.png" type="image/x-icon" />
  <style type="text/css">
    .modal .modal-dialog .modal-content .modal-body .btn{
      position: relative;
    }

    .modal .modal-dialog .modal-content .modal-body .file_btn{
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
    }
  </style>
</head>
<body style="background:#fff;">
<div class="container">
  <h2>活码管理系统 - 管理渠道码</h2>
  <p>什么是渠道码？就是一个二维码，相比于其他二维码，渠道码可以统计访问次数，可以创建不同的渠道码，用于投放在不同的场所，可以统计不同渠道的效果，也可以在不变更二维码的前提下修改文本或跳转的链接，可以理解为文本活码和网址活码。</p>
  <!-- Nav pills -->
  <ul class="nav nav-pills" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="pill" href="#home">管理渠道码</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="index.php">返回首页</a>
    </li>
  </ul>

    <!-- Tab panes -->
  <div class="tab-content">
    <div id="home" class="tab-pane active"><br>
      <?php
      header("Content-type:text/html;charset=utf-8");
      session_start();
      if(isset($_SESSION["huoma.admin"])){
        // 已登录
        $lguser = $_SESSION["huoma.admin"];
        // 数据库配置
        include '../MySql.php';

        // 创建连接
        $conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
        
        // 检查连接
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
        } 

        //计算总渠道码数量
        $sql_qudao = "SELECT * FROM qun_huoma_qudao";
        $result_qudao = $conn->query($sql_qudao);
        $allqudao_num = $result_qudao->num_rows;

        //每页显示的渠道码数量
        $lenght = 5;

        //当前页码
        @$page = $_GET['page']?$_GET['page']:1;

        //每页第一行
        $offset = ($page-1)*$lenght;

        //总数页
        $allpage = ceil($allqudao_num/$lenght);

        //上一页     
        $prepage = $page-1;
        if($page==1){
          $prepage=1;
        }

        //下一页
        $nextpage = $page+1;
        if($page==$allpage){
          $nextpage=$allpage;
        }
         
        $sql = "SELECT * FROM qun_huoma_qudao ORDER BY ID DESC limit {$offset},{$lenght}";
        $result = $conn->query($sql);
         
        if ($result->num_rows > 0) {
            // 输出数据
            while($row = $result->fetch_assoc()) {

            $id  = $row["id"];
            $qudao_id  = $row["qudao_id"];
            $qudao_adduser  = $row["qudao_adduser"];
            $qudao_title  = $row["qudao_title"];
            $qudao_yuming  = $row["qudao_yuming"];
            $qudao_type  = $row["qudao_type"];
            $qudao_content  = $row["qudao_content"];
            $qudao_biaoqian  = $row["qudao_biaoqian"];
            $qudao_pageview  = $row["qudao_pageview"];
            $qudao_update_time  = $row["qudao_update_time"];

          echo '<div class="card" style="margin-bottom:15px;">
              <div class="card-body">
                <h4 class="card-title">'.$qudao_title.'</h4>
                <a href="edi_qudao.php?qudaoid='.$qudao_id.'" class="card-link" style="color:#333;">编辑</a>
                <a href="#" class="card-link" data-toggle="modal" data-target="#del-qudao" id="'.$qudao_id.'" onclick="get_qudao_delid(this);" style="outline:none;color:#333;">删除</a>
                <a href="#" class="card-link" data-toggle="modal" data-target="#share-huoma" id="'.$qudao_id.'" onclick="sharequdaoma(this);" style="outline:none;color:#333;">分享</a>
                <span class="badge badge-secondary" style="float: right;">访问量：'.$qudao_pageview.'</span>
                <span class="badge badge-secondary" style="float: right;margin-right:10px;">'.$qudao_update_time.'</span>
                <span class="badge badge-warning" style="float: right;margin-right:10px;">'.$qudao_biaoqian.'</span>
                <span class="badge badge-warning" style="float: right;margin-right:10px;">账号:'.$qudao_adduser.'</span>';
              echo "</div>";
            echo "</div>";
            }
            echo "<ul class=\"pagination\">";
              if ($page == 1) {
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"javascript:;\" style=\"color:#333;font-size:14px;\">当前是第".$page."页</a></li>";
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"qudao.php?page=".$nextpage."\" style=\"color:#333;font-size:14px;\">下一页</a></li>";
              }else if ($page == $allpage) {
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"qudao.php?page=".$prepage."\" style=\"color:#333;font-size:14px;\">上一页</a></li>";
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"javascript:;\" style=\"color:#333;font-size:14px;\">当前是第".$page."页，已经是最后一页</a></li>";
              }else{
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"qudao.php?page=".$prepage."\" style=\"color:#333;font-size:14px;\">上一页</a></li>";
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"javascript:;\" style=\"color:#333;font-size:14px;\">当前是第".$page."页</a></li>";
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"qudao.php?page=".$nextpage."\" style=\"color:#333;font-size:14px;\">下一页</a></li>";
              }
            echo "</ul>";
        } else {
            echo "暂无用户创建渠道码，你也可以前往<a href='../user/qudao.php'>用户后台</a>创建";
        }
        $conn->close();
      }else{
        // 未登录
        echo "<script>location.href='login.php';</script>";
      }
    ?>

    </div>
  </div>
  <!-- Result -->
  <div class="Result" style="margin-top: 30px;display: none;"></div>
</div>


<!-- 删除模态框 -->
<div class="modal fade" id="del-qudao">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- 模态框头部 -->
      <div class="modal-header">
        <h4 class="modal-title">删除渠道码</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
   
      <!-- 模态框主体内容 -->
      <div class="modal-body">确定要删除吗？</div>
   
      <!-- 模态框底部 -->
      <div class="modal-footer"></div>

    </div>
  </div>
</div>

<!-- 分享模态框 -->
<div class="modal fade" id="share-huoma">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- 模态框头部 -->
      <div class="modal-header">
        <h4 class="modal-title">分享渠道码</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
   
      <!-- 模态框主体内容 -->
      <div class="modal-body"></div>
   
      <!-- 模态框底部 -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
      </div>
 
    </div>
  </div>
</div>

<script>
 //删除渠道码
  function get_qudao_delid(event){
    var qudaodelid = event.id;
      $(".modal .modal-dialog .modal-footer").html("<button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\" id="+qudaodelid+" onclick=\"delqudaoma(this);\">确定删除</button>");
  }

  function delqudaoma(event){
    var delete_qudaoid = event.id;
    $.ajax({
        type: "GET",
        url: "del_qudao.php?qudaoid="+delete_qudaoid,
        success: function (data) {
          if (data.result == "101") {
            alert(data.msg);
          }else if (data.result == "102") {
            alert(data.msg);
          }else if (data.result == "100") {
            location.reload();
          }else{
            alert("未知错误");
          }
        },
        error : function() {
          alert("error");
        }
    });
  }

  // 分享
  function sharequdaoma(event){
      var sharequdaoid = event.id;
      $.ajax({
          type: "GET",
          url: "share_qudao.php?qudaoid="+sharequdaoid,
          success: function (data) {
            if (data.result == "101") {
              alert(data.msg);
            }else if (data.result == "102") {
              alert(data.msg);
            }else if (data.result == "100") {
              $("#share-huoma .modal-dialog .modal-body").html("链接："+data.url+"<br/><img src='../qrcode.php?content="+data.url+"' width='200'/>");
            }else{
              alert("未知错误");
            }
          },
          error : function() {
            alert("渠道码分享出错，请检查服务器");
          }
      });
  }
</script>
</body>
</html>
