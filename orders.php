<?php
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

    $province = $city = $subdistrict = $village = "";

    $status = "";
    if (!isset($_GET["select"])) {
        $status = 'Menunggu Pembayaran';
    } else {
        if ($_GET["select"] == "wfp") {
            $status = 'Menunggu Pembayaran';
        } else if ($_GET["select"] == "wfs") {
            $status = 'Menunggu Pengiriman';
        } else if ($_GET["select"] == "s") {
            $status = 'Sedang Dikirim';
        } else if ($_GET["select"] == "f") {
            $status = 'Pesanan Selesai';
        }
    }
    $from = $to = "";
    if (isset($_GET['from']) && isset($_GET['to'])) {
        $from = $_GET['from'];
        $to = $_GET['to'];
    }

    $queryS = "SELECT DISTINCT IdCabang FROM pemesanan WHERE Status = '$status' ORDER BY IdCabang ASC";
    $resultS = mysqli_query($connect, $queryS);
    $S = [];
    $Snew = [];
    while($rowS = mysqli_fetch_array($resultS)) {
        array_push($S, $rowS["IdCabang"]);
    }
                                    
    if (in_array(0, $S)) {
        array_push($Snew, 0);
    }
    $S = '(' . implode(',', $S) .')';
                                    
    $queryTemp2 = "SELECT Kelurahan, IdToko FROM address WHERE Jenis = 'branch' AND IdToko IN " . $S . " ORDER BY Kelurahan ASC";
    $resultTemp2 = mysqli_query($connect, $queryTemp2);
    while($rowTemp2 = mysqli_fetch_array($resultTemp2)) {
        array_push($Snew, $rowTemp2['Kelurahan']);
    }
    $Snew = '(' . implode(',', $Snew) .')';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/orders.css">
    <link rel="icon" type="image/x-icon" href="img/logo/favicon.png">
