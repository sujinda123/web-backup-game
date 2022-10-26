<?php session_start();?>
<?php 

if (!$_SESSION["UserID"]){  //check session
        session_destroy();
	    Header("Location: index.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 
}else{

    include("connection.php");
    $building_id = $_GET['building_id'];
    $building_name = $_GET['building_name'];
    
    if($building_id == null || $building_name == null){
	    Header("Location: index.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 
    }

    if(isset($_POST["year_select"]) && isset($_POST["month_select"])){
        $year_select = $_POST["year_select"];
        $month_select = $_POST["month_select"];
        if($year_select != 0 && $month_select != 0){
            $per_year = $_POST['per_year'];
            if($per_year == $year_select){
                $sql_month_list = "SELECT MONTH(date) as month FROM data_power WHERE building_id = ".$building_id." AND YEAR(DATE) = ".$year_select." AND  MONTH(DATE) = ".$month_select." GROUP BY month ORDER BY data_power.id DESC";
                $sql_power_list = "SELECT * FROM data_power WHERE building_id = ".$building_id." AND YEAR(DATE) = ".$year_select." AND  MONTH(DATE) = ".$month_select." ORDER BY data_power.id DESC";
            }else{
                $sql_month_list = "SELECT MONTH(date) as month FROM data_power WHERE building_id = ".$building_id." AND YEAR(DATE) = ".$year_select." GROUP BY month ORDER BY data_power.id DESC";
                $sql_power_list = "SELECT * FROM data_power WHERE building_id = ".$building_id." AND YEAR(DATE) = ".$year_select." ORDER BY data_power.id DESC";
            }
        }else if($year_select != 0){
            $sql_month_list = "SELECT MONTH(date) as month FROM data_power WHERE building_id = ".$building_id." AND YEAR(DATE) = ".$year_select." GROUP BY month ORDER BY data_power.id DESC";
            $sql_power_list = "SELECT * FROM data_power WHERE building_id = ".$building_id." AND YEAR(DATE) = ".$year_select." ORDER BY data_power.id DESC";
        }else if($month_select != 0){
            $sql_month_list = "SELECT MONTH(date) as month FROM data_power WHERE building_id = ".$building_id." GROUP BY month ORDER BY data_power.id DESC";
            $sql_power_list = "SELECT * FROM data_power WHERE building_id = ".$building_id." AND MONTH(DATE) = ".$month_select." ORDER BY data_power.id DESC";
        }else{
            $sql_month_list = "SELECT MONTH(date) as month FROM data_power WHERE building_id = ".$building_id." GROUP BY month ORDER BY data_power.id DESC";
            $sql_power_list = "SELECT * FROM data_power WHERE building_id = ".$building_id." ORDER BY data_power.id DESC";
        }

    }else{
        $sql_month_list = "SELECT MONTH(date) as month FROM data_power WHERE building_id = ".$building_id." GROUP BY month ORDER BY data_power.id DESC";
        $sql_power_list = "SELECT * FROM data_power WHERE building_id = ".$building_id." ORDER BY data_power.id DESC";
    }

    $result_power_list = mysqli_query($con,$sql_power_list);

    $sql_year_list = "SELECT YEAR(date) as year FROM data_power WHERE building_id = ".$building_id." GROUP BY year ORDER BY data_power.id DESC";
    $result_year_list = mysqli_query($con,$sql_year_list);

    $result_month_list = mysqli_query($con,$sql_month_list);

    // SELECT * FROM data_power WHERE building_id = 1 and MONTH(date) = 09 AND YEAR(date) = 2022 ORDER BY data_power.id DESC 
?>

<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Admin page</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body>
    <?php include("admin_navbar.php");?>
    <div class="container-lg mt-5">
        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
            <button type="button" class='btn btn-primary' onclick="location.href = 'index.php';">
                < ย้อนกลับ</button>
                    <div class="input-group">
                        <form method="POST" action="">
                            <input type="hidden" class="form-control" id="per_year" name="per_year" value="<?=$year_select?>" />
                            ปี :
                            <select name="year_select" onchange="this.form.submit()">
                                <option value="0">--ทั้งหมด--</option>
                                <?php
                                    foreach ($result_year_list as $year) {
                                        if($year_select == $year['year']){
                                            echo '<option value="'.$year['year'].'" selected>'.$year['year'].'</option>'; 
                                        }else{
                                            echo '<option value="'.$year['year'].'">'.$year['year'].'</option>'; 
                                        }
                                    }
                                ?>
                            </select>

                            เดือน :
                            <select name="month_select" onchange="this.form.submit()">
                                <option value="0">--ทั้งหมด--</option>
                                <?php
                                    foreach ($result_month_list as $month) {
                                        if($month_select == $month['month']){
                                            echo '<option value="'.$month['month'].'" selected>'.$month['month'].'</option>'; 
                                        }else{
                                            echo '<option value="'.$month['month'].'">'.$month['month'].'</option>'; 
                                        }
                                    }
                                ?>
                            </select>
                        </form>
                    </div>
        </div>
        <br />
        <br />
        <h4 style="text-align: center;"><?=$_GET['building_name']?></h4>
        <hr />
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">ว/ด/ป</th>
                    <th scope="col">ช่วงเช้าเวลา</th>
                    <th scope="col">ช่วงบ่ายเวลา</th>
                    <th scope="col">หน่วยการใช้ไฟช่วงเช้า</th>
                    <th scope="col">หน่วยการใช้ไฟช่วงบ่าย</th>
                    <th scope="col">จำนวนใช้ไฟวันนี้</th>
                <?php if($_SESSION["Role"] == 'ADMIN'){ ?>
                    <th scope="col">แก้ไข</th>
                <?php }?>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($result_power_list as $power) {
                ?>
                <tr>
                    <th scope='row'><?=date("d/m/Y", strtotime($power[date]))?></th>
                    <td><?=$power[forenoon_time]?></td>
                    <td><?=$power[afternoon_time]?></td>
                    <td><?=$power[unit_forenoon]?></td>
                    <td><?=$power[unit_afternoon]?></td>
                    <td><?php echo ($power[unit_afternoon] - $power[unit_forenoon]);?></td>
                <?php if($_SESSION["Role"] == 'ADMIN'){ ?>
                    <td><button type='button'
                            onclick='SetEditPower("<?=$power[unit_forenoon]?>", "<?=$power[unit_afternoon]?>", "<?=$power[id]?>")'
                            class='btn btn-warning' data-bs-toggle='modal'
                            data-bs-target='#ModalEditPower'>Edit</button></td>
                <?php }?>
                </tr>
                <?php
                        }
                    ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Edit Power-->
    <div class="modal fade" id="ModalEditPower" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">หน้าแก้ไขผู้ใช้งาน</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="mui-form" name="frmdeletepower" method="post" action="save_edit_power.php">
                    <input type="hidden" class="form-control" id="edit_power_id" name="edit_power_id" value="" />
                    <div class="modal-body">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row">หน่วยการใช้ไฟช่วงเช้า</th>
                                    <td><input type="text" class="form-control" id="edit_unit_forenoon"
                                            name="edit_unit_forenoon" value="" />
                                    </td>

                                </tr>
                                <tr>
                                    <th scope="row">หน่วยการใช้ไฟช่วงบ่าย</th>
                                    <td><input type="text" class="form-control" id="edit_unit_afternoon"
                                            name="edit_unit_afternoon" value="" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function SetEditPower(unit_forenoon, unit_afternoon, power_id) {
        document.getElementById('edit_unit_forenoon').value = unit_forenoon;
        document.getElementById('edit_unit_afternoon').value = unit_afternoon;
        document.getElementById('edit_power_id').value = power_id;
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>

    <!-- <p><strong>hi</strong> :&nbsp;<?php print_r($_SESSION);?> //show detail login
        <?php //session_destroy();?>
    </p> -->

</body>

</html>
<?php }?>