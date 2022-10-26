<?php 
session_start();
    if(isset($_POST['edit_power_id'])){
            //connection
                include("connection.php");
            //รับค่า user & password
                $edit_power_id = $_POST['edit_power_id'];
                $edit_unit_forenoon = floatval(trim($_POST['edit_unit_forenoon']));
                $edit_unit_afternoon = floatval(trim($_POST['edit_unit_afternoon']));

                $sql = 'UPDATE data_power
                SET unit_forenoon = "'.$edit_unit_forenoon.'", unit_afternoon = "'.$edit_unit_afternoon.'"
                WHERE id = '.$edit_power_id;

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