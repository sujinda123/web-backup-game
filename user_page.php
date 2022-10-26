<?php session_start();?>
<?php 
date_default_timezone_set("Asia/Bangkok");
if (!$_SESSION["UserID"]){  //check session
    session_destroy();
	Header("Location: index.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 
}else{
    
    include("connection.php");
    $sql_building_list = "SELECT 
            data_building.id AS building_id, 
            data_building.name AS building_name, 
            data_power.id AS power_id, 
            data_power.forenoon_time AS forenoon_time, 
            data_power.afternoon_time AS afternoon_time, 
            data_power.unit_forenoon AS unit_forenoon, 
            data_power.unit_afternoon AS unit_afternoon
        FROM users
        JOIN data_role ON data_role.id = users.role
        LEFT JOIN  data_user_building ON data_user_building.user_id = users.user_id
        LEFT JOIN   data_building ON data_building.id = data_user_building.building_id
        LEFT JOIN   data_power ON data_power.building_id = data_building.id AND DATE = '". date("Y-m-d")."'
        WHERE users.user_id = ".$_SESSION["UserID"]."
        GROUP BY data_building.id
        ORDER BY data_building.id ASC";
    $result_building_list = mysqli_query($con,$sql_building_list);

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
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="user_page.php">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="user_page.php">รายงาน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">ช่วงเช้า</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">ช่วงบ่าย</a>
                    </li> -->
                </ul>

                <div class="d-flex">
                    <p style="margin: 0;padding: 0;margin-right: 15px;padding-top: 6px;justify-content: center;">
                        <?=$_SESSION["Firstname"]." ".$_SESSION["Lastname"]?></p>
                    <a href="logout.php">
                        <button class="btn btn-outline-danger" type="submit">Log out</button>
                    </a>
                </div>

            </div>
        </div>
    </nav>
    <div class="container-lg mt-5">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" class='col-md-9'>ชื่ออาคาร</th>
                    <th scope="col" class='col-md-3'>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($result_building_list as $building) {
                ?>
                <tr>
                    <td><?=$building[building_name]?></td>
                    <td>
                        <button class="btn btn-primary" type="button" onclick="SetShowDataPower('<?=trim($building[building_name])?>', '<?=$building[power_id]?>', '<?=$building[forenoon_time]?>', '<?=$building[afternoon_time]?>', '<?=$building[unit_forenoon]?>', '<?=$building[unit_afternoon]?>')" data-bs-toggle="modal" data-bs-target="#ModalShowData">
                            รายงาน
                        </button>
                        <button class="btn btn-secondary" type="button" onclick="SetDataForenoon('<?=trim($building[building_name])?>', '<?=$building[building_id]?>', '<?=$building[power_id]?>', '<?=$building[forenoon_time]?>', '<?=$building[unit_forenoon]?>')" data-bs-toggle="modal" data-bs-target="#ModalAddDataForenoon">
                            ช่วงเช้า
                        </button>
                        <button class="btn btn-secondary" type="button" onclick="SetDataAfternoon('<?=trim($building[building_name])?>', '<?=$building[power_id]?>', '<?=$building[afternoon_time]?>', '<?=$building[unit_afternoon]?>')" data-bs-toggle="modal"
                            data-bs-target="#ModalAddDataAfternoon"
                            <?php if($building[power_id] == "" ){echo "disabled";}?>>
                            ช่วงเย็น
                        </button>
                    </td>
                </tr>
                <?php }?>
            </tbody>
        </table>
        <!-- <div class="d-grid gap-2">

        </div> -->
    </div>

    <!-- Button trigger modal -->

    <!-- Modal -->
    <div class="modal fade" id="ModalShowData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">หน้ารายงาน</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div style="text-align: center;">
                        <h2>รายงาน <?php echo date("d/m/Y");?></h2>
                        <h3 style="color: #444444; text-align: center;"><t id="text_building_name"></t></h3>
                    </div>
                    <div class="row g-3">
                        <div class="col">
                            <h3 style="color: #444444; text-align: center;">ช่วงเช้า</h3>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th scope="row">เวลา</th>
                                        <td><t id="show_time_forenoon">-</t></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">หน่วยไฟ</th>
                                        <td><t id="show_unit_forenoon">-</t></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col">
                            <h3 style="color: #444444; text-align: center;">ช่วงเย็น</h3>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th scope="row">เวลา</th>
                                        <td><t id="show_time_afternoon">-</t></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">หน่วยไฟ</th>
                                        <td><t id="show_unit_afternoon">-</t></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Add Data Forenoon-->
    <div class="modal fade" id="ModalAddDataForenoon" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">หน้าเลือกกรอกข้อมูล</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="mui-form" name="frmdeleteuser" method="post" action="save_add_or_edit_forenoon.php">
                    <input type="hidden" class="form-control" id="edit_power_id" name="edit_power_id" value="" />
                    <input type="hidden" class="form-control" id="edit_building_id" name="edit_building_id" value="" />
                    <div class="modal-body">
                        <div style="text-align: center;">
                            <h2>ช่วงเช้า</h2>
                        </div>
                        <h3 style="color: #444444; text-align: center;"><t id="text_building_name_forenoon"></h3>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row">เวลา</th>
                                    <td><input type="time" class="form-control" id="edit_time_forenoon" name="edit_time_forenoon" value="" />
                                    </td>

                                </tr>
                                <tr>
                                    <th scope="row">หน่วยไฟ</th>
                                    <td><input type="text" class="form-control" id="edit_unit_forenoon" name="edit_unit_forenoon" value="" />
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

    <!-- Modal Add Data Afternoon-->
    <div class="modal fade" id="ModalAddDataAfternoon" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">หน้าเลือกกรอกข้อมูล</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="mui-form" name="frmdeleteuser" method="post" action="save_add_afternoon.php">
                    <input type="hidden" class="form-control" id="edit_afternoon_power_id" name="edit_afternoon_power_id" value="" />
                    <div class="modal-body">
                        <div style="text-align: center;">
                            <h2>ช่วงเย็น</h2>
                        </div>
                        <h3 style="color: #444444; text-align: center;"><t id="text_building_name_afternoon"></h3>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row">เวลา</th>
                                    <td><input type="time" class="form-control" id="edit_time_afternoon" name="edit_time_afternoon" value="" />
                                    </td>

                                </tr>
                                <tr>
                                    <th scope="row">หน่วยไฟ</th>
                                    <td><input type="text" class="form-control" id="edit_unit_afternoon" name="edit_unit_afternoon" value="" />
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
    function SetShowDataPower(building_name, power_id, forenoon_time, afternoon_time, unit_forenoon, unit_afternoon) {
        document.getElementById('text_building_name').innerText = building_name;
        document.getElementById('show_time_forenoon').innerText = forenoon_time === "" ? "-" : forenoon_time;
        document.getElementById('show_unit_forenoon').innerText = unit_forenoon === "" ? "-" : unit_forenoon;
        document.getElementById('show_time_afternoon').innerText = afternoon_time === "" ? "-" : afternoon_time;
        document.getElementById('show_unit_afternoon').innerText = unit_afternoon === "" ? "-" : unit_afternoon;
    }

    function SetDataForenoon(building_name, building_id, power_id, forenoon_time, unit_forenoon) {
        console.log("555");
        console.log(building_id);
        document.getElementById('text_building_name_forenoon').innerText = building_name;
        document.getElementById('edit_building_id').value = building_id;
        document.getElementById('edit_power_id').value = power_id;
        document.getElementById('edit_time_forenoon').value = forenoon_time;
        document.getElementById('edit_unit_forenoon').value = unit_forenoon;
    }

    

    function SetDataAfternoon(building_name, power_id, afternoon_time, unit_afternoon) {
        document.getElementById('text_building_name_afternoon').innerText = building_name;
        document.getElementById('edit_afternoon_power_id').value = power_id;
        document.getElementById('edit_time_afternoon').value = afternoon_time;
        document.getElementById('edit_unit_afternoon').value = unit_afternoon;
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