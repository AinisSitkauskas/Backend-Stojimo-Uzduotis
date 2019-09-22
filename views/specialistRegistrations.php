<!DOCTYPE html>
<html lang="lt">
    <head>
        <meta charset="UTF-8">
        <title>Specialisto puslapis</title>
        <link rel="stylesheet" href="styles/styles.css">
    </head>
    <body>
        <h2>  Sveiki, <?= $_SESSION['specialistName'] ?> ! </h2>
        <a href="specialist.php?controller=login&action=logout">Atsijungti</a><br>

        <h2>Priskirti klientai: </h2>

        <?php
        if (empty($takenClient)) {
            ?>
            <h2>Šiuo nėra kviečiamų klientų </h2>

            <?php
            if (!empty($waitingClients)) {
                ?>
                <a href="specialist.php?controller=registration&action=take">Pakviesti naują klientą</a><br>

                <?php
            }
        } else {
            ?>         

            <table>
                <tr>
                    <th> Vardas </th>
                    <th> Pavardė </th>
                    <th> Registracijos laikas </th>
                </tr>
                <tr>

                    <td><?= $takenClient[0]["clientName"]; ?></td>
                    <td><?= $takenClient[0]["clientSurname"]; ?></td>
                    <td><?= $takenClient[0]["registrationTime"]; ?></td> 
                </tr>
            </table><br>

            <a href="specialist.php?controller=registration&action=service">Klientas aptarnautas</a><br><br>      
            <a href="specialist.php?controller=registration&action=cancel">Klientas neatvyko</a><br>      
            <?php
        }
        ?>

        <h2>Laukiančių klientų sąrašas</h2>

        <?php
        if (empty($waitingClients)) {
            ?>
            <h2>Šiuo metu nėra laukiančių klientų </h2>

            <?php
        } else {
            ?>         

            <table>
                <tr>
                    <th> Eil. Nr. </th>
                    <th> Vardas </th>
                    <th> Pavardė </th>
                    <th> Registracijos laikas </th>
                </tr>

                <?php
                for ($i = 0; $i < $numberOfWaitingClients; $i++) {
                    ?> 
                    <tr>
                        <td><?= $i + 1; ?></td>
                        <td><?= $waitingClients[$i]["clientName"]; ?></td>
                        <td><?= $waitingClients[$i]["clientSurname"]; ?></td>
                        <td><?= $waitingClients[$i]["registrationTime"]; ?></td> 
                    </tr>

                    <?php
                }
                ?>
            </table>
            <?php
        }
        ?>
    </body>
</html>


