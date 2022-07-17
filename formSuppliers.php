<?php
    $addOrEdit = "";
    if (isset($_GET["id"])) {
        $addOrEdit = "Edit";
    } else {
        $addOrEdit = "Add";
    }

    session_start();
    if(!isset($_SESSION["email"])) {
        header("Location: login.php");
        exit;
    }

    $connect = mysqli_connect("localhost", "root", "", "vast_retail");
    $connectI = mysqli_connect("localhost", "root", "", "indonesia");

    $email = $_SESSION["email"];
    $query = "SELECT Nama FROM employee WHERE Email = '$email'";
    $nama = mysqli_query($connect, $query);
    $nama = mysqli_fetch_array($nama, MYSQLI_BOTH);

    $name = $email = $province = $city = $subdistrict = $village = $address = $postalcode = "";

    // check form
    $eName = $eEmail = $eAddress = $ePostalCode = "";

    if (isset($_POST["submit"])) {
        $name = check($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $eName = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Name can only consist of letters and spaces.</p></div>";
        }
        if (strlen($name) < 4) {
            $eName = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Name length minimum 4.</p></div>";
        } elseif (strlen($name) > 60) {
            $eName = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Name length maximum 60.</p></div>";
        }

        if (!isset($_GET["id"])) {
            $email = $_POST["email"];
            $query = "SELECT * FROM suppliers WHERE Email = '$email'";
            $cEmail = mysqli_query($connect, $query);
            if (mysqli_num_rows($cEmail) == 1) {
                $eEmail = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Email already exists.</p></div>";
            }
        } else {
            $id = $_GET["id"];
            $email = $_POST["email"];
            $query = "SELECT * FROM suppliers WHERE NOT Id = $id AND Email = '$email'";
            $cEmail = mysqli_query($connect, $query);
            if (mysqli_num_rows($cEmail) != 0) {
                $eEmail = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Email already exists.</p></div>";
            }
        }
        
        if (strlen($email) < 10) {
            $eEmail = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Email length minimum 10.</p></div>";
        } elseif (strlen($email) > 40) {
            $eEmail = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Email length maximum 40.</p></div>";
        }

        $address = check($_POST["address"]);
        if (strlen($address) < 10) {
            $eAddress = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Address length minimum 10.</p></div>";
        } elseif (strlen($address) > 30) {
            $eAddress = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Address length maximum 30.</p></div>";
        }

        $postalcode = check($_POST["postalcode"]);
        if (strlen($postalcode) != 5) {
            $ePostalCode = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Postal code length must be 5.</p></div>";
        }

        // no error
        if ($eName == "" && $eEmail == "" && $eAddress == "" && $ePostalCode == "") {
            if (!isset($_GET["id"])) {
                mysqli_query($connect, "insert into suppliers set
                    Id = null,
                    Nama = '$_POST[name]',
                    Email = '$_POST[email]'");
                $query = "SELECT * FROM suppliers WHERE Email = '" . $_POST['email'] . "'";
                $result = mysqli_query($connect, $query);
                $id = mysqli_fetch_assoc($result);
                $id = $id['Id'];

                mysqli_query($connect, "insert into address set
                    Jenis = 'supplier',
                    IdToko = $id,
                    Provinsi = '$_POST[province]',
                    Kabupaten = '$_POST[city]',
                    Kecamatan = '$_POST[subdistrict]',
                    Kelurahan = '$_POST[village]',
                    Alamat = '$_POST[address]',
                    KodePos = '$_POST[postalcode]'");
                $name = "";
                $email = "";
                $address = "";
                $postalcode = "";
?>
                <script>
                    document.location.href = "suppliers.php";
                    alert('Supplier added successfully.');
                </script>
        <?php
            } else {
                $isEdited = false;
                $id = $_GET["id"];

                $queryS = "SELECT * FROM suppliers WHERE Id = $id";
                $resultS = $connect -> query($queryS);
                $rowS = mysqli_fetch_array($resultS);
                $name = $rowS["Nama"];
                $email = $rowS["Email"];

                $queryA = "SELECT * FROM address WHERE Jenis = 'supplier' AND IdToko = $id";
                $resultA = $connect -> query($queryA);
                $rowA = mysqli_fetch_array($resultA);
                $province = $rowA["Provinsi"];
                $city = $rowA["Kabupaten"];
                $subdistrict = $rowA["Kecamatan"];
                $village = $rowA["Kelurahan"];
                $address = $rowA["Alamat"];
                $postalcode = $rowA["KodePos"];

                if ($_POST['name'] != $name) {
                    $query = sprintf("UPDATE suppliers SET Nama = '%s' WHERE Id = %s", $_POST['name'], $id);
                    mysqli_query($connect, $query);
                    $isEdited = true;
                }
                if ($_POST['email'] != $email) {
                    $query = sprintf("UPDATE suppliers SET Email = '%s' WHERE Id = %s", $_POST['email'], $id);
                    mysqli_query($connect, $query);
                    $isEdited = true;
                }
                if ($_POST['province'] != $province) {
                    $query = sprintf("UPDATE address SET Provinsi = %s WHERE IdToko = %s AND Jenis = 'supplier'", $_POST['province'], $id);
                    mysqli_query($connect, $query);
                    $isEdited = true;
                }
                if ($_POST['city'] != $city) {
                    $query = sprintf("UPDATE address SET Kabupaten = %s WHERE IdToko = %s AND Jenis = 'supplier'", $_POST['city'], $id);
                    mysqli_query($connect, $query);
                    $isEdited = true;
                }
                if ($_POST['subdistrict'] != $subdistrict) {
                    $query = sprintf("UPDATE address SET Kecamatan = %s WHERE IdToko = %s AND Jenis = 'supplier'", $_POST['subdistrict'], $id);
                    mysqli_query($connect, $query);
                    $isEdited = true;
                }
                if ($_POST['village'] != $village) {
                    $query = sprintf("UPDATE address SET Kelurahan = %s WHERE IdToko = %s AND Jenis = 'supplier'", $_POST['village'], $id);
                    mysqli_query($connect, $query);
                    $isEdited = true;
                }
                if ($_POST['address'] != $address) {
                    $query = sprintf("UPDATE address SET Alamat = '%s' WHERE IdToko = %s AND Jenis = 'supplier'", $_POST['address'], $id);
                    mysqli_query($connect, $query);
                    $isEdited = true;
                }
                if ($_POST['postalcode'] != $postalcode) {
                    $query = sprintf("UPDATE address SET KodePos = '%s' WHERE IdToko = %s AND Jenis = 'supplier'", $_POST['postalcode'], $id);
                    mysqli_query($connect, $query);
                    $isEdited = true;
                }

                if ($isEdited == true) {
        ?>
                    <script>
                        alert('Supplier edited successfully.');
                    </script>
            <?php
                }
            ?>
                <script>
                    document.location.href = "suppliers.php";
                </script>
<?php
            }
        }
    }

    function check($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $addOrEdit ?> Supplier</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/suppliers.css">
    <link rel="icon" type="image/x-icon" href="img/logo/favicon.png">
</head>
<body>
    <div class="container">
        <div class="navbar">
            <img class="expand" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABmJLR0QA/wD/AP+gvaeTAAACBUlEQVR4nO2ZTW7TQBiGnw+ugEQXnMD2qoq4COOpKAcIlEvkDiAqJLaVaJQlhW3uQD0SXCE5A8OixkKR2tjz49rS96yikd7P8z52Io8CiqIoiqIoiqIoiqIEIMaYN9ba14BkzGThaeyAuq7PReQKeFVV1UnTNDc5Mrl4EjvAey//fV5aay85cldDMrmIfgKcc7dVVZ0Ai3ZpceyuhmRyES0AoGmam6IononIy3ZpUZblC2vtt+1261NlcpBEAIBz7sdBodPdbvdgoZBMapIJgHlKSCoA5ichuQCYl4QsAmA+ErIJgHlIyCoApi8huwCYtoRRBMB0JYwmAKYpYVQB0BV6LiL/zgGn+/3+2NlhcKYvowsAcM59Pyi0qKrqV9M0t0MyZVn+ds79jNlL9HH4MfHe/4md8RgCxBjzSUTedgsin9fr9dehmc1mcx27mbEFiDHmw0GRL0VRXAD3/aCFZPpvKHbAkGu1Rd53C3dFlqvV6r5HOSQzbFMphvS5zhTLwzgCJlse8guYdHnI+x4g1tqPwKDyAZm4TeYYSlvEe3/RLfQsPzATv9EcM+dSHtILmFV5SCtgduUhnYBZloc0AsRae+m9X3YLd+/273jg9TYgk4VoAXVdnwNX3cAeRUIyuUj673DfIiGZXCT5ChhjzgDa42mfIiEZRVEURVEURVEURVHS8BeiPUB1BfqrXAAAAABJRU5ErkJggg==">
            <ul>
                <li><a href="inventory.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAABg0lEQVRoge2ZzU3DQBBGP9vUwQVuFICEQEK0YEY+QBUUQh/WICghB5JD2gDq8HKJ0QJZe7w/8WLmHeOxPW9Hu5+sAIqShCLVc5umOe+6jgDUu/c8lWXJbdtuAZjoL4z5sLquz6qqImPMPYATR9mHMea5KApm5g0iSYWK2Ct/C+DYuvaG3RQAYKwmdFJeIgMr/26MeRlabeveOwCn1qWgSYlFQpoXPDNYalAkRfOCd3lJ/RI5ZPMufKS+iRDRCsC19VO0zejJ0GGyYuabr0L7LiLqG32csXkXttQDADDz/q1BRMaSyZZ9fZZzNRMbFcmNI0kREW0AXCTuxcWama/GiqQTmUsCAC4lRaKJ9DiPu0RMOUEXs0dUJDdUJDcWIzLp+B1jYnCKgk5K7IlMCU5R0EmJOpGeseBM8amwmD2iIrmhIrmhIrnhnSNDKS7NiT113mkfMpEUn7/eaR+c7NIUT532i9kjKpIbKpIbKpIbrn+s/gR2Nv2cyPrAvYTwOncDyr/gEwgV6TaoFPsuAAAAAElFTkSuQmCC"></a></li>
                <li><a href="products.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAACKElEQVRoge2YMYgTQRSGv9nIxUJbW7HWwkJRBCsPGzFoMrOetWksrSy9UktBG/GuES0y2RhSHWcjaiGc5dkK2gZBsUvMjkUSCHF3b3cz2dsL83WZ98j7f96bCXngcDiSEGmSlFJngA5wfrly/mMvDMONIAi+HZR4oBEp5RUhxFvglBVp2flpjJHtdvt9UlKiEd/37xpjtoHjwO5wOLzT7XZ/ZVGhlDIAWutU3Z9Sq9VOVqvV18BNYCCEuN9qtbbj8r2Yc+H7/qYx5g1jEy/6/f6NrCYWodfr/QFuG2OeAGvGmC0p5VOlVCVS8PyBUuoE8Aq4BfwFHmitn+UVlLcjs0gpm0KI58AasANsaK1/z+ZEGdkDLuQtWhBftNYXZw+iRqvsJiBC47G4zEVGYZlMR3WeuMt+5HBGykbsHclDvV4/XalUtoCrjJ/KKAbAB+Ce1vqHrdpWOzIxcY14E0xi68BLm7WtdgS4BBCG4bkgCL5GJTQajbOe5+0Dl20Wtm3kM7Dued6+UipNrjWsjtZoNGoC7xjfgzgGwC7QtFnbakc6nc534LrN70zLyjy/zkjZcEbKhjNSNpyRsuGMLIrv+5tSysdZY3EcipHJ8u+REOLhvOCkWBKFG5kKnX6eFZwUy4xSysStXGySVCdPzF32srEyRmz/Z09N0ko2z7q2cCN5HpI0xtxo5WVZW/6V6cjKGIkdrSJ+3W0S1ZFPhavIzsfDFuBwHDX+AZTiuWzva/QsAAAAAElFTkSuQmCC"></a></li>
                <li><a href="suppliers.php" class="notmain"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAENElEQVRoge2aW2hcRRjHf9/ZJfZqjC1JzpqKN5D6IEillpqitWqgEARlc7ZeEEVao1TEC1gfpKAv+qAo+KBESFshWVelFNToQys0RWtFvNBaoS3FNtmNt1iKNIbd8/chWzE1m+yeORul5P9y5rDzffP778ycmTm7MKc5zWk6WT2SXnq3moolOgRrMVYgmoElGF/lC6zlUyvG3WasRpo3qCUZ8rRgE7BoqjqC+wtZ2x5nuxCjkdaMbjbRD7SUE+8HPg6NL5LGiWJIu8HrwCjGgMTAkpD+gzkbj6P9WIykutQp430gaWJ3aDxTyNqBSZXSSvgee4A1/2j8qIxMvt++dGVwNtKa1jXm8TmwWPBSYTlb2GphpfoX36MLG8a5zjy2AB0Gp8KQ1YWcHXJlcZIfaMAPpFRGvbVFyvxA2/xA8gMNunJ4LsEtgVYBHcBowxiP1xZtCht4BBgBbmxN6yYXFicjCbgTQOLN4zvt91rjR3bYH4i3ADyPLhcWJyMybgEw+DAygMduAMEqFxYnI4g2gPACDkdNUSxxrFy8wgXFzQhcBLD4F05FTbAUhgAMFrqAuA0tOAFwehGpqDl+hUYAiREXFicjJr4GMI/rI+dIsqJMctSFxc2I8R6AQSZyjpAHAUy868LiOkcuAzC4JGoCwbrytdEFxHWOPAkQwuaoOUwE5eujLiyuPbIAoCHBkcgAYh+AjCYXEDcjxgGAYsjtUVMUE9xQLn7vguL61HobQCGvpbrUWWt8KtAdJrYDCHa6sDgZGR6hV2IXRrOM52uNF7wILBNkSyEvu7DEcLCS+QE/AU2JkOaTOfutmqiWtC73PI4BQ/mstblSuE52wGSwB0gUExNrQlUNe2wsFz9wZ4jFCAA9ACaeXZbRjNuVpffKN+gGMHgjDoD4Xj4E6jPIGOwfztq/tuR+oH3A6rjamyRjMK4eYd58HgAQfz9Oz1V9TEw02p6MK9fxXhvzA81YL5+1WN+l+YEE8c2R/1yx9YiL/Iz2ItqnrWQM5vttTaWPY+uRVFpXl4t/xpVzksS04zaWHmnboGtLIdny7Y5a46f7pquVk5HmQFcmYHMppBtoMPjuTMhTrlBRVLuRtBIpYz3GRsF6JoanDHrHz/DY6C47HTtlFaraSKpTC7SQTYQ8IePs3mhM0JeAV4ey9k2dGKvSzEa2yms9TLdCnkM0l/cCP8joocS2Qs5+rjdkNZrWSFNajfMOkcO4DQMZnxHyQuEdPgKbefWbRVU00pbW/FKCTxArgRETD+ez5nT4qacqGil5vIJYKTjihawbztmPswlWq6ZcEP1Ay4GHgGLS467/uwmotLIb9wEJg4GTffbt7CJF09RGxK0AmNsLgdlUpTlyFYBEjx+oZxZ5IqvS0Do4yxzOmrJH4tjEnauzB6B66bw5WNXlvyhT6fzpEcP5t/RptPcv0k5Df8OlDIYAAAAASUVORK5CYII="></a></li>
                <li><a href="branches.php"><img src="data:image/png;base64,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"></a></li>
                <li><a href="orders.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABmJLR0QA/wD/AP+gvaeTAAAFoklEQVR4nO2aXWgcVRTH/+fu7Ka1wY/UbKxQW6UaSfelEfEbA5VCBF/s3m2gUoMPoiDYh1Lpg5IXpWC1KFqkIC0UErOTzT6IaF/Egl+UWhCx29Ani1ZT2wRTV7a7M/f4kJkys2y2uTez222yv5e9M3PuOWfOnnvPnZkLtGnTps0KhpaqYPv27fcLIe4mongUDkWFUsoholO2bf9bT840AJROp18mon0ANhjqaAYOgO8A7Ldt+6taAiYBoEwmc5SZdy3JtSbDzAcmJib2AuDgee0ApNPp3UR00Du8QEQHmLlARE4UjprAzG8BeMo7vMzMR4joNwB9AHYBWAMARPRaNpv9ONhXKwADAwNWd3f3BQDdAM47jtOfz+cvL/kOloCUshdAAfP38guArbZt/+1f9+aoEwDWAZgBsCE4LwgdYz09PVswf/Ng5ndu9M17pOH9kUqpl4I3DwC5XO4cgD3eYRcRPRO8rhUApdQ6vx2LxU4buRsxRPSA17yUy+V+qiXjOM5xv83MDwavaQUgWOqUUiWdvo1CKbXKa15E1QTn42WqCwDMfEvwmm4GiEBbaXnaouhmwDV5y7JWXgCY+VrVWC4ZYOkICyGImf129XgjKWUKQEJD33/j4+MFHR+iRisASilBRH47lAGZTOZtZt6nqQ+ZTOb1bDb7oU6/KDGeA6ozQCm1xsQB035RoZUBRCT8IVCdAV1dXXtmZmbyQohF62TmUiqV+n5iYkLHjUgxHgKu64YCcPjw4QqAb3QdsG1bt0ukGA+BRCKxLKqAcQCqM+BmxXgdsCIDEMyAWCxWc919s6E1CQYzwHGcUAYMDAxYyWTycWgshDRwOzo6Th47dqwYtWLjMmhZVigDksnku8y8O0LfQpRKpS8BPBu1XuOnweoMAFCOxKOFaYh+42eB1atXhwLQ19e3r1AojCqltHQu0i7Pzc39GrVeYAkLoUqlEgrAyMiIAvBzdK5pc5+U8lSd6zWzXXsO8NuJRKLVqsAqAA9dT0gIEXqTpV0F/Awol8uttg6YI6Lj9QSUUlcsy/o0eE53vMb8RqlUarUA/J7NZjO6nYwXQmvXrm21ABhhPASmp6dDc8Dw8PCqYrG4jZk7IvQPAOB9dTph2/ZM1LqNy+D69etDGVAsFt8H8KofoAbwNYCtUSs1XgjNzs6GAkBEF6NyqhbMPN0IvcZlcPPmzRx8mZHNZkeklGMAOqNzbx4hxFWlVENenpo+C7C38Alh2/ZUVI41C9Mh0GqLIGO0AiCE8Ge4ZVECAfMMWJkBCEyCyyYAoUlQSpkgohcBdNUSZubHvGYsk8m80QiHlFJXXdcdzefzDS2rPqEAENErzPzBIvrFmXl/IxwiIliW9SiAoUboryY0BJRSdzbD6CJINsvQQusANx6PdzfLCZ9KpfI5gCeaaXPBhdDo6OhsMx0BACll07faaVWB5Ug7AKEDIfz6TohgI7UBrve76GcUIvJljYZPdQb4mwyFlLLHROFSCDxS62zA9mX/MrEZCgAzX3utTUSRf4VZBCe933t27NjRfz3hoaGhjQC2AAAz13slviDVGfADgD88hW9KKW8zUWqK4ziTACoAoJR6T0oZqyNOrusexPw9MDOPm9gMGThz5gynUqkrAJ4DcDuAJ3t7e784e/Zs5B8la1EoFP5JpVJ3AXgYwEYAvZs2bTo+NTUV+iw2ODjY0d/f/xGAFwCAmT/L5XKHTGzWmuhISpnF/CZkAJgFcATAt0R0xcSIDt5W1qMA7vBOXfDsn2RmRUT9AIYB3OtdPxePxx8xXbfUnOkHBwc7Ojs7P/EMtTI/uq77/OTk5J+mCuqWunQ6vY2I9gJ4GvofURrJaQCHABy1bdu9nnA9FlXrd+7ceWu5XN4ghFijlKosxaApSinLsqyKEOL82NjYpRvhQ5s2bdosO/4H34MRzzxn6msAAAAASUVORK5CYII="></a></li>
                <li><a href="logout.php" onclick="return confirm('Do you want to log out?');"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAACmklEQVRoge2Yv4sTQRTHv28SQ4z/gKcHFilTqIVea6FycBzImsnFSjiRwx+1hY1p7C3u1MaIRSBkQJYggiB4pRoQi6S3ELS4IlHuDLtmnkX2IFccN7OZEIv5NLuEfd/3/e5mdt8u4PF4PJ4pIFdCtVpNdLvd60R0lpmz1kaI/gL4WiqVXtdqNW1aZ93osP69Xi8kotXETGqhXq/XBnANABs1Tt1pAinlCoA3AH4A2ErOphXJVbsP4CQRrbRarbcmda6uwLlkW1dKPU4rUi6XC0T0kJnPAzAKINI2m4SIjiVb6zN/wIwQ8aSeUc00Df8HfIB54wMAgNZ6DwCYedeFng1ObqNRFD3L5/M7w+FQudCzwUmAdrv9G0DdhZYtqQNIKU8T0W1mfq6U+unSlA2pAgRBsAhgm5mLzNwH8MStLXOsF3EQBIuZTGYbQBFAh4heurdljlWAxPwHjM1/AbCslBrMxJkhxn+harV6ajQa7Z95TUQdrfWDcrlsVJ/MSZuu14txAK31GsbmAUAw84bt3M/MO3C8XowDMHMdwA0AF5KfNonou0X9bhRFryz9HYlxAKXUQEp5BcA7AEsAVoUQl5rN5jfXpmywWsRKqUEcx8sAOgDOjEaj90EQLMzGmhnWt9EwDPtxHF/FOERRCLHm3pY5qYa5MAz7uVzuMhHdiqLohWtTNqQeJRqNxi/Maf6ZxMkwFwTBQjab3ZjHXOTkfSCTyawz8yMiWnehZ4PTrxIAci70bPCvlPPGB5g3TgIw8zDZPT6lTiHZ/WNa4+Q5wMwfiQjMfEdKWUjzeYWITgC4ua9nXGfb6DAqlcoWM9+dVoeInrZarXvGx0/bcBIp5UVmXhJCFI4++iBa6z0i+qSU+uzSk8fj8cyWf82k4Tv/UUmuAAAAAElFTkSuQmCC"></a></li>
            </ul>
        </div>
        <div class="wide">
            <div class="navbar">
                <div class="left collapse"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABmJLR0QA/wD/AP+gvaeTAAACBUlEQVR4nO2ZTW7TQBiGnw+ugEQXnMD2qoq4COOpKAcIlEvkDiAqJLaVaJQlhW3uQD0SXCE5A8OixkKR2tjz49rS96yikd7P8z52Io8CiqIoiqIoiqIoiqIEIMaYN9ba14BkzGThaeyAuq7PReQKeFVV1UnTNDc5Mrl4EjvAey//fV5aay85cldDMrmIfgKcc7dVVZ0Ai3ZpceyuhmRyES0AoGmam6IononIy3ZpUZblC2vtt+1261NlcpBEAIBz7sdBodPdbvdgoZBMapIJgHlKSCoA5ichuQCYl4QsAmA+ErIJgHlIyCoApi8huwCYtoRRBMB0JYwmAKYpYVQB0BV6LiL/zgGn+/3+2NlhcKYvowsAcM59Pyi0qKrqV9M0t0MyZVn+ds79jNlL9HH4MfHe/4md8RgCxBjzSUTedgsin9fr9dehmc1mcx27mbEFiDHmw0GRL0VRXAD3/aCFZPpvKHbAkGu1Rd53C3dFlqvV6r5HOSQzbFMphvS5zhTLwzgCJlse8guYdHnI+x4g1tqPwKDyAZm4TeYYSlvEe3/RLfQsPzATv9EcM+dSHtILmFV5SCtgduUhnYBZloc0AsRae+m9X3YLd+/273jg9TYgk4VoAXVdnwNX3cAeRUIyuUj673DfIiGZXCT5ChhjzgDa42mfIiEZRVEURVEURVEURVHS8BeiPUB1BfqrXAAAAABJRU5ErkJggg=="></div>
                <ul>
                    <li>
                        <a href="inventory.php">
                            <div class="left"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAABg0lEQVRoge2ZzU3DQBBGP9vUwQVuFICEQEK0YEY+QBUUQh/WICghB5JD2gDq8HKJ0QJZe7w/8WLmHeOxPW9Hu5+sAIqShCLVc5umOe+6jgDUu/c8lWXJbdtuAZjoL4z5sLquz6qqImPMPYATR9mHMea5KApm5g0iSYWK2Ct/C+DYuvaG3RQAYKwmdFJeIgMr/26MeRlabeveOwCn1qWgSYlFQpoXPDNYalAkRfOCd3lJ/RI5ZPMufKS+iRDRCsC19VO0zejJ0GGyYuabr0L7LiLqG32csXkXttQDADDz/q1BRMaSyZZ9fZZzNRMbFcmNI0kREW0AXCTuxcWama/GiqQTmUsCAC4lRaKJ9DiPu0RMOUEXs0dUJDdUJDcWIzLp+B1jYnCKgk5K7IlMCU5R0EmJOpGeseBM8amwmD2iIrmhIrmhIrnhnSNDKS7NiT113mkfMpEUn7/eaR+c7NIUT532i9kjKpIbKpIbKpIbrn+s/gR2Nv2cyPrAvYTwOncDyr/gEwgV6TaoFPsuAAAAAElFTkSuQmCC"></div>
                            <p>Inventory</p>
                        </a>
                    </li>
                    <li>
                        <a href="products.php">
                            <div class="left"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAACKElEQVRoge2YMYgTQRSGv9nIxUJbW7HWwkJRBCsPGzFoMrOetWksrSy9UktBG/GuES0y2RhSHWcjaiGc5dkK2gZBsUvMjkUSCHF3b3cz2dsL83WZ98j7f96bCXngcDiSEGmSlFJngA5wfrly/mMvDMONIAi+HZR4oBEp5RUhxFvglBVp2flpjJHtdvt9UlKiEd/37xpjtoHjwO5wOLzT7XZ/ZVGhlDIAWutU3Z9Sq9VOVqvV18BNYCCEuN9qtbbj8r2Yc+H7/qYx5g1jEy/6/f6NrCYWodfr/QFuG2OeAGvGmC0p5VOlVCVS8PyBUuoE8Aq4BfwFHmitn+UVlLcjs0gpm0KI58AasANsaK1/z+ZEGdkDLuQtWhBftNYXZw+iRqvsJiBC47G4zEVGYZlMR3WeuMt+5HBGykbsHclDvV4/XalUtoCrjJ/KKAbAB+Ce1vqHrdpWOzIxcY14E0xi68BLm7WtdgS4BBCG4bkgCL5GJTQajbOe5+0Dl20Wtm3kM7Dued6+UipNrjWsjtZoNGoC7xjfgzgGwC7QtFnbakc6nc534LrN70zLyjy/zkjZcEbKhjNSNpyRsuGMLIrv+5tSysdZY3EcipHJ8u+REOLhvOCkWBKFG5kKnX6eFZwUy4xSysStXGySVCdPzF32srEyRmz/Z09N0ko2z7q2cCN5HpI0xtxo5WVZW/6V6cjKGIkdrSJ+3W0S1ZFPhavIzsfDFuBwHDX+AZTiuWzva/QsAAAAAElFTkSuQmCC"></div>
                            <p>Products</p>
                        </a>
                    </li>
                    <li>
                        <a href="suppliers.php" class="notmain">
                            <div class="left"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAENElEQVRoge2aW2hcRRjHf9/ZJfZqjC1JzpqKN5D6IEillpqitWqgEARlc7ZeEEVao1TEC1gfpKAv+qAo+KBESFshWVelFNToQys0RWtFvNBaoS3FNtmNt1iKNIbd8/chWzE1m+yeORul5P9y5rDzffP778ycmTm7MKc5zWk6WT2SXnq3moolOgRrMVYgmoElGF/lC6zlUyvG3WasRpo3qCUZ8rRgE7BoqjqC+wtZ2x5nuxCjkdaMbjbRD7SUE+8HPg6NL5LGiWJIu8HrwCjGgMTAkpD+gzkbj6P9WIykutQp430gaWJ3aDxTyNqBSZXSSvgee4A1/2j8qIxMvt++dGVwNtKa1jXm8TmwWPBSYTlb2GphpfoX36MLG8a5zjy2AB0Gp8KQ1YWcHXJlcZIfaMAPpFRGvbVFyvxA2/xA8gMNunJ4LsEtgVYBHcBowxiP1xZtCht4BBgBbmxN6yYXFicjCbgTQOLN4zvt91rjR3bYH4i3ADyPLhcWJyMybgEw+DAygMduAMEqFxYnI4g2gPACDkdNUSxxrFy8wgXFzQhcBLD4F05FTbAUhgAMFrqAuA0tOAFwehGpqDl+hUYAiREXFicjJr4GMI/rI+dIsqJMctSFxc2I8R6AQSZyjpAHAUy868LiOkcuAzC4JGoCwbrytdEFxHWOPAkQwuaoOUwE5eujLiyuPbIAoCHBkcgAYh+AjCYXEDcjxgGAYsjtUVMUE9xQLn7vguL61HobQCGvpbrUWWt8KtAdJrYDCHa6sDgZGR6hV2IXRrOM52uNF7wILBNkSyEvu7DEcLCS+QE/AU2JkOaTOfutmqiWtC73PI4BQ/mstblSuE52wGSwB0gUExNrQlUNe2wsFz9wZ4jFCAA9ACaeXZbRjNuVpffKN+gGMHgjDoD4Xj4E6jPIGOwfztq/tuR+oH3A6rjamyRjMK4eYd58HgAQfz9Oz1V9TEw02p6MK9fxXhvzA81YL5+1WN+l+YEE8c2R/1yx9YiL/Iz2ItqnrWQM5vttTaWPY+uRVFpXl4t/xpVzksS04zaWHmnboGtLIdny7Y5a46f7pquVk5HmQFcmYHMppBtoMPjuTMhTrlBRVLuRtBIpYz3GRsF6JoanDHrHz/DY6C47HTtlFaraSKpTC7SQTYQ8IePs3mhM0JeAV4ey9k2dGKvSzEa2yms9TLdCnkM0l/cCP8joocS2Qs5+rjdkNZrWSFNajfMOkcO4DQMZnxHyQuEdPgKbefWbRVU00pbW/FKCTxArgRETD+ez5nT4qacqGil5vIJYKTjihawbztmPswlWq6ZcEP1Ay4GHgGLS467/uwmotLIb9wEJg4GTffbt7CJF09RGxK0AmNsLgdlUpTlyFYBEjx+oZxZ5IqvS0Do4yxzOmrJH4tjEnauzB6B66bw5WNXlvyhT6fzpEcP5t/RptPcv0k5Df8OlDIYAAAAASUVORK5CYII="></div>
                            <p>Suppliers</p>
                        </a>
                    </li>
                    <li>
                        <a href="branches.php">
                            <div class="left"><img src="data:image/png;base64,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"></div>
                            <p>Branches</p>
                        </a>
                    </li>
                    <li>
                        <a href="orders.php">
                            <div class="left"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABmJLR0QA/wD/AP+gvaeTAAAFoklEQVR4nO2aXWgcVRTH/+fu7Ka1wY/UbKxQW6UaSfelEfEbA5VCBF/s3m2gUoMPoiDYh1Lpg5IXpWC1KFqkIC0UErOTzT6IaF/Egl+UWhCx29Ani1ZT2wRTV7a7M/f4kJkys2y2uTez222yv5e9M3PuOWfOnnvPnZkLtGnTps0KhpaqYPv27fcLIe4mongUDkWFUsoholO2bf9bT840AJROp18mon0ANhjqaAYOgO8A7Ldt+6taAiYBoEwmc5SZdy3JtSbDzAcmJib2AuDgee0ApNPp3UR00Du8QEQHmLlARE4UjprAzG8BeMo7vMzMR4joNwB9AHYBWAMARPRaNpv9ONhXKwADAwNWd3f3BQDdAM47jtOfz+cvL/kOloCUshdAAfP38guArbZt/+1f9+aoEwDWAZgBsCE4LwgdYz09PVswf/Ng5ndu9M17pOH9kUqpl4I3DwC5XO4cgD3eYRcRPRO8rhUApdQ6vx2LxU4buRsxRPSA17yUy+V+qiXjOM5xv83MDwavaQUgWOqUUiWdvo1CKbXKa15E1QTn42WqCwDMfEvwmm4GiEBbaXnaouhmwDV5y7JWXgCY+VrVWC4ZYOkICyGImf129XgjKWUKQEJD33/j4+MFHR+iRisASilBRH47lAGZTOZtZt6nqQ+ZTOb1bDb7oU6/KDGeA6ozQCm1xsQB035RoZUBRCT8IVCdAV1dXXtmZmbyQohF62TmUiqV+n5iYkLHjUgxHgKu64YCcPjw4QqAb3QdsG1bt0ukGA+BRCKxLKqAcQCqM+BmxXgdsCIDEMyAWCxWc919s6E1CQYzwHGcUAYMDAxYyWTycWgshDRwOzo6Th47dqwYtWLjMmhZVigDksnku8y8O0LfQpRKpS8BPBu1XuOnweoMAFCOxKOFaYh+42eB1atXhwLQ19e3r1AojCqltHQu0i7Pzc39GrVeYAkLoUqlEgrAyMiIAvBzdK5pc5+U8lSd6zWzXXsO8NuJRKLVqsAqAA9dT0gIEXqTpV0F/Awol8uttg6YI6Lj9QSUUlcsy/o0eE53vMb8RqlUarUA/J7NZjO6nYwXQmvXrm21ABhhPASmp6dDc8Dw8PCqYrG4jZk7IvQPAOB9dTph2/ZM1LqNy+D69etDGVAsFt8H8KofoAbwNYCtUSs1XgjNzs6GAkBEF6NyqhbMPN0IvcZlcPPmzRx8mZHNZkeklGMAOqNzbx4hxFWlVENenpo+C7C38Alh2/ZUVI41C9Mh0GqLIGO0AiCE8Ge4ZVECAfMMWJkBCEyCyyYAoUlQSpkgohcBdNUSZubHvGYsk8m80QiHlFJXXdcdzefzDS2rPqEAENErzPzBIvrFmXl/IxwiIliW9SiAoUboryY0BJRSdzbD6CJINsvQQusANx6PdzfLCZ9KpfI5gCeaaXPBhdDo6OhsMx0BACll07faaVWB5Ug7AKEDIfz6TohgI7UBrve76GcUIvJljYZPdQb4mwyFlLLHROFSCDxS62zA9mX/MrEZCgAzX3utTUSRf4VZBCe933t27NjRfz3hoaGhjQC2AAAz13slviDVGfADgD88hW9KKW8zUWqK4ziTACoAoJR6T0oZqyNOrusexPw9MDOPm9gMGThz5gynUqkrAJ4DcDuAJ3t7e784e/Zs5B8la1EoFP5JpVJ3AXgYwEYAvZs2bTo+NTUV+iw2ODjY0d/f/xGAFwCAmT/L5XKHTGzWmuhISpnF/CZkAJgFcATAt0R0xcSIDt5W1qMA7vBOXfDsn2RmRUT9AIYB3OtdPxePxx8xXbfUnOkHBwc7Ojs7P/EMtTI/uq77/OTk5J+mCuqWunQ6vY2I9gJ4GvofURrJaQCHABy1bdu9nnA9FlXrd+7ceWu5XN4ghFijlKosxaApSinLsqyKEOL82NjYpRvhQ5s2bdosO/4H34MRzzxn6msAAAAASUVORK5CYII="></div>
                            <p>Orders</p>
                        </a>
                    </li>
                    <li>
                        <a href="logout.php" onclick="return confirm('Do you want to log out?');">
                            <div class="left"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAACmklEQVRoge2Yv4sTQRTHv28SQ4z/gKcHFilTqIVea6FycBzImsnFSjiRwx+1hY1p7C3u1MaIRSBkQJYggiB4pRoQi6S3ELS4IlHuDLtmnkX2IFccN7OZEIv5NLuEfd/3/e5mdt8u4PF4PJ4pIFdCtVpNdLvd60R0lpmz1kaI/gL4WiqVXtdqNW1aZ93osP69Xi8kotXETGqhXq/XBnANABs1Tt1pAinlCoA3AH4A2ErOphXJVbsP4CQRrbRarbcmda6uwLlkW1dKPU4rUi6XC0T0kJnPAzAKINI2m4SIjiVb6zN/wIwQ8aSeUc00Df8HfIB54wMAgNZ6DwCYedeFng1ObqNRFD3L5/M7w+FQudCzwUmAdrv9G0DdhZYtqQNIKU8T0W1mfq6U+unSlA2pAgRBsAhgm5mLzNwH8MStLXOsF3EQBIuZTGYbQBFAh4heurdljlWAxPwHjM1/AbCslBrMxJkhxn+harV6ajQa7Z95TUQdrfWDcrlsVJ/MSZuu14txAK31GsbmAUAw84bt3M/MO3C8XowDMHMdwA0AF5KfNonou0X9bhRFryz9HYlxAKXUQEp5BcA7AEsAVoUQl5rN5jfXpmywWsRKqUEcx8sAOgDOjEaj90EQLMzGmhnWt9EwDPtxHF/FOERRCLHm3pY5qYa5MAz7uVzuMhHdiqLohWtTNqQeJRqNxi/Maf6ZxMkwFwTBQjab3ZjHXOTkfSCTyawz8yMiWnehZ4PTrxIAci70bPCvlPPGB5g3TgIw8zDZPT6lTiHZ/WNa4+Q5wMwfiQjMfEdKWUjzeYWITgC4ua9nXGfb6DAqlcoWM9+dVoeInrZarXvGx0/bcBIp5UVmXhJCFI4++iBa6z0i+qSU+uzSk8fj8cyWf82k4Tv/UUmuAAAAAElFTkSuQmCC"></div>
                            <p>Log Out</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="content">
            <div class="heading">
                <img src="img/logo/logo.png" alt="">
                <div class="user">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAABoUlEQVRIie3VsUsbYRjH8e9zBqRwB1IKUjqIi6Ax1Kn+CeUg0BAIgTiLOFrofl1L/wGzdDHTSwZBCfQ/KKWQoSRDB6eCdImtHqKQ93EwLSVpuPf0RSjkNz78eD4HdzwHszxQJE85juP5KIp2VLUBrI/GX1W1laZps9PpXHmHa7XaM+AYeD6l0gXKxpjvLvsCl1Icx/MZKMAGcDTq+oGjKNrJQP/gYRhue4NVdculN4pT1wkG1nLA69kVdzjMATt1XeGLHPAvn3AvB+zUdf24DnLALW9wmqZNbg9EVrpA0xs8OoXlDPz35bp22TnnUgLo9XrnxWLxA/ADeAwsANfAF+AdsGuMOXPdN8uDxem3mCRJ0O/3X1hrXwGbwCq37xhgAPRF5NNwODwslUqfkySx94Kr1erTIAjeiEgDWHR5SOBURFqq+t4Yc5oLrlQqC4VC4a2IbAOPHMHxXIrIvqomxpifmXC9Xl+x1n4Elu4IjufEWvuy3W5/+3s4cUCstW2PKMByEARmfPivy/XEIzp15wSsqnuAzws0EJHXHvfN8p/kBr/TfPFqWHKEAAAAAElFTkSuQmCC">
                    <p class="name"><?php echo $nama[0] ?></p>
                </div>
            </div>
            <div class="top form">
                <div class="breadcrumbs">
                    <a href="suppliers.php">Suppliers</a>
                    <p class="divider">/</p>
                    <p class="now"><?php echo $addOrEdit ?> Supplier</p>
                </div>
                <h1 class="title"><?php echo $addOrEdit ?> Supplier</h1>
            </div>
            <div class="main">
                <?php
                    $redirect = "";
                    $disabledOrNot = "";
                    function getAddressName($connect, $prvns, $kbptn, $kcmtn, $klrhn) {
                        $query = "SELECT * FROM provinces WHERE id = " . $prvns;
                        $result = mysqli_query($connect, $query);
                        $provinceName = mysqli_fetch_assoc($result);
                        $provinceName = ucwords(strtolower($provinceName['name']));

                        $query = "SELECT * FROM regencies WHERE id = " . $kbptn;
                        $result = mysqli_query($connect, $query);
                        $cityName = mysqli_fetch_assoc($result);
                        $cityName = ucwords(strtolower($cityName['name']));

                        $query = "SELECT * FROM districts WHERE id = " . $kcmtn;
                        $result = mysqli_query($connect, $query);
                        $subdistrictName = mysqli_fetch_assoc($result);
                        $subdistrictName = ucwords(strtolower($subdistrictName['name']));

                        $query = "SELECT * FROM villages WHERE id = " . $klrhn;
                        $result = mysqli_query($connect, $query);
                        $villageName = mysqli_fetch_assoc($result);
                        $villageName = ucwords(strtolower($villageName['name']));

                        return array($provinceName, $cityName, $subdistrictName, $villageName);
                    }

                    if (isset($_GET["id"])) {
                        if (!isset($_POST["submit"])) {
                            $id = $_GET["id"];

                            $queryS = "SELECT * FROM suppliers WHERE Id = $id";
                            $resultS = $connect -> query($queryS);
                            $rowS = mysqli_fetch_array($resultS);
                            $name = $rowS["Nama"];
                            $email = $rowS["Email"];

                            $queryA = "SELECT * FROM address WHERE Jenis = 'supplier' AND IdToko = $id";
                            $resultA = $connect -> query($queryA);
                            $rowA = mysqli_fetch_array($resultA);

                            $provinceName = getAddressName($connectI, $rowA["Provinsi"], $rowA["Kabupaten"], $rowA["Kecamatan"], $rowA["Kelurahan"])[0];
                            $cityName = getAddressName($connectI, $rowA["Provinsi"], $rowA["Kabupaten"], $rowA["Kecamatan"], $rowA["Kelurahan"])[1];
                            $subdistrictName = getAddressName($connectI, $rowA["Provinsi"], $rowA["Kabupaten"], $rowA["Kecamatan"], $rowA["Kelurahan"])[2];
                            $villageName = getAddressName($connectI, $rowA["Provinsi"], $rowA["Kabupaten"], $rowA["Kecamatan"], $rowA["Kelurahan"])[3];

                            $province = "<option value='" . $rowA["Provinsi"] . "' hidden selected>" . $provinceName . "</option>";
                            $city = "<option value='" . $rowA["Kabupaten"] . "' hidden selected>" . $cityName . "</option>";
                            $subdistrict = "<option value='" . $rowA["Kecamatan"] . "' hidden selected>" . $subdistrictName . "</option>";
                            $village = "<option value='" . $rowA["Kelurahan"] . "' hidden selected>" . $villageName . "</option>";
                            $address = $rowA["Alamat"];
                            $postalcode = $rowA["KodePos"];

                            $disabledOrNot = "";
                            $redirect = "formSuppliers.php?id=$id";
                        } else {
                            $provinceName = getAddressName($connectI, $_POST['province'], $_POST['city'], $_POST['subdistrict'], $_POST['village'])[0];
                            $cityName = getAddressName($connectI, $_POST['province'], $_POST['city'], $_POST['subdistrict'], $_POST['village'])[1];
                            $subdistrictName = getAddressName($connectI, $_POST['province'], $_POST['city'], $_POST['subdistrict'], $_POST['village'])[2];
                            $villageName = getAddressName($connectI, $_POST['province'], $_POST['city'], $_POST['subdistrict'], $_POST['village'])[3];

                            $province = "<option value='" . $_POST['province'] . "' hidden selected>" . $provinceName . "</option>";
                            $city = "<option value='" . $_POST['city'] . "' hidden selected>" . $cityName . "</option>";
                            $subdistrict = "<option value='" . $_POST['subdistrict'] . "' hidden selected>" . $subdistrictName . "</option>";
                            $village = "<option value='" . $_POST['village'] . "' hidden selected>" . $villageName . "</option>";
                        }
                    } else {
                        if (isset($_POST["submit"])) {
                            $provinceName = getAddressName($connectI, $_POST["province"], $_POST["city"], $_POST["subdistrict"], $_POST["village"])[0];
                            $cityName = getAddressName($connectI, $_POST["province"], $_POST["city"], $_POST["subdistrict"], $_POST["village"])[1];
                            $subdistrictName = getAddressName($connectI, $_POST["province"], $_POST["city"], $_POST["subdistrict"], $_POST["village"])[2];
                            $villageName = getAddressName($connectI, $_POST["province"], $_POST["city"], $_POST["subdistrict"], $_POST["village"])[3];

                            $province = "<option value='" . $_POST["province"] . "' hidden selected>" . $provinceName . "</option>";
                            $city = "<option value='" . $_POST["city"] . "' hidden selected>" . $cityName . "</option>";
                            $subdistrict = "<option value='" . $_POST["subdistrict"] . "' hidden selected>" . $subdistrictName . "</option>";
                            $village = "<option value='" . $_POST["village"] . "' hidden selected>" . $villageName . "</option>";
                        } else {
                            $province = "<option value='' disabled hidden selected>Select Supplier Province</option>";
                            $city = "<option value='' disabled hidden selected>Select Supplier City</option>";
                            $subdistrict = "<option value='' disabled hidden selected>Select Supplier Sub District</option>";
                            $village = "<option value='' disabled hidden selected>Select Supplier Village</option>";
                            $disabledOrNot = "disabled";
                            $redirect = "formSuppliers.php";
                        }
                    }
                ?>
                <form action="<?php echo $redirect ?>" method="POST" id="addedit">
                    <div class="modify input">
                        <div>
                            <label for="name">Name</label>
                            <input type="text" id="name" placeholder="Enter Supplier Name" name="name" value="<?php echo $name; ?>" required>
                            <?php echo $eName ?>
                        </div>
                        
                        <div>
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="Enter Supplier Email" value="<?php echo $email; ?>" required>
                            <?php echo $eEmail ?>
                        </div>
                        
                        <div>
                            <label for="province">Province</label>
                            <select required name="province" id="province" onchange="Province()">
                                <?php echo $province ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="city">City</label>
                            <select required name="city" id="city" <?php echo $disabledOrNot; ?> onchange="City()">
                                <?php echo $city ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="subdistrict">Sub District</label>
                            <select required name="subdistrict" id="subdistrict" <?php echo $disabledOrNot; ?> onchange="SubDistrict()">
                                <?php echo $subdistrict ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="village">Village</label>
                            <select required name="village" id="village" <?php echo $disabledOrNot; ?> onchange="Village()">
                                <?php echo $village ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="address">Address</label>
                            <input type="text" id="address" placeholder="Enter Supplier Address" name="address" value="<?php echo $address; ?>" required>
                            <?php echo $eAddress ?>
                        </div>
                        
                        <div>
                            <label for="postalcode">Postal Code</label>
                            <input type="number" id="postalcode" placeholder="Enter Supplier Postal Code" name="postalcode" value="<?php echo $postalcode; ?>" required>
                            <?php echo $ePostalCode ?>
                        </div>
                    </div>
                    
                    <div class="button">
                        <input type="button" class="reset" value="Reset">
                        <input type="submit" name="submit" id="submit" value="<?php echo $addOrEdit ?>">
                    </div>

                    <?php
                        if (isset($_GET["id"])) {
                            if (!isset($_POST["submit"])) {
                                $id = $_GET["id"];
    
                                $queryS = "SELECT * FROM suppliers WHERE Id = $id";
                                $resultS = $connect -> query($queryS);
                                $rowS = mysqli_fetch_array($resultS);
                                $name = $rowS["Nama"];
                                $email = $rowS["Email"];
    
                                $queryA = "SELECT * FROM address WHERE Jenis = 'supplier' AND IdToko = $id";
                                $resultA = $connect -> query($queryA);
                                $rowA = mysqli_fetch_array($resultA);
    
                                $provinceName = getAddressName($connectI, $rowA["Provinsi"], $rowA["Kabupaten"], $rowA["Kecamatan"], $rowA["Kelurahan"])[0];
                                $cityName = getAddressName($connectI, $rowA["Provinsi"], $rowA["Kabupaten"], $rowA["Kecamatan"], $rowA["Kelurahan"])[1];
                                $subdistrictName = getAddressName($connectI, $rowA["Provinsi"], $rowA["Kabupaten"], $rowA["Kecamatan"], $rowA["Kelurahan"])[2];
                                $villageName = getAddressName($connectI, $rowA["Provinsi"], $rowA["Kabupaten"], $rowA["Kecamatan"], $rowA["Kelurahan"])[3];
    
                                $province = "<option value='" . $rowA["Provinsi"] . "' hidden selected>" . $provinceName . "</option>";
                                $city = "<option value='" . $rowA["Kabupaten"] . "' hidden selected>" . $cityName . "</option>";
                                $subdistrict = "<option value='" . $rowA["Kecamatan"] . "' hidden selected>" . $subdistrictName . "</option>";
                                $village = "<option value='" . $rowA["Kelurahan"] . "' hidden selected>" . $villageName . "</option>";
                                $address = $rowA["Alamat"];
                                $postalcode = $rowA["KodePos"];
    
                                $disabledOrNot = "";
                                $redirect = "formSuppliers.php?id=$id";
                            } else {
                                $provinceName = getAddressName($connectI, $_POST['province'], $_POST['city'], $_POST['subdistrict'], $_POST['village'])[0];
                                $cityName = getAddressName($connectI, $_POST['province'], $_POST['city'], $_POST['subdistrict'], $_POST['village'])[1];
                                $subdistrictName = getAddressName($connectI, $_POST['province'], $_POST['city'], $_POST['subdistrict'], $_POST['village'])[2];
                                $villageName = getAddressName($connectI, $_POST['province'], $_POST['city'], $_POST['subdistrict'], $_POST['village'])[3];
    
                                $province = "<option value='" . $_POST['province'] . "' hidden selected>" . $provinceName . "</option>";
                                $city = "<option value='" . $_POST['city'] . "' hidden selected>" . $cityName . "</option>";
                                $subdistrict = "<option value='" . $_POST['subdistrict'] . "' hidden selected>" . $subdistrictName . "</option>";
                                $village = "<option value='" . $_POST['village'] . "' hidden selected>" . $villageName . "</option>";
                            }   
                    ?>
                            <script>
                                let reset = document.querySelector("form input.reset");
                                reset.addEventListener("click", function() {
                                    let name = document.querySelector("input#name");
                                    let email = document.querySelector("input#email");
                                    let province = document.querySelector("select#province");
                                    let city = document.querySelector("select#city");
                                    let subdistrict = document.querySelector("select#subdistrict");
                                    let village = document.querySelector("select#village");
                                    let address = document.querySelector("input#address");
                                    let postalcode = document.querySelector("input#postalcode");

                                    <?php
                                        $nameValue = $rowS["Nama"];
                                        $emailValue = $rowS["Email"];
                                        $provinceValue = $rowA["Provinsi"];
                                        $cityValue = $rowA["Kabupaten"];
                                        $subdistrictValue = $rowA["Kecamatan"];
                                        $villageValue = $rowA["Kelurahan"];
                                        $addressValue = $rowA["Alamat"];
                                        $postalcodeValue = $rowA["KodePos"];
                                    ?>

                                    name.value = "<?php echo $nameValue ?>";
                                    email.value = "<?php echo $emailValue ?>";
                                    address.value = "<?php echo $addressValue ?>";
                                    postalcode.value = "<?php echo $postalcodeValue ?>";

                                    const cProvince = document.getElementById("province");
                                    const cCity = document.getElementById("city");
                                    const cSubDistrict = document.getElementById("subdistrict");
                                    const cVillage = document.getElementById("village");

                                    cCity.removeAttribute("disabled");
                                    cSubDistrict.removeAttribute("disabled");
                                    cVillage.removeAttribute("disabled");

                                    cProvince.insertAdjacentHTML("beforeend", "<option selected hidden value='" + <?php echo $provinceValue ?> + "'>" + "<?php echo $provinceName ?>" + "</option>");
                                    cCity.insertAdjacentHTML("beforeend", "<option selected hidden value='" + <?php echo $cityValue ?> + "'>" + "<?php echo $cityName ?>" + "</option>");
                                    cSubDistrict.insertAdjacentHTML("beforeend", "<option selected hidden value='" + <?php echo $subdistrictValue ?> + "'>" + "<?php echo $subdistrictName ?>" + "</option>");
                                    cVillage.insertAdjacentHTML("beforeend", "<option selected hidden value='" + <?php echo $villageValue ?> + "'>" + "<?php echo $villageName ?>" + "</option>");
                                });
                            </script>
                    <?php
                        } else {
                    ?>
                            <script>
                                let reset = document.querySelector("form input.reset");
                                reset.addEventListener("click", function() {
                                    let name = document.querySelector("input#name");
                                    let email = document.querySelector("input#email");
                                    let province = document.querySelector("select#province");
                                    let city = document.querySelector("select#city");
                                    let subdistrict = document.querySelector("select#subdistrict");
                                    let village = document.querySelector("select#village");
                                    let address = document.querySelector("input#address");
                                    let postalcode = document.querySelector("input#postalcode");

                                    <?php
                                        $nameValue = "";
                                        $emailValue = "";
                                        $provinceValue = "";
                                        $cityValue = "";
                                        $subdistrictValue = "";
                                        $villageValue = "";
                                        $addressValue = "";
                                        $postalcodeValue = "";
                                    ?>

                                    name.value = "";
                                    email.value = "";
                                    address.value = "";
                                    postalcode.value = "";

                                    name.value = "<?php echo $nameValue ?>";
                                    email.value = "<?php echo $emailValue ?>";
                                    address.value = "<?php echo $addressValue ?>";
                                    postalcode.value = "<?php echo $postalcodeValue ?>";

                                    const cProvince = document.getElementById("province");
                                    const cCity = document.getElementById("city");
                                    const cSubDistrict = document.getElementById("subdistrict");
                                    const cVillage = document.getElementById("village");

                                    cCity.removeAttribute("disabled");
                                    cSubDistrict.removeAttribute("disabled");
                                    cVillage.removeAttribute("disabled");

                                    cProvince.insertAdjacentHTML("beforeend", "<option selected hidden value='" + "<?php echo $provinceValue ?>" + "'>Select Supplier Province</option>");
                                    cCity.insertAdjacentHTML("beforeend", "<option selected hidden value='" + "<?php echo $cityValue ?>" + "'>Select Supplier City</option>");
                                    cCity.setAttribute("disabled", "");
                                    cSubDistrict.insertAdjacentHTML("beforeend", "<option selected hidden value='" + "<?php echo $subdistrictValue ?>" + "'>Select Supplier Sub District</option>");
                                    cSubDistrict.setAttribute("disabled", "");
                                    cVillage.insertAdjacentHTML("beforeend", "<option selected hidden value='" + "<?php echo $villageValue ?>" + "'>Select Supplier Village</option>");
                                    cVillage.setAttribute("disabled", "");
                                });
                            </script>
                    <?php
                        }
                    ?>
                </form>
            </div>
        </div>
    </div>

    <script>
        // show options
        const cProvince = document.getElementById("province");
        const cCity = document.getElementById("city");
        const cSubDistrict = document.getElementById("subdistrict");
        const cVillage = document.getElementById("village");

        let province = "";
        let city = "";
        let subdistrict = "";
        let village = "";

        function titleCase(str) {
            var splitStr = str.toLowerCase().split(' ');
            for (var i = 0; i < splitStr.length; i++) {
                splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);     
            }
            return splitStr.join(' '); 
        }

        function addHMTL(data, container) {
            let textHTML = "";
            for (let i = 0; i < data.length; i++) {
                data[i].name = titleCase(data[i].name);

                textHTML += "<option value='" + data[i].id + "'>" + data[i].name + "</option>";
            }
            container.insertAdjacentHTML("beforeend", textHTML);
        }

        // provinces
        cProvince.addEventListener("mousedown", function() {
            const request = new XMLHttpRequest();
            request.open(
                "GET",
                "https://helenry.github.io/indonesia/provinces.json"
            );
            request.onload = function () {
                let data = JSON.parse(request.responseText);
                addHMTL(data, cProvince);
            };
            request.send();
        }, {once: true});

        function provinceClear() {
            cSubDistrict.innerHTML = "<option value='' disabled hidden selected>Select Supplier Sub District</option>";
            cVillage.innerHTML = "<option value='' disabled hidden selected>Select Supplier Village</option>";
            cSubDistrict.setAttribute("disabled", "");
            cVillage.setAttribute("disabled", "");
        }
        
        function showCities() {
            const request = new XMLHttpRequest();
            request.open(
                "GET",
                "https://helenry.github.io/indonesia/cities.json"
            );
            request.onload = function () {
                let data = JSON.parse(request.responseText);
                provinceID = String(getProvince());
                data = data.filter(x => x.province_id == provinceID);
                addHMTL(data, cCity);
            };
            request.send();
        }

        function Province() {
            provinceClear();
            cCity.removeAttribute("disabled");
            cCity.innerHTML = "<option value='' disabled hidden selected>Select Supplier City</option>";
            cSubDistrict.innerHTML = "<option value='' disabled hidden selected>Select Supplier Sub District</option>";
            cVillage.innerHTML = "<option value='' disabled hidden selected>Select Supplier Village</option>";

            showCities();
        }

        showCities();

        function getProvince() {
            province = cProvince.value;
            return province;
        }

        // cities
        function cityClear() {
            cSubDistrict.innerHTML = "<option value='' disabled hidden selected>Select Supplier Sub District</option>";
            cVillage.innerHTML = "<option value='' disabled hidden selected>Select Supplier Village</option>";
            cSubDistrict.setAttribute("disabled", "");
            cVillage.setAttribute("disabled", "");
        }

        function showSubDistricts() {
            const request = new XMLHttpRequest();
            request.open(
                "GET",
                "https://helenry.github.io/indonesia/subdistricts.json"
            );
            request.onload = function () {
                let data = JSON.parse(request.responseText);
                provinceID = String(getProvince());
                cityID = String(getCity());
                data = data.filter(x => x.regency_id.substring(0, 2) == provinceID);
                data = data.filter(x => x.regency_id == cityID);
                addHMTL(data, cSubDistrict);
            };
            request.send();
        }

        function City() {
            cityClear();
            cSubDistrict.removeAttribute("disabled");

            showSubDistricts();
        }

        showSubDistricts();

        function getCity() {
            city = cCity.value;
            return city;
        }

        // subdistricts
        function subDistrictClear() {
            cVillage.innerHTML = "<option value='' disabled hidden selected>Select Supplier Village</option>";
            cVillage.setAttribute("disabled", "");
        }

        function showVillages() {
            const request = new XMLHttpRequest();
            request.open(
                "GET",
                "https://helenry.github.io/indonesia/villages.json"
            );
            request.onload = function () {
                let data = JSON.parse(request.responseText);
                provinceID = String(getProvince());
                cityID = String(getCity());
                subDistrictID = String(getSubDistrict());
                data = data.filter(x => x.district_id.substring(0, 2) == provinceID);
                data = data.filter(x => x.district_id.substring(0, 4) == cityID);
                data = data.filter(x => x.district_id == subDistrictID);
                addHMTL(data, cVillage);
            };
            request.send();
        }

        function SubDistrict() {
            subDistrictClear();
            cVillage.removeAttribute("disabled");

            showVillages();
        }

        showVillages();

        function getSubDistrict() {
            subdistrict = cSubDistrict.value;
            return subdistrict;
        }

        // villages
        function Village() {
            village = cVillage.value;
            return village;
        }

        function getVillage() {
            village = cVillage.value;
            return village;
        }
    </script>

    <script>
        let wide = document.querySelector(".wide");
        let expand = document.querySelector(".navbar:not(.wide .navbar) img.expand");
        let collapse = document.querySelector(".wide .navbar .collapse img");
        expand.addEventListener("click", function() {
            wide.style.display = "initial";
        });
        collapse.addEventListener("click", function() {
            wide.style.display = "none";
        });
    </script>
</body>
</html>