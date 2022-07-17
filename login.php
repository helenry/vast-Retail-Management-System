<?php
    $connect = mysqli_connect("localhost", "root", "", "vast_retail");

    session_start();
    if(isset($_SESSION["email"]) && isset($_SESSION["password"])) {
        $query = sprintf("SELECT * FROM employee WHERE Email = '%s'", $_SESSION["email"]);
        $cEmail = mysqli_query($connect, $query);
        $cPassword = mysqli_fetch_assoc($cEmail);
        if(mysqli_num_rows($cEmail) == 1 && password_verify($_SESSION["password"], $cPassword["Password"])) {
            header("Location: inventory.php");
            exit;
        }
    }
    
    // employee pertama
    $aEmail = "helenyemima@vast.com";
    $aPassword = password_hash("Abcde%12345", PASSWORD_DEFAULT);
    $query = "INSERT IGNORE INTO employee (Email, Password, Nama, IdCabang) VALUES ('$aEmail', '$aPassword', 'Helen Ruth Yemima', 1)";
    mysqli_query($connect, $query);

    $email = $password = $eEmail = $ePassword = "";
    if(isset($_POST["login"])){
        $_SESSION["email"] = $email = $_POST["email"];
        $_SESSION["password"] = $password = $_POST["password"];

        $query = "SELECT * FROM employee WHERE Email = '$email'";
        $cEmail = mysqli_query($connect, $query);
        if(mysqli_num_rows($cEmail) == 1) {
            $cPassword = mysqli_fetch_assoc($cEmail);
            if (password_verify($password, $cPassword["Password"])) {
                header("Location: inventory.php");
                exit;
            } else {
                $ePassword = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Password does not match.</p></div>";
            }
        } else {
            $eEmail = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Email has not been registered.</p></div>";
            $email = "";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" type="image/x-icon" href="img/logo/favicon.png">
</head>
<body>
    <div class="cont">
        <div class="image">
            <div class="title">
                <h1>Manage</h1>
                <h1>your stores</h1>
                <h1>effectively</h1>
            </div>
            <img src="img/cart.png" alt="">
        </div>
        <div class="login form">
            <div class="head">
                <img src="img/logo/logo.png" alt="" class="logo">
                <h2>Log In</h2>
            </div>

            <form action="login.php" method="POST">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter Your Email" value="<?php echo $email; ?>" required>
                <?php echo $eEmail ?>

                <label for="password">Password</label>
                <input type="password" id="password" placeholder="Enter Your Password" name="password" value="" required>
                <?php echo $ePassword ?>

                <input type="submit" name="login" value="Log In">
            </form>
        </div>
    </div>
</body>
</html>