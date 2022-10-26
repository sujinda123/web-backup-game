<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_page.php">Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="admin_page.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="user_management_page.php">User</a>
                </li>
                <?php if($_SESSION["Role"] == 'ADMIN'){ ?>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="admin_send_line_report.php">Send Line Report</a>
                </li>
                <?php }?>
                <!-- <li class="nav-item">
                    <a class="nav-link active" href="#">User Manager</a>
                </li> -->
            </ul>
            <div class="d-flex">
                <p style="margin: 0;padding: 0;margin-right: 15px;padding-top: 6px;justify-content: center;">
                    <?=$_SESSION["Firstname"]." ".$_SESSION["Lastname"]?></p>
                <button class="btn btn-outline-warning" style="margin-right: 10px;" type="submit" data-bs-toggle='modal' onclick="SetEditAdmin('<?=$_SESSION['Username']?>','<?=$_SESSION['Firstname']?>','<?=$_SESSION['Lastname']?>',<?=$_SESSION['UserID']?>)" data-bs-target='#ModalEditAdminProfile'>Edit</button>
                <a href="logout.php">
                    <button class="btn btn-outline-danger" type="submit">Log out</button>
                </a>
            </div>

        </div>
    </div>
</nav>

<!-- Modal Edit Power-->
<div class="modal fade" id="ModalEditAdminProfile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">หน้าแก้ไขโปรไฟล์</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form autocomplete="off" class="mui-form" name="frmdeletepower" method="post" action="save_edit_admin_profile.php">
                <input type="hidden" class="form-control" id="edit_admin_user_id" name="edit_admin_user_id" value="" />
                <div class="modal-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Username</th>
                                <td><input type="text" class="form-control" id="edit_admin_username" name="edit_admin_username" required />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Password</th>
                                <td><input type="password" class="form-control" id="edit_admin_password" name="edit_admin_password" value=""  autocomplete="new-password" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Confirm Password</th>
                                <td><input type="password" class="form-control" id="edit_admin_confirm_password" name="edit_admin_confirm_password" value=""  autocomplete="new-password" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Firstname</th>
                                <td><input type="text" class="form-control" id="edit_admin_firstname" name="edit_admin_firstname" required />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Lasttname</th>
                                <td><input type="text" class="form-control" id="edit_admin_lastname" name="edit_admin_lastname" required />
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
    function SetEditAdmin(username, firstname, lastname, user_id) {
        document.getElementById('edit_admin_user_id').value = user_id.toString();
        document.getElementById('edit_admin_username').value = username.toString();
        document.getElementById('edit_admin_firstname').value = firstname.toString();
        document.getElementById('edit_admin_lastname').value = lastname.toString();
    }
</script>