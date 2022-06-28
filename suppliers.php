<?php
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppliers</title>
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
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABmJLR0QA/wD/AP+gvaeTAAACBUlEQVR4nO2ZTW7TQBiGnw+ugEQXnMD2qoq4COOpKAcIlEvkDiAqJLaVaJQlhW3uQD0SXCE5A8OixkKR2tjz49rS96yikd7P8z52Io8CiqIoiqIoiqIoiqIEIMaYN9ba14BkzGThaeyAuq7PReQKeFVV1UnTNDc5Mrl4EjvAey//fV5aay85cldDMrmIfgKcc7dVVZ0Ai3ZpceyuhmRyES0AoGmam6IononIy3ZpUZblC2vtt+1261NlcpBEAIBz7sdBodPdbvdgoZBMapIJgHlKSCoA5ichuQCYl4QsAmA+ErIJgHlIyCoApi8huwCYtoRRBMB0JYwmAKYpYVQB0BV6LiL/zgGn+/3+2NlhcKYvowsAcM59Pyi0qKrqV9M0t0MyZVn+ds79jNlL9HH4MfHe/4md8RgCxBjzSUTedgsin9fr9dehmc1mcx27mbEFiDHmw0GRL0VRXAD3/aCFZPpvKHbAkGu1Rd53C3dFlqvV6r5HOSQzbFMphvS5zhTLwzgCJlse8guYdHnI+x4g1tqPwKDyAZm4TeYYSlvEe3/RLfQsPzATv9EcM+dSHtILmFV5SCtgduUhnYBZloc0AsRae+m9X3YLd+/273jg9TYgk4VoAXVdnwNX3cAeRUIyuUj673DfIiGZXCT5ChhjzgDa42mfIiEZRVEURVEURVEURVHS8BeiPUB1BfqrXAAAAABJRU5ErkJggg==">
            <ul>
                <li><a href="dashboard.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAFOUlEQVRoge2YXYhVVRTHf+vcO6OYPvThRwpSKUqio0GmvZSaFqMEyp09d4aMJo3xrZ56iaJJigiCKPogQxMGnZn2HQcp582c6MG+wI8UzIw00coxFWQk5Zy9erj7yrnXex3vmRGF7h8u55y19l77/2d/rLUv1FBDDTXU8H+GjEaQxsbGMePHj18MLAPmAbOBe4E7AAWGgD+BoyJyUFX3AHuttVdGOvaIBBhjFqjqBhHJAndW2f28qvaIyKfW2v1JOSQSkMlkGoIgeBtojMU4JSI7VfUAcMw5dzIIgqPAlVQqNTsMwxkiMkNE5qvqamCq76dAv3Puld7e3oM3VUBbW9vYoaGht4AXgTRwBdgMdFprv/NkADDG1AOXgSvW2jHxOB0dHcGhQ4cWA8+KyDqgHghF5P1x48a9unXr1n9HXYAxZiZggQVABHRGUdSxY8eOExXaVxQQR0tLy31hGHaIyFogBewLgsD09PT8NmoCstnsQufcLmAicFpVW3K53LfD9TPG/ABctNY+MVzb5ubmx1S1m/zmH3TOrezt7f1puH7DCshkMg8HQbAHGA8MhGGY7evrOzNcvyRobW2dHIZhD/A4cNE5t2w4EcH1nMaYmUEQ9JMnD/mTJhoVtmUQhqEAk/znhCAI+rPZ7Izr9akooK2tbSz5NT8R+AY4AsxPp9O716xZc/cocb4KY8wU4GvgQeAAMABMdM5Zz6UsKgrwp80C4HQYhs3AUryIurq6jaNJHkBE3sGTT6fTy9PpdAtwGnjo0qVLb1bqV1ZAJpNpIH9URqra0tfXd8Za+5cXsdk51z3aAoBOVf0snU4v7+rqOtvV1fW3iLR6Di8ZY+aV61R2ExtjdgErVXVLLpdbfxPI3jCampq2ishzwFfW2qdL/dfMgDFmAfkMe9k5N6Kl0tTU9KQxZsVIYojIG+QT5irPrQjXCFDVDeRnZkulJHUjMMZMEZEvga/8Bk0Ea+3vIvK559Re6i8SYIyp94UZQGfSQQFU9XnyJUK9XwIjQYFL1mf4qyidgUfJn/WnfG2TFOJrHABUdd31Gg+HOXPm7CV/It2lqoviviIBqrrEj76TWGFWLbLZ7CJgJnDK/2ZlMplF1+9VGR0dHQ7Y6T+XxX1FAkSksEkS1+cAURSt9a/bVbULIJVKPTOSmL5MR0Qa4vbSJTTLN76hSrAc2tvb60Sk2X9uF5FtPmZLe3t7XdK4wDH/nB03lgqYBBBFUWIBFy5cWEG+/Dhsrd3vb1uHgYnelwjOuYKAyXF7uqTdBIBUKnXcGFOw7bbWLq9irFUAItJTMIhIj6pu9L7+Gw3ky/GF5TgWUDoDrkycqm5tqroUQER2xWz9/rmkmlgVUMSxdAbOAdNSqdT07u7ukwkHuB/AOXc0ZvvFPx+oJpC19pHCuzFmOnACOB9vUzoD/wCo6rRqBirBHwAi8lTBoKqN/vX4COIWOJ2LG0tn4FegIYqiWUCiRKaqn4jIe6q6zRhTIL7W+z5OEtOjcPociRtL88CP/rk46Shz5879QFXfBeqA9f5Xr6of5XK5D5PGLWRgESm6YhbNgHPuexGBfN2fCD5rvpzJZDqDIFguIhpF0e4k//nEISLLAFS1aGUUnTDGmBT51D8ZaLDW/jySQUcLvozeBwwODg5OHRgYCAu+oiVkrY1EpNd/3tKLTByFwlBVv4iThzL3ARHZRL6Qe+FmXN6rRWtr6z2+mlVV3VTqr3Sl7ANW32xyVWKHtTZTaix7qQ+C4DV8TrhNcDYIgtdvNYkaaqihhhpuP/wH2O7yjMJOncIAAAAASUVORK5CYII="></a></li>
                <li><a href="inventory.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAABg0lEQVRoge2ZzU3DQBBGP9vUwQVuFICEQEK0YEY+QBUUQh/WICghB5JD2gDq8HKJ0QJZe7w/8WLmHeOxPW9Hu5+sAIqShCLVc5umOe+6jgDUu/c8lWXJbdtuAZjoL4z5sLquz6qqImPMPYATR9mHMea5KApm5g0iSYWK2Ct/C+DYuvaG3RQAYKwmdFJeIgMr/26MeRlabeveOwCn1qWgSYlFQpoXPDNYalAkRfOCd3lJ/RI5ZPMufKS+iRDRCsC19VO0zejJ0GGyYuabr0L7LiLqG32csXkXttQDADDz/q1BRMaSyZZ9fZZzNRMbFcmNI0kREW0AXCTuxcWama/GiqQTmUsCAC4lRaKJ9DiPu0RMOUEXs0dUJDdUJDcWIzLp+B1jYnCKgk5K7IlMCU5R0EmJOpGeseBM8amwmD2iIrmhIrmhIrnhnSNDKS7NiT113mkfMpEUn7/eaR+c7NIUT532i9kjKpIbKpIbKpIbrn+s/gR2Nv2cyPrAvYTwOncDyr/gEwgV6TaoFPsuAAAAAElFTkSuQmCC"></a></li>
                <li><a href="products.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAACKElEQVRoge2YMYgTQRSGv9nIxUJbW7HWwkJRBCsPGzFoMrOetWksrSy9UktBG/GuES0y2RhSHWcjaiGc5dkK2gZBsUvMjkUSCHF3b3cz2dsL83WZ98j7f96bCXngcDiSEGmSlFJngA5wfrly/mMvDMONIAi+HZR4oBEp5RUhxFvglBVp2flpjJHtdvt9UlKiEd/37xpjtoHjwO5wOLzT7XZ/ZVGhlDIAWutU3Z9Sq9VOVqvV18BNYCCEuN9qtbbj8r2Yc+H7/qYx5g1jEy/6/f6NrCYWodfr/QFuG2OeAGvGmC0p5VOlVCVS8PyBUuoE8Aq4BfwFHmitn+UVlLcjs0gpm0KI58AasANsaK1/z+ZEGdkDLuQtWhBftNYXZw+iRqvsJiBC47G4zEVGYZlMR3WeuMt+5HBGykbsHclDvV4/XalUtoCrjJ/KKAbAB+Ce1vqHrdpWOzIxcY14E0xi68BLm7WtdgS4BBCG4bkgCL5GJTQajbOe5+0Dl20Wtm3kM7Dued6+UipNrjWsjtZoNGoC7xjfgzgGwC7QtFnbakc6nc534LrN70zLyjy/zkjZcEbKhjNSNpyRsuGMLIrv+5tSysdZY3EcipHJ8u+REOLhvOCkWBKFG5kKnX6eFZwUy4xSysStXGySVCdPzF32srEyRmz/Z09N0ko2z7q2cCN5HpI0xtxo5WVZW/6V6cjKGIkdrSJ+3W0S1ZFPhavIzsfDFuBwHDX+AZTiuWzva/QsAAAAAElFTkSuQmCC"></a></li>
                <li><a href="suppliers.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAENElEQVRoge2aW2hcRRjHf9/ZJfZqjC1JzpqKN5D6IEillpqitWqgEARlc7ZeEEVao1TEC1gfpKAv+qAo+KBESFshWVelFNToQys0RWtFvNBaoS3FNtmNt1iKNIbd8/chWzE1m+yeORul5P9y5rDzffP778ycmTm7MKc5zWk6WT2SXnq3moolOgRrMVYgmoElGF/lC6zlUyvG3WasRpo3qCUZ8rRgE7BoqjqC+wtZ2x5nuxCjkdaMbjbRD7SUE+8HPg6NL5LGiWJIu8HrwCjGgMTAkpD+gzkbj6P9WIykutQp430gaWJ3aDxTyNqBSZXSSvgee4A1/2j8qIxMvt++dGVwNtKa1jXm8TmwWPBSYTlb2GphpfoX36MLG8a5zjy2AB0Gp8KQ1YWcHXJlcZIfaMAPpFRGvbVFyvxA2/xA8gMNunJ4LsEtgVYBHcBowxiP1xZtCht4BBgBbmxN6yYXFicjCbgTQOLN4zvt91rjR3bYH4i3ADyPLhcWJyMybgEw+DAygMduAMEqFxYnI4g2gPACDkdNUSxxrFy8wgXFzQhcBLD4F05FTbAUhgAMFrqAuA0tOAFwehGpqDl+hUYAiREXFicjJr4GMI/rI+dIsqJMctSFxc2I8R6AQSZyjpAHAUy868LiOkcuAzC4JGoCwbrytdEFxHWOPAkQwuaoOUwE5eujLiyuPbIAoCHBkcgAYh+AjCYXEDcjxgGAYsjtUVMUE9xQLn7vguL61HobQCGvpbrUWWt8KtAdJrYDCHa6sDgZGR6hV2IXRrOM52uNF7wILBNkSyEvu7DEcLCS+QE/AU2JkOaTOfutmqiWtC73PI4BQ/mstblSuE52wGSwB0gUExNrQlUNe2wsFz9wZ4jFCAA9ACaeXZbRjNuVpffKN+gGMHgjDoD4Xj4E6jPIGOwfztq/tuR+oH3A6rjamyRjMK4eYd58HgAQfz9Oz1V9TEw02p6MK9fxXhvzA81YL5+1WN+l+YEE8c2R/1yx9YiL/Iz2ItqnrWQM5vttTaWPY+uRVFpXl4t/xpVzksS04zaWHmnboGtLIdny7Y5a46f7pquVk5HmQFcmYHMppBtoMPjuTMhTrlBRVLuRtBIpYz3GRsF6JoanDHrHz/DY6C47HTtlFaraSKpTC7SQTYQ8IePs3mhM0JeAV4ey9k2dGKvSzEa2yms9TLdCnkM0l/cCP8joocS2Qs5+rjdkNZrWSFNajfMOkcO4DQMZnxHyQuEdPgKbefWbRVU00pbW/FKCTxArgRETD+ez5nT4qacqGil5vIJYKTjihawbztmPswlWq6ZcEP1Ay4GHgGLS467/uwmotLIb9wEJg4GTffbt7CJF09RGxK0AmNsLgdlUpTlyFYBEjx+oZxZ5IqvS0Do4yxzOmrJH4tjEnauzB6B66bw5WNXlvyhT6fzpEcP5t/RptPcv0k5Df8OlDIYAAAAASUVORK5CYII="></a></li>
                <li><a href="branches.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABmJLR0QA/wD/AP+gvaeTAAAFlElEQVR4nO2bTWgcZRjHf88kSEVKP0x60IpSRXDTg3jw0IPuSQpFCs2+k6g9qLUNrfYiLWqRMlKhIK092A/74UG00OxOAoJaRYVc6knFw+6Ilgpi8dKgFj+IJXkfD92tk81O9mvmXaH5n9535tn5P///vN+TwBKWsIQl3MSQIAi8SqXylIjc0coPVNV6nvfB+Pj4D91yFwqFJz3Ps8Vi8Ryg3TxsZGTkfmvtZhHxWolX1V+GhobO9pfL5a0i8q5q6/zW2jHgvk6TBcT3/ROqOqaq+L7/aLFY3EkXJlhrzwPr2tFRqVTwPM9b1ylph7ghvnZBVcd83z8BiNNERO7tT7j3m4icttYusNPzvL/m5ubOdcpZL76Gqgl02hKstRv7+vpGrbW3NchZVHU7sKr+XpIBq1T19vXr1+8IgsC2m0wCxPf9o43E16CqY8aY/qGhobZ5JyYmLgIHknhpIB6gfsCYjpW3VSqVtJpl7c3vSuCaxxtF0XFHvPMNEJFjqnosdmmHMeZ0EAQtjayLJDHvzYvIGeB4Em+1JWTOCwtbAGEY7q4zYVu5XO40mVoSN96AiJzJ5XJjIjKvnzfirVQqp7LmbfRwDcNwt4icjP342XK5/Ha7yRhjDtUlcbJYLCb17wW8XDf/UDucNGj2i/EmCdJisbizzoTtURSdbNOEHXVJNBvhG/Fua4NvwSzTjHcxMQuSUdXn2jFBVY8AfwBvtjG91XgPA3+KyJFWuOhAPCRPg/OSKRQKsyLyPFw3oVwue0EQbG82VYVhuB/Y36KAet49wJ4W4xsOeLlcbhdNTPeAa7WKtfafRsk0GhO6GKDSRlt9njq9noiEwCXgkqqGCSQLugPdjdJpoe1mX6+3v7qra2Vjs6A7cH3R4gVB8FyKK8ZW0VGzr9fb7tvT+vlaVZ+JouiM45aQOM+3+yI6SbrXJqQmHjozABJMqFQq72RsQqrioXMDoIEJwNMZmpC6eOjOAEgwoVwuv2+M6evy2XFkIh66NwAamCAiT4hIWiZkJh7SMQAajwmjKZiQqXhIzwBI34TMxUO6BsAiJuTz+Wb7jjiciIf0DYAEE9asWfNeiyY4Ew/ZGACdm+BUPGRnACSYMDg4mNQdnIsHNx8ipFAovBXbQAGMi8hFVX0VQEReAwZdiwd3X2IafRD5HVhZLU8DAzeCWzs+SwWudnBaLBZ31q0YV8bKcfEtneSkhTSXq00RRdH5XC43ICIPN7rvqtnH4dQASDahF+LB8dfYOK8xpgQMV+sTpVLJ4KjZx9Gr8zwVkXKtUi07Fw+9M+B/gyUDep1Ar7FkQK8T6DWWDOh1Ar3GTW+A85Xg6OjoPdbaF1R1BFhbvXxZVc9Za49OTk7+5DIflwaIMWYP8DpwS0LMNVXdF4bhYVdJOdsMGWPeAIImnH0i8lgul7s1iqLPXeTlZAwoFAqbgb2xSxdEZCOwHFheLV+o3RSRl3zff9xFbu0cVXcKEZGDsfr4lStXtk5NTc3Grn2az+e/GBgYOCsiPoCqHgQ+JONNUuYtwBjzEPBAtforMFYnHoCpqalZEdlRjQEYGh4efjDr/Fx0gfjBx0elUulqUmCpVLqqqh/X6kknR2kicwNEZFWsfLlZvOd5P8eqqzNJKs6XNYGqTsfKaxeLBbDW3lUre543vVhsGnDRBb6OlTcZY1YkBRpjVojIplp9bm7uq0wzw4EBpVLpG+D7anW1qp5q9GUon8/3q+op/vu7/u8mJia+zTo/F9OgAq8AkwAi4g8ODt7p+/6BmZmZLwGWLVu2QVX3Axtiv3sZB+eEzpbCxpjDwIutxKrqoTAM9zaP7B7OlsJRFH2Wy+X+FpFHSG55M8C+MAwDV3k53w1u2bLl7v7+/l2quhGo/cfajyLyyezs7HHXu8El3Oz4Fzv6jRsXnIdJAAAAAElFTkSuQmCC"></a></li>
                <li><a href="sales.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAABmJLR0QA/wD/AP+gvaeTAAAF4UlEQVR4nO2bbYhc1RnHf8+9s04IKtpYTUyL0mqju75VQVEhRWqLtiHuztwzNywNhn5QNGoUUYNvXfOhUA02aesLRUqIRDNzbhKQNn4IKoaooNWYyGpUfEH9oESLL9lVdmbO44e5sztd3Wgy98wgmf+XufPMc5/z/z+cl+eecwd66KGHHno4dCHtBiiVShcD52TA5YDhnPtvkiRPtBOjrQQYY84CXmo3ThvQIAh+WS6Xdx1sgKBNAifRPfEA4pw7uZ0AuayYqOofVfXprOLtDyLyKxH5VxaxMksA8OGmTZvezjDejIiiaEFWsdodAj949BLQbQLdRi8B3SbQbRzyCchsGRSRrcaYrMJ1DL0ekGGsx4H3M4y3P/wUuDSLQFmWwn9PkuTxrOLtD1EUXSoimSTgkB8CvQR0m0C3keUkOCOGhoaODYJgfhAEQa1We3fLli2fdKLd7wOvCYjj+OfOuQeBX5NunORyOWeM2Qost9a+N9O9ixcvPiKfz59orX3FJ0dvQ8AYM9859xxwMf+/axQAi4CnjTE//rZ74zj+RT6f3wPsNsYM++LYJOMFqroKaArcJyJ3i8ga4MvUdiKwcvp9xpgFzrmngONT0+G+OILHBIjIopavV1cqlVsqlcoNwM0t9t+33mOMWQBMiheRhwYGBh7yxRH8rgJHTDYSBHua16r6WovPkc2LYrF4Cg3x86Ahvr+//8qRkRHnkaPXSXA3cB6Ac+5WY8zSarWaE5HWbr8LII7jU51zTwJzoXPiwW8PWN1yPQj8r6+vby+NSRFAVfWv3RQPHhNgrU2APwFNIX1M9bgqcIOIfJBOeF0RD57rAGvtKmPMYyJSUtU5AKr6URiGj9ZqtSAIgieB46A74qEDlaC19mXg5VZbsVgcSMUfC1PiR0dH88aYuc45mTVr1scbNmz43De/jj8LxHF82nTxYRjePjo6+jDwGfB2EARvTUxM7DXGJIVC4Sc++XQ0AcaY09MJb1L8xMTETbVabQcwTGOeaOIwoBiG4Q5jzFxfnDqWgGKxeAbwBGl12Oz2uVxuJY1DVmhUifeIyN3AF6ntBBG5wxevjiSgWCyeEQTBN8SPjIw4ERlscb3RWntzpVK5BVjeNKrqZb64eU9AHMdnpuKPgW+d7Y9ucX99klhL9TjNJ1N4XQUGBwePcs5tY2bxAKOkcwKwcnh4eOe+fftqzrnbW3xe9cXRawLCMPwRMAf2u86vAS5Kr39TrVb35vN5ncbtb744eh0C6fsClwBLZipyrLWPqeoIUxVjyJR4VdXV1tqHfXHsRCG0Lf2c0SdJkrviON5ar9f/EATBvNS8F3jEWvuMT34d2RP8PiiXyy8AL3S63a4lYNmyZbPGx8evApg9e/YD69at+6obPLqyLV4qlS4aGxvbqar3quq9Y2Njo1EUZXLSc6DoaA8oFArzwjD8i6ounfbTz9LT5X+HYXj1xo0bO3XG2JkEGGNC4BpgFVPbYHVVvQ9ARJbTmP0X1ev1hcaYO4F/WGvrvrl1ohI8G3iWxnrfFP8ScEGSJCuSJFkRBME5wHPpb0emvi8aY873zc9bAgYHB4+Komitc+554NzU/ClwPXCutfb5pm+5XN5lrb1QVS8HPk7NZwLPGGPWDw0NzfHF00sCoigyfX19e0TkOhpdGxGxuVzuFGvt2hm6tiZJsh5YAPwTUBoHKktzudwbxpgr8PBabrsvS0eABVDV34nIm8B9wG9b3N6kcQy27UBil0qlhap6PzDQYt4OXKWqJ4jI1iaNdP/xoJBZDxCRa2k82DTFj4vIbcBpByoeoFKpbAfOTmOMp+aFwM60rUyQ5SrQuo7/B7i2Uqm8005Aa+0E8OdCobA+DMM1QJHGTlFmNUNbCRARVdVW0/vACmvtlrZYTcPmzZs/ACJjzBCwlsY7QpMc2ond1hCo1+u7ga+AqqquBvqzFt+KNHZ/2lYV+FJVD/rPEpnAGDO/UCjM+27PzNudu2TJkuO/27OHHnrooYceZsLXoGQOQ24y4U0AAAAASUVORK5CYII="></a></li>
                <li><a href="orders.php"><img src="data:image/png;base64,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"></a></li>
                <li><a href="logout.php"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAACmklEQVRoge2Yv4sTQRTHv28SQ4z/gKcHFilTqIVea6FycBzImsnFSjiRwx+1hY1p7C3u1MaIRSBkQJYggiB4pRoQi6S3ELS4IlHuDLtmnkX2IFccN7OZEIv5NLuEfd/3/e5mdt8u4PF4PJ4pIFdCtVpNdLvd60R0lpmz1kaI/gL4WiqVXtdqNW1aZ93osP69Xi8kotXETGqhXq/XBnANABs1Tt1pAinlCoA3AH4A2ErOphXJVbsP4CQRrbRarbcmda6uwLlkW1dKPU4rUi6XC0T0kJnPAzAKINI2m4SIjiVb6zN/wIwQ8aSeUc00Df8HfIB54wMAgNZ6DwCYedeFng1ObqNRFD3L5/M7w+FQudCzwUmAdrv9G0DdhZYtqQNIKU8T0W1mfq6U+unSlA2pAgRBsAhgm5mLzNwH8MStLXOsF3EQBIuZTGYbQBFAh4heurdljlWAxPwHjM1/AbCslBrMxJkhxn+harV6ajQa7Z95TUQdrfWDcrlsVJ/MSZuu14txAK31GsbmAUAw84bt3M/MO3C8XowDMHMdwA0AF5KfNonou0X9bhRFryz9HYlxAKXUQEp5BcA7AEsAVoUQl5rN5jfXpmywWsRKqUEcx8sAOgDOjEaj90EQLMzGmhnWt9EwDPtxHF/FOERRCLHm3pY5qYa5MAz7uVzuMhHdiqLohWtTNqQeJRqNxi/Maf6ZxMkwFwTBQjab3ZjHXOTkfSCTyawz8yMiWnehZ4PTrxIAci70bPCvlPPGB5g3TgIw8zDZPT6lTiHZ/WNa4+Q5wMwfiQjMfEdKWUjzeYWITgC4ua9nXGfb6DAqlcoWM9+dVoeInrZarXvGx0/bcBIp5UVmXhJCFI4++iBa6z0i+qSU+uzSk8fj8cyWf82k4Tv/UUmuAAAAAElFTkSuQmCC"></a></li>
            </ul>
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
                <h1 class="title">Suppliers</h1>
                <a href="" class="add">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAAz0lEQVRoge3ZQQrCMBCF4TfqPl5FFLz/AcSTuHDfOi7c1YkklMFH+r5lCp3+WKFpARHZBMs6sbtfABwXyw8zu2fMywyZATwXy8XM9hnzMkM8HGiWMnOXcdJ/UAgbhbBRCBuFsFEIG4WwGSYkfKR29xOAW+14ownAoWGthwM4R5uz2i9S8L0p6hVd8JoI4HNNJTowzK2lEDa1kOq92GFqXOtR/e/q5QMbhbBRCBuFsFEIG4WwUQibYULW7qF/eSH4GJo1LDPkiuDzdOI8EdmCNyEqI4JpdT0YAAAAAElFTkSuQmCC">
                    <p>Add</p>
                </a>
            </div>
            <div class="main">
                <div class="addedit">
                    <div class="exit"></div>
                    <div class="add form">
                        <h1>Add</h1>
                        <label for="nama">Nama</label>
                        <input type="text" id="nama" placeholder="" name="nama" value="" required>
                        <?php // echo $ePassword ?>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="" value="<?php // echo $email; ?>" required>
                        <?php // echo $eEmail ?>
                    </div>
                </div>

                <?php
                    $query = "SELECT * FROM supplier";
                    $result = $connect -> query($query);
                    if (!empty($result) && $result -> num_rows > 0) {
                ?>
                        <form action='' class='preferences' method='POST'>
                        <div class='lt'>
                            <div class='tp'>
                                <div class='search'>
                                    <img src='data:image/png;base64,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'>
                                    <input type='text' placeholder='Search' name='search'>
                                </div>
                                <div class='sort'>
                                    <img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAACDUlEQVRYhe2XvWtTURjGf8+5jXFxMsTFUXATtF2CQ4NUWxyCITmguIibg39BcRGc9D9wEVTw3tuUipaKGepWERF1sIubUxEXB79q8jokqbHcfMnNdp/xvM+5z++cw+G8V0xB3vsS0AQ2gQtxHLcGeTWl8E0gD5iZNSWdHwThphD+AjhgZgYg6aykde99MFWAvpXngHfOOQM+Az/MbBHYSIJIBaB/5ZJWgevd0hfn3CzwDViQ1CyXyzMTAVQqlUO1Wu3EGOE5SatRFHlJ1quHYbjtnJsDvptZuVgsPu+HGAmQz+cbzrk33vtjAyyPgZyZPYqiyAO23xCG4Xa73S7ROY5yoVBYGhsAOKqOjiQVJS0DN1ZWVi4nhffUaDTeA3Nmdq/Vam31xmcGTRhXURTdHdcbx/EH4Gr/WKrX8H+UAWQAGUAGkAFkABnAXj9QrVYPB0FwW9Kd7rs9TKrX68vOuZ1J+oEk7e1AEAQlSVeA18N6wG74Q0k3zexWksHMdqyjT2MDSNoAngEHJb3y3s8mhXvvH0i6CPx2zlWSPhrH8cd2u31KUm0UwD9/Rt2+/QmwZGa/JJ0G7ks6bmbzwDXgkpntBkEwH4bhy1EBEwH0QTwFFoGfwFdJBeCtmZ0EdoFyHMdb++emAtCDkLRuZucAzAxJpB0+EKAHwd+dUPdIUg0fCtAHsQacARbSDgf4A9tAyCb9zgBkAAAAAElFTkSuQmCC'>
                                    <p class='caption'>Sort by: </p>
                                    <div class='dropdown'>
                                        <p>Nama</p>
                                        <img class='dd' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAABPElEQVRIie2US04CQRRFzxN2gDrQHVCMNLIPkn5vDSL4YQe9BMNHE9kBIIw0DllFhzWQduyE2E4a0mJDCzrsO6uql3OTW7cKcuX6N6nqrZm1APkDRsysZWY3qw2AWq1WKhaLYbz3OBwOG0C0K1xVe0AdYLFYHE4mk/cCwGw2+yiXy0ciUgUunHPHQRC87guPoqg3Ho+fAQ6WE6PR6Broxsu6mT35vn/wA5UC9zyvk4D3K5XKKqJCcjIIgjfnXAmoAmdhGJ6q6st0Ot0Ul3ie1xGRRgJ+6fv+Z6pBmsl8Pj8xszQTUdW2iDQ3wVMN1k1E5DzFRFS1DWyFbzTIMOG3cMju/Ld2iMhDDL2KzzMrndWSKAZ0l+AlPIqivnOuuQ0OWyJKau3iM2NJapdvQczsDmAwGNyz+0vPlWtPfQFO+677u9G6MQAAAABJRU5ErkJggg=='>
                                    </div>
                                </div>
                            </div>
                            <div class='bt'>
                                <div class='filter'>
                                    <img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAACkElEQVRoge2ZzWsTQRiHn1lzE/8CT+JFkr2KUlFPpVRt/SCbiijePXgreBU9elDBm4eAp+QdiqC0UIQGhWr10oOJiHhQ0YNBFIzUr+T1VEnTpN2vpJuyz21233f292NnduadhZRkYdobnuc9B/ZvkZagLInIwdWG03FzWEwAHGhvdBoZWratkWdboiIca7SuMZLJZCaA2kDlhONNJpM51X7BdEZ4nrcbeALsGZSqgHxoNpuHZ2Zm3rVfXDdHROSj4zijwKeBSfPPZ2C00wT0mOylUumt4zhjwJd+KwvAN2BMRF53u9nzq1UqlV46jjMOfO+XsgD8UNXjIrLcK2DdHOkkn8+PGGPmgZ2xSvPPiqoes9ZWNgradB2x1i6q6hngV2zS/PNHVQubmQCfC6K1dl5VzwHNyNL80zLGXLTWPvQTvMNvr7Va7VUul3sPnMTHkIyIquolESn6TfBtBKBWqy27rvsVGA8sLQCqesVaeztITiAjANVqdcl13QxwJGiuH1T1mrX2etC8wEYAqtXqguu6u4CRMPm9UNU71trpMLmhd7/lcnnaGHM3bH4X7rmuezlscqg3sko2m51V1X3GmFyUfoD79Xr9fLFYDP1VjFSPiEjTGHMBmI3QzaNGo3G2Uqn8jaIlcmElIr+BPPA4RPpT4PTc3FzkxTaWClFEVoBJYCFA2iIwLiKNODSkJI1Ytxqe52mQeBGJ7fnb9hRlaEmNJI3USNJIjSSN1EjSSI0kjdRI0ojbyIuY+/NN3EaOGmOuAj9j7ndT+nKGOzU1tbfVat0ETmwUF2dh1dfD6EKhMKGqt+jxP3JoKsRyufwAyA1iuPX798B/ug23oRla3fA8bxK4AdRF5NCgn58yKP4BQD7MFThPz+UAAAAASUVORK5CYII='>
                                    <p class='caption'>Filter: </p>
                                    <div class='dropdown'>
                                        <p>Provinsi</p>
                                        <img class='dd' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAABPElEQVRIie2US04CQRRFzxN2gDrQHVCMNLIPkn5vDSL4YQe9BMNHE9kBIIw0DllFhzWQduyE2E4a0mJDCzrsO6uql3OTW7cKcuX6N6nqrZm1APkDRsysZWY3qw2AWq1WKhaLYbz3OBwOG0C0K1xVe0AdYLFYHE4mk/cCwGw2+yiXy0ciUgUunHPHQRC87guPoqg3Ho+fAQ6WE6PR6Broxsu6mT35vn/wA5UC9zyvk4D3K5XKKqJCcjIIgjfnXAmoAmdhGJ6q6st0Ot0Ul3ie1xGRRgJ+6fv+Z6pBmsl8Pj8xszQTUdW2iDQ3wVMN1k1E5DzFRFS1DWyFbzTIMOG3cMju/Ld2iMhDDL2KzzMrndWSKAZ0l+AlPIqivnOuuQ0OWyJKau3iM2NJapdvQczsDmAwGNyz+0vPlWtPfQFO+677u9G6MQAAAABJRU5ErkJggg=='>
                                    </div>
                                    <div class='dropdown'>
                                        <p>Kabupaten</p>
                                        <img class='dd' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAABPElEQVRIie2US04CQRRFzxN2gDrQHVCMNLIPkn5vDSL4YQe9BMNHE9kBIIw0DllFhzWQduyE2E4a0mJDCzrsO6uql3OTW7cKcuX6N6nqrZm1APkDRsysZWY3qw2AWq1WKhaLYbz3OBwOG0C0K1xVe0AdYLFYHE4mk/cCwGw2+yiXy0ciUgUunHPHQRC87guPoqg3Ho+fAQ6WE6PR6Broxsu6mT35vn/wA5UC9zyvk4D3K5XKKqJCcjIIgjfnXAmoAmdhGJ6q6st0Ot0Ul3ie1xGRRgJ+6fv+Z6pBmsl8Pj8xszQTUdW2iDQ3wVMN1k1E5DzFRFS1DWyFbzTIMOG3cMju/Ld2iMhDDL2KzzMrndWSKAZ0l+AlPIqivnOuuQ0OWyJKau3iM2NJapdvQczsDmAwGNyz+0vPlWtPfQFO+677u9G6MQAAAABJRU5ErkJggg=='>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='rt'>
                            <input type='submit' value='Go'>
                        </div>
                    </form>
                            <table>
                                <tbody>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Provinsi</th>
                                        <th>Kabupaten</th>
                                        <th>Kecamatan</th>
                                        <th>Kelurahan</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                    <?php    
                        $number = 1;
                        while($rowS = $result -> fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $number . "</td>";
                            echo "<td>" . $rowS["Nama"] . "</td>";
                            echo "<td>" . $rowS["Email"] . "</td>";
                            $query = sprintf("SELECT * FROM address WHERE Jenis = 'supplier' AND IdToko = '%s'", $rowS["Id"]);
                            $result = $connect -> query($query);
                            $rowA = $result -> fetch_assoc();
                            echo "<td>" . $rowA["Provinsi"] . "</td>";
                            echo "<td>" . $rowA["Kabupaten"] . "</td>";
                            echo "<td>" . $rowA["Kecamatan"] . "</td>";
                            echo "<td>" . $rowA["Kelurahan"] . "</td>";
                    ?>
                            <td class='edit'><a href=''><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAC8UlEQVRoge2Zz2oTURTGv5OZ7DtlXIhv0AbfoCBSFy7qIuYOFlyI2BGtIOjOVV9BaRViKbQKjXMncS1uUi24UKib5AVaFVJou2vBkOOijWC88zczkxHz7ebce+78vvtv7swAY4011j8tGjUAAFQqlRkiWgQwA8AEsE9EH5l5RUq57Zc7UgO2bRcPDw+fArjnVYeInnc6nYfNZrOrKtdTowvQGfwmgOt+9Zj5vmmaDOCBqnwkIyCE0IjoNTPfiJB2SUq5NRgsJMgVRZeZ2YqSwMx3VfGRGJBSvieiBQC9sDlENKOKZ2bAtu2iZVmz/WvHcdYimjinCmZioL9gmfmdZVm3+/GIJvZVwdQNDOw2BWZ+GdPEB1UwVQNCCO3g4GAdf26VBWZeFUL83vsdx1lj5jvwMcHMK6q4lhjtgGzbLp6cnNSISLXbEICrpVJpr9Vq7QBAu93+WiqV9gDM4e/tfdl13arqPqmMQMiHVNjpVDcM45FXI4mPgBBCOz4+3vDo+UERgGvT09Oddrv9BQBardbO1NTULhHNAWgYhjFfrVZ/+jWQmMIeDxTqEdGC4zhr/YBlWbMTExNbfvBAggaEEBozvyKi+ZhNMIBFKeWLKEmJrAHbtosA3gwBD5x25rIQ4kqUpKENCCG0o6OjDUSfNiq9NQyjGSVhqCkU81TppXrQglUptoE8wAMxDeQFHohhIE/wQEQDeYMHIhjIIzwQ0kBe4YEQz4GlpaUCEW3mER4IcZgzTfMWgCcJ3CtxeCDECJwdb4dVKvBAwBoQQlwAsBtUL0CpwQPBI1BBjuGBYANiiLZThwd8erdcLp/XNG0P8U6smcADPnC6rgu/ch9lBg/4ADJzJUZ7mcIDHlMozvRhZmdycvJmlvCAx/8BXdfLzBwGvgfgE4BV13XXcfpem6mUBgI+ffehJQBXSvktDbCw8vpDc3Hgugdgm5ldXdfrtVrte8pcoeVl4BmAxwA+E5Hb7XYbjUbjR4ZcY4011v+iX8OzYo/wFlllAAAAAElFTkSuQmCC'></a></td>
                            <td class='delete'><a href='delete.php?type=suppliers&id=<?php echo $rowS["Id"] ?>' onclick="return confirm('Do you want to delete this data?');"><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAFjElEQVRoge2aP4wbRRTGfzNrO5c7wyEldxEhQeTSpEJ0KSJERxcFFxvJiEAX0UCFEEJInEIDEkUk0tDiRIk81jndKRUNUBAKmkuRP5cDEv4Erojic5R4bx+Fd2Htndld+3xHCr7Knjf73vv2zXwzs7uKCcH3/YpS6hMROQU8m9P9V+Ar4GNjzONJxC9NwgmAiJwB3i/YfT/wgVJKgA8nEX9iRJRSpwBE5Fir1fouq+/JkyePicg3IvIWEyKiJ+Ekwn6APBIAzWbz2+Q1k4DKMtZqtflSqfQl8CowPamgI6ILXAmC4O12u33P1SlzaJVKpRbw8qQzGxHTQK1UKu0BXnF1SlXE9/05pdTnInICmN3GBMfBfRG5XC6X37t48eJfScMAkXq9vjcIgh+B53Y0vdFxJwiCl9rt9nrcMDDZe73eZzz5JAAOlMvlT5MNA0SUUid2Np/xISKvJf8Py+/3O5jLVnH1v07gf2TBuiD6vn8BeH2HcykEpdSFZrP5xnC7dYuilLqx/SmNhzAMrblZibg6Pwlw3WQrEc/zrjv8XAXeBO447I+Bd4DLGbm0ReRdoOew/xLF+MFm1FqPVBErEaXUeWNMA2g6krhhjDmntV502AnDcLHVan0BuGI0oxjnR8nNSsQYcx/40+LkYBRs1ZHnFEAQBA8cdrTWsW23I9HVKMZBi/lelFvarysgljumlFpIBrNgBqBSqWy4nAZBsJHsa4mxCiAiC0VyipFFxDYWF6Igtx3XzMBAsilUq9VMIkDsO0UkS02dRBwXHQbodru3gdBinwGUMWYDEItdGo1Gl/76ZTuohZ1OZy36fShlzFBTJxERsZXxqXq9vnd5efkR/SchKX++70/RJ/HQYu8Ccvz48d2O2HeXl5cf+b4/Bzydcq716EPLJXNBEMQltw6vUqkUDxnb8NoAmJqayhxWYRja5gebm5ujV6RSqdzEMjziSSgi1gkvItXoZ8dijtuqFhvAKoDneTYiMj09fcuVr5NIo9HYwDJ8tNaZFen1erkV0VpnVsShWHejnKzIexxkG5OHwL2WJJJ0EgmCIFN6sUx0Ry7/xs0yYpHgxN2yVkQpteWKMKL0Qg4Rx8ULAJ7nuRbFsedIEASxzxSRvI1sJhGHBB/0fb9y6dKl3+jL6XDAmehaZ0USVUvi4dLS0u+nT58uAwdSiWZIL+QQcUiwBzxPX9F+GjbGSWqtU0RicjHZIdwGZH19/YUoxgCypBdyiIRheAvYtCQUlz41vGIitorE5BwVyZLeTa21ayj3fWcZo3cXqbseS7BDucaaI/G65JDetbz3KEWexttKGstjSrmy5kjc5qhI7Msmvbkn1rGIxHfNtp0fd44kqjuy9EIBIlm7YNt2fgtzJPZ1eNhQ5BlCLhGHBC9EiaUqsh1DK096oQARhwTP1mq1PcaYDkNH4kSSzskuIsNE7hljOvV6fS+WVxl50gsFiIRhuEb/6cgAKpWKS4KrkD1HSKvWKgwcEZJ4rLVOKecwcokYYzYtyf5zZrBs551Dy/M81zE3S3pvRTlkoujLUKdyWdaSGYAwDFNEEm0DRGIfiSNCZmwbChFRSjm386TXkrgiWQvicEWcE90hNikUImKTP6XU0cXFRS0iu4ZM877vz3qet8/iZ973/VlgbijZXZGvo5bwN4vkWOiDAa31dZHUqffFlZWVn5VSc0PtM8CazbfW+msgIF2RsysrKx9hee9eRHqxBbMhCIIbnpfakIL7feMzjnbXWb2C4+OBokMr84OBZD/f9x/gfqi2XegaY6rYn5ENoKhqCQXVY8K4TgESMMK3KCJyZex0xke7aMfCRJRSZ3G/F9kO3ATOFe1sncE2XLt2rXPkyJElpdQ+pdQ87om7VfxBvxKnjDHOj2iG8TdTr16n1iXrYQAAAABJRU5ErkJggg=='></a></td>
                        </tr>
                        <?php
                            $number += 1;
                        }
                        ?>

                        </tbody>
                        </table>
                    <?php
                    } else {
                        echo "<div class='empty'>0 results.</div>";
                    }
                    ?>
            </div>
        </div>
    </div>

    <script>
        let add = document.querySelector("a.add");
        let addedit = document.querySelector(".addedit");
        let exit = document.querySelector(".exit");

        // addedit.style.display = "none";

        add.addEventListener("click", event => {
            addedit.style.display = "flex";
            console.log("tessss");
            event.preventDefault();
        });

        exit.addEventListener("click", event => {
            addedit.style.display = "none";
            event.preventDefault();
        });
    </script>
</body>
</html>