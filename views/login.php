<!DOCTYPE html>
<html lang="lt">
    <head>
        <meta charset="UTF-8">
        <title>Specialisto puslapis</title>
        <link rel="stylesheet" href="styles/styles.css">
    <body>
        <h1>Specialisto puslapis </h1>
        <h3>Prisijunkite įvesdami savo vartotojo vardą ir slaptažodį </h3>
        <form  method = "post" action="specialist.php?controller=login&action=login" >
            Vartotojo vardas:<br>
            <input type="text" name="specialistName">
            <br>
            Slaptažodis:<br>
            <input type="password" name="specialistSurname">
            <br><br>
            <input type="submit"  value="Prisijungti">
        </form>
    </body>
</html>
