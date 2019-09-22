<!DOCTYPE html>
<html lang="lt">
    <head>
        <meta charset="UTF-8">
        <title>Registracija</title>
        <link rel="stylesheet" href="styles/styles.css">
    </head>
    <body>

        <h1>Registracija</h1>

        <form  method = "post" action="client.php" >

            Jūsų vardas:<br>
            <input type="text" name="clientName">
            <br>
            Jūsų pavardė:<br>
            <input type="text" name="clientSurname">
            <br>
            Pasirinkite specialistą:<br>
            <select name="specialist">               
                <?php
                $n = count($specialist);
                for ($i = 0; $i < $n; $i++) {
                    ?>
                    <option> <?= $specialist[$i]["specialistName"] . " " . $specialist[$i]["specialistSurname"]; ?> </option>

                    <?php
                }
                ?>
            </select><br><br>
            <input type="submit" name="submit"  value="Registruotis">
        </form><br><br>

        <a href="client.php?action=statistic">Specialistų aptarnavimo statistika</a><br>

    </body>
</html>
