<!DOCTYPE html>
<html lang="lt">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="refresh" content="5">
        <title>Švieslentė</title>
        <link rel="stylesheet" href="styles/styles.css">
    </head>
    <body>
        <h1> Registracijos </h1>


        <h1>Klientai, kurie kviečiami ateiti pas specialistą</h1>

        <?php
        if (empty($takenClients)) {
            ?>
            <h2>Šiuo nėra kviečiamų klientų </h2>

            <?php
        } else {
            ?>         

            <table>
                <tr>
                    <th> Eil. Nr. </th>
                    <th> Vardas </th>
                    <th> Pavardė </th>
                    <th> Specialistas </th>
                </tr>
                <tr>

                    <?php
                    for ($i = 0; $i < $numberOfTakenClients; $i++) {
                        ?>            
                        <td><?= $i + 1; ?></td>
                        <td><?= $takenClients[$i]["clientName"]; ?></td>
                        <td><?= $takenClients[$i]["clientSurname"]; ?></td>
                        <td><?= $takenClients[$i]["specialistName"] . " " . $takenClients[$i]["specialistSurname"]; ?></td> 
                    </tr>

                    <?php
                }
                ?>
            </table>
            <?php
        }
        ?>

        <h1>Greitai sulauksiančių eilės klientų sąrašas</h1>

        <?php
        if (empty($waitingClients)) {
            ?>
            <h2>Šiuo nėra laukiančių klientų </h2>

            <?php
        } else {
            ?>         

            <table>
                <tr>
                    <th> Eil. Nr. </th>
                    <th> Vardas </th>
                    <th> Pavardė </th>
                    <th> Specialistas </th>
                    <th> Numanomas laukimo laikas </th>
                </tr>
                <tr>

                    <?php
                    for ($i = 0; $i < $numberOfWaitingClients; $i++) {
                        ?>            
                        <td><?= $i + 1; ?></td>
                        <td><?= $waitingClients[$i]["clientName"]; ?></td>
                        <td><?= $waitingClients[$i]["clientSurname"]; ?></td>
                        <td><?= $waitingClients[$i]["specialistName"] . " " . $waitingClients[$i]["specialistSurname"]; ?></td> 
                        <td><?= $waitingTime[$i]; ?></td> 
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
