<?php 
session_start();
    if(isset($_POST['edit_admin_username'])){
            //connection
                include("connection.php");
            //รับค่า user & password
                $edit_user_id = $_POST['edit_admin_user_id'];
                $edit_username = trim($_POST['edit_admin_username']);
                $edit_firstname = trim($_POST['edit_admin_firstname']);
                $edit_lastname = trim($_POST['edit_admin_lastname']);

                if($_POST['edit_admin_password'] != ""){
                    if($_POST['edit_admin_password'] != $_POST['edit_admin_confirm_password']){
                        echo "<script>";
                            echo "alert(\"Password ไม่ตรงกัน\");"; 
                            echo "window.history.back()";
                        echo "</script>";
                    }
                    $edit_password = md5($_POST['edit_admin_password']);
                    
                    $sql = 'UPDATE users
                    SET username = "'.$edit_username.'", password = "'.$edit_password.'", firstname = "'.$edit_firstname.'", lastname = "'.$edit_lastname.'"
                    WHERE user_id = '.$edit_user_id;
    
                    $result = mysqli_query($con,$sql);
    
                    if ($result === TRUE) {
                        $_SESSION["Username"] = $edit_username;
                        $_SESSION["Firstname"] = $edit_firstname;
                        $_SESSION["Lastname"] = $edit_lastname;
                        echo "<script>";
                            echo "window.history.back()";
                        echo "</script>";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }else{
                    $sql = 'UPDATE users
                    SET username = "'.$edit_username.'", firstname = "'.$edit_firstname.'", lastname = "'.$edit_lastname.'"
                    WHERE user_id = '.$edit_user_id;
    
                    $result = mysqli_query($con,$sql);
    
                    if ($result === TRUE) {
                        $_SESSION["Username"] = $edit_username;
                        $_SESSION["Firstname"] = $edit_firstname;
                        $_SESSION["Lastname"] = $edit_lastname;
                        echo "<script>";
                            echo "window.history.back()";
                        echo "</script>";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }
                

    }else{


            Header("Location: index.php"); //user & password incorrect back to login again

    }
?>