<?php 
session_start();
        if(isset($_POST['Username'])){
				//connection
                  include("connection.php");
				//รับค่า user & password
                  $Username = $_POST['Username'];
                  $Password = md5($_POST['Password']);
				//query 
                  $sql="SELECT users.*, data_role.name AS role_name FROM users JOIN data_role ON data_role.id = users.role Where username='".$Username."' and password='".$Password."' ";

                  $result = mysqli_query($con,$sql);
				
                  if(mysqli_num_rows($result)==1){

                      $row = mysqli_fetch_array($result);

                      $_SESSION["UserID"] = $row["user_id"];
                      $_SESSION["Username"] = $Username;
                      $_SESSION["Firstname"] = $row["firstname"];
                      $_SESSION["Lastname"] = $row["lastname"];
                      $_SESSION["Role"] = $row["role_name"];

                      if($_SESSION["Role"]=="ADMIN" || $_SESSION["Role"]=="MANAGER" ){ //ถ้าเป็น admin ให้กระโดดไปหน้า admin_page.php

                        Header("Location: admin_page.php");

                      }

                      if ($_SESSION["Role"]=="USER"){  //ถ้าเป็น user ให้กระโดดไปหน้า user_page.php

                        Header("Location: user_page.php");

                      }

                  }else{
                    echo "<script>";
                        echo "alert(\" user หรือ  password ไม่ถูกต้อง\");"; 
                        echo "window.history.back()";
                    echo "</script>";

                  }

        }else{


             Header("Location: index.php"); //user & password incorrect back to login again

        }
?>