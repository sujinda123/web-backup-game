<?php session_start();?>
<?php 

if (!$_SESSION["UserID"]){  //check session
        session_destroy();
	    Header("Location: index.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 
}else{

    $month_arr=array(
		"1"=>"มกราคม",
		"2"=>"กุมภาพันธ์",
		"3"=>"มีนาคม",
		"4"=>"เมษายน",
		"5"=>"พฤษภาคม",
		"6"=>"มิถุนายน", 
		"7"=>"กรกฎาคม",
		"8"=>"สิงหาคม",
		"9"=>"กันยายน",
		"10"=>"ตุลาคม",
		"11"=>"พฤศจิกายน",
		"12"=>"ธันวาคม"                 
	);

    include("connection.php");
    $building_id = $_GET['building_id'];
    $building_name = $_GET['building_name'];
    
    if($building_id == null || $building_name == null){
	    Header("Location: index.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 
    }

    if(isset($_POST["year_select"])){
        $year_select = $_POST["year_select"];
        // $month_select = $_POST["month_select"];
        if($year_select != 0 && $month_select != 0){
            $per_year = $_POST['per_year'];
            if($per_year == $year_select){
                // $sql_month_list = "SELECT MONTH(date) as month FROM data_power WHERE building_id = ".$building_id." AND YEAR(DATE) = ".$year_select." AND  MONTH(DATE) = ".$month_select." GROUP BY month ORDER BY data_power.id DESC";
                $sql_power_list = "SELECT MONTH(date) as month, year(date) as year, SUM(unit_forenoon) AS sum_unit_forenoon, SUM(unit_afternoon) AS sum_unit_afternoon FROM data_power WHERE building_id = ".$building_id." AND YEAR(DATE) = ".$year_select." GROUP BY YEAR, month ORDER BY YEAR DESC, MONTH DESC";
            }else{
                // $sql_month_list = "SELECT MONTH(date) as month FROM data_power WHERE building_id = ".$building_id." AND YEAR(DATE) = ".$year_select." GROUP BY month ORDER BY data_power.id DESC";
                $sql_power_list = "SELECT MONTH(date) as month, year(date) as year, SUM(unit_forenoon) AS sum_unit_forenoon, SUM(unit_afternoon) AS sum_unit_afternoon FROM data_power WHERE building_id = ".$building_id." AND YEAR(DATE) = ".$year_select." GROUP BY YEAR, month ORDER BY YEAR DESC, MONTH DESC";
            }
        }else if($year_select != 0){
            // $sql_month_list = "SELECT MONTH(date) as month FROM data_power WHERE building_id = ".$building_id." AND YEAR(DATE) = ".$year_select." GROUP BY month ORDER BY data_power.id DESC";
            $sql_power_list = "SELECT MONTH(date) as month, year(date) as year, SUM(unit_forenoon) AS sum_unit_forenoon, SUM(unit_afternoon) AS sum_unit_afternoon FROM data_power WHERE building_id = ".$building_id." AND YEAR(DATE) = ".$year_select." GROUP BY YEAR, month ORDER BY YEAR DESC, MONTH DESC";
        // }else if($month_select != 0){
            // $sql_month_list = "SELECT MONTH(date) as month FROM data_power WHERE building_id = ".$building_id." GROUP BY month ORDER BY data_power.id DESC";
            // $sql_power_list = "SELECT * FROM data_power WHERE building_id = ".$building_id." AND MONTH(DATE) = ".$month_select." ORDER BY data_power.id DESC";
        }else{
            // $sql_month_list = "SELECT MONTH(date) as month FROM data_power WHERE building_id = ".$building_id." GROUP BY month ORDER BY data_power.id DESC";
            $sql_power_list = "SELECT MONTH(date) as month, year(date) as year, SUM(unit_forenoon) AS sum_unit_forenoon, SUM(unit_afternoon) AS sum_unit_afternoon FROM data_power WHERE building_id = ".$building_id." GROUP BY YEAR, month ORDER BY YEAR DESC, MONTH DESC";
        }

    }else{
        // $sql_month_list = "SELECT MONTH(date) as month FROM data_power WHERE building_id = ".$building_id." GROUP BY month ORDER BY data_power.id DESC";
        $sql_power_list = "SELECT MONTH(date) as month, year(date) as year, SUM(unit_forenoon) AS sum_unit_forenoon, SUM(unit_afternoon) AS sum_unit_afternoon FROM data_power WHERE building_id = ".$building_id." GROUP BY YEAR, month ORDER BY YEAR DESC, MONTH DESC";
    }

    $result_power_list = mysqli_query($con,$sql_power_list);

    $sql_year_list = "SELECT YEAR(date) as year FROM data_power WHERE building_id = ".$building_id." GROUP BY YEAR, month ORDER BY YEAR DESC, MONTH DESC";
    $result_year_list = mysqli_query($con,$sql_year_list);

    // $result_month_list = mysqli_query($con,$sql_month_list);

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
            <button type="button" class='btn btn-primary' onclick="location.href = 'admin_send_line_report.php';">
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

                        </form>
                    </div>
        </div>
        <br />
        <br />
        <h4 style="text-align: center;"><?=$_GET[building_name]?></h4>
        <hr />
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">เดือน/ปี</th>
                    <th scope="col">หน่วยการใช้ไฟช่วงเช้าทั้งหมด</th>
                    <th scope="col">หน่วยการใช้ไฟช่วงบ่ายทั้งหมด</th>
                    <th scope="col">จำนวนใช้ไฟทั้งเดือน</th>
                <?php if($_SESSION["Role"] == 'ADMIN'){ ?>
                    <th scope="col">Action</th>
                <?php }?>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($result_power_list as $power) {
                ?>
                <tr>
                    <th scope='row'><?=$power[month]?>/<?=$power[year]?></th>
                    <td><?=$power[sum_unit_forenoon]?></td>
                    <td><?=$power[sum_unit_afternoon]?></td>
                    <td><?php echo ($power[sum_unit_afternoon] - $power[sum_unit_forenoon]);?></td>
                <?php if($_SESSION["Role"] == 'ADMIN'){ ?>
                    <td><button type='button'
                            onclick='SetSendLine(
                                "<?=$_GET[building_name]?>",
                                "<?=($power[sum_unit_afternoon] - $power[sum_unit_forenoon])?>", 
                                "<?=number_format((float)(($power[sum_unit_afternoon] - $power[sum_unit_forenoon])/2.3488), 2, ".", "")?>", 
                                "<?=$_GET[building_token]?>",
                                "<?=$power[month]?>",
                                "<?=$month_arr[(int)$power[month]]?>")'
                            class='btn btn-success' data-bs-toggle='modal'
                            data-bs-target='#ModalSendLine'>ส่ง</button></td>
                <?php }?>
                </tr>
                <?php
                        }
                    ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Edit Power-->
    <div class="modal fade" id="ModalSendLine" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">หน้ายืนยันการส่ง</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="mui-form" name="frmdeletepower" method="post" action="send_line_report.php">
                    <input type="hidden" class="form-control" id="line_token" name="line_token" value="" />
                    <input type="hidden" class="form-control" id="send_building_name" name="send_building_name" value="" />
                    <input type="hidden" class="form-control" id="send_month" name="send_month" value="" />
                    <input type="hidden" class="form-control" id="send_total_power" name="send_total_power" value="" />
                    <input type="hidden" class="form-control" id="send_total_money" name="send_total_money" value="" />
                    <div class="modal-body">
                    <h3 style="color: #444444; text-align: center;">ค่าไฟฟ้า</h3>
                    <h4 style="color: #444444; text-align: center;"><t id="text_building_name"></t></h4>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row">วัน/เดือน/ปี ที่ส่ง</th>
                                    <td>
                                        <?=date("d/m/Y")?>
                                    </td>

                                </tr>
                                <tr>
                                    <th scope="row">หน่วยที่ใช้ในเดือน <t id="text_month"></t></th>
                                    <td>
                                        <t id="text_total_power"></t> หน่วย
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">จำนวนเงินทั้งหมด</th>
                                    <td>
                                        <t id="text_total_money"></t> บาท
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary">ยืนยันการส่ง</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function SetSendLine(building_name, total_power, total_money, line_token, send_month, text_month) {
        document.getElementById('text_building_name').innerText = building_name;
        document.getElementById('text_month').innerText = text_month;
        document.getElementById('text_total_power').innerText = total_power;
        document.getElementById('text_total_money').innerText = total_money;

        document.getElementById('send_building_name').value = building_name;
        document.getElementById('send_month').value = send_month;
        document.getElementById('send_total_power').value = total_power;
        document.getElementById('send_total_money').value = total_money;

        document.getElementById('line_token').value = line_token;
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