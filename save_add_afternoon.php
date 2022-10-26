<?php 
session_start();
    if(isset($_POST['edit_afternoon_power_id'])){
            //connection
                include("connection.php");
            //รับค่า user & password
                $edit_power_id = trim($_POST['edit_afternoon_power_id']);
                $afternoon_time = trim($_POST['edit_time_afternoon']);
                $unit_afternoon = floatval($_POST['edit_unit_afternoon']);

                $sql = 'UPDATE data_power
                SET afternoon_time = "'.$afternoon_time.'", unit_afternoon = '.$unit_afternoon.'
                WHERE id = '.$edit_power_id;

                $result = mysqli_query($con,$sql);

                if ($result === TRUE) {
                    Header("Location: user_page.php");
                } else {
                    echo "Error: " . $sql . "<br>" . $con->error;
                }

    }else{


            Header("Location: index.php"); //user & password incorrect back to login again

    }
?>