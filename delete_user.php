<?php 
session_start();
    if(isset($_POST['delete_user_id'])){
            //connection
                include("connection.php");
            //รับค่า user & password
                $delete_user_id = $_POST['delete_user_id'];

                //query 
                $sql = 'DELETE FROM users WHERE user_id = '.$delete_user_id;

                $result = mysqli_query($con,$sql);

                if ($result === TRUE) {
                    Header("Location: user_management_page.php");
                  } else {
                    echo "Error: ".$con->error;
                  }

    }else{


            Header("Location: index.php"); //user & password incorrect back to login again

    }
?>