<?php 
session_start();
    if(isset($_POST['add_building_user_id'])){
            //connection
                include("connection.php");
            //รับค่า user & password
                $add_building_user_id = $_POST['add_building_user_id'];
                $add_building_id = $_POST['add_building_id'];

                $sql_check = "SELECT * FROM data_user_building WHERE user_id = ".$add_building_user_id." and building_id = ".$add_building_id;
                $result_check = mysqli_query($con,$sql_check);
                $Checker = mysqli_num_rows($result_check);
                if  ($Checker != 0) {
                    echo "<script>";
                        echo "alert(\"มีตึกอยู่แล้ว\");"; 
                        echo "window.history.back()";
                    echo "</script>";
                } else{
                    $sql = 'INSERT INTO data_user_building (user_id, building_id)
                    VALUES ('.$add_building_user_id.', '.$add_building_id.');';
    
                    $result = mysqli_query($con,$sql);
    
                    if ($result === TRUE) {
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