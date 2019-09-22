<!DOCTYPE html>
<html lang="lt">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="refresh" content="5">
        <title>Registracija</title>
        <link rel="stylesheet" href="styles/styles.css">
    </head>
    <body>
        <h2> Jūsų registracija </h2>

        <table>
            <tr>
                <th> Vardas </th>
                <th> Pavardė </th>
                <th> Registracijos data </th>
                <th> Specialistas </th>
                <th> Statusas </th>
                <th> Numanomas pakvietimo laikas </th>
            </tr>
            <tr>
                <td><?= $clientRegistration[0]["clientName"]; ?></td>
                <td><?= $clientRegistration[0]["clientSurname"]; ?></td>
                <td><?= $clientRegistration[0]["registrationTime"]; ?></td>
                <td><?= $clientRegistration[0]["specialistName"] . " " . $clientRegistration[0]["specialistSurname"]; ?></td>
                <?php
                if ($clientRegistration[0]["clientStatus"] == "taken") {
                    ?>
                    <td>Klientas kviečiamas pas specialistą </td>
                    <?php
                } else {
                    ?>         
                    <td>Laukiama kvietimo pas specialistą</td> 
                    <?php
                }
                ?>
                <td><?= $clientRegistration[0]["impliedTakeRegistration"]; ?></td>
            </tr>
        </table> <br>

        <?php
        if ($clientRegistration[0]["clientStatus"] == "taken") {
            ?>
            <h1>Klientas kviečiamas pas specialistą! </h1>
            <?php
        } else if ($waitingTime == 0) {
            ?>      
            <h2>Jums liko laukti:  </h2>
            <h1> 0 h 0 min 0 s </h1>
            <h1> Specialistas turėtų netrukus jus pakviesti ! </h1>
            <?php
        } else {
            ?>
            <h2>Jums liko laukti:  </h2>
            <h1> <?= $waitingHours; ?> h <?= $waitingMinutes; ?> min <?= $waitingSeconds; ?> s* </h1>               
            <h5> Tai yra numanomas laikas, specialistas visada gali jus pakviesti anksčiau ! </h5>
            <?php
        }
        ?>

        <a href="client.php?action=delete">Atšaukti rezervaciją</a><br><br>
        <a href="client.php?action=delay">Pavėlinti</a><br><br>
        <a href="client.php?action=statistic">Specialistų aptarnavimo statistika</a><br>
    </body>
</html>
