<?php 
session_start();
    if(isset($_POST['edit_time_forenoon'])){
            //connection
                include("connection.php");
            //รับค่า user & password
            
                $edit_power_id = trim($_POST['edit_power_id']);
                $edit_building_id = trim($_POST['edit_building_id']);
                $add_date = date("Y-m-d");
                $forenoon_time = trim($_POST['edit_time_forenoon']);
                $unit_forenoon = floatval(trim($_POST['edit_unit_forenoon']));

                if($edit_power_id == ""){
                  $sql = 'INSERT INTO data_power (date, forenoon_time, afternoon_time, unit_forenoon, unit_afternoon, building_id, create_by) 
                  VALUES ("'.$add_date.'", "'.$forenoon_time.'", "00:00", "'.$unit_forenoon.'", 0.00, '.$edit_building_id.', '.$_SESSION["UserID"].')';
  
                  $result = mysqli_query($con,$sql);
  
                  if ($result === TRUE) {
                      Header("Location: user_page.php");
                  } else {
                      echo "Error: " . $sql . "<br>" . $con->error;
                  }
  
                }else{

                  $sql = 'UPDATE data_power
                  SET forenoon_time = "'.$forenoon_time.'", unit_forenoon = '.$unit_forenoon.'
                  WHERE id = '.$edit_power_id;
  
                  $result = mysqli_query($con,$sql);
  
                  if ($result === TRUE) {
                      Header("Location: user_page.php");
                  } else {
                      echo "Error: " . $sql . "<br>" . $con->error;
                  }
  
                }

    }else{


            Header("Location: index.php"); //user & password incorrect back to login again

    }
?>