</head>
<body>
    <div class="container">
        <div class="navbar">
            <img class="expand" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABmJLR0QA/wD/AP+gvaeTAAACBUlEQVR4nO2ZTW7TQBiGnw+ugEQXnMD2qoq4COOpKAcIlEvkDiAqJLaVaJQlhW3uQD0SXCE5A8OixkKR2tjz49rS96yikd7P8z52Io8CiqIoiqIoiqIoiqIEIMaYN9ba14BkzGThaeyAuq7PReQKeFVV1UnTNDc5Mrl4EjvAey//fV5aay85cldDMrmIfgKcc7dVVZ0Ai3ZpceyuhmRyES0AoGmam6IononIy3ZpUZblC2vtt+1261NlcpBEAIBz7sdBodPdbvdgoZBMapIJgHlKSCoA5ichuQCYl4QsAmA+ErIJgHlIyCoApi8huwCYtoRRBMB0JYwmAKYpYVQB0BV6LiL/zgGn+/3+2NlhcKYvowsAcM59Pyi0qKrqV9M0t0MyZVn+ds79jNlL9HH4MfHe/4md8RgCxBjzSUTedgsin9fr9dehmc1mcx27mbEFiDHmw0GRL0VRXAD3/aCFZPpvKHbAkGu1Rd53C3dFlqvV6r5HOSQzbFMphvS5zhTLwzgCJlse8guYdHnI+x4g1tqPwKDyAZm4TeYYSlvEe3/RLfQsPzATv9EcM+dSHtILmFV5SCtgduUhnYBZloc0AsRae+m9X3YLd+/273jg9TYgk4VoAXVdnwNX3cAeRUIyuUj673DfIiGZXCT5ChhjzgDa42mfIiEZRVEURVEURVEURVHS8BeiPUB1BfqrXAAAAABJRU5ErkJggg==">
            <ul>
                <li><a href="inventory.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAABg0lEQVRoge2ZzU3DQBBGP9vUwQVuFICEQEK0YEY+QBUUQh/WICghB5JD2gDq8HKJ0QJZe7w/8WLmHeOxPW9Hu5+sAIqShCLVc5umOe+6jgDUu/c8lWXJbdtuAZjoL4z5sLquz6qqImPMPYATR9mHMea5KApm5g0iSYWK2Ct/C+DYuvaG3RQAYKwmdFJeIgMr/26MeRlabeveOwCn1qWgSYlFQpoXPDNYalAkRfOCd3lJ/RI5ZPMufKS+iRDRCsC19VO0zejJ0GGyYuabr0L7LiLqG32csXkXttQDADDz/q1BRMaSyZZ9fZZzNRMbFcmNI0kREW0AXCTuxcWama/GiqQTmUsCAC4lRaKJ9DiPu0RMOUEXs0dUJDdUJDcWIzLp+B1jYnCKgk5K7IlMCU5R0EmJOpGeseBM8amwmD2iIrmhIrmhIrnhnSNDKS7NiT113mkfMpEUn7/eaR+c7NIUT532i9kjKpIbKpIbKpIbrn+s/gR2Nv2cyPrAvYTwOncDyr/gEwgV6TaoFPsuAAAAAElFTkSuQmCC"></a></li>
                <li><a href="products.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAACKElEQVRoge2YMYgTQRSGv9nIxUJbW7HWwkJRBCsPGzFoMrOetWksrSy9UktBG/GuES0y2RhSHWcjaiGc5dkK2gZBsUvMjkUSCHF3b3cz2dsL83WZ98j7f96bCXngcDiSEGmSlFJngA5wfrly/mMvDMONIAi+HZR4oBEp5RUhxFvglBVp2flpjJHtdvt9UlKiEd/37xpjtoHjwO5wOLzT7XZ/ZVGhlDIAWutU3Z9Sq9VOVqvV18BNYCCEuN9qtbbj8r2Yc+H7/qYx5g1jEy/6/f6NrCYWodfr/QFuG2OeAGvGmC0p5VOlVCVS8PyBUuoE8Aq4BfwFHmitn+UVlLcjs0gpm0KI58AasANsaK1/z+ZEGdkDLuQtWhBftNYXZw+iRqvsJiBC47G4zEVGYZlMR3WeuMt+5HBGykbsHclDvV4/XalUtoCrjJ/KKAbAB+Ce1vqHrdpWOzIxcY14E0xi68BLm7WtdgS4BBCG4bkgCL5GJTQajbOe5+0Dl20Wtm3kM7Dued6+UipNrjWsjtZoNGoC7xjfgzgGwC7QtFnbakc6nc534LrN70zLyjy/zkjZcEbKhjNSNpyRsuGMLIrv+5tSysdZY3EcipHJ8u+REOLhvOCkWBKFG5kKnX6eFZwUy4xSysStXGySVCdPzF32srEyRmz/Z09N0ko2z7q2cCN5HpI0xtxo5WVZW/6V6cjKGIkdrSJ+3W0S1ZFPhavIzsfDFuBwHDX+AZTiuWzva/QsAAAAAElFTkSuQmCC"></a></li>
                <li><a href="suppliers.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAETUlEQVRoge2aW2hcVRSGv3VGLImpsRhS1AreQMyDIJW21BStVQuFICTZZ3JBQZHWKhXxgtYHCeiLPigKPlgiTI2hndmOhIBafWhTm6K1Il5IVGj7YOlDTLBWEZKZZC8f5gRCzGQu+0wopf/LXuyz1tr/f9bZsy8MXMZlXMZykFok7enpWZPL5baLyFZgPdAMXAt8Pzk5uXVkZGQ27jGviDNZd3f32rm5uRfz+fwuEWlYwqW1qampB/gwznEhxop0dnbeJyIHgbVR1wngC+Bb59xZEWkVkfeA86p6CDgkIgettbk4xo9FSBiGbar6CYUKHw6C4OV0On1yoY8xJgEcAbYs6D7tnOvKZrPf+XLwFmKMaQG+AVaLyJstLS17+/r6XDH/3t7eq3O53F3AXmA7cAHYbK0d9+ERxxx5C1itqvuttS+Vch4cHPwbOAp8FYZhSlUfBfYBrT4kAp/gZDK5icJbPT87O/tsheG6atWqp4AJ4B5jzL0+XLyEOOfaAVR139DQ0F+Vxg8MDPyrqh8AiEjow8VLCHA/QBAEn3nkOAygqpt8iPgKWQeQz+d/rTaBiJyJzFt8iPgKuQZgenr6gkeOc1F7lQ8RXyFnARoaGq73yNEYtRM+RHyF/ACgqnd75Fgftad9iHgJUdUsgIh0VZtDRB6PzI99uHgJEZGbIvOGanOo6rbIbFzWsQR8P63nAYIg2FNtAhFJRubTPkR8hdQDJBKJU9UmUNXjkbnGh4ivkJMA+Xz+oWoTqOrGyPzFh4jvZP8oMt8Nw7Ct0vjOzs6HRWT+kDXkw8VLyNTUVAoYBppV9bVK40XkDeBGIE1hF1014jhYiTHmDwrfeLO19s9ygowxNwNngHPW2nW+JHznCICKyBEgsWBNKAc7o/bTGDjEIgTnXD+Aqr7S1dVVcrvS3t5+HbAbIAiC9+PgENvlQxiGB1S1Czhhrf3fltwYcxzYHNd4izAaS0UA6uvrH4vMjUVcaiUCoDW2e61UKjVtjCnpZ62N9VLQGKMQ0xy5GBDrTWO1MMYco/Qtyqi1dkuxh7FVxBhze2TOxJVzEXS5h7FUpKOj404KqzMiMlBp/HJvulx4CUkmk7c65/ZQWBOuBH5W1Rd8SVWDioUYYxIiskNVdzrndlD4PBVIzczMPDM8PPxP7CzLQNlC2tra6uvq6nap6nOqOr83mhaRAyLyTjqd/rFGHMtCSSF9fX3B+Pj4blV9VVWbo+7fRKRfVfdnMpnJGnMsC8sKMcY0jo2NWeDBqOtrEXk9k8l8TolfkZVGUSHGmDrgS2ADMCEiT2YyGa/DTy1RVIiIvK2qG4BTwLZMJvP7ytGqHEsKSSaTdzjnngBmnXMd2Wz2ohYBRVZ2VX0ESACHstnsTytLqToUE/JAZF60c2Ixis2R26K23xjTv1JkfFBs0zi2oixiwJIViWMTtxjzB6Ba4ZI5WNXkvyhL4VKqyGgNcx/7Dw3uXQQOdcOxAAAAAElFTkSuQmCC"></a></li>
                <li><a href="branches.php"><img src="data:image/png;base64,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"></a></li>
                <li><a href="orders.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABmJLR0QA/wD/AP+gvaeTAAAFeUlEQVR4nO2aTWxUVRSAv3Nfp62gKAi0M6KgAf9wAy78i5EEQ4ILYwxDJxiVuDCamMiCYFhocKEhESWa+BONgQRtC01koYm6MZL4F4IkakxjcAMp05a/JqDYTufd42JmOm/Gmbb39c0wtvNtZt68c88979xz7zn3zoMmTZo0mcPITBUs26KrsuMkjBCLwqCosIas73PsbJ/8NZlcSAeoxLt4FtgJLA+noy5kEb5H2D3YI19VEgjhAJV4F/uBp2ZoXF0RZU/6EDtAtOR3V0XxLt0G7AVQSIuwB6FffLIR2eqMCq8ADwIgnBfLPoSTwJ2aG6j5ebkXhnrl3WBbNwes05Z4B2lgCXDKZFh7+rCcj+AZQpNI6m1q6AdE4DdrWT/UJ2cL95dt0VU2yxEV4sCFrGV5cF0wLp11drCG3MMDvH6lHx5ADZvID6QKzwQfHmCgW05g2J6/XBQTHg7ed3KAyXkx1zEcD2dy5Nya/zw32MvPlQRkjK+LF9wevOfkALXFVKeWUZe2tUKE9vzXM+ULXIF8pPoAKPOC99wcIEV5acE6WdqguE6BogPsHHQAWswas8UBLU7SihRcIIay+abSkeQuA63T1me4PHhQ+p1siBgnB+gkUyCe4jWUna4GJFL6YrpX3nFtFxVuUyDggEx5BNhcteWKhmwXFW4RYDFSmAJlETB4Hds7L3LYWAedymh6NT+42BA1Tg4wgikMu4mVLYIfyvgQfOtsQZ9zi0gJPQVmSxZwrQQn5I0/Bx2AKdYBo21z0QGBCJCx8jrg/4lrHVBIApj2sghYpy3xpdwvLoXQNLHgaxtHhw/I31HrdqsEhYnsL6OlERDv4A1gWy3CQgDJ8CXwSNS63TZDWpT3sqURoJCJyqgq1ES/awRIYdy9q0odMHQHO2/op9u6FELT7dag8y7ye9R6wXUNCETApfGyNWCX2NPwS0R2heGWeEqPVb2rlaPddbSKWaC14bJAO8rdUwmpKT3Jco0AKaQBL9NwdcBFIXD2VwErXLLCx8Hf3PYC4BWGPTbacA4YSB+Uza6NQu8FWq9vOAeEwnkKFIrhluHSNWDFVm3PjLLBKm3RmZdDhKznc2SgTy5ErdttETTFNPjnjaURkPmHtxSen/HfzZVQsMI3wPqoVYfeCzBSNgWEM5FYVAU1DNdCr/OZ4MQIr0aDhxnpXtmVSGqPwtXRmZfDGsaGfWpyeOp+IpSbAsou+c8imO6TPyKyq26EnQKNVgSFxu1EqHggMitSIISPgDnqAJl9DihZBFcntfWC8LQYFlUSVrgPBQEvkdKXamGQKmO2le7hA1LTtFqgxAEjHs+hvK1TLHEKMZTdtTJKMtwLpGqlP0jpFLAsrkenU2Fgab36qlYH+DFv4l2gujHu8znwQD37rFoIneqWkXoaAhBPabbeFYZbFpiFNB1QdlXI7wJak53tZIjNvcmlDnsU1ZysEu5N1RIHWCi8ZGiWJOkIo3Am2OKWevovYEtOVmAoTJ8lDjB+8Vg7JtH/CzMVIhzNf70p0aVrp5LvTOkKlDUAKNWPxCehxAFp+BE4DaDCywuTem0YpaExfCYwDqDwJkn1qguriLKX3DOo53EwXJdB+sRHeTV/taLd44uOJ7VuRclgt5wEPspfrus0fLr4Ub2mXG7lRm2Ld/E+8BgAQu9Aj/waps8KC51KfDOHEDblfxgB9iF8J5ZLYTpxwjBPlf3AQsi/kg/7RDjqK1ZgrcBW4OZ8ixMxj3vC1i0VV/qVG7Xt8gI+0FxHDYvAT5kYj5/7RAZnoKM6iS7dYGGHwEO4/41WS46L8F7aZz994s9E0bRy/aIndEEsw3IM843NLVL1xnq0eMo4hlPpHjl3JWxo0qRJk1nHvyo+orkM0SKJAAAAAElFTkSuQmCC"></a></li>
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
                            <div class="left"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABmJLR0QA/wD/AP+gvaeTAAAFeUlEQVR4nO2aTWxUVRSAv3Nfp62gKAi0M6KgAf9wAy78i5EEQ4ILYwxDJxiVuDCamMiCYFhocKEhESWa+BONgQRtC01koYm6MZL4F4IkakxjcAMp05a/JqDYTufd42JmOm/Gmbb39c0wtvNtZt68c88979xz7zn3zoMmTZo0mcPITBUs26KrsuMkjBCLwqCosIas73PsbJ/8NZlcSAeoxLt4FtgJLA+noy5kEb5H2D3YI19VEgjhAJV4F/uBp2ZoXF0RZU/6EDtAtOR3V0XxLt0G7AVQSIuwB6FffLIR2eqMCq8ADwIgnBfLPoSTwJ2aG6j5ebkXhnrl3WBbNwes05Z4B2lgCXDKZFh7+rCcj+AZQpNI6m1q6AdE4DdrWT/UJ2cL95dt0VU2yxEV4sCFrGV5cF0wLp11drCG3MMDvH6lHx5ADZvID6QKzwQfHmCgW05g2J6/XBQTHg7ed3KAyXkx1zEcD2dy5Nya/zw32MvPlQRkjK+LF9wevOfkALXFVKeWUZe2tUKE9vzXM+ULXIF8pPoAKPOC99wcIEV5acE6WdqguE6BogPsHHQAWswas8UBLU7SihRcIIay+abSkeQuA63T1me4PHhQ+p1siBgnB+gkUyCe4jWUna4GJFL6YrpX3nFtFxVuUyDggEx5BNhcteWKhmwXFW4RYDFSmAJlETB4Hds7L3LYWAedymh6NT+42BA1Tg4wgikMu4mVLYIfyvgQfOtsQZ9zi0gJPQVmSxZwrQQn5I0/Bx2AKdYBo21z0QGBCJCx8jrg/4lrHVBIApj2sghYpy3xpdwvLoXQNLHgaxtHhw/I31HrdqsEhYnsL6OlERDv4A1gWy3CQgDJ8CXwSNS63TZDWpT3sqURoJCJyqgq1ES/awRIYdy9q0odMHQHO2/op9u6FELT7dag8y7ye9R6wXUNCETApfGyNWCX2NPwS0R2heGWeEqPVb2rlaPddbSKWaC14bJAO8rdUwmpKT3Jco0AKaQBL9NwdcBFIXD2VwErXLLCx8Hf3PYC4BWGPTbacA4YSB+Uza6NQu8FWq9vOAeEwnkKFIrhluHSNWDFVm3PjLLBKm3RmZdDhKznc2SgTy5ErdttETTFNPjnjaURkPmHtxSen/HfzZVQsMI3wPqoVYfeCzBSNgWEM5FYVAU1DNdCr/OZ4MQIr0aDhxnpXtmVSGqPwtXRmZfDGsaGfWpyeOp+IpSbAsou+c8imO6TPyKyq26EnQKNVgSFxu1EqHggMitSIISPgDnqAJl9DihZBFcntfWC8LQYFlUSVrgPBQEvkdKXamGQKmO2le7hA1LTtFqgxAEjHs+hvK1TLHEKMZTdtTJKMtwLpGqlP0jpFLAsrkenU2Fgab36qlYH+DFv4l2gujHu8znwQD37rFoIneqWkXoaAhBPabbeFYZbFpiFNB1QdlXI7wJak53tZIjNvcmlDnsU1ZysEu5N1RIHWCi8ZGiWJOkIo3Am2OKWevovYEtOVmAoTJ8lDjB+8Vg7JtH/CzMVIhzNf70p0aVrp5LvTOkKlDUAKNWPxCehxAFp+BE4DaDCywuTem0YpaExfCYwDqDwJkn1qguriLKX3DOo53EwXJdB+sRHeTV/taLd44uOJ7VuRclgt5wEPspfrus0fLr4Ub2mXG7lRm2Ld/E+8BgAQu9Aj/waps8KC51KfDOHEDblfxgB9iF8J5ZLYTpxwjBPlf3AQsi/kg/7RDjqK1ZgrcBW4OZ8ixMxj3vC1i0VV/qVG7Xt8gI+0FxHDYvAT5kYj5/7RAZnoKM6iS7dYGGHwEO4/41WS46L8F7aZz994s9E0bRy/aIndEEsw3IM843NLVL1xnq0eMo4hlPpHjl3JWxo0qRJk1nHvyo+orkM0SKJAAAAAElFTkSuQmCC"></div>
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
            <div class="top">
                <h1 class="title">Orders</h1>
                <a href="formInventory.php" class="add">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAAz0lEQVRoge3ZQQrCMBCF4TfqPl5FFLz/AcSTuHDfOi7c1YkklMFH+r5lCp3+WKFpARHZBMs6sbtfABwXyw8zu2fMywyZATwXy8XM9hnzMkM8HGiWMnOXcdJ/UAgbhbBRCBuFsFEIG4WwGSYkfKR29xOAW+14ownAoWGthwM4R5uz2i9S8L0p6hVd8JoI4HNNJTowzK2lEDa1kOq92GFqXOtR/e/q5QMbhbBRCBuFsFEIG4WwUQibYULW7qF/eSH4GJo1LDPkiuDzdOI8EdmCNyEqI4JpdT0YAAAAAElFTkSuQmCC">
                    <p>Order</p>
                </a>
            </div>
            <div class="main">
                <div class="status">
                    <div>Waiting for Payment</div>
                    <div>Waiting for Shipment</div>
                    <div>Shipping</div>
                    <div>Finished</div>

                    <script>
                        function active(option) {
                            option.style.color = "#1957ff";
                            option.style.borderBottom = "2px solid #1957ff";
                        }

                        let options = document.querySelectorAll(".status div");
                        <?php
                            if (isset($_GET["select"])) {
                                if ($_GET["select"] == "wfp") {
                        ?>
                                    active(options[0]);
                        <?php
                                } else if ($_GET["select"] == "wfs") {
                        ?>
                                    active(options[1]);
                        <?php
                                } else if ($_GET["select"] == "s") {
                        ?>
                                    active(options[2]);
                        <?php
                                } else if ($_GET["select"] == "f") {
                        ?>
                                    active(options[3]);
                        <?php
                                }
                            } else {
                        ?>
                                active(options[0]);
                        <?php
                            }
                        ?>

                        options[0].addEventListener("click", function() {
                            document.location.href = "orders.php?select=wfp";
                        });
                        options[1].addEventListener("click", function() {
                            document.location.href = "orders.php?select=wfs";
                        });
                        options[2].addEventListener("click", function() {
                            document.location.href = "orders.php?select=s";
                        });
                        options[3].addEventListener("click", function() {
                            document.location.href = "orders.php?select=f";
                        });
                    </script>
                </div>

                <form action='' class='preferences form2' method='GET'>
                    <div class="input2">
                        <div class='search'>
                            <img src='data:image/png;base64,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'>
                            <p class="caption">Search by</p>
                            <select name="searchby" id="searchby">
                                <option value="NamaProduk" selected>Product Name</option>
                                <option value="NoTransaksi">Transaction Number</option>
                            </select>
                            <input type='text' placeholder='Keyword' name='search'>
                        </div>

                        <div class='sort'>
                            <img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAACDUlEQVRYhe2XvWtTURjGf8+5jXFxMsTFUXATtF2CQ4NUWxyCITmguIibg39BcRGc9D9wEVTw3tuUipaKGepWERF1sIubUxEXB79q8jokqbHcfMnNdp/xvM+5z++cw+G8V0xB3vsS0AQ2gQtxHLcGeTWl8E0gD5iZNSWdHwThphD+AjhgZgYg6aykde99MFWAvpXngHfOOQM+Az/MbBHYSIJIBaB/5ZJWgevd0hfn3CzwDViQ1CyXyzMTAVQqlUO1Wu3EGOE5SatRFHlJ1quHYbjtnJsDvptZuVgsPu+HGAmQz+cbzrk33vtjAyyPgZyZPYqiyAO23xCG4Xa73S7ROY5yoVBYGhsAOKqOjiQVJS0DN1ZWVi4nhffUaDTeA3Nmdq/Vam31xmcGTRhXURTdHdcbx/EH4Gr/WKrX8H+UAWQAGUAGkAFkABnAXj9QrVYPB0FwW9Kd7rs9TKrX68vOuZ1J+oEk7e1AEAQlSVeA18N6wG74Q0k3zexWksHMdqyjT2MDSNoAngEHJb3y3s8mhXvvH0i6CPx2zlWSPhrH8cd2u31KUm0UwD9/Rt2+/QmwZGa/JJ0G7ks6bmbzwDXgkpntBkEwH4bhy1EBEwH0QTwFFoGfwFdJBeCtmZ0EdoFyHMdb++emAtCDkLRuZucAzAxJpB0+EKAHwd+dUPdIUg0fCtAHsQacARbSDgf4A9tAyCb9zgBkAAAAAElFTkSuQmCC'>
                            <p class='caption'>Sort by </p>
                            <select name="sortby" id="sortby">
                                <option value="NamaProduk" selected>Name</option>
                                <option value="Total">Total</option>
                                <option value="Kuantitas">Quantity</option>
                                <?php
                                $value = "";
                                $display = "";
                                    if (isset($_GET["select"])) {
                                        if ($_GET["select"] == "wfp") {
                                            $value = "WaktuDibuat";
                                            $display = "Date Created";
                                        } else if ($_GET["select"] == "wfs") {
                                            $value = "WaktuDibayar";
                                            $display = "Date Paid";
                                        } else if ($_GET["select"] == "s") {
                                            $value = "WaktuDikirim";
                                            $display = "Date Shipped";
                                        } else if ($_GET["select"] == "f") {
                                            $value = "WaktuDiterima";
                                            $display = "Date Finished";
                                        }
                                    } else {
                                        $value = "WaktuDibuat";
                                        $display = "Date Created";
                                    }
                                ?>
                                <option value="<?php echo $value; ?>"><?php echo $display; ?></option>
                            </select>
                            <select name="sorttype" id="sorttype">
                                <option value="ASC" selected>Ascending</option>
                                <option value="DESC">Descending</option>
                            </select>
                        </div>

                        <div class='filter'>
                            <img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAACkElEQVRoge2ZzWsTQRiHn1lzE/8CT+JFkr2KUlFPpVRt/SCbiijePXgreBU9elDBm4eAp+QdiqC0UIQGhWr10oOJiHhQ0YNBFIzUr+T1VEnTpN2vpJuyz21233f292NnduadhZRkYdobnuc9B/ZvkZagLInIwdWG03FzWEwAHGhvdBoZWratkWdboiIca7SuMZLJZCaA2kDlhONNJpM51X7BdEZ4nrcbeALsGZSqgHxoNpuHZ2Zm3rVfXDdHROSj4zijwKeBSfPPZ2C00wT0mOylUumt4zhjwJd+KwvAN2BMRF53u9nzq1UqlV46jjMOfO+XsgD8UNXjIrLcK2DdHOkkn8+PGGPmgZ2xSvPPiqoes9ZWNgradB2x1i6q6hngV2zS/PNHVQubmQCfC6K1dl5VzwHNyNL80zLGXLTWPvQTvMNvr7Va7VUul3sPnMTHkIyIquolESn6TfBtBKBWqy27rvsVGA8sLQCqesVaeztITiAjANVqdcl13QxwJGiuH1T1mrX2etC8wEYAqtXqguu6u4CRMPm9UNU71trpMLmhd7/lcnnaGHM3bH4X7rmuezlscqg3sko2m51V1X3GmFyUfoD79Xr9fLFYDP1VjFSPiEjTGHMBmI3QzaNGo3G2Uqn8jaIlcmElIr+BPPA4RPpT4PTc3FzkxTaWClFEVoBJYCFA2iIwLiKNODSkJI1Ytxqe52mQeBGJ7fnb9hRlaEmNJI3USNJIjSSN1EjSSI0kjdRI0ojbyIuY+/NN3EaOGmOuAj9j7ndT+nKGOzU1tbfVat0ETmwUF2dh1dfD6EKhMKGqt+jxP3JoKsRyufwAyA1iuPX798B/ug23oRla3fA8bxK4AdRF5NCgn58yKP4BQD7MFThPz+UAAAAASUVORK5CYII='>
                            <p class='caption'>Filter </p>
                            <div class='dropdown cabang'>
                                <p>Branch</p>
                                <img class='dd' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAABPElEQVRIie2US04CQRRFzxN2gDrQHVCMNLIPkn5vDSL4YQe9BMNHE9kBIIw0DllFhzWQduyE2E4a0mJDCzrsO6uql3OTW7cKcuX6N6nqrZm1APkDRsysZWY3qw2AWq1WKhaLYbz3OBwOG0C0K1xVe0AdYLFYHE4mk/cCwGw2+yiXy0ciUgUunHPHQRC87guPoqg3Ho+fAQ6WE6PR6Broxsu6mT35vn/wA5UC9zyvk4D3K5XKKqJCcjIIgjfnXAmoAmdhGJ6q6st0Ot0Ul3ie1xGRRgJ+6fv+Z6pBmsl8Pj8xszQTUdW2iDQ3wVMN1k1E5DzFRFS1DWyFbzTIMOG3cMju/Ld2iMhDDL2KzzMrndWSKAZ0l+AlPIqivnOuuQ0OWyJKau3iM2NJapdvQczsDmAwGNyz+0vPlWtPfQFO+677u9G6MQAAAABJRU5ErkJggg=='>
                                <div class="options cabang">
                                </div>
                            </div>
                            <div class='dropdown supplier'>
                                <p>Supplier</p>
                                <img class='dd' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAABPElEQVRIie2US04CQRRFzxN2gDrQHVCMNLIPkn5vDSL4YQe9BMNHE9kBIIw0DllFhzWQduyE2E4a0mJDCzrsO6uql3OTW7cKcuX6N6nqrZm1APkDRsysZWY3qw2AWq1WKhaLYbz3OBwOG0C0K1xVe0AdYLFYHE4mk/cCwGw2+yiXy0ciUgUunHPHQRC87guPoqg3Ho+fAQ6WE6PR6Broxsu6mT35vn/wA5UC9zyvk4D3K5XKKqJCcjIIgjfnXAmoAmdhGJ6q6st0Ot0Ul3ie1xGRRgJ+6fv+Z6pBmsl8Pj8xszQTUdW2iDQ3wVMN1k1E5DzFRFS1DWyFbzTIMOG3cMju/Ld2iMhDDL2KzzMrndWSKAZ0l+AlPIqivnOuuQ0OWyJKau3iM2NJapdvQczsDmAwGNyz+0vPlWtPfQFO+677u9G6MQAAAABJRU5ErkJggg=='>
                                <div class="options supplier">
                                </div>
                            </div>
                            <div class="dropdown date">
                                <p class="caption"><?php echo $display; ?></p>
                                <img class='dd' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAABPElEQVRIie2US04CQRRFzxN2gDrQHVCMNLIPkn5vDSL4YQe9BMNHE9kBIIw0DllFhzWQduyE2E4a0mJDCzrsO6uql3OTW7cKcuX6N6nqrZm1APkDRsysZWY3qw2AWq1WKhaLYbz3OBwOG0C0K1xVe0AdYLFYHE4mk/cCwGw2+yiXy0ciUgUunHPHQRC87guPoqg3Ho+fAQ6WE6PR6Broxsu6mT35vn/wA5UC9zyvk4D3K5XKKqJCcjIIgjfnXAmoAmdhGJ6q6st0Ot0Ul3ie1xGRRgJ+6fv+Z6pBmsl8Pj8xszQTUdW2iDQ3wVMN1k1E5DzFRFS1DWyFbzTIMOG3cMju/Ld2iMhDDL2KzzMrndWSKAZ0l+AlPIqivnOuuQ0OWyJKau3iM2NJapdvQczsDmAwGNyz+0vPlWtPfQFO+677u9G6MQAAAABJRU5ErkJggg=='>
                                <div class="options date">
                                    <label for="from">from</label>
                                    <?php date_default_timezone_set("Asia/Bangkok"); ?>
                                    <input type="date" name="from" id="from" value="<?php echo $from; ?>" max="<?php echo date("Y-m-d"); ?>">
                                    <label for="to">to</label>
                                    <input type="date" name="to" id="to" value="<?php echo $to; ?>" max="<?php echo date("Y-m-d"); ?>">
                                </div>
                            </div>

                            <script>
                                const fSupplier = document.querySelector('.dropdown.supplier');
                                const sSupplier = document.querySelector('.dropdown.supplier .options.supplier');
                                const fCabang = document.querySelector('.dropdown.cabang');
                                const sCabang = document.querySelector('.dropdown.cabang .options.cabang');
                                const fDate = document.querySelector('.dropdown.date');
                                const sDate = document.querySelector('.dropdown.date .options.date');

                                sSupplier.style.display = "none";
                                fSupplier.addEventListener('click', () => {
                                    if (sSupplier.style.display == "grid") {
                                        sSupplier.style.display = "none";
                                    } else if (sSupplier.style.display == "none") {
                                        sSupplier.style.display = "grid";
                                        sCabang.style.display = "none";
                                        sDate.style.display = "none";
                                    }
                                });
                                sCabang.style.display = "none";
                                fCabang.addEventListener('click', () => {
                                    if (sCabang.style.display == "grid") {
                                        sCabang.style.display = "none";
                                    } else if (sCabang.style.display == "none") {
                                        sCabang.style.display = "grid";
                                        sSupplier.style.display = "none";
                                        sDate.style.display = "none";
                                    }
                                });
                                sDate.style.display = "none";
                                fDate.addEventListener('click', () => {
                                    if (sDate.style.display == "grid") {
                                        sDate.style.display = "none";
                                    } else if (sDate.style.display == "none") {
                                        sDate.style.display = "grid";
                                        sSupplier.style.display = "none";
                                        sCabang.style.display = "none";
                                    }
                                });

                                function addHMTL(data, container, type, idA) {
                                    let textHTML = "";
                                    for (let i = 0; i < data.length; i++) {
                                        let nameType = "";
                                        let dataName = "";
                                        if (type == "supplier") {
                                            nameType = "fSupplier[]";
                                            <?php
                                                $queryTemp = "SELECT * FROM suppliers ORDER BY Nama ASC";
                                                $resultTemp = mysqli_query($connect, $queryTemp);
                                                while($rowTemp = mysqli_fetch_array($resultTemp)) {
                                            ?>
                                                    if (<?php echo $rowTemp["Id"]; ?> == data[i]) {
                                                        dataName = "<?php echo $rowTemp["Nama"] ?>";
                                                    }
                                                    if (data[i] == 0) {
                                                        dataName = "Belum ada";
                                                    }
                                            <?php
                                                }
                                            ?>
                                        } else if (type == "cabang") {
                                            nameType = "fCabang[]";
                                            <?php
                                                $Snew = getSnew();
                                                $queryTemp = "SELECT * FROM villages WHERE id IN " . $Snew . " ORDER BY id ASC";
                                                $resultTemp = mysqli_query($connectI, $queryTemp);
                                                while($rowTemp = mysqli_fetch_array($resultTemp)) {
                                            ?>
                                                    if (<?php echo $rowTemp["id"]; ?> == data[i]) {
                                                        dataName = "<?php echo ucwords(strtolower($rowTemp["name"])); ?>";
                                                    }
                                                    if (data[i] == 0) {
                                                        dataName = "Belum ada";
                                                    }
                                            <?php
                                                }
                                            ?>
                                        }

                                        if (type == "supplier") {
                                            textHTML += "<input type='checkbox' id='" + dataName + "' name='" + nameType + "' value='" + data[i] + "'";
                                        } else if (type == "cabang") {
                                            textHTML += "<input type='checkbox' id='" + dataName + "' name='" + nameType + "' value='" + idA[i] + "'";
                                        }
                                        
                                        
                                        if (type == "supplier") {
                                            <?php
                                                if (isset($_GET['fSupplier'])) {
                                                    $fSupplier = $_GET['fSupplier'];
                                                    for ($i = 0; $i < count($fSupplier); $i++) {
                                            ?>
                                                        if (data[i] == <?php echo $fSupplier[$i] ?>) {
                                                            textHTML += " checked";
                                                        }
                                            <?php
                                                    }
                                                }
                                            ?>
                                        } else if (type == "cabang") {
                                            <?php
                                                if (isset($_GET['fCabang'])) {
                                                    $fCabang = $_GET['fCabang'];
                                                    for ($i = 0; $i < count($fCabang); $i++) {
                                            ?>
                                                        if (idA[i] == <?php echo $fCabang[$i] ?>) {
                                                            textHTML += " checked";
                                                        }
                                            <?php
                                                    }
                                                }
                                            ?>
                                        }
                                        
                                        textHTML += "><label for='" + dataName + "'>" + dataName + "</label>";
                                    }
                                    container.insertAdjacentHTML("beforeend", textHTML);
                                }

                                let dataK = [];
                                let dataS = [];
                                let dataS2 = [];

                                <?php
                                    $queryK = "SELECT DISTINCT IdSupplier FROM pemesanan WHERE Status = '$status' ORDER BY IdSupplier ASC";
                                    $resultK = mysqli_query($connect, $queryK);
                                    $K = [];
                                    while($rowK = mysqli_fetch_array($resultK)) {
                                        array_push($K, $rowK["IdSupplier"]);
                                    }
                                    
                                    if (in_array(0, $K)) {
                                ?>
                                        dataK.push(0);
                                <?php
                                    }
                                    $K = '(' . implode(',', $K) .')';
                                    $queryTemp2 = "SELECT * FROM suppliers WHERE Id IN " . $K . " ORDER BY Nama ASC";
                                    $resultTemp2 = mysqli_query($connect, $queryTemp2);
                                    while($rowTemp2 = mysqli_fetch_array($resultTemp2)) {
                                ?>
                                        dataK.push("<?php echo $rowTemp2['Id']; ?>");
                                <?php
                                    }

                                    $queryS = "SELECT DISTINCT IdCabang FROM pemesanan WHERE Status = '$status' ORDER BY IdCabang ASC";
                                    $resultS = mysqli_query($connect, $queryS);
                                    $S = [];
                                    $Snew = [];
                            
                                    while($rowS = mysqli_fetch_array($resultS)) {
                                        array_push($S, $rowS["IdCabang"]);
                                    }
                                    
                                    if (in_array(0, $S)) {
                                        array_push($Snew, 0);
                                ?>
                                        dataS.push(0);
                                <?php
                                    }
                                    $S = '(' . implode(',', $S) .')';
                                    
                                    $queryTemp2 = "SELECT Kelurahan, IdToko FROM address WHERE Jenis = 'branch' AND IdToko IN " . $S . " ORDER BY Kelurahan ASC";
                                    $resultTemp2 = mysqli_query($connect, $queryTemp2);
                                    while($rowTemp2 = mysqli_fetch_array($resultTemp2)) {
                                ?>
                                        dataS.push("<?php echo $rowTemp2['Kelurahan']; ?>");
                                        dataS2.push("<?php echo $rowTemp2['IdToko']; ?>");
                                <?php
                                        array_push($Snew, $rowTemp2['Kelurahan']);
                                    }
                                    $Snew = '(' . implode(',', $Snew) .')';
                                    function getSnew() {
                                        return $GLOBALS['Snew'];
                                    }
                                ?>

                                fSupplier.addEventListener('click', () => {
                                    addHMTL(dataK, sSupplier, "supplier", []);
                                }, {once: true});
                                fCabang.addEventListener('click', () => {
                                    addHMTL(dataS, sCabang, "cabang", dataS2);
                                }, {once: true});
                            </script>
                        </div>

                        <div class="reset" onclick="reset()">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABmJLR0QA/wD/AP+gvaeTAAADBElEQVR4nO2bvW7UQBRGz02UijeJXwCIWEQDimgo1uZNQCiABNptSAsv4dkKJEAgbVIAoqCeAt6FocCWwPL4987YCJ8u87Oa7yROPHcmsLCwsLDw/yJNnVmWrZxzpyLyJc/zN7EWpUGapvdF5BbwOs/zt75xXgFF+AvgoGjaGmMeK68zCGmaboCz8mvnXLbb7Uzd2IO6xmLSaaX/rPjgWVMNDyAiV33jmwR8rmmetYS68ADOua++OYe+Dmvt9yRJjoBVpWuVJMmRtXY/eKUB8IUHtrvd7qVvnlcAgLV2f3x8fCgiNytdqyRJrlhrPw5Yqzrr9fq5iNT9fjo3xjxqmtsoAMBae+GRcDIHCUX4JzVd58aYB23zWwXAfCWMDQ8dBcD8JGiEhx4CYD4StMJDTwEwvQTN8DBAAEwnQTs8DBQA8SWECA8jBEA8CaHCw0gBEF5CyPCgIAB+S0iS5ABQleALLyIvjDEPh3xmFRUBANbaS00JTeHzPFcJD4oCQE9CrPCgLADGS4gZHgIIgOESYoeHQAKgv4QpwkNAAdBdwlThoaUqrIWvWiMiGwDnXF0xI0oRNooAgDRNnwFPu4yN8Z0vCfoI/EnD4/AXMcNDRAHQLiF2eGgoi4fCOed97Jr6QhH1J6BhY1MSvbwWTUCH8CVRJUQR0PR33jm3n7LGGFxA20tOqK10V4IK6PqGp72V7kMwAX1fb6eSEETA0Hf7KSSoCxi7sYktQVWA1q4upgQ1Adpb2lgSVASE2s/HkDBaQOhiRmgJowS0HFqo7eqstZehDl8GCwh9YlMl1AnUIAGxw5eEkNBbwFThS7Ql9BIwdfgSTQmdBcwlfImWhE4C5ha+RENCq4C5hi8ZK6FRQJqmG88NzG3bDcyYFEWVumu9J23Xer0Csiy7B7yq6ZrltXlr7b7hbvM3a+2PunlNt8Vv1DTPMnxJsbZttd05d903xytARD5UmmYdvqRGwk8Reecb33gQsV6vb4vIHRG5/Af/ZeYucA14b4z5NPV6FhYWFhbmyC8MV2tmYUX/LAAAAABJRU5ErkJggg==">

                            <script>
                                let search = document.querySelector(".search input");
                                let searchby = document.querySelector("select#searchby");
                                let sortby = document.querySelector("select#sortby");
                                let sorttype = document.querySelector("select#sorttype");
                                let filterS = document.getElementsByName("fCabang[]");
                                let filterK = document.getElementsByName("fSupplier[]");

                                function reset() {
                                    search.value = "";
                                    searchby.value = "NamaProduk";
                                    sortby.value = "NamaProduk";
                                    sorttype.value = "ASC";
                                    for (let i = 0; i < filterS.length; i++) {
                                        filterS[i].checked = false;
                                    }
                                    for (let i = 0; i < filterK.length; i++) {
                                        filterK[i].checked = false;
                                    }
                                }
                            </script>
                        </div>
                    </div>
                            
                    <input type='submit' value='Go'>
                </form>

                <?php
                    $search = $searchby = $sortby = $sorttype = "";
                    if (!isset($_GET['search'])) {
                        $queryS = "SELECT * FROM pemesanan JOIN produk_transaksi ON pemesanan.NoTransaksi = produk_transaksi.NoTransaksi WHERE Status = '$status' ORDER BY $value DESC";
                    } else {
                        $search = $_GET['search'];
                        $searchby = $_GET['searchby'];
                        $sortby = $_GET['sortby'];
                        $sorttype = $_GET['sorttype'];

                ?>
                        <script>
                            search.value = "<?php echo $search ?>";
                            searchby.value = "<?php echo $searchby ?>";
                            sortby.value = "<?php echo $sortby ?>";
                            sorttype.value = "<?php echo $sorttype ?>";
                        </script>
                <?php
                        if ($searchby == "NoTransaksi") {
                            $queryS = "SELECT * FROM pemesanan JOIN produk_transaksi ON pemesanan.NoTransaksi = produk_transaksi.NoTransaksi WHERE Status = '$status' AND pemesanan." . $searchby . " LIKE '%" . $search . "%'";
                        } else {
                            $queryS = "SELECT * FROM pemesanan JOIN produk_transaksi ON pemesanan.NoTransaksi = produk_transaksi.NoTransaksi WHERE Status = '$status' AND " . $searchby . " LIKE '%" . $search . "%'";
                        }
                        

                        if (isset($_GET['fCabang'])) {
                            $fCabang = $_GET['fCabang'];
                            $queryS = $queryS . " AND (";
                            for ($i = 0; $i < count($fCabang); $i++) {
                                if ($i == 0) {
                                    $queryS = $queryS . "IdCabang = " . $fCabang[$i];
                                } else {
                                    $queryS = $queryS . " OR IdCabang = " . $fCabang[$i];
                                }
                            }
                            $queryS = $queryS . ")";
                        }
                        if (isset($_GET['fSupplier'])) {
                            $fSupplier = $_GET['fSupplier'];
                            if (isset($_GET['fCabang'])) {
                                $queryS = $queryS . " OR (";
                            } else {
                                $queryS = $queryS . " AND (";
                            }
                            for ($i = 0; $i < count($fSupplier); $i++) {
                                if ($i == 0) {
                                    $queryS = $queryS . "IdSupplier = " . $fSupplier[$i];
                                } else {
                                    $queryS = $queryS . " OR IdSupplier = " . $fSupplier[$i];
                                }
                            }
                            $queryS = $queryS . ")";
                        }

                        if (isset($_GET['from']) && isset($_GET['to'])) {
                            $from = $_GET['from'];
                            $to = $_GET['to'];
                            $queryS = $queryS . " AND $value BETWEEN '$from' AND '$to'";
                        }

                        if ($sortby == $value) {
                            if ($sorttype == "ASC") {
                                $sorttype = "DESC";
                            } else if ($sorttype == "DESC") {
                                $sorttype = "ASC";
                            }
                        }

                        $queryS = $queryS . " ORDER BY " . $sortby . " " . $sorttype;
                ?>
                        <script>
                            let preferences = document.querySelector("form.preferences");
                            preferences.style.display = "flex";
                        </script>
                <?php
                    }
                    $resultS = $connect -> query($queryS);
                    if ((!empty($resultS) && $resultS -> num_rows > 0) && !isset($_GET['search'])) {
                ?>
                        <script>
                            let preferences = document.querySelector("form.preferences");
                            preferences.style.display = "flex";
                        </script>
                <?php
                    }
                    if ((!empty($resultS) && $resultS -> num_rows > 0)) {
                ?>
                        <div class="table">
                            <table>
                                <tbody>
                                    <tr>
                                        <th></th>
                                        <th>No.</th>
                                        <th>Transaction Number</th>
                                        <th><?php echo $display; ?></th>
                                        <th>Total</th>
                                        <th>Branch</th>
                                        <th>Supplier</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Buy Price</th>
                                    </tr>
                    <?php    
                        $number = 1;
                        while($rowS = mysqli_fetch_array($resultS)) {
                            echo "<tr>";
                    ?>
                            <td class='delete'><a href='invoice.php?id=<?php echo $rowS["NoTransaksi"]; ?>'><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAACRklEQVRoge2ZvWsUQRjGn2f3FCz8SHONmGiwE+wEwTLYmGo5dsE7BEvzF1j5gabwr7CMxexBBLW5lLHRThFEIZJCJNhEjES8Yx6bi+ze997mbnK4v2pn33dmnoeZ2XdggQK3cFhCtVqdazabSwDmSR6boJYTAC5KOkNy11r7vNVqNdbX13cHdeprIAiCsu/7qyRvA5ik8EE0ST6VdM8Y871XQk8DlUrlkud5rwDMT1Te6Gxba5fr9fqHzkCXgSAIyqVS6S0ciJe0R/KTpB2ScwCuJsLbAK50rkSpcxDf91cxffGS9CCO48fJl1EU3ZC0BuA0gAVJjwCsJHNSK9A+sDuY8p6XdL9T/AFhGC4DeNFu/gFQNsb8OIh7yeT212ba4vf6iQcAY8xLAG/azeOSlpLxlAFJC4cvcTAkP46QtpnIP58MpAx4nnfykHRl4dewBJI/E8+nkjGvO322KAy4pqsODMC2v8lfM85xlmQNI9y7xmFkA5LW4ji+Nc4kURRRUm2cvsP4f7YQyVoURQTwPssEki5LuplZ2YhkOQOT2gbnoii6OyhB0rV+sSwGJsWipCfjdp65M2Ct/ZxsH4UVeC1pc3gaQPJdHMfPku+c1wGSG8aYhxnH/EdRB1xT1AHXzPwWKgy4xnkdyEtRB1xT1AHXzPwWKgy4pjDgmsKAa1IGrLXfHGjIerdK0fmHpgHgdy452diXtJFngJSBer2+RfIOpmNin+SKMeZLnkF6XnHDMLwA4LqkxTyD952U3ALQyCu+4CjwF14P+w/EKHcvAAAAAElFTkSuQmCC"></a></td>
                        <?php
                            echo "<td>" . $number . "</td>";
                            echo "<td>" . $rowS["NoTransaksi"] . "</td>";
                            echo "<td>" . $rowS[$value] . "</td>";
                            echo "<td>" . $rowS["Total"] . "</td>";
                            $query = "SELECT name from villages WHERE id = " . $rowS["KelurahanCabang"];
                            $result = $connectI -> query($query);
                            $row = mysqli_fetch_array($result);
                            echo "<td>" . ucwords(strtolower($row["name"])) . "</td>";
                            echo "<td>" . $rowS["NamaSupplier"] . "</td>";
                            echo "<td>" . $rowS["NamaProduk"] . "</td>";
                            echo "<td>" . $rowS["Kuantitas"] . "</td>";
                            echo "<td>" . $rowS["HargaBeli"] . "</td>";
                        ?>
                            </tr>
                    <?php
                            $number += 1;
                        }
                    ?>

                                </tbody>
                            </table>
                        </div>

                        <div class="page">
                            <script>
                                const page = document.querySelector('.page');
                                let rows = document.querySelectorAll("tr:not(tr:first-child)");
                                const show = 15;
                                let pageCurrent = 1;
                                let pageTotal = Math.ceil(rows.length / show);
                                let start = ((pageCurrent - 1) * show) + 1
                                let end = pageCurrent * show
                                if (end > rows.length) {
                                    end = rows.length
                                }
                                page.innerHTML = "<div class='bottomleft'></div>";
                                let bottomleft = document.querySelector('.bottomleft');
                                bottomleft.innerHTML = `<p>${start}-${end} of ${rows.length} entries.</p>`;

                                let image = "<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAABV0lEQVRoge3ZzU3DQBBA4QfrGpKIAhDCTRBEA4u0ln2hARqzfMAN8HvljgUUAFIkOrAlDhZnknhm1pH2FTDxd7Ayu4ZUKpVKpQ6sEMKqKIpbyZlOctg2hRBWwBNwk+f5T9d1rxJzjyWGbFtVVUvgHjgDPpxzd1Kzj6QG/VdVVcu+7x+AnBFxWdf1l9R8E4g2AgwgFghQhlghQBFiiQAliDUCFCAxECAMiYUAQUhMBAhBYiNAADIHBEyEzAUBEyBzQsCea7z3fgE8A+fAO7BumuZb8sF2zXSN12wvSNu2m77vL4A3xrPFS1mWJ6JPtmOTXnbv/SLLskfG9+TTObc+uJf9r7lgRP4Q54ARW1FiY0SXxpgY8TU+FkblYBUDo3bUtcaoXj5YYtSvg6wwJhd0FhizK1NtjBkEdDGma3zbthvgivEMczoMw7Xl74un8aEnlUqlUqkp/QLR++kzC5DmggAAAABJRU5ErkJggg=='><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAABP0lEQVRoge3Z3UkDQRhG4aOzNSRiASJYRSQNzMUsOzVoORYxeJMGomIRgcQCRKxhlvVivRU0fj8Kcwp4h4dlYJeFVqvVarX+SH3f36SUzjzOPpUaSindTtN0Bzx5YMQgtdZ7YAdcAs/DMJxLbX+nE8mxGOOi67pH4Ap4CSGsSimvkmd8lSgE/DDiEPDBqEDAHqMGAVuMKgTsMOoQsMGYQEAfYwYBXYwpBPQw5hDQwbhAQB7jBgFZjNjb7zFtNpt3YA3sgYtxHLcxxsUxW64QyVwhOeclsGX+hjmEENafT+nHud2RnPOy1vrAfD8OIYTrf3fZpRHgANFAgDFECwGGEE0EGEG0EWAAsUCAMsQKAYoQSwQoQawRoADxQIAwxAsBghBPBEAnNVRrTcyIPbAqpbxJbZvn+aOn1Wq1Wq3f9AHI0uwGQtaNWQAAAABJRU5ErkJggg=='>";
                                page.innerHTML += "<div class='navigation'></div>";
                                const navigation = document.querySelector('.navigation');
                                navigation.innerHTML = image;
                                const prev = document.querySelector(".navigation img:first-child");
                                const next = document.querySelector(".navigation img:last-child");

                                if (rows.length > show) {
                                    if (pageCurrent == 1) {
                                        for (let i = show; i < rows.length; i++) {
                                            rows[i].style.display = "none";
                                        }
                                    }

                                    if (pageCurrent == 1) {
                                        prev.classList.add("disabled");
                                    } else if (pageCurrent == pageTotal) {
                                        next.classList.add("disabled");
                                    }

                                    prev.addEventListener("click", function() {
                                        if (pageCurrent > 1) {
                                            pageCurrent -= 1;
                                            if (pageCurrent < pageTotal) {
                                                next.classList.remove("disabled");
                                            }
                                            if (pageCurrent == 1) {
                                            prev.classList.add("disabled");
                                        } 
                                            start = ((pageCurrent - 1) * show) + 1
                                            end = pageCurrent * show
                                            if (end > rows.length) {
                                                end = rows.length
                                            }
                                            bottomleft = document.querySelector('.bottomleft');
                                            bottomleft.innerHTML = `<p>${start}-${end} of ${rows.length} entries.</p>`;
                                            rows.forEach(row => {
                                                row.style.display = "none";
                                            });
                                            for (let i = 0; i < rows.length; i++) {
                                                if (i >= start - 1 && i <= end - 1) {
                                                    rows[i].style.display = "table-row";
                                                }
                                            }
                                        }
                                    });

                                    next.addEventListener("click", function() {
                                        if (pageCurrent < pageTotal) {
                                            pageCurrent += 1;
                                            if (pageCurrent > 1) {
                                                prev.classList.remove("disabled");
                                            }
                                            if (pageCurrent == pageTotal) {
                                                next.classList.add("disabled");
                                            }
                                            start = ((pageCurrent - 1) * show) + 1
                                            end = pageCurrent * show
                                            if (end > rows.length) {
                                                end = rows.length
                                            }
                                            bottomleft = document.querySelector('.bottomleft');
                                            bottomleft.innerHTML = `<p>${start}-${end} of ${rows.length} entries.</p>`;
                                            rows.forEach(row => {
                                                row.style.display = "none";
                                            });
                                            for (let i = 0; i < rows.length; i++) {
                                                if (i >= start - 1 && i <= end - 1) {
                                                    rows[i].style.display = "table-row";
                                                }
                                            }
                                        }
                                    });
                                } else {
                                    prev.style.opacity = "0";
                                    next.style.opacity = "0";
                                    navigation.style.display = "none";
                                }
                            </script>
                        </div>
                <?php
                    } else {
                        echo "<div class='empty'>0 results.</div>";
                    }
                ?>
            </div>
        </div>
    </div>

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