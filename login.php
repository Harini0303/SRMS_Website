<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
ob_start();

$system = $conn->query("SELECT * FROM system_settings")->fetch_array();
foreach ($system as $k => $v) {
    $_SESSION['system'][$k] = $v;
}

ob_end_flush();
?>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Login | <?php echo $_SESSION['system']['name'] ?></title>

    <?php include('./header.php'); ?>
    <?php 
    if (isset($_SESSION['login_id'])) header("location:index.php?page=home");
    ?>

</head>
<style>
    body {
      margin: 0;
            padding: 0;
            background-image: url('kpr1.jpg'); /* Replace 'background.jpg' with your background image URL */
            background-size: cover;
            font-family: Arial, sans-serif;
            text-align: center;
    }

    .container {
           position: absolute;
            top: 50%;
            left: 50%;
            width: 30%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
    }

    h1 {
      font-size: 24px;
      font-weight:bold;
      color: black; /* Change the heading color to black */
    }

    form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    input[type="text"],
    input[type="password"] {
        width: 250px;
        padding: 10px;
        margin: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .button-container {
        display: flex;
        justify-content: space-between;
        width: 250px;
    }

    button.login-button {
        flex: 1;
        padding: 10px;
        background-color: #007bff; /* Blue color for login button */
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin: 10px;
    }

    button.login-button:hover {
        background-color: #0056b3; /* Hover color for login button */
    }

    button.view-button {
        flex: 1;
        padding: 10px;
        background-color: #4CAF50; /* Green color for view result button */
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin: 10px;
    }

    button.view-button:hover {
        background-color: #45a049; /* Hover color for view result button */
    }
</style>

<body class="bg-dark">
    <main id="main">
        <div class="container">
            <h1>Student Result Management System - Admin</h1>
            <form id="login-form">
                <div class="form-group">
                    <input type="text" id="username" name="username" class="form-control form-control-sm" placeholder="Username">
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" class="form-control form-control-sm" placeholder="Password">
                </div>
                <div class="w-100 d-flex justify-content-center">
                    <button class="btn btn-primary m-0 mr-1">Login</button>
                    <button class="btn btn-success" type="button" id="view_result">View Result</button>
                </div>
            </form>
        </div>
    </main>

    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>
    <div class="modal fade" id="view_student_results" role='dialog'>
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form id="vsr-frm">
                            <div class="form-group">
                                <label for="student_code" class="control-label text-dark">Student ID #:</label>
                                <input type="text" id="student_code" name="student_code" class="form-control form-control-sm">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id='submit' onclick="$('#view_student_results form').submit()">View</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include 'footer.php' ?>
<script>
    $('#view_result').click(function(){
        $('#view_student_results').modal('show');
    })

    $('#login-form').submit(function(e){
        e.preventDefault();
        $('#login-form button[type="button"]').attr('disabled', true).html('Logging in...');
        if ($(this).find('.alert-danger').length > 0) {
            $(this).find('.alert-danger').remove();
        }

        $.ajax({
            url: 'ajax.php?action=login',
            method: 'POST',
            data: $(this).serialize(),
            error: err => {
                console.log(err);
                $('#login-form button[type="button"]').removeAttr('disabled').html('Login');
            },
            success: function(resp) {
                if (resp == 1) {
                    location.href = 'index.php?page=home';
                } else {
                    $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>');
                    $('#login-form button[type="button"]').removeAttr('disabled').html('Login');
                }
            }
        });
    });

    $('#vsr-frm').submit(function(e){
        e.preventDefault();
        start_load();

        if ($(this).find('.alert-danger').length > 0) {
            $(this).find('.alert-danger').remove();
        }

        $.ajax({
            url: 'ajax.php?action=login2',
            method: 'POST',
            data: $(this).serialize(),
            error: err => {
                console.log(err);
                end_load();
            },
            success: function(resp) {
                if (resp == 1) {
                    location.href = 'student_results.php';
                } else {
                    $('#login-form').prepend('<div class="alert alert-danger">Student ID # is incorrect.</div>');
                    end_load();
                }
            }
        });
    });

    $('.number').on('input keyup keypress', function() {
        var val = $(this).val();
        val = val.replace(/[^0-9 \,]/, '');
        val = val.toLocaleString('en-US');
        $(this).val(val);
    });
</script>
</html>

