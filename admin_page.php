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
                    placeholder="ค้นหาชื่ออาคาร" aria-label="search_building" aria-describedby="button-addon2" value="<?=$search_building?>">
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
                            href="admin_view_power.php?building_id=<?=$building[id]?>&building_name=<?=$building[name]?>"><button
                                type='button' class='btn btn-warning' data-bs-toggle='modal'
                                data-bs-target='#ModalViewPower'>View</button></a></td>
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