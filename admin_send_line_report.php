<?php session_start();?>
<?php 

if (!$_SESSION["UserID"]){  //check session
        session_destroy();
	    Header("Location: index.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

}else{

    include("connection.php");
    
    if(isset($_POST["search_building"])){
        $search_building = $_POST['search_building'];
        $sql_building_list = "SELECT * FROM data_building WHERE name LIKE '%".$search_building."%' ORDER BY data_building.id ASC";

    }else{
        $search_building = "";
        $sql_building_list = "SELECT * FROM data_building ORDER BY data_building.id ASC";
    }

    $result_building_list = mysqli_query($con,$sql_building_list);

    $sql_year_list = "SELECT YEAR(date) as year FROM data_power WHERE building_id = ".$building_id." GROUP BY year ORDER BY data_power.id DESC";
    $result_year_list = mysqli_query($con,$sql_year_list);

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
        <form method="POST" action="">
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="search_building" name="search_building"
                    placeholder="ค้นหาชื่ออาคาร" aria-label="search_building" aria-describedby="button-addon2"
                    value="<?=$search_building?>">
                <button class="btn btn-outline-secondary" type="submit">ค้นหา</button>
            </div>
        </form>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" class='col-md-10'>ชื่ออาคาร</th>
                    <th scope="col" class='col-md-2'>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($result_building_list as $building) 
                    {
                ?>
                <tr>
                    <td><?=$building[name]?></td>
                    <td><a
                            href="admin_selete_year_month_send_line_report.php?building_id=<?=$building[id]?>&building_name=<?=$building[name]?>&building_token=<?=$building[line_token]?>"><button
                                type='button' class='btn btn-info'>View</button></a></td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Send Report -->
    <div class="modal fade" id="ModalSendRePort" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">หน้าจัดการ Report</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="mui-form" name="frmdeletepower" method="post" action="send_line_report.php">
                    <input type="hidden" class="form-control" id="line_token" name="line_token" value="" />
                    <div class="modal-body">
                        <form method="POST" action="">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th scope="row">เลือกปี</th>
                                        <td> 
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
                                        </td>

                                    </tr>
                                    <tr>
                                        <th scope="row">เลือกเดือน</th>
                                        <td>                                
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
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary">ส่งรายงาน</button>
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

    function SetSendLine(line_token) {
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