<?php 
session_start();
    if(isset($_POST['edit_username'])){
            //connection
                include("connection.php");
            //รับค่า user & password
                $edit_user_id = $_POST['edit_user_id'];
                $edit_username = trim($_POST['edit_username']);
                // $password = md5($_POST['password']);
                $edit_firstname = trim($_POST['edit_firstname']);
                $edit_lastname = trim($_POST['edit_lastname']);
                $edit_role_id = $_POST['edit_role_id'];
                // $edit_building_id = $_POST['edit_building_id'];

                $sql = 'UPDATE users
                SET username = "'.$edit_username.'", firstname = "'.$edit_firstname.'", lastname = "'.$edit_lastname.'", role = "'.$edit_role_id.'"
                WHERE user_id = '.$edit_user_id;

                $result = mysqli_query($con,$sql);

                if ($result === TRUE) {
                    Header("Location: user_management_page.php");
                  } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                  }

    }else{


            Header("Location: index.php"); //user & password incorrect back to login again

    }
?>