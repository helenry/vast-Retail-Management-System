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

    $queryS = "SELECT DISTINCT IdCabang FROM inventory ORDER BY IdCabang ASC";
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
    <title>Inventory</title>
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
                <li><a href="inventory.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAABjUlEQVRoge2ZTU7DMBBGnyPuANmwgR0HQEIgIa4AVYTgFByEcxAF0SN0QbvgGoBacYkOmwYSNT9ObDcm+O3aTJN5HtmfokIg4ATl5rai9hNOI5gA15vnPK8h+0p5AyW2n2hV5OBWTiJhgnAvcFRZJHwCL0rIlhkLW1KGIqWVvwEOCxff2UwBoK3GdFK9RBpW/gNh2rTa+W/Xwp2C458LhpPSFjFpvu2eNqQaRVw03/asvlJbIrtsvo4+UiWROJEZcFn4ytpm7Ef9YaJgtkzVVeHzL3EieaOPwzVfR0nqAWCVquqtESciBRlvqeozGqoZ2wQR39jTKYoTWQBnjnupRjFfPamLtjLdiQwjASCc65RpTSSn9rhzRJcTdDR7JIj4RhDxjdGIdDp+2+gUnJpBp4vtiegHp2bQ6WJ1IjltweniVWE0eySI+EYQ8Y0g4hu9c6QpxXVzYqvOIO1NJmL/9dcg7Y2TXTfFXaf9aPZIEPGNIOIbQcQ36v6x+hMUs6k8EcV8593053XoBgL/gm/1v9vUkDae5QAAAABJRU5ErkJggg=="></a></li>
                <li><a href="products.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAACKElEQVRoge2YMYgTQRSGv9nIxUJbW7HWwkJRBCsPGzFoMrOetWksrSy9UktBG/GuES0y2RhSHWcjaiGc5dkK2gZBsUvMjkUSCHF3b3cz2dsL83WZ98j7f96bCXngcDiSEGmSlFJngA5wfrly/mMvDMONIAi+HZR4oBEp5RUhxFvglBVp2flpjJHtdvt9UlKiEd/37xpjtoHjwO5wOLzT7XZ/ZVGhlDIAWutU3Z9Sq9VOVqvV18BNYCCEuN9qtbbj8r2Yc+H7/qYx5g1jEy/6/f6NrCYWodfr/QFuG2OeAGvGmC0p5VOlVCVS8PyBUuoE8Aq4BfwFHmitn+UVlLcjs0gpm0KI58AasANsaK1/z+ZEGdkDLuQtWhBftNYXZw+iRqvsJiBC47G4zEVGYZlMR3WeuMt+5HBGykbsHclDvV4/XalUtoCrjJ/KKAbAB+Ce1vqHrdpWOzIxcY14E0xi68BLm7WtdgS4BBCG4bkgCL5GJTQajbOe5+0Dl20Wtm3kM7Dued6+UipNrjWsjtZoNGoC7xjfgzgGwC7QtFnbakc6nc534LrN70zLyjy/zkjZcEbKhjNSNpyRsuGMLIrv+5tSysdZY3EcipHJ8u+REOLhvOCkWBKFG5kKnX6eFZwUy4xSysStXGySVCdPzF32srEyRmz/Z09N0ko2z7q2cCN5HpI0xtxo5WVZW/6V6cjKGIkdrSJ+3W0S1ZFPhavIzsfDFuBwHDX+AZTiuWzva/QsAAAAAElFTkSuQmCC"></a></li>
                <li><a href="suppliers.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAETUlEQVRoge2aW2hcVRSGv3VGLImpsRhS1AreQMyDIJW21BStVQuFICTZZ3JBQZHWKhXxgtYHCeiLPigKPlgiTI2hndmOhIBafWhTm6K1Il5IVGj7YOlDTLBWEZKZZC8f5gRCzGQu+0wopf/LXuyz1tr/f9bZsy8MXMZlXMZykFok7enpWZPL5baLyFZgPdAMXAt8Pzk5uXVkZGQ27jGviDNZd3f32rm5uRfz+fwuEWlYwqW1qampB/gwznEhxop0dnbeJyIHgbVR1wngC+Bb59xZEWkVkfeA86p6CDgkIgettbk4xo9FSBiGbar6CYUKHw6C4OV0On1yoY8xJgEcAbYs6D7tnOvKZrPf+XLwFmKMaQG+AVaLyJstLS17+/r6XDH/3t7eq3O53F3AXmA7cAHYbK0d9+ERxxx5C1itqvuttS+Vch4cHPwbOAp8FYZhSlUfBfYBrT4kAp/gZDK5icJbPT87O/tsheG6atWqp4AJ4B5jzL0+XLyEOOfaAVR139DQ0F+Vxg8MDPyrqh8AiEjow8VLCHA/QBAEn3nkOAygqpt8iPgKWQeQz+d/rTaBiJyJzFt8iPgKuQZgenr6gkeOc1F7lQ8RXyFnARoaGq73yNEYtRM+RHyF/ACgqnd75Fgftad9iHgJUdUsgIh0VZtDRB6PzI99uHgJEZGbIvOGanOo6rbIbFzWsQR8P63nAYIg2FNtAhFJRubTPkR8hdQDJBKJU9UmUNXjkbnGh4ivkJMA+Xz+oWoTqOrGyPzFh4jvZP8oMt8Nw7Ct0vjOzs6HRWT+kDXkw8VLyNTUVAoYBppV9bVK40XkDeBGIE1hF1014jhYiTHmDwrfeLO19s9ygowxNwNngHPW2nW+JHznCICKyBEgsWBNKAc7o/bTGDjEIgTnXD+Aqr7S1dVVcrvS3t5+HbAbIAiC9+PgENvlQxiGB1S1Czhhrf3fltwYcxzYHNd4izAaS0UA6uvrH4vMjUVcaiUCoDW2e61UKjVtjCnpZ62N9VLQGKMQ0xy5GBDrTWO1MMYco/Qtyqi1dkuxh7FVxBhze2TOxJVzEXS5h7FUpKOj404KqzMiMlBp/HJvulx4CUkmk7c65/ZQWBOuBH5W1Rd8SVWDioUYYxIiskNVdzrndlD4PBVIzczMPDM8PPxP7CzLQNlC2tra6uvq6nap6nOqOr83mhaRAyLyTjqd/rFGHMtCSSF9fX3B+Pj4blV9VVWbo+7fRKRfVfdnMpnJGnMsC8sKMcY0jo2NWeDBqOtrEXk9k8l8TolfkZVGUSHGmDrgS2ADMCEiT2YyGa/DTy1RVIiIvK2qG4BTwLZMJvP7ytGqHEsKSSaTdzjnngBmnXMd2Wz2ohYBRVZ2VX0ESACHstnsTytLqToUE/JAZF60c2Ixis2R26K23xjTv1JkfFBs0zi2oixiwJIViWMTtxjzB6Ba4ZI5WNXkvyhL4VKqyGgNcx/7Dw3uXQQOdcOxAAAAAElFTkSuQmCC"></a></li>
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
            <div class="top">
                <h1 class="title">Inventory</h1>
                <a href="formInventory.php" class="add">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAAz0lEQVRoge3ZQQrCMBCF4TfqPl5FFLz/AcSTuHDfOi7c1YkklMFH+r5lCp3+WKFpARHZBMs6sbtfABwXyw8zu2fMywyZATwXy8XM9hnzMkM8HGiWMnOXcdJ/UAgbhbBRCBuFsFEIG4WwGSYkfKR29xOAW+14ownAoWGthwM4R5uz2i9S8L0p6hVd8JoI4HNNJTowzK2lEDa1kOq92GFqXOtR/e/q5QMbhbBRCBuFsFEIG4WwUQibYULW7qF/eSH4GJo1LDPkiuDzdOI8EdmCNyEqI4JpdT0YAAAAAElFTkSuQmCC">
                    <p>Order</p>
                </a>
            </div>
            <div class="main">
                <form action='' class='preferences' method='GET'>
                    <div class="input">
                        <div class='search'>
                            <img src='data:image/png;base64,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'>
                            <p class="caption">Search by</p>
                            <select name="searchby" id="searchby">
                                <option value="NamaProduk" selected>Name</option>
                            </select>
                            <input type='text' placeholder='Keyword' name='search'>
                        </div>

                            <div class='sort'>
                                <img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAACDUlEQVRYhe2XvWtTURjGf8+5jXFxMsTFUXATtF2CQ4NUWxyCITmguIibg39BcRGc9D9wEVTw3tuUipaKGepWERF1sIubUxEXB79q8jokqbHcfMnNdp/xvM+5z++cw+G8V0xB3vsS0AQ2gQtxHLcGeTWl8E0gD5iZNSWdHwThphD+AjhgZgYg6aykde99MFWAvpXngHfOOQM+Az/MbBHYSIJIBaB/5ZJWgevd0hfn3CzwDViQ1CyXyzMTAVQqlUO1Wu3EGOE5SatRFHlJ1quHYbjtnJsDvptZuVgsPu+HGAmQz+cbzrk33vtjAyyPgZyZPYqiyAO23xCG4Xa73S7ROY5yoVBYGhsAOKqOjiQVJS0DN1ZWVi4nhffUaDTeA3Nmdq/Vam31xmcGTRhXURTdHdcbx/EH4Gr/WKrX8H+UAWQAGUAGkAFkABnAXj9QrVYPB0FwW9Kd7rs9TKrX68vOuZ1J+oEk7e1AEAQlSVeA18N6wG74Q0k3zexWksHMdqyjT2MDSNoAngEHJb3y3s8mhXvvH0i6CPx2zlWSPhrH8cd2u31KUm0UwD9/Rt2+/QmwZGa/JJ0G7ks6bmbzwDXgkpntBkEwH4bhy1EBEwH0QTwFFoGfwFdJBeCtmZ0EdoFyHMdb++emAtCDkLRuZucAzAxJpB0+EKAHwd+dUPdIUg0fCtAHsQacARbSDgf4A9tAyCb9zgBkAAAAAElFTkSuQmCC'>
                            <p class='caption'>Sort by </p>
                            <select name="sortby" id="sortby">
                                <option value="NamaProduk" selected>Name</option>
                                <option value="Stok">Quantity</option>
                                <option value="HargaBeli">Buy Price</option>
                                <option value="HargaJual">Sell Price</option>
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
                            <div class='dropdown kategori'>
                                <p>Category</p>
                                <img class='dd' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAABPElEQVRIie2US04CQRRFzxN2gDrQHVCMNLIPkn5vDSL4YQe9BMNHE9kBIIw0DllFhzWQduyE2E4a0mJDCzrsO6uql3OTW7cKcuX6N6nqrZm1APkDRsysZWY3qw2AWq1WKhaLYbz3OBwOG0C0K1xVe0AdYLFYHE4mk/cCwGw2+yiXy0ciUgUunHPHQRC87guPoqg3Ho+fAQ6WE6PR6Broxsu6mT35vn/wA5UC9zyvk4D3K5XKKqJCcjIIgjfnXAmoAmdhGJ6q6st0Ot0Ul3ie1xGRRgJ+6fv+Z6pBmsl8Pj8xszQTUdW2iDQ3wVMN1k1E5DzFRFS1DWyFbzTIMOG3cMju/Ld2iMhDDL2KzzMrndWSKAZ0l+AlPIqivnOuuQ0OWyJKau3iM2NJapdvQczsDmAwGNyz+0vPlWtPfQFO+677u9G6MQAAAABJRU5ErkJggg=='>
                                <div class="options kategori">
                                </div>
                            </div>

                            <script>
                                const fKategori = document.querySelector('.dropdown.kategori');
                                const sKategori = document.querySelector('.dropdown.kategori .options.kategori');
                                const fCabang = document.querySelector('.dropdown.cabang');
                                const sCabang = document.querySelector('.dropdown.cabang .options.cabang');

                                sKategori.style.display = "none";
                                fKategori.addEventListener('click', () => {
                                    if (sKategori.style.display == "grid") {
                                        sKategori.style.display = "none";
                                    } else if (sKategori.style.display == "none") {
                                        sKategori.style.display = "grid";
                                        sCabang.style.display = "none";
                                    }
                                });
                                sCabang.style.display = "none";
                                fCabang.addEventListener('click', () => {
                                    if (sCabang.style.display == "grid") {
                                        sCabang.style.display = "none";
                                    } else if (sCabang.style.display == "none") {
                                        sCabang.style.display = "grid";
                                        sKategori.style.display = "none";
                                    }
                                });

                                function addHMTL(data, container, type, idA) {
                                    let textHTML = "";
                                    for (let i = 0; i < data.length; i++) {
                                        let nameType = "";
                                        let dataName = "";
                                        if (type == "kategori") {
                                            nameType = "fKategori[]";
                                            <?php
                                                $queryTemp = "SELECT * FROM kategori ORDER BY Nama ASC";
                                                $resultTemp = mysqli_query($connect, $queryTemp);
                                                while($rowTemp = mysqli_fetch_array($resultTemp)) {
                                            ?>
                                                    if (<?php echo $rowTemp["Id"]; ?> == data[i]) {
                                                        dataName = "<?php echo $rowTemp["Nama"] ?>";
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

                                        if (type == "kategori") {
                                            textHTML += "<input type='checkbox' id='" + dataName + "' name='" + nameType + "' value='" + data[i] + "'";
                                        } else if (type == "cabang") {
                                            textHTML += "<input type='checkbox' id='" + dataName + "' name='" + nameType + "' value='" + idA[i] + "'";
                                        }
                                        
                                        
                                        if (type == "kategori") {
                                            <?php
                                                if (isset($_GET['fKategori'])) {
                                                    $fKategori = $_GET['fKategori'];
                                                    for ($i = 0; $i < count($fKategori); $i++) {
                                            ?>
                                                        if (data[i] == <?php echo $fKategori[$i] ?>) {
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
                                    $queryK = "SELECT DISTINCT Kategori FROM inventory ORDER BY Kategori ASC";
                                    $resultK = mysqli_query($connect, $queryK);
                                    $K = [];
                                    while($rowK = mysqli_fetch_array($resultK)) {
                                        array_push($K, $rowK["Kategori"]);
                                    }
                                    $K = '(' . implode(',', $K) .')';
                                    $queryTemp1 = "SELECT * FROM kategori WHERE Id IN " . $K . " ORDER BY Nama ASC";
                                    $resultTemp1 = mysqli_query($connect, $queryTemp1);
                                    while($rowTemp1 = mysqli_fetch_array($resultTemp1)) {
                                ?>
                                        dataK.push("<?php echo $rowTemp1['Id']; ?>");
                                <?php
                                    }

                                    $queryS = "SELECT DISTINCT IdCabang FROM inventory ORDER BY IdCabang ASC";
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

                                fKategori.addEventListener('click', () => {
                                    addHMTL(dataK, sKategori, "kategori", []);
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
                                let filterK = document.getElementsByName("fKategori[]");

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
                        $queryS = "SELECT * FROM inventory ORDER BY NamaProduk ASC";
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

                        $queryS = "SELECT * FROM inventory WHERE " . $searchby . " LIKE '%" . $search . "%'";

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
                        if (isset($_GET['fKategori'])) {
                            $fKategori = $_GET['fKategori'];
                            if (isset($_GET['fCabang'])) {
                                $queryS = $queryS . " OR (";
                            } else {
                                $queryS = $queryS . " AND (";
                            }
                            for ($i = 0; $i < count($fKategori); $i++) {
                                if ($i == 0) {
                                    $queryS = $queryS . "Kategori = " . $fKategori[$i];
                                } else {
                                    $queryS = $queryS . " OR Kategori = " . $fKategori[$i];
                                }
                            }
                            $queryS = $queryS . ")";
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
                                        <!-- 1 --> <th></th>
                                        <!-- 2 --> <th></th>
                                        <!-- 3 --> <th>No.</th>
                                        <!-- 4 --> <th>Code</th>
                                        <!-- 5 --> <th>Name</th>
                                        <!-- 5 --> <th>Quantity</th>
                                        <!-- 5 --> <th>Branch</th>
                                        <!-- 8 --> <th>Buy Price</th>
                                        <!-- 9 --> <th>Sell Price</th>
                                        <!-- 10 --> <th>Category</th>
                                    </tr>
                    <?php    
                        $number = 1;
                        while($rowS = mysqli_fetch_array($resultS)) {
                            echo "<tr>";
                    ?>
                            <td class='edit'><a href='formInventory.php?id=<?php echo $rowS["KodeProduk"] ?>&cabang=<?php echo $rowS["IdCabang"] ?>'><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAC8UlEQVRoge2Zz2oTURTGv5OZ7DtlXIhv0AbfoCBSFy7qIuYOFlyI2BGtIOjOVV9BaRViKbQKjXMncS1uUi24UKib5AVaFVJou2vBkOOijWC88zczkxHz7ebce+78vvtv7swAY4011j8tGjUAAFQqlRkiWgQwA8AEsE9EH5l5RUq57Zc7UgO2bRcPDw+fArjnVYeInnc6nYfNZrOrKtdTowvQGfwmgOt+9Zj5vmmaDOCBqnwkIyCE0IjoNTPfiJB2SUq5NRgsJMgVRZeZ2YqSwMx3VfGRGJBSvieiBQC9sDlENKOKZ2bAtu2iZVmz/WvHcdYimjinCmZioL9gmfmdZVm3+/GIJvZVwdQNDOw2BWZ+GdPEB1UwVQNCCO3g4GAdf26VBWZeFUL83vsdx1lj5jvwMcHMK6q4lhjtgGzbLp6cnNSISLXbEICrpVJpr9Vq7QBAu93+WiqV9gDM4e/tfdl13arqPqmMQMiHVNjpVDcM45FXI4mPgBBCOz4+3vDo+UERgGvT09Oddrv9BQBardbO1NTULhHNAWgYhjFfrVZ/+jWQmMIeDxTqEdGC4zhr/YBlWbMTExNbfvBAggaEEBozvyKi+ZhNMIBFKeWLKEmJrAHbtosA3gwBD5x25rIQ4kqUpKENCCG0o6OjDUSfNiq9NQyjGSVhqCkU81TppXrQglUptoE8wAMxDeQFHohhIE/wQEQDeYMHIhjIIzwQ0kBe4YEQz4GlpaUCEW3mER4IcZgzTfMWgCcJ3CtxeCDECJwdb4dVKvBAwBoQQlwAsBtUL0CpwQPBI1BBjuGBYANiiLZThwd8erdcLp/XNG0P8U6smcADPnC6rgu/ch9lBg/4ADJzJUZ7mcIDHlMozvRhZmdycvJmlvCAx/8BXdfLzBwGvgfgE4BV13XXcfpem6mUBgI+ffehJQBXSvktDbCw8vpDc3Hgugdgm5ldXdfrtVrte8pcoeVl4BmAxwA+E5Hb7XYbjUbjR4ZcY4011v+iX8OzYo/wFlllAAAAAElFTkSuQmCC'></a></td>
                            <td class='delete'><a href='delete.php?type=inventory&id=<?php echo $rowS["KodeProduk"] ?>&cabang=<?php echo $rowS["IdCabang"] ?>' onclick="return confirm('Do you want to delete this item?');"><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAFjElEQVRoge2aP4wbRRTGfzNrO5c7wyEldxEhQeTSpEJ0KSJERxcFFxvJiEAX0UCFEEJInEIDEkUk0tDiRIk81jndKRUNUBAKmkuRP5cDEv4Erojic5R4bx+Fd2Htndld+3xHCr7Knjf73vv2zXwzs7uKCcH3/YpS6hMROQU8m9P9V+Ar4GNjzONJxC9NwgmAiJwB3i/YfT/wgVJKgA8nEX9iRJRSpwBE5Fir1fouq+/JkyePicg3IvIWEyKiJ+Ekwn6APBIAzWbz2+Q1k4DKMtZqtflSqfQl8CowPamgI6ILXAmC4O12u33P1SlzaJVKpRbw8qQzGxHTQK1UKu0BXnF1SlXE9/05pdTnInICmN3GBMfBfRG5XC6X37t48eJfScMAkXq9vjcIgh+B53Y0vdFxJwiCl9rt9nrcMDDZe73eZzz5JAAOlMvlT5MNA0SUUid2Np/xISKvJf8Py+/3O5jLVnH1v07gf2TBuiD6vn8BeH2HcykEpdSFZrP5xnC7dYuilLqx/SmNhzAMrblZibg6Pwlw3WQrEc/zrjv8XAXeBO447I+Bd4DLGbm0ReRdoOew/xLF+MFm1FqPVBErEaXUeWNMA2g6krhhjDmntV502AnDcLHVan0BuGI0oxjnR8nNSsQYcx/40+LkYBRs1ZHnFEAQBA8cdrTWsW23I9HVKMZBi/lelFvarysgljumlFpIBrNgBqBSqWy4nAZBsJHsa4mxCiAiC0VyipFFxDYWF6Igtx3XzMBAsilUq9VMIkDsO0UkS02dRBwXHQbodru3gdBinwGUMWYDEItdGo1Gl/76ZTuohZ1OZy36fShlzFBTJxERsZXxqXq9vnd5efkR/SchKX++70/RJ/HQYu8Ccvz48d2O2HeXl5cf+b4/Bzydcq716EPLJXNBEMQltw6vUqkUDxnb8NoAmJqayhxWYRja5gebm5ujV6RSqdzEMjziSSgi1gkvItXoZ8dijtuqFhvAKoDneTYiMj09fcuVr5NIo9HYwDJ8tNaZFen1erkV0VpnVsShWHejnKzIexxkG5OHwL2WJJJ0EgmCIFN6sUx0Ry7/xs0yYpHgxN2yVkQpteWKMKL0Qg4Rx8ULAJ7nuRbFsedIEASxzxSRvI1sJhGHBB/0fb9y6dKl3+jL6XDAmehaZ0USVUvi4dLS0u+nT58uAwdSiWZIL+QQcUiwBzxPX9F+GjbGSWqtU0RicjHZIdwGZH19/YUoxgCypBdyiIRheAvYtCQUlz41vGIitorE5BwVyZLeTa21ayj3fWcZo3cXqbseS7BDucaaI/G65JDetbz3KEWexttKGstjSrmy5kjc5qhI7Msmvbkn1rGIxHfNtp0fd44kqjuy9EIBIlm7YNt2fgtzJPZ1eNhQ5BlCLhGHBC9EiaUqsh1DK096oQARhwTP1mq1PcaYDkNH4kSSzskuIsNE7hljOvV6fS+WVxl50gsFiIRhuEb/6cgAKpWKS4KrkD1HSKvWKgwcEZJ4rLVOKecwcokYYzYtyf5zZrBs551Dy/M81zE3S3pvRTlkoujLUKdyWdaSGYAwDFNEEm0DRGIfiSNCZmwbChFRSjm386TXkrgiWQvicEWcE90hNikUImKTP6XU0cXFRS0iu4ZM877vz3qet8/iZ973/VlgbijZXZGvo5bwN4vkWOiDAa31dZHUqffFlZWVn5VSc0PtM8CazbfW+msgIF2RsysrKx9hee9eRHqxBbMhCIIbnpfakIL7feMzjnbXWb2C4+OBokMr84OBZD/f9x/gfqi2XegaY6rYn5ENoKhqCQXVY8K4TgESMMK3KCJyZex0xke7aMfCRJRSZ3G/F9kO3ATOFe1sncE2XLt2rXPkyJElpdQ+pdQ87om7VfxBvxKnjDHOj2iG8TdTr16n1iXrYQAAAABJRU5ErkJggg=='></a></td>
                        <?php
                            echo "<td>" . $number . "</td>";
                            echo "<td>" . $rowS["KodeProduk"] . "</td>";
                            echo "<td>" . $rowS["NamaProduk"] . "</td>";
                            echo "<td>" . $rowS["Stok"] . "</td>";
                            if ($rowS["IdCabang"] == 0) {
                                $cabang = "Belum ada";
                                echo "<td class='null'>" . $cabang . "</td>";
                            } else {
                                $queryTemp = "SELECT Kelurahan FROM address WHERE Jenis = 'branch' AND IdToko = " . $rowS["IdCabang"];
                                $resultTemp = mysqli_query($connect, $queryTemp);
                                $rowTemp = mysqli_fetch_array($resultTemp);
                                $cabang = $rowTemp["Kelurahan"];
                                $queryTemp = "SELECT * FROM villages WHERE id = $cabang";
                                $resultTemp = mysqli_query($connectI, $queryTemp);
                                $rowTemp = mysqli_fetch_array($resultTemp);
                                $cabang = $rowTemp["name"];
                                $cabang = ucwords(strtolower($cabang));
                                echo "<td>" . $cabang . "</td>";
                            }
                            echo "<td>" . $rowS["HargaBeli"] . "</td>";
                            echo "<td>" . $rowS["HargaJual"] . "</td>";
                            $queryTemp = "SELECT * FROM kategori WHERE Id = '" . $rowS["Kategori"] . "'";
                            $resultTemp = mysqli_query($connect, $queryTemp);
                            $rowTemp = mysqli_fetch_array($resultTemp);
                            echo "<td>" . $rowTemp["Nama"] . "</td>";
                            
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