<?php 
session_start();
    if(isset($_POST['username'])){
            //connection
                include("connection.php");
            //รับค่า user & password
                $username = trim($_POST['username']);
                $password = md5($_POST['password']);
                $firstname = trim($_POST['firstname']);
                $lastname = trim($_POST['lastname']);
                $role = $_POST['role'];
                // $building = $_POST['building'];
                //query 
                $sql = 'INSERT INTO users (username, password, firstname, lastname, role) 
                VALUES ("'.$username.'", "'.$password.'", "'.$firstname.'", "'.$lastname.'", "'.$role.'")';

                $result = mysqli_query($con,$sql);

                if ($result === TRUE) {
                    Header("Location: user_management_page.php");
                  } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                  }
                // if(mysqli_num_rows($result)==1){

                //     $row = mysqli_fetch_array($result);

                //     $_SESSION["UserID"] = $row["user_id"];
                //     $_SESSION["User"] = $row["firstname"]." ".$row["lastname"];
                //     $_SESSION["Role"] = $row["role"];

                //     if($_SESSION["Role"]=="ADMIN"){ //ถ้าเป็น admin ให้กระโดดไปหน้า admin_page.php

                //     Header("Location: admin_page.php");

                //     }

                //     if ($_SESSION["Role"]=="USER"){  //ถ้าเป็น user ให้กระโดดไปหน้า user_page.php

                //     Header("Location: user_page.php");

                //     }

                // }else{
                // echo "<script>";
                //     echo "alert(\" user หรือ  password ไม่ถูกต้อง\");"; 
                //     echo "window.history.back()";
                // echo "</script>";

                // }

    }else{


            Header("Location: index.php"); //user & password incorrect back to login again

    }
?>