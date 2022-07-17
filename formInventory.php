<?php
    $addOrEdit = "";
    if (isset($_GET["id"])) {
        $addOrEdit = "Edit";
    } else {
        $addOrEdit = "Order";
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

    $product = $quantity = $branch = "";

    // check form
    $eProductBranch = $eQuantity = "";

    if (isset($_POST["submit"])) {
        $quantity = $_POST['quantity'];
        $minQuantity = 0;
        if (isset($_GET['id'])) {
            $minQuantity = 0;
        } else {
            $minQuantity = 50;
        }
        if ($quantity < $minQuantity) {
            $eQuantity = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Quantity minimum $minQuantity.</p></div>";
        } else if ($quantity > 100000000) {
            $eQuantity = "<div class='warning'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD7ElEQVRoge2Z3WscVRjGf+/ZfDVN1aZZGxVLi6Gx3ZnZ1BU0FLFeiELvNLnwE6TFC70SRfGjBKTaUqJN/xcv9FK8UwrZXcVNRSKI0mQn1kL8aHdnXi+yrWnS3Z2PM4ZKH1g4M+d9z/M8886ZmXMWbuM2bk0se97UsudNZc0jWQ5e97yHUP0GQIyZHCmXv86Ky2Q1sIKgOtfiMGEYzmmGFy4zI77rPg88du1YYNJ33eey4svkCv08ObltYHW1BuzZ0PVLIDI+Wqn8YZszk4oMrK6+y2YTAPflVN/OgtN6RVYc5/5QpAYMtgn5q5nLHbxnfv4nm7zWKxKKzNLeBMC2njA8bZvXakWWCoXDxpivIo57JF+tfmmL21pFFIwxZo6oF0dkTqenc7b4rRnxXfcY8HDkBNUJf2HhFVv8Vm6t+vj4Dvr6LgCjMVOXc319+4fPn7+cVoOdivT3zxDfBMDdQaPxgQ0JqSuyXCiMiTHfAv0Jh7iKiJevVBbS6EhdEVmb4ElNAPQBs6l1pEleKhafMmH4eYeQBrDaag8Bve0CQ2Oe3l0uf5FUS+KKaKnUa8LwbJewU/lqdThfrQ4DHV+CJgzPaqnU1mg3JDbiNxqvAwe6hAXXW6pBhziAA/VG47WkehIZuVwoDKuqlafNeojqzK+l0kiS3ERGruZyHwnsSpLbBTt7r1z5MElibCMrxWIB1eNJyCJB5NUlx/HipsU2EqxN8J64eTGQa32zxUIsI0ue96zAk3FJYkP1Cd9xnomTEtnID2Nj/UbV+jqiHVRkdnHv3oGo8ZGN3Dk4+BYwlkhVMuzbMTT0RtTgSEaWHGe3ZLTW7gQVea9+6NC9UWIjGRE4A9yRSlUyDGmz+XGUwK5G6q5bEpEX02tKBoGXfc97pFtcRyMKgsi5bnEZQ0LVrruUHQWueN5LqB62qys+BB5dcZwXOsW0NXLR87araqT7swP+3VwQSbXRoCKnL3re9nb9bcvlO85JFXk/DTkx1iNRIHBypFo90aZvM34rFPYExnxP5422rcDfgcjB0UplcWPHTW+t0JhPsGOiCVxq/ZoWxhvoUT1zs45NRuqOc0TB1j9M76xbIVp5oSpM1V338Y3nbzCi09M5RGJ/ebYlVW1ca8vafLGFcxt3KW8w4tdqx4GiRcKsUPRrtWPrT1yf7JcmJu5qBsEFIG+RcBH4sdV+ANhncex6Ty63f+f8/O+wboHUDIIZ7JqANeE2xa9HvhkEJ4A3oVUR33UfVKiQ8jm/BWgaYyZ2lcvfGQCFT7n1TAD0tJbeSN1xjiLy2VYrSgOFo0ZF/rPla1YQOGUE/K0WkhYKf261htv43+If9PQTANGwIgMAAAAASUVORK5CYII='><p>Quantity maximum 100000000.</p></div>";
        }

        // no error
        if ($eQuantity == "") {
            if (!isset($_GET["id"])) {
                function generateRandomString($length = 15) {
                    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $charactersLength = strlen($characters);
                    $randomString = '';
                    for ($i = 0; $i < $length; $i++) {
                        $randomString .= $characters[rand(0, $charactersLength - 1)];
                    }
                    return $randomString;
                }
                $notransaksi = generateRandomString();
                $query = "SELECT * FROM pemesanan WHERE NoTransaksi = '$notransaksi'";
                $result = $connect -> query($query);
                while ($result -> num_rows != 0) {
                    $notransaksi = generateRandomString();
                    $query = "SELECT * FROM pemesanan WHERE NoTransaksi = '$notransaksi'";
                    $result = $connect -> query($query);
                }

                $product = $_POST["product"];
                $quantity = $_POST["quantity"];
                $branch = $_POST["branch"];

                $queryS = "SELECT * FROM produk WHERE KodeProduk = " . $_POST["product"];
                $resultS = $connect -> query($queryS);
                $rowS = mysqli_fetch_array($resultS);
                $total = $_POST["quantity"] * $rowS["HargaBeli"];
                $namaproduk = $rowS["NamaProduk"];
                $kategori = $rowS["Kategori"];
                $hargabeli = $rowS["HargaBeli"];
                $hargajual = $rowS["HargaJual"];
                $idsupplier = $rowS["IdSupplier"];

                date_default_timezone_set("Asia/Bangkok");
                $time = (new DateTime())->format("Y-m-d H:i:s");

                $queryO = "SELECT * FROM branch WHERE Id = $branch";
                $resultO = $connect -> query($queryO);
                $rowO = mysqli_fetch_array($resultO);
                $emailmanagercabang = $rowO["EmailManager"];

                $queryP = "SELECT * FROM address WHERE Jenis = 'branch' AND IdToko = $branch";
                $resultP = $connect -> query($queryP);
                $rowP = mysqli_fetch_array($resultP);
                $kelurahancabang = $rowP["Kelurahan"];
                $provinsicabang = $rowP["Provinsi"];
                $kabupatencabang = $rowP["Kabupaten"];
                $kecamatancabang = $rowP["Kecamatan"];
                $alamatcabang = $rowP["Alamat"];
                $kodeposcabang = $rowP["KodePos"];

                $queryQ = "SELECT * FROM suppliers WHERE Id = $idsupplier";
                $resultQ = $connect -> query($queryQ);
                $rowQ = mysqli_fetch_array($resultQ);
                $namasupplier = $rowQ["Nama"];
                $emailsupplier = $rowQ["Email"];

                $queryR = "SELECT * FROM address WHERE Jenis = 'supplier' AND IdToko = $idsupplier";
                $resultR = $connect -> query($queryR);
                $rowR = mysqli_fetch_array($resultR);
                $provinsisupplier = $rowR["Provinsi"];
                $kabupatensupplier = $rowR["Kabupaten"];
                $kecamatansupplier = $rowR["Kecamatan"];
                $kelurahansupplier = $rowR["Kelurahan"];
                $alamatsupplier = $rowR["Alamat"];
                $kodepossupplier = $rowR["KodePos"];

                mysqli_query($connect, "insert into pemesanan set
                    NoTransaksi = '$notransaksi',
                    WaktuDibuat = '$time',
                    WaktuDibayar = null,
                    WaktuDikirim = null,
                    WaktuDiterima = null,
                    Total = $total,
                    Status = 'Menunggu Pembayaran',
                    IdCabang = $branch,
                    EmailManagerCabang = '$emailmanagercabang',
                    KelurahanCabang = '$kelurahancabang',
                    ProvinsiCabang = '$provinsicabang',
                    KabupatenCabang = '$kabupatencabang',
                    KecamatanCabang = '$kecamatancabang',
                    AlamatCabang = '$alamatcabang',
                    KodePosCabang = $kodeposcabang,
                    IdSupplier = $idsupplier,
                    NamaSupplier = '$namasupplier',
                    EmailSupplier = '$emailsupplier',
                    ProvinsiSupplier = '$provinsisupplier',
                    KabupatenSupplier = '$kabupatensupplier',
                    KecamatanSupplier = '$kecamatansupplier',
                    KelurahanSupplier = '$kelurahansupplier',
                    AlamatSupplier = '$alamatsupplier',
                    KodePosSupplier = $kodepossupplier");

                mysqli_query($connect, "insert into produk_transaksi set
                    NoTransaksi = '$notransaksi',
                    KodeProduk = $product,
                    NamaProduk = '$namaproduk',
                    Kategori = '$kategori',
                    HargaBeli = $hargabeli,
                    HargaJual = $hargajual,
                    Kuantitas = $quantity,
                    Subtotal = $total");

                $quantity = "";
?>
                <script>
                    document.location.href = "orders.php";
                    alert('Item ordered successfully.');
                </script>
        <?php
            } else {
                $isEdited = false;
                $id = $_GET["id"];
                $cabang = $_GET["cabang"];

                $queryS = "SELECT * FROM inventory WHERE KodeProduk = $id AND IdCabang = $cabang";
                $resultS = $connect -> query($queryS);
                $rowS = mysqli_fetch_array($resultS);
                $quantity = $rowS["Stok"];
                $product = $rowS["KodeProduk"];
                $branch = $rowS["IdCabang"];

                if ($_POST['quantity'] != $quantity) {
                    $query = sprintf("UPDATE inventory SET Stok = %s WHERE KodeProduk = %s AND IdCabang = %s", $_POST['quantity'], $id, $cabang);
                    mysqli_query($connect, $query);
                    $isEdited = true;
                }
                if ($_POST['product'] != $product) {
                    $query = sprintf("UPDATE inventory SET KodeProduk = %s WHERE KodeProduk = %s AND IdCabang = %s", $_POST['product'], $id, $cabang);
                    mysqli_query($connect, $query);

                    $queryS = "SELECT * FROM produk WHERE KodeProduk = " . $_POST['product'];
                    $resultS = $connect -> query($queryS);
                    $rowS = mysqli_fetch_array($resultS);

                    $query = sprintf("UPDATE inventory SET NamaProduk = '%s' WHERE KodeProduk = %s AND IdCabang = %s", $rowS["NamaProduk"], $_POST['product'], $cabang);
                    echo $query;
                    mysqli_query($connect, $query);

                    $query = sprintf("UPDATE inventory SET Kategori = %s WHERE KodeProduk = %s AND IdCabang = %s", $rowS["Kategori"], $_POST['product'], $cabang);
                    mysqli_query($connect, $query);

                    $query = sprintf("UPDATE inventory SET HargaBeli = %s WHERE KodeProduk = %s AND IdCabang = %s", $rowS["HargaBeli"], $_POST['product'], $cabang);
                    mysqli_query($connect, $query);

                    $query = sprintf("UPDATE inventory SET HargaJual = %s WHERE KodeProduk = %s AND IdCabang = %s", $rowS["HargaJual"], $_POST['product'], $cabang);
                    mysqli_query($connect, $query);

                    $isEdited = true;
                }
                if ($_POST['branch'] != $branch) {
                    $query = sprintf("UPDATE inventory SET IdCabang = %s WHERE KodeProduk = %s AND IdCabang = %s", $_POST['branch'], $id, $cabang);
                    mysqli_query($connect, $query);
                    $isEdited = true;
                }

                if ($isEdited == true) {
        ?>
                    <script>
                        alert('Item edited successfully.');
                    </script>
            <?php
                }
            ?>
                <script>
                    document.location.href = "inventory.php";
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
    <title><?php echo $addOrEdit ?> Inventory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/inventory.css">
    <link rel="icon" type="image/x-icon" href="img/logo/favicon.png">
</head>
<body>
    <div class="container">
        <div class="navbar">
            <img class="expand" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABmJLR0QA/wD/AP+gvaeTAAACBUlEQVR4nO2ZTW7TQBiGnw+ugEQXnMD2qoq4COOpKAcIlEvkDiAqJLaVaJQlhW3uQD0SXCE5A8OixkKR2tjz49rS96yikd7P8z52Io8CiqIoiqIoiqIoiqIEIMaYN9ba14BkzGThaeyAuq7PReQKeFVV1UnTNDc5Mrl4EjvAey//fV5aay85cldDMrmIfgKcc7dVVZ0Ai3ZpceyuhmRyES0AoGmam6IononIy3ZpUZblC2vtt+1261NlcpBEAIBz7sdBodPdbvdgoZBMapIJgHlKSCoA5ichuQCYl4QsAmA+ErIJgHlIyCoApi8huwCYtoRRBMB0JYwmAKYpYVQB0BV6LiL/zgGn+/3+2NlhcKYvowsAcM59Pyi0qKrqV9M0t0MyZVn+ds79jNlL9HH4MfHe/4md8RgCxBjzSUTedgsin9fr9dehmc1mcx27mbEFiDHmw0GRL0VRXAD3/aCFZPpvKHbAkGu1Rd53C3dFlqvV6r5HOSQzbFMphvS5zhTLwzgCJlse8guYdHnI+x4g1tqPwKDyAZm4TeYYSlvEe3/RLfQsPzATv9EcM+dSHtILmFV5SCtgduUhnYBZloc0AsRae+m9X3YLd+/273jg9TYgk4VoAXVdnwNX3cAeRUIyuUj673DfIiGZXCT5ChhjzgDa42mfIiEZRVEURVEURVEURVHS8BeiPUB1BfqrXAAAAABJRU5ErkJggg==">
            <ul>
                <li><a href="inventory.php" class="notmain"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAABjUlEQVRoge2ZTU7DMBBGnyPuANmwgR0HQEIgIa4AVYTgFByEcxAF0SN0QbvgGoBacYkOmwYSNT9ObDcm+O3aTJN5HtmfokIg4ATl5rai9hNOI5gA15vnPK8h+0p5AyW2n2hV5OBWTiJhgnAvcFRZJHwCL0rIlhkLW1KGIqWVvwEOCxff2UwBoK3GdFK9RBpW/gNh2rTa+W/Xwp2C458LhpPSFjFpvu2eNqQaRVw03/asvlJbIrtsvo4+UiWROJEZcFn4ytpm7Ef9YaJgtkzVVeHzL3EieaOPwzVfR0nqAWCVquqtESciBRlvqeozGqoZ2wQR39jTKYoTWQBnjnupRjFfPamLtjLdiQwjASCc65RpTSSn9rhzRJcTdDR7JIj4RhDxjdGIdDp+2+gUnJpBp4vtiegHp2bQ6WJ1IjltweniVWE0eySI+EYQ8Y0g4hu9c6QpxXVzYqvOIO1NJmL/9dcg7Y2TXTfFXaf9aPZIEPGNIOIbQcQ36v6x+hMUs6k8EcV8593053XoBgL/gm/1v9vUkDae5QAAAABJRU5ErkJggg=="></a></li>
                <li><a href="products.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAACKElEQVRoge2YMYgTQRSGv9nIxUJbW7HWwkJRBCsPGzFoMrOetWksrSy9UktBG/GuES0y2RhSHWcjaiGc5dkK2gZBsUvMjkUSCHF3b3cz2dsL83WZ98j7f96bCXngcDiSEGmSlFJngA5wfrly/mMvDMONIAi+HZR4oBEp5RUhxFvglBVp2flpjJHtdvt9UlKiEd/37xpjtoHjwO5wOLzT7XZ/ZVGhlDIAWutU3Z9Sq9VOVqvV18BNYCCEuN9qtbbj8r2Yc+H7/qYx5g1jEy/6/f6NrCYWodfr/QFuG2OeAGvGmC0p5VOlVCVS8PyBUuoE8Aq4BfwFHmitn+UVlLcjs0gpm0KI58AasANsaK1/z+ZEGdkDLuQtWhBftNYXZw+iRqvsJiBC47G4zEVGYZlMR3WeuMt+5HBGykbsHclDvV4/XalUtoCrjJ/KKAbAB+Ce1vqHrdpWOzIxcY14E0xi68BLm7WtdgS4BBCG4bkgCL5GJTQajbOe5+0Dl20Wtm3kM7Dued6+UipNrjWsjtZoNGoC7xjfgzgGwC7QtFnbakc6nc534LrN70zLyjy/zkjZcEbKhjNSNpyRsuGMLIrv+5tSysdZY3EcipHJ8u+REOLhvOCkWBKFG5kKnX6eFZwUy4xSysStXGySVCdPzF32srEyRmz/Z09N0ko2z7q2cCN5HpI0xtxo5WVZW/6V6cjKGIkdrSJ+3W0S1ZFPhavIzsfDFuBwHDX+AZTiuWzva/QsAAAAAElFTkSuQmCC"></a></li>
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
                        <a href="inventory.php" class="notmain">
                            <div class="left"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAABjUlEQVRoge2ZTU7DMBBGnyPuANmwgR0HQEIgIa4AVYTgFByEcxAF0SN0QbvgGoBacYkOmwYSNT9ObDcm+O3aTJN5HtmfokIg4ATl5rai9hNOI5gA15vnPK8h+0p5AyW2n2hV5OBWTiJhgnAvcFRZJHwCL0rIlhkLW1KGIqWVvwEOCxff2UwBoK3GdFK9RBpW/gNh2rTa+W/Xwp2C458LhpPSFjFpvu2eNqQaRVw03/asvlJbIrtsvo4+UiWROJEZcFn4ytpm7Ef9YaJgtkzVVeHzL3EieaOPwzVfR0nqAWCVquqtESciBRlvqeozGqoZ2wQR39jTKYoTWQBnjnupRjFfPamLtjLdiQwjASCc65RpTSSn9rhzRJcTdDR7JIj4RhDxjdGIdDp+2+gUnJpBp4vtiegHp2bQ6WJ1IjltweniVWE0eySI+EYQ8Y0g4hu9c6QpxXVzYqvOIO1NJmL/9dcg7Y2TXTfFXaf9aPZIEPGNIOIbQcQ36v6x+hMUs6k8EcV8593053XoBgL/gm/1v9vUkDae5QAAAABJRU5ErkJggg=="></div>
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
                    <a href="inventory.php">Inventory</a>
                    <p class="divider">/</p>
                    <p class="now"><?php echo $addOrEdit ?> Inventory</p>
                </div>
                <h1 class="title"><?php echo $addOrEdit ?> Inventory</h1>
            </div>
            <div class="main">
                <?php
                    $redirect = "";
                    function getCSName($connect, $prdct, $brnch) {
                        $query = "SELECT NamaProduk FROM produk WHERE KodeProduk = " . $prdct;
                        $result = mysqli_query($connect, $query);
                        $productName = mysqli_fetch_assoc($result);
                        $productName = $productName['NamaProduk'];

                        if (empty($brnch)) {
                            $cabangName = "";
                            $cabangMngr = "";
                        } else {
                            $query = "SELECT Kelurahan FROM address WHERE Jenis = 'branch' AND IdToko = " . $brnch;
                            $result = mysqli_query($connect, $query);
                            $cabangName = mysqli_fetch_assoc($result);
                            $query = "SELECT name FROM villages WHERE id = " . $cabangName['Kelurahan'];
                            $result = mysqli_query($GLOBALS['connectI'], $query);
                            $cabangName = mysqli_fetch_assoc($result);
                            $cabangName = ucwords(strtolower($cabangName['name']));

                            $query = "SELECT EmailManager FROM branch WHERE Id = " . $brnch;
                            $result = mysqli_query($connect, $query);
                            $cabangMngr = mysqli_fetch_assoc($result);
                            $cabangMngr = $cabangMngr["EmailManager"];
                        }

                        return array($productName, $cabangName, $cabangMngr);
                    }

                    if (isset($_GET["id"])) {
                        if (!isset($_POST["submit"])) {
                            $id = $_GET["id"];
                            $cabang = $_GET["cabang"];

                            $queryS = "SELECT * FROM inventory WHERE KodeProduk = $id AND IdCabang = $cabang";
                            $resultS = $connect -> query($queryS);
                            $rowS = mysqli_fetch_array($resultS);
                            $quantity = $rowS["Stok"];

                            $CSName = getCSName($connect, $id, $cabang);
                            $productName = $CSName[0];
                            $cabangName = $CSName[1];
                            $cabangMngr = $CSName[2];

                            $product = "<option value='" . $id . "' hidden selected>" . $productName . "</option>";
                            if (empty($cabangName)) {
                                $branch = "<option value='0' hidden selected>Select Inventory Branch</option>";
                            } else {
                                $branch = "<option value='" . $cabang . "' hidden selected>" . $cabangName . " (" . $cabangMngr . ")</option>";
                            }

                            $redirect = "formInventory.php?id=$id&cabang=$cabang";
                        } else {
                            $CSName = getCSName($connect, $_POST['product'], $_POST['branch']);
                            $productName = $CSName[0];
                            $cabangName = $CSName[1];
                            $cabangMngr = $CSName[2];

                            $product = "<option value='" . $_POST['product'] . "' hidden selected>" . $productName . "</option>";
                            if (empty($cabangName)) {
                                $branch = "<option value='0' hidden selected>Select Inventory Branch</option>";
                            } else {
                                $branch = "<option value='" . $_POST['branch'] . "' hidden selected>" . $cabangName . " (" . $cabangMngr . ")</option>";
                            }
                        }
                    } else {
                        if (isset($_POST["submit"])) {
                            $CSName = getCSName($connect, $_POST["product"], $_POST["branch"]);
                            $productName = $CSName[0];
                            $cabangName = $CSName[1];
                            $cabangMngr = $CSName[2];

                            $product = "<option value='" . $_POST["product"] . "' hidden selected>" . $productName . "</option>";
                            if (empty($cabangName)) {
                                $branch = "<option value='0' hidden selected>Select Inventory Branch</option>";
                            } else {
                                $branch = "<option value='" . $_POST["branch"] . "' hidden selected>" . $cabangName . " (" . $cabangMngr . ")</option>";
                            }
                        } else {
                            $product = "<option value='' hidden selected>Select Product</option>";
                            $branch = "<option value='' hidden selected>Select Inventory Branch</option>";
                            $redirect = "formInventory.php";
                        }
                    }
                ?>
                <form action="<?php echo $redirect ?>" method="POST" id="addedit">
                    <div class="modify input">
                        <div>
                            <label for="product">Product</label>
                            <select required name="product" id="product">
                                <?php echo $product ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="quantity">Quantity</label>
                            <input type="number" id="quantity" name="quantity" placeholder="Enter Product Quantity" value="<?php echo $quantity; ?>" required>
                            <?php echo $eQuantity ?>
                        </div>

                        <div>
                            <label for="branch">Branch</label>
                            <select name="branch" id="branch">
                                <?php echo $branch ?>
                            </select>
                        </div>

                        <div>

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
                                $cabang = $_GET["cabang"];
    
                                $$queryS = "SELECT * FROM inventory WHERE KodeProduk = $id AND IdCabang = $cabang";
                                $resultS = $connect -> query($queryS);
                                $rowS = mysqli_fetch_array($resultS);
                                $quantity = $rowS["Stok"];
    
                                $CSName = getCSName($connect, $id, $cabang);
                                $productName = $CSName[0];
                                $cabangName = $CSName[1];
                                $cabangMngr = $CSName[2];
    
                                $product = "<option value='" . $id . "' hidden selected>" . $productName . "</option>";
                                if (empty($cabangName)) {
                                    $branch = "<option value='0' hidden selected>Select Inventory Branch</option>";
                                } else {
                                    $branch = "<option value='" . $cabang . "' hidden selected>" . $cabangName . " (" . $cabangMngr . ")</option>";
                                }
    
                                $redirect = "formInventory.php?id=$id&cabang=$cabang";
                            } else {
                                $CSName = getCSName($connect, $_POST['product'], $_POST['branch']);
                                $productName = $CSName[0];
                                $cabangName = $CSName[1];
                                $cabangMngr = $CSName[2];
    
                                $product = "<option value='" . $_POST['product'] . "' hidden selected>" . $productName . "</option>";
                                if (empty($cabangName)) {
                                    $branch = "<option value='0' hidden selected>Select Inventory Branch</option>";
                                } else {
                                    $branch = "<option value='" . $_POST['branch'] . "' hidden selected>" . $cabangName . " (" . $cabangMngr . ")</option>";
                                }
                                
                            }   
                    ?>
                            <script>
                                let reset = document.querySelector("form input.reset");
                                reset.addEventListener("click", function() {
                                    let quantity = document.querySelector("input#quantity");
                                    let product = document.querySelector("select#product");
                                    let branch = document.querySelector("select#branch");

                                    <?php
                                        $id = $_GET["id"];
                                        $cabang = $_GET["cabang"];

                                        $queryS = "SELECT * FROM inventory WHERE KodeProduk = $id AND IdCabang = $cabang";
                                        $resultS = $connect -> query($queryS);
                                        $rowS = mysqli_fetch_array($resultS);

                                        $quantityValue = $rowS["Stok"];
                                        $productValue = $rowS["KodeProduk"];
                                        $cabangValue = $rowS["IdCabang"];
                                    ?>

                                    quantity.value = "<?php echo $quantityValue ?>";

                                    const cProduk = document.getElementById("product");
                                    const cCabang = document.getElementById("branch");

                                    cProduk.insertAdjacentHTML("beforeend", "<option selected hidden value='" + "<?php echo $productValue; ?>" + "'>" + "<?php echo $productName ?>" + "</option>");
                                    if ("<?php echo $cabangName ?>" != "") {
                                        cCabang.insertAdjacentHTML("beforeend", "<option selected hidden value='" + "<?php echo $cabangValue; ?>" + "'>" + "<?php echo $cabangName ?>" + "</option>");
                                    } else {
                                        cCabang.insertAdjacentHTML("beforeend", "<option value='0' hidden selected>Select Inventory Branch</option>");
                                    }
                                    
                                });
                            </script>
                    <?php
                        } else {
                    ?>
                            <script>
                                let reset = document.querySelector("form input.reset");
                                reset.addEventListener("click", function() {
                                    let quantity = document.querySelector("input#quantity");
                                    let product = document.querySelector("select#product");
                                    let branch = document.querySelector("select#branch");

                                    <?php
                                        $quantityValue = "";
                                        $productValue = "";
                                        $cabangValue = "";
                                    ?>

                                    quantity.value = "<?php echo $quantityValue ?>";

                                    const cProduk = document.getElementById("product");
                                    const cCabang = document.getElementById("branch");

                                    cProduk.insertAdjacentHTML("beforeend", "<option selected hidden value='" + "<?php echo $productValue; ?>" + "'>Select Product</option>");
                                    cCabang.insertAdjacentHTML("beforeend", "<option selected hidden value='" + "<?php echo $cabangValue; ?>" + "'>Select Inventory Branch</option>");
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
        const cProduk = document.getElementById("product");
        const cCabang = document.getElementById("branch");

        let product = "";
        let branch = "";

        function addHMTL(container, type) {
            let textHTML = "";
            <?php
                $queryTemp = "";
            ?>
            if (type == "product") {
                <?php
                    $queryTemp = "SELECT * FROM produk WHERE NOT IdSupplier = 0 ORDER BY NamaProduk ASC";
                    $resultTemp = mysqli_query($connect, $queryTemp);
                    while($rowTemp = mysqli_fetch_array($resultTemp)) {
                ?>
                        textHTML += "<option value='" + <?php echo $rowTemp["KodeProduk"]; ?> + "'>" + "<?php echo $rowTemp["NamaProduk"]; ?>" + "</option>";
                <?php
                    }
                ?>
            } else if (type == "branch") {
                <?php
                    function getBranchVillage($connect, $id) {
                        $queryK = "SELECT name FROM villages WHERE id = $id";
                        $resultK = mysqli_query($connect, $queryK);
                        $rowK = mysqli_fetch_array($resultK);
                        return ucwords(strtolower($rowK["name"]));
                    }
                    $queryTemp = "SELECT * FROM branch ORDER BY Id ASC";
                    $resultTemp = mysqli_query($connect, $queryTemp);
                    while($rowTemp = mysqli_fetch_array($resultTemp)) {
                        $queryT = "SELECT Kelurahan FROM address WHERE IdToko = '" . $rowTemp["Id"] . "' AND Jenis = 'branch'";
                        $resultT = mysqli_query($connect, $queryT);
                        $rowT = mysqli_fetch_array($resultT);
                        $name = getBranchVillage($connectI, $rowT["Kelurahan"]);
                ?>
                        textHTML += "<option value='" + <?php echo $rowTemp["Id"]; ?> + "'>" + "<?php echo $name; ?> (<?php echo $rowTemp["EmailManager"]; ?>)" + "</option>";
                <?php
                    }
                ?>
            }
            container.insertAdjacentHTML("beforeend", textHTML);
        }

        cProduk.addEventListener("mousedown", function() {
            addHMTL(cProduk, "product");
        }, {once: true});
        cCabang.addEventListener("mousedown", function() {
            addHMTL(cCabang, "branch");
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