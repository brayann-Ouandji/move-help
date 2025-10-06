<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Exercice Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php
    $maintenant = new DateTime();
    $maintenant-> setTimezone(new DateTimeZone ('Europe/Paris'));
    echo "<p> Bonjour en PHP</p>\n";
    echo "<p> nOUS SOMMES LE" .$maintenant -> format('d/m/Y')."</p>\n";
    echo "<p> il est ".$maintenant->('H:i/s')."</p>\n";
    ?>
</body>
</html>