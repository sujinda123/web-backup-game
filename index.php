<?php session_start();?>
<?php 
if ($_SESSION["Role"]){  //check session
  echo $_SESSION["Role"];
    if($_SESSION["Role"]=="ADMIN" || $_SESSION["Role"]=="MANAGER"){ //ถ้าเป็น admin ให้กระโดดไปหน้า admin_page.php
        Header("Location: admin_page.php");
    }
    else if ($_SESSION["Role"]=="USER"){  //ถ้าเป็น user ให้กระโดดไปหน้า user_page.php
        Header("Location: user_page.php");
    }
}else{?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- load MUI -->
    <title>ระบบจัดเก็บข้อมูลการใช้ไฟฟ้า</title>
    <link href="//cdn.muicss.com/mui-0.10.3/css/mui.min.css" rel="stylesheet" type="text/css" />
    <script src="//cdn.muicss.com/mui-0.10.3/js/mui.min.js"></script>
  </head>
  <body>
    <!-- example content -->
    <div class="mui-container" style="display: table;">
      <div class="mui-panel" style="width: 500px; margin-top: 50px;">
        <h1 class="mui--text-center">ระบบจัดเก็บข้อมูลการใช้ไฟฟ้ามหาวิทยาลัยราชมงคลล้านนา พิษณุโลก</h1>
        <form class="mui-form" name="frmlogin"  method="post" action="login.php">
            <div class="mui-textfield mui-textfield--float-label">
                <input type="text" id="Username" required name="Username">
                <label>Username</label>
            </div>
            <div class="mui-textfield mui-textfield--float-label">
                <input type="password" id="Password" required name="Password">
                <label>Password</label>
            </div>
            <div class="mui--text-center">
                <button type="submit" class="mui-btn mui-btn--primary mui-btn--raised">Login</button>
            </div>
        </form>
      </div>
    </div>
  </body>
</html>
<?php }?>