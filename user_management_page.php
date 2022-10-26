<?php session_start();?>
<?php 

if (!$_SESSION["UserID"]){  //check session
        session_destroy();
	    Header("Location: index.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

}else{

    include("connection.php");
    $sql_user_list = "SELECT users.*, data_role.name AS role_name, GROUP_CONCAT(data_building.id,':',data_building.name) AS building_name FROM users
            JOIN data_role ON data_role.id = users.role
            LEFT JOIN  data_user_building ON data_user_building.user_id = users.user_id
            LEFT JOIN   data_building ON data_building.id = data_user_building.building_id
            GROUP BY users.user_id
            ORDER BY users.user_id ASC";
    $result_user_list = mysqli_query($con,$sql_user_list);

    $sql_role_list = "SELECT * FROM data_role";
    $result_role_list = mysqli_query($con,$sql_role_list);

    $sql_building_list = "SELECT * FROM data_building";
    $result_building_list = mysqli_query($con,$sql_building_list);

?>

<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <style>
    .chip {
        display: inline-block;
        padding: 0 15px;
        height: 40px;
        font-size: 18px;
        line-height: 40px;
        border-radius: 25px;
        background-color: #f1f1f1;
    }

    .chip img {
        float: left;
        margin: 0 10px 0 -25px;
        height: 50px;
        width: 50px;
        border-radius: 50%;
    }

    .closebtn {
        padding-left: 10px;
        color: #888;
        font-weight: bold;
        float: right;
        font-size: 25px;
        cursor: pointer;
    }

    .closebtn:hover {
        color: #000;
    }
    </style>
</head>

<body>
    <?php include("admin_navbar.php");?>
    <div class="container-lg mt-5">
        <?php if($_SESSION["Role"] == 'ADMIN'){ ?>
        <div style="text-align: right;">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#ModalAddUser">เพิ่มผู้ใช้งาน</button>
        </div>
        <?php }?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <!-- <th scope="col">User ID</th> -->
                    <th scope="col">Username</th>
                    <th scope="col">Firstname</th>
                    <th scope="col">Lastname</th>
                    <th scope="col">Role</th>
                    <th scope="col">Building</th>
                    <?php if($_SESSION["Role"] == 'ADMIN'){ ?>
                    <th scope="col">Edit / Delete</th>
                    <?php }?>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($result_user_list as $user) {
                        if($user[role_name] != 'ADMIN'){
                ?>
                <tr class='something'>
                    <th scope='row' class='col-md-2'><?=$user[username]?></th>
                    <td class='col-md-2'><?=$user[firstname]?></td>
                    <td class='col-md-2'><?=$user[lastname]?></td>
                    <td class='col-md-1'><?=$user[role_name]?></td>
                    <td class='col-md-2'>
                        <?php 
                                    $holds = explode(',', $user['building_name']);
                                    foreach ($holds as $building){
                                        if($building != null){
                                        $split = explode(':', $building);
                                ?>
                        <div class='chip' style='display: inline-flex;max-width: 300px;margin-bottom: 5px;'>
                            <p style='white-space: nowrap;text-overflow: ellipsis;max-width: 250px;overflow: hidden;margin: 0;padding: 0;'
                                title='<?=$split[1]?>'><?=$split[1]?></p>
                            <?php if($_SESSION["Role"] == 'ADMIN'){ ?>

                            <span class='closebtn' onclick='SetDeleteUserBuilding(<?=$user[user_id]?>, <?=$split[0]?>)'
                                data-bs-toggle='modal' data-bs-target='#ModalDeleteBuilding'>&times;</span>
                            <?php }?>
                        </div>
                        <?php   
                                        }
                                    }
                                ?>
                    </td>
                    <?php if($_SESSION["Role"] == 'ADMIN'){ ?>
                    <td class='col-md-2.5'>
                        <button type='button' onclick='SetAddBuildingUserId(<?=$user[user_id]?>)'
                            class='btn btn-success' data-bs-toggle='modal'
                            data-bs-target='#ModalAddBuilding'>เพิ่มตึก</button>
                        <button type='button'
                            onclick='SetEditUser("<?=$user[username]?>", "<?=$user[firstname]?>", "<?=$user[lastname]?>", "<?=$user[role]?>", "<?=$user[user_id]?>")'
                            class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#ModalEditUser'>Edit</button>
                        <button type='button' onclick='SetDeleteUserId(<?=$user[user_id]?>)' class='btn btn-danger'
                            data-bs-toggle='modal' data-bs-target='#ModalDeleteUser'>Delete</button>
                    </td>
                    <?php }?>
                </tr>
                <?php
                        }

                    }
                ?>
            </tbody>
        </table>
    </div>


    <!-- Modal Add User-->
    <div class="modal fade" id="ModalAddUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">หน้าเพิ่มผู้ใช้งาน</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="mui-form" name="frmadduser" method="post" action="save_add_user.php">
                    <div class="modal-body">
                        <!-- <div style="text-align: center;">
                            <h2>ช่วงเช้า</h2>
                        </div>
                        <h3 style="color: #444444; text-align: center;">ตึก ...</h3> -->
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row">Username</th>
                                    <td><input type="text" class="form-control" id="username" name="username"
                                            required />
                                    </td>

                                </tr>
                                <tr>
                                    <th scope="row">Password</th>
                                    <td><input type="password" class="form-control" id="password" name="password"
                                            required />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Firstname</th>
                                    <td><input type="text" class="form-control" id="firstname" name="firstname"
                                            required />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Lasttname</th>
                                    <td><input type="text" class="form-control" id="lastname" name="lastname"
                                            required />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Role</th>
                                    <td>
                                        <select name="role" id="role" required>
                                            <?php
                                            foreach ($result_role_list as $role) {
                                                echo '<option value="'.$role['id'].'">'.$role['name'].'</option>'; 
                                            }
                                        ?>
                                        </select>
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


    <!-- Modal Edit User-->
    <div class="modal fade" id="ModalEditUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">หน้าแก้ไขผู้ใช้งาน</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="mui-form" name="frmdeleteuser" method="post" action="save_edit_user.php">
                    <input type="hidden" class="form-control" id="edit_user_id" name="edit_user_id" value="" />
                    <div class="modal-body">
                        <!-- <div style="text-align: center;">
                        <h2>ช่วงเช้า</h2>
                    </div>
                    <h3 style="color: #444444; text-align: center;">ตึก ...</h3> -->
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row">Username</th>
                                    <td><input type="text" class="form-control" id="edit_username" name="edit_username"
                                            value="" />
                                    </td>

                                </tr>
                                <!-- <tr>
                                <th scope="row">Password</th>
                                <td><input type="password" class="form-control" id="unit_forenoon" name="unit_forenoon">
                                </td>
                            </tr> -->
                                <tr>
                                    <th scope="row">Firstname</th>
                                    <td><input type="text" class="form-control" id="edit_firstname"
                                            name="edit_firstname" value="" />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Lasttname</th>
                                    <td><input type="text" class="form-control" id="edit_lastname" name="edit_lastname"
                                            value="" />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Role</th>
                                    <td>
                                        <select name="edit_role_id" id="edit_role_id" value="" required>
                                            <?php
                                            foreach ($result_role_list as $role) {
                                                echo '<option value="'.$role['id'].'">'.$role['name'].'</option>'; 
                                            }
                                        ?>
                                        </select>
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

    <!-- Modal Delete User-->
    <div class="modal fade" id="ModalDeleteUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">คุณต้องการลบผู้ใช้งานหรือไม่</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="mui-form" name="frmdeleteuser" method="post" action="delete_user.php">
                    <input type="hidden" class="form-control" id="delete_user_id" name="delete_user_id" value="" />
                    <!-- <div class="modal-body">
                    </div> -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-danger">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Add Building-->
    <div class="modal fade" id="ModalAddBuilding" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-ml">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">เพิ่มตึก</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="mui-form" name="frmdeleteuser" method="post" action="save_add_building.php">
                    <input type="hidden" class="form-control" id="add_building_user_id" name="add_building_user_id"
                        value="" />
                    <div class="modal-body">
                        <select name="add_building_id" id="add_building_id"
                            style="width: 100%;font-size: 18px;padding: 10px 5px;border-radius: 10px;" required>
                            <?php
                            foreach ($result_building_list as $building) {
                                echo '<option value="'.$building['id'].'">'.$building['name'].'</option>'; 
                            }
                        ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete Building-->
    <div class="modal fade" id="ModalDeleteBuilding" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">คุณต้องการลบตึกหรือไม่</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="mui-form" name="frmdeleteuser" method="post" action="delete_user_building.php">
                    <input type="hidden" class="form-control" id="delete_building_user_id"
                        name="delete_building_user_id" value="" />
                    <input type="hidden" class="form-control" id="delete_building_id" name="delete_building_id"
                        value="" />
                    <!-- <div class="modal-body">
                    </div> -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-danger">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function SetDeleteUserId(UserID) {
        document.getElementById('delete_user_id').value = UserID;
    }

    function SetDeleteUserBuilding(UserID, BuildingID) {
        document.getElementById('delete_building_user_id').value = UserID;
        document.getElementById('delete_building_id').value = BuildingID;
    }

    function SetAddBuildingUserId(UserID) {
        document.getElementById('add_building_user_id').value = UserID;
    }

    function SetEditUser(username, firstname, lastname, role_id, user_id) {
        document.getElementById('edit_user_id').value = user_id.toString();
        document.getElementById('edit_username').value = username.toString();
        document.getElementById('edit_firstname').value = firstname.toString();
        document.getElementById('edit_lastname').value = lastname.toString();
        document.getElementById('edit_role_id').value = role_id;
        // document.getElementById('edit_building_id').value = building_id;

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