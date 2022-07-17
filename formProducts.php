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

    $email = $_SESSION["email"];
    $query = "SELECT Nama FROM employee WHERE Email = '$email'";
    $nama = mysqli_query($connect, $query);
    $nama = mysqli_fetch_array($nama, MYSQLI_BOTH);

    $name = $buyprice = $sellprice = "";

    // check form
    $eName = $eBuyprice = $eSellprice = "";

    if (isset($_POST["submit"])) {
        $name = check($_POST["name"]);
        if (strlen($name) < 4) {
            $eName = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Name length minimum 4.</p></div>";
        } elseif (strlen($name) > 60) {
            $eName = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Name length maximum 60.</p></div>";
        }

        $buyprice = $_POST['buyprice'];
        if ($buyprice < 1000) {
            $eBuyprice = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Buy price minimum 4.</p></div>";
        } else if ($buyprice > 100000000) {
            $eBuyprice = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Buy price maximum 100000000.</p></div>";
        }

        $sellprice = $_POST['sellprice'];
        if ($sellprice < 1000) {
            $eSellprice = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Sell price minimum 4.</p></div>";
        } else if ($sellprice > 100000000) {
            $eSellprice = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Sell price maximum 100000000.</p></div>";
        }

        // no error
        if ($eName == "" && $eBuyprice == "" && $eSellprice == "") {
            if (!isset($_GET["id"])) {
                if ($_POST["supplier"] == "") {
                    $supplier = "null";
                } else {
                    $supplier = $_POST["supplier"];
                }
                mysqli_query($connect, "insert into produk set
                    NamaProduk = '$_POST[name]',
                    KodeProduk = null,
                    HargaBeli = '$_POST[buyprice]',
                    HargaJual = '$_POST[sellprice]',
                    Kategori = '$_POST[category]',
                    IdSupplier = $supplier");
                $name = "";
                $buyprice = "";
                $sellprice = "";
?>
                <script>
                    document.location.href = "products.php";
                    alert('Product added successfully.');
                </script>
        <?php
            } else {
                $isEdited = false;
                $id = $_GET["id"];

                $queryS = "SELECT * FROM produk WHERE KodeProduk = $id";
                $resultS = $connect -> query($queryS);
                $rowS = mysqli_fetch_array($resultS);
                $name = $rowS["NamaProduk"];
                $buyprice = $rowS["HargaBeli"];
                $sellprice = $rowS["HargaJual"];
                $category = $rowS["Kategori"];
                $supplier = $rowS["IdSupplier"];

                if ($_POST['name'] != $name) {
                    $query = sprintf("UPDATE produk SET NamaProduk = '%s' WHERE KodeProduk = %s", $_POST['name'], $id);
                    mysqli_query($connect, $query);
                    $isEdited = true;
                }
                if ($_POST['buyprice'] != $buyprice) {
                    $query = sprintf("UPDATE produk SET HargaBeli = '%s' WHERE KodeProduk = %s", $_POST['buyprice'], $id);
                    mysqli_query($connect, $query);
                    $isEdited = true;
                }
                if ($_POST['sellprice'] != $sellprice) {
                    $query = sprintf("UPDATE produk SET HargaJual = '%s' WHERE KodeProduk = %s", $_POST['sellprice'], $id);
                    mysqli_query($connect, $query);
                    $isEdited = true;
                }
                if ($_POST['category'] != $category) {
                    $query = sprintf("UPDATE produk SET Kategori = %s WHERE KodeProduk = %s", $_POST['category'], $id);
                    mysqli_query($connect, $query);
                    $isEdited = true;
                }
                if ($_POST['supplier'] != $supplier) {
                    $query = sprintf("UPDATE produk SET IdSupplier = %s WHERE KodeProduk = %s", $_POST['supplier'], $id);
                    mysqli_query($connect, $query);
                    $isEdited = true;
                }

                if ($isEdited == true) {
        ?>
                    <script>
                        alert('Product edited successfully.');
                    </script>
            <?php
                }
            ?>
                <script>
                    document.location.href = "products.php";
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
    <title><?php echo $addOrEdit ?> Product</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/products.css">
    <link rel="icon" type="image/x-icon" href="img/logo/favicon.png">
</head>
<body>
    <div class="container">
        <div class="navbar">
            <img class="expand" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABmJLR0QA/wD/AP+gvaeTAAACBUlEQVR4nO2ZTW7TQBiGnw+ugEQXnMD2qoq4COOpKAcIlEvkDiAqJLaVaJQlhW3uQD0SXCE5A8OixkKR2tjz49rS96yikd7P8z52Io8CiqIoiqIoiqIoiqIEIMaYN9ba14BkzGThaeyAuq7PReQKeFVV1UnTNDc5Mrl4EjvAey//fV5aay85cldDMrmIfgKcc7dVVZ0Ai3ZpceyuhmRyES0AoGmam6IononIy3ZpUZblC2vtt+1261NlcpBEAIBz7sdBodPdbvdgoZBMapIJgHlKSCoA5ichuQCYl4QsAmA+ErIJgHlIyCoApi8huwCYtoRRBMB0JYwmAKYpYVQB0BV6LiL/zgGn+/3+2NlhcKYvowsAcM59Pyi0qKrqV9M0t0MyZVn+ds79jNlL9HH4MfHe/4md8RgCxBjzSUTedgsin9fr9dehmc1mcx27mbEFiDHmw0GRL0VRXAD3/aCFZPpvKHbAkGu1Rd53C3dFlqvV6r5HOSQzbFMphvS5zhTLwzgCJlse8guYdHnI+x4g1tqPwKDyAZm4TeYYSlvEe3/RLfQsPzATv9EcM+dSHtILmFV5SCtgduUhnYBZloc0AsRae+m9X3YLd+/273jg9TYgk4VoAXVdnwNX3cAeRUIyuUj673DfIiGZXCT5ChhjzgDa42mfIiEZRVEURVEURVEURVHS8BeiPUB1BfqrXAAAAABJRU5ErkJggg==">
            <ul>
                <li><a href="inventory.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAABg0lEQVRoge2ZzU3DQBBGP9vUwQVuFICEQEK0YEY+QBUUQh/WICghB5JD2gDq8HKJ0QJZe7w/8WLmHeOxPW9Hu5+sAIqShCLVc5umOe+6jgDUu/c8lWXJbdtuAZjoL4z5sLquz6qqImPMPYATR9mHMea5KApm5g0iSYWK2Ct/C+DYuvaG3RQAYKwmdFJeIgMr/26MeRlabeveOwCn1qWgSYlFQpoXPDNYalAkRfOCd3lJ/RI5ZPMufKS+iRDRCsC19VO0zejJ0GGyYuabr0L7LiLqG32csXkXttQDADDz/q1BRMaSyZZ9fZZzNRMbFcmNI0kREW0AXCTuxcWama/GiqQTmUsCAC4lRaKJ9DiPu0RMOUEXs0dUJDdUJDcWIzLp+B1jYnCKgk5K7IlMCU5R0EmJOpGeseBM8amwmD2iIrmhIrmhIrnhnSNDKS7NiT113mkfMpEUn7/eaR+c7NIUT532i9kjKpIbKpIbKpIbrn+s/gR2Nv2cyPrAvYTwOncDyr/gEwgV6TaoFPsuAAAAAElFTkSuQmCC"></a></li>
                <li><a href="products.php" class="notmain"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAACQUlEQVRoge2YPYgTURDHf5OEqKCdH9ltxFoLC0URrDxsRFEwH2drGksr7UyppaCNeNeIOXMHKlZyNqIWB2d5tqIWSQ4UFRsNumNxCYTL7t7u5iXZC+/XLPtm2Jn/zszb5YHFYglDojgdKOqhTIanwNER57OZVS9LZb0uH7dy3FKIW9FT6vEMYb+Z3GIifFO43H4ir8PdQnBLOqvCPLATWN7xh/Kn5/IjTh5OWRWg1ZBI1e+x94Luye3iscB5oCPKteaizAf5Z/yXVdyK1lSosyHiQWudc3FFDMPXF/Kr7XFJlTtAXoU5p6R3KWrWz3/gLe0r6u5chkfAReCvwPVmQ+4lTShpRfpxy1pVuA/kUV7+Virfl+Rnv8/Aw52yrgLHkgYdE+9bDTnev+DXWmkXAT455oI8h2mFUdJr1c0EDPv2wwpJG4EzkgTnih7Uf8wJnAbyAW4d4I14XG0uyRdTsY1WpCviDMEi6NpmVHhoMrbRigicANAMR9oL8sHPpzCrh8VjDeGkydhGhQArwIx4rDll310SvO5VWTEZ2OywZ6mivGJjDoLoAMuiVE2GNlqRVl0+A2dNPjMqU7P9WiFpwwpJG1ZI2rBC0oYVMixuRWuFkt6OawvC9N9vJNyK1lS5JQKFktJelJtRbGGMvSK9RHv3Itzovf0wW2ycsmrQkYtJwuIksdlhTxtTI2QiuxaEH8kmOa4du5AkG0kUYba1kjKqU/6pqcjUCAlsrXF83U0yWBHh3QTyiMvbSSdgsWw3/gMDksKNsBMn2QAAAABJRU5ErkJggg=="></a></li>
                <li><a href="suppliers.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAETUlEQVRoge2aW2hcVRSGv3VGLImpsRhS1AreQMyDIJW21BStVQuFICTZZ3JBQZHWKhXxgtYHCeiLPigKPlgiTI2hndmOhIBafWhTm6K1Il5IVGj7YOlDTLBWEZKZZC8f5gRCzGQu+0wopf/LXuyz1tr/f9bZsy8MXMZlXMZykFok7enpWZPL5baLyFZgPdAMXAt8Pzk5uXVkZGQ27jGviDNZd3f32rm5uRfz+fwuEWlYwqW1qampB/gwznEhxop0dnbeJyIHgbVR1wngC+Bb59xZEWkVkfeA86p6CDgkIgettbk4xo9FSBiGbar6CYUKHw6C4OV0On1yoY8xJgEcAbYs6D7tnOvKZrPf+XLwFmKMaQG+AVaLyJstLS17+/r6XDH/3t7eq3O53F3AXmA7cAHYbK0d9+ERxxx5C1itqvuttS+Vch4cHPwbOAp8FYZhSlUfBfYBrT4kAp/gZDK5icJbPT87O/tsheG6atWqp4AJ4B5jzL0+XLyEOOfaAVR139DQ0F+Vxg8MDPyrqh8AiEjow8VLCHA/QBAEn3nkOAygqpt8iPgKWQeQz+d/rTaBiJyJzFt8iPgKuQZgenr6gkeOc1F7lQ8RXyFnARoaGq73yNEYtRM+RHyF/ACgqnd75Fgftad9iHgJUdUsgIh0VZtDRB6PzI99uHgJEZGbIvOGanOo6rbIbFzWsQR8P63nAYIg2FNtAhFJRubTPkR8hdQDJBKJU9UmUNXjkbnGh4ivkJMA+Xz+oWoTqOrGyPzFh4jvZP8oMt8Nw7Ct0vjOzs6HRWT+kDXkw8VLyNTUVAoYBppV9bVK40XkDeBGIE1hF1014jhYiTHmDwrfeLO19s9ygowxNwNngHPW2nW+JHznCICKyBEgsWBNKAc7o/bTGDjEIgTnXD+Aqr7S1dVVcrvS3t5+HbAbIAiC9+PgENvlQxiGB1S1Czhhrf3fltwYcxzYHNd4izAaS0UA6uvrH4vMjUVcaiUCoDW2e61UKjVtjCnpZ62N9VLQGKMQ0xy5GBDrTWO1MMYco/Qtyqi1dkuxh7FVxBhze2TOxJVzEXS5h7FUpKOj404KqzMiMlBp/HJvulx4CUkmk7c65/ZQWBOuBH5W1Rd8SVWDioUYYxIiskNVdzrndlD4PBVIzczMPDM8PPxP7CzLQNlC2tra6uvq6nap6nOqOr83mhaRAyLyTjqd/rFGHMtCSSF9fX3B+Pj4blV9VVWbo+7fRKRfVfdnMpnJGnMsC8sKMcY0jo2NWeDBqOtrEXk9k8l8TolfkZVGUSHGmDrgS2ADMCEiT2YyGa/DTy1RVIiIvK2qG4BTwLZMJvP7ytGqHEsKSSaTdzjnngBmnXMd2Wz2ohYBRVZ2VX0ESACHstnsTytLqToUE/JAZF60c2Ixis2R26K23xjTv1JkfFBs0zi2oixiwJIViWMTtxjzB6Ba4ZI5WNXkvyhL4VKqyGgNcx/7Dw3uXQQOdcOxAAAAAElFTkSuQmCC"></a></li>
                <li><a href="branches.php"><img src="data:image/png;base64,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"></a></li>
                <li><a href="orders.php"><img src="data:image/png;base64,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"></a></li>
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
                        <a href="products.php" class="notmain">
                            <div class="left"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAACQUlEQVRoge2YPYgTURDHf5OEqKCdH9ltxFoLC0URrDxsRFEwH2drGksr7UyppaCNeNeIOXMHKlZyNqIWB2d5tqIWSQ4UFRsNumNxCYTL7t7u5iXZC+/XLPtm2Jn/zszb5YHFYglDojgdKOqhTIanwNER57OZVS9LZb0uH7dy3FKIW9FT6vEMYb+Z3GIifFO43H4ir8PdQnBLOqvCPLATWN7xh/Kn5/IjTh5OWRWg1ZBI1e+x94Luye3iscB5oCPKteaizAf5Z/yXVdyK1lSosyHiQWudc3FFDMPXF/Kr7XFJlTtAXoU5p6R3KWrWz3/gLe0r6u5chkfAReCvwPVmQ+4lTShpRfpxy1pVuA/kUV7+Virfl+Rnv8/Aw52yrgLHkgYdE+9bDTnev+DXWmkXAT455oI8h2mFUdJr1c0EDPv2wwpJG4EzkgTnih7Uf8wJnAbyAW4d4I14XG0uyRdTsY1WpCviDMEi6NpmVHhoMrbRigicANAMR9oL8sHPpzCrh8VjDeGkydhGhQArwIx4rDll310SvO5VWTEZ2OywZ6mivGJjDoLoAMuiVE2GNlqRVl0+A2dNPjMqU7P9WiFpwwpJG1ZI2rBC0oYVMixuRWuFkt6OawvC9N9vJNyK1lS5JQKFktJelJtRbGGMvSK9RHv3Itzovf0wW2ycsmrQkYtJwuIksdlhTxtTI2QiuxaEH8kmOa4du5AkG0kUYba1kjKqU/6pqcjUCAlsrXF83U0yWBHh3QTyiMvbSSdgsWw3/gMDksKNsBMn2QAAAABJRU5ErkJggg=="></div>
                            <p>Products</p>
                        </a>
                    </li>
                    <li>
                        <a href="suppliers.php">
                            <div class="left"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAETUlEQVRoge2aW2hcVRSGv3VGLImpsRhS1AreQMyDIJW21BStVQuFICTZZ3JBQZHWKhXxgtYHCeiLPigKPlgiTI2hndmOhIBafWhTm6K1Il5IVGj7YOlDTLBWEZKZZC8f5gRCzGQu+0wopf/LXuyz1tr/f9bZsy8MXMZlXMZykFok7enpWZPL5baLyFZgPdAMXAt8Pzk5uXVkZGQ27jGviDNZd3f32rm5uRfz+fwuEWlYwqW1qampB/gwznEhxop0dnbeJyIHgbVR1wngC+Bb59xZEWkVkfeA86p6CDgkIgettbk4xo9FSBiGbar6CYUKHw6C4OV0On1yoY8xJgEcAbYs6D7tnOvKZrPf+XLwFmKMaQG+AVaLyJstLS17+/r6XDH/3t7eq3O53F3AXmA7cAHYbK0d9+ERxxx5C1itqvuttS+Vch4cHPwbOAp8FYZhSlUfBfYBrT4kAp/gZDK5icJbPT87O/tsheG6atWqp4AJ4B5jzL0+XLyEOOfaAVR139DQ0F+Vxg8MDPyrqh8AiEjow8VLCHA/QBAEn3nkOAygqpt8iPgKWQeQz+d/rTaBiJyJzFt8iPgKuQZgenr6gkeOc1F7lQ8RXyFnARoaGq73yNEYtRM+RHyF/ACgqnd75Fgftad9iHgJUdUsgIh0VZtDRB6PzI99uHgJEZGbIvOGanOo6rbIbFzWsQR8P63nAYIg2FNtAhFJRubTPkR8hdQDJBKJU9UmUNXjkbnGh4ivkJMA+Xz+oWoTqOrGyPzFh4jvZP8oMt8Nw7Ct0vjOzs6HRWT+kDXkw8VLyNTUVAoYBppV9bVK40XkDeBGIE1hF1014jhYiTHmDwrfeLO19s9ygowxNwNngHPW2nW+JHznCICKyBEgsWBNKAc7o/bTGDjEIgTnXD+Aqr7S1dVVcrvS3t5+HbAbIAiC9+PgENvlQxiGB1S1Czhhrf3fltwYcxzYHNd4izAaS0UA6uvrH4vMjUVcaiUCoDW2e61UKjVtjCnpZ62N9VLQGKMQ0xy5GBDrTWO1MMYco/Qtyqi1dkuxh7FVxBhze2TOxJVzEXS5h7FUpKOj404KqzMiMlBp/HJvulx4CUkmk7c65/ZQWBOuBH5W1Rd8SVWDioUYYxIiskNVdzrndlD4PBVIzczMPDM8PPxP7CzLQNlC2tra6uvq6nap6nOqOr83mhaRAyLyTjqd/rFGHMtCSSF9fX3B+Pj4blV9VVWbo+7fRKRfVfdnMpnJGnMsC8sKMcY0jo2NWeDBqOtrEXk9k8l8TolfkZVGUSHGmDrgS2ADMCEiT2YyGa/DTy1RVIiIvK2qG4BTwLZMJvP7ytGqHEsKSSaTdzjnngBmnXMd2Wz2ohYBRVZ2VX0ESACHstnsTytLqToUE/JAZF60c2Ixis2R26K23xjTv1JkfFBs0zi2oixiwJIViWMTtxjzB6Ba4ZI5WNXkvyhL4VKqyGgNcx/7Dw3uXQQOdcOxAAAAAElFTkSuQmCC"></div>
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
                            <div class="left"><img src="data:image/png;base64,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"></div>
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
                    <a href="products.php">Products</a>
                    <p class="divider">/</p>
                    <p class="now"><?php echo $addOrEdit ?> Product</p>
                </div>
                <h1 class="title"><?php echo $addOrEdit ?> Product</h1>
            </div>
            <div class="main">
                <?php
                    $redirect = "";
                    function getCSName($connect, $ctgry, $spplr) {
                        $query = "SELECT * FROM kategori WHERE Id = " . $ctgry;
                        $result = mysqli_query($connect, $query);
                        $categoryName = mysqli_fetch_assoc($result);
                        $categoryName = $categoryName['Nama'];

                        if (empty($spplr)) {
                            $supplierName = "";
                        } else {
                            $query = "SELECT * FROM suppliers WHERE Id = " . $spplr;
                            $result = mysqli_query($connect, $query);
                            $supplierName = mysqli_fetch_assoc($result);
                            $supplierName = $supplierName['Nama'];
                        }

                        return array($categoryName, $supplierName);
                    }

                    if (isset($_GET["id"])) {
                        if (!isset($_POST["submit"])) {
                            $id = $_GET["id"];

                            $queryS = "SELECT * FROM produk WHERE KodeProduk = $id";
                            $resultS = $connect -> query($queryS);
                            $rowS = mysqli_fetch_array($resultS);
                            $name = $rowS["NamaProduk"];
                            $buyprice = $rowS["HargaBeli"];
                            $sellprice = $rowS["HargaJual"];

                            $CSName = getCSName($connect, $rowS["Kategori"], $rowS["IdSupplier"]);
                            $categoryName = $CSName[0];
                            $supplierName = $CSName[1];

                            $category = "<option value='" . $rowS["Kategori"] . "' hidden selected>" . $categoryName . "</option>";
                            if (empty($supplierName)) {
                                $supplier = "<option value='0' hidden selected>Select Product Supplier</option>";
                            } else {
                                $supplier = "<option value='" . $rowS["IdSupplier"] . "' hidden selected>" . $supplierName . "</option>";
                            }

                            $redirect = "formProducts.php?id=$id";
                        } else {
                            $CSName = getCSName($connect, $_POST['category'], $_POST['supplier']);
                            $categoryName = $CSName[0];
                            $supplierName = $CSName[1];

                            $category = "<option value='" . $_POST['category'] . "' hidden selected>" . $categoryName . "</option>";
                            if (empty($supplierName)) {
                                $supplier = "<option value='0' hidden selected>Select Product Supplier</option>";
                            } else {
                                $supplier = "<option value='" . $_POST['supplier'] . "' hidden selected>" . $supplierName . "</option>";
                            }
                        }
                    } else {
                        if (isset($_POST["submit"])) {
                            $CSName = getCSName($connect, $_POST["category"], $_POST["supplier"]);
                            $categoryName = $CSName[0];
                            $supplierName = $CSName[1];

                            $category = "<option value='" . $_POST["category"] . "' hidden selected>" . $categoryName . "</option>";
                            if (empty($supplierName)) {
                                $supplier = "<option value='0' hidden selected>Select Product Supplier</option>";
                            } else {
                                $supplier = "<option value='" . $_POST["supplier"] . "' hidden selected>" . $supplierName . "</option>";
                            }
                        } else {
                            $category = "<option value='' hidden selected>Select Product Category</option>";
                            $supplier = "<option value='' hidden selected>Select Product Supplier</option>";
                            $redirect = "formProducts.php";
                        }
                    }
                ?>
                <form action="<?php echo $redirect ?>" method="POST" id="addedit">
                    <div class="modify input">
                        <div>
                            <label for="name">Name</label>
                            <input type="text" id="name" placeholder="Enter Product Name" name="name" value="<?php echo $name; ?>" required>
                            <?php echo $eName ?>
                        </div>

                        <div>

                        </div>
                        
                        <div>
                            <label for="buyprice">Buy Price</label>
                            <input type="number" id="buyprice" name="buyprice" placeholder="Enter Product Buy Price" value="<?php echo $buyprice; ?>" required>
                            <?php echo $eBuyprice ?>
                        </div>

                        <div>
                            <label for="sellprice">Sell Price</label>
                            <input type="number" id="sellprice" name="sellprice" placeholder="Enter Product Sell Price" value="<?php echo $sellprice; ?>" required>
                            <?php echo $eSellprice ?>
                        </div>
                        
                        <div>
                            <label for="category">Category</label>
                            <select required name="category" id="category">
                                <?php echo $category ?>
                            </select>
                        </div>

                        <div>
                            <label for="supplier">Supplier</label>
                            <select name="supplier" id="supplier">
                                <?php echo $supplier ?>
                            </select>
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
    
                                $queryS = "SELECT * FROM produk WHERE KodeProduk = $id";
                                $resultS = $connect -> query($queryS);
                                $rowS = mysqli_fetch_array($resultS);
                                $name = $rowS["NamaProduk"];
                                $buyprice = $rowS["HargaBeli"];
                                $sellprice = $rowS["HargaJual"];
    
                                $CSName = getCSName($connect, $rowS["Kategori"], $rowS["IdSupplier"]);
                                $categoryName = $CSName[0];
                                $supplierName = $CSName[1];
    
                                $category = "<option value='" . $rowS["Kategori"] . "' hidden selected>" . $categoryName . "</option>";
                                if (empty($supplierName)) {
                                    $supplier = "<option value='0' hidden selected>Select Product Supplier</option>";
                                } else {
                                    $supplier = "<option value='" . $rowS["IdSupplier"] . "' hidden selected>" . $supplierName . "</option>";
                                }
    
                                $redirect = "formProducts.php?id=$id";
                            } else {
                                $CSName = getCSName($connect, $_POST['category'], $_POST['supplier']);
                                $categoryName = $CSName[0];
                                $supplierName = $CSName[1];
    
                                $category = "<option value='" . $_POST['category'] . "' hidden selected>" . $categoryName . "</option>";
                                if (empty($supplierName)) {
                                    $supplier = "<option value='0' hidden selected>Select Product Supplier</option>";
                                } else {
                                    $supplier = "<option value='" . $_POST['supplier'] . "' hidden selected>" . $supplierName . "</option>";
                                }
                                
                            }   
                    ?>
                            <script>
                                let reset = document.querySelector("form input.reset");
                                reset.addEventListener("click", function() {
                                    let name = document.querySelector("input#name");
                                    let buyprice = document.querySelector("input#buyprice");
                                    let sellprice = document.querySelector("input#sellprice");
                                    let category = document.querySelector("select#category");
                                    let supplier = document.querySelector("select#supplier");

                                    <?php
                                        $nameValue = $rowS["NamaProduk"];
                                        $buypriceValue = $rowS["HargaBeli"];
                                        $sellpriceValue = $rowS["HargaJual"];
                                        $categoryValue = $rowS["Kategori"];
                                        $supplierValue = $rowS["IdSupplier"];
                                    ?>

                                    name.value = "";
                                    buyprice.value = "";
                                    sellprice.value = "";

                                    name.value = "<?php echo $nameValue ?>";
                                    buyprice.value = "<?php echo $buypriceValue ?>";
                                    sellprice.value = "<?php echo $sellpriceValue ?>";

                                    const cCategory = document.getElementById("category");
                                    const cSupplier = document.getElementById("supplier");

                                    cCategory.insertAdjacentHTML("beforeend", "<option selected hidden value='" + "<?php echo $categoryValue; ?>" + "'>" + "<?php echo $categoryName ?>" + "</option>");
                                    if ("<?php echo $supplierName ?>" != "") {
                                        cSupplier.insertAdjacentHTML("beforeend", "<option selected hidden value='" + "<?php echo $supplierValue; ?>" + "'>" + "<?php echo $supplierName ?>" + "</option>");
                                    } else {
                                        cSupplier.insertAdjacentHTML("beforeend", "<option value='0' hidden selected>Select Product Supplier</option>");
                                    }
                                    
                                });
                            </script>
                    <?php
                        } else {
                    ?>
                            <script>
                                let reset = document.querySelector("form input.reset");
                                reset.addEventListener("click", function() {
                                    let name = document.querySelector("input#name");
                                    let buyprice = document.querySelector("input#buyprice");
                                    let sellprice = document.querySelector("input#sellprice");
                                    let category = document.querySelector("select#category");
                                    let supplier = document.querySelector("select#supplier");

                                    <?php
                                        $nameValue = "";
                                        $buypriceValue = "";
                                        $sellpriceValue = "";
                                        $categoryValue = "";
                                        $supplierValue = "";
                                    ?>

                                    name.value = "";
                                    buyprice.value = "";
                                    sellprice.value = "";

                                    name.value = "<?php echo $nameValue ?>";
                                    buyprice.value = "<?php echo $buypriceValue ?>";
                                    sellprice.value = "<?php echo $sellpriceValue ?>";

                                    const cCategory = document.getElementById("category");
                                    const cSupplier = document.getElementById("supplier");

                                    cCategory.insertAdjacentHTML("beforeend", "<option selected hidden value='" + "<?php echo $categoryValue; ?>" + "'>Select Product Category</option>");
                                    cSupplier.insertAdjacentHTML("beforeend", "<option selected hidden value='" + "<?php echo $supplierValue; ?>" + "'>Select Product Supplier</option>");
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
        const cCategory = document.getElementById("category");
        const cSupplier = document.getElementById("supplier");

        let category = "";
        let supplier = "";

        function addHMTL(container, type) {
            let textHTML = "";
            <?php
                $queryTemp = "";
            ?>
            if (type == "category") {
                <?php
                    $queryTemp = "SELECT * FROM kategori ORDER BY Nama ASC";
                    $resultTemp = mysqli_query($connect, $queryTemp);
                    while($rowTemp = mysqli_fetch_array($resultTemp)) {
                ?>
                        textHTML += "<option value='" + <?php echo $rowTemp["Id"]; ?> + "'>" + "<?php echo $rowTemp["Nama"]; ?>" + "</option>";
                <?php
                    }
                ?>
            } else if (type == "supplier") {
                <?php
                    $queryTemp = "SELECT * FROM suppliers ORDER BY Nama ASC";
                    $resultTemp = mysqli_query($connect, $queryTemp);
                    while($rowTemp = mysqli_fetch_array($resultTemp)) {
                ?>
                        textHTML += "<option value='" + <?php echo $rowTemp["Id"]; ?> + "'>" + "<?php echo $rowTemp["Nama"]; ?>" + "</option>";
                <?php
                    }
                ?>
            }
            container.insertAdjacentHTML("beforeend", textHTML);
        }

        cCategory.addEventListener("mousedown", function() {
            addHMTL(cCategory, "category");
        }, {once: true});
        cSupplier.addEventListener("mousedown", function() {
            addHMTL(cSupplier, "supplier");
        }, {once: true});
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