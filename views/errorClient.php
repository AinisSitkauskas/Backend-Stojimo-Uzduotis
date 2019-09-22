<!DOCTYPE html>
<html lang="lt">
    <head>
        <meta charset="UTF-8">
        <title>Registracija</title>
        <link rel="stylesheet" href="styles/styles.css">
    </head>
    <body>
        <p style="color:red; font-size:20px"> <?= $error ?></p><br>

        <a href="client.php">Grįžti į pradinį puslapį</a><br><br>

        <?php
        if (!empty($_SESSION['clientCode'])) {
            ?>
            <a href="client.php?clientCode=<?= $_SESSION['clientCode'] ?>">Grįžti į registaciją</a>
            <?php
        }
        ?>    

    </body>
</html>