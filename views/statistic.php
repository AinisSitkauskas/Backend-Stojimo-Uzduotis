<!DOCTYPE html>
<html lang="lt">
    <head>
        <meta charset="UTF-8">
        <title>Registracija</title>
        <link rel="stylesheet" href="styles/styles.css">
    </head>
    <body>

        <h1>Specialistų aptarnavimo statistika</h1>

        <form  method = "post" action="client.php?action=statistic" >

            Pasirinkite specialistą:<br>
            <select name="specialist">               
                <?php
                $n = count($allSpecialist);
                for ($i = 0; $i < $n; $i++) {
                    ?>
                    <option> <?= $allSpecialist[$i]["specialistName"] . " " . $allSpecialist[$i]["specialistSurname"]; ?> </option>
                    <?php
                }
                ?>
            </select><br><br>
            <input type="submit" name="submit"  value="Ieškoti"><br><br>
        </form>

        <a href="client.php">Grįžti į pradinį puslapį</a><br><br> 

                <?php
        if (!empty($_SESSION['clientCode'])) {
            ?>
            <a href="client.php?clientCode=<?= $_SESSION['clientCode'] ?>">Grįžti į registaciją</a>
            <?php
        }
        ?>
        
        
        <?php
        if (!empty($_POST['specialist'])) {
            ?>
            <h2>Specialistas:  <?= $specialist[0] . " " . $specialist[1] ?></h2>

            <table>
                <tr>
                    <th> Savaitės diena </th>
                    <th> Klientų vidutinis laukimo laikas </th>
                </tr>

                <?php
                for ($i = 0; $i < 7; $i++) {
                    ?>  
                    <tr>

                        <td><?= $weekDay[$i]; ?></td>
                        <td><?= $averageWaitingTime[$i]; ?></td>

                    </tr>

                    <?php
                }
                ?>
            </table><br>

            <?php
        }
        ?>
    </body>
</html>
