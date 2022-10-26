<?php 
session_start();
    if(isset($_POST['delete_building_user_id'])){
            //connection
                include("connection.php");
            //รับค่า user & password
                $delete_building_user_id = $_POST['delete_building_user_id'];
                $delete_building_id = $_POST['delete_building_id'];

                $sql = 'DELETE FROM data_user_building WHERE user_id = '.$delete_building_user_id.' and building_id = '.$delete_building_id;

                $result = mysqli_query($con,$sql);

                if ($result === TRUE) {
                    echo "<script>";
                        echo "window.history.back()";
                    echo "</script>";
                  } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                  }

    }else{


            Header("Location: index.php"); //user & password incorrect back to login again

    }
?>