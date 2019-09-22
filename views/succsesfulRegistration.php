<!DOCTYPE html>
<html lang="lt">
    <head>
        <meta charset="UTF-8">
        <title>Registracija</title>
        <link rel="stylesheet" href="styles/styles.css">
    </head>
    <body>
        <h2> Užregistruota sėkmingai !  </h2>
        <h3> Jūsų unikali nuoroda su kuria galite stebėti registracijos būseną ir numanomą laukimo laiką: </h3>
        <h4>http://www.minidienynas.vhost.lt/client.php?clientCode=<?= $uniqueURL ?></h4>
        <a href="client.php?clientCode=<?= $uniqueURL ?>">Peržiūrėti registraciją</a><br>
    </body>
</html>


