<?php

date_default_timezone_set('Europe/Vilnius');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

include_once("vendor/autoload.php");
include_once("src/Controller/TimetableController.php");
include_once("src/Exception/PrivateException.php");
include_once("src/Exception/PublicException.php");

try {

    $log = new Logger('name');
    $log->pushHandler(new StreamHandler('log.txt', Logger::WARNING));

    include_once('connection.php');

    $controller = new TimetableController($connection);
    $controller->registrations();
} catch (PublicException $exception) {
    $error = $exception->getMessage();
    include("views/errorTimetable.php");
} catch (PrivateException $exception) {
    $log->error($exception);
    $error = "Įvyko klaida";
    include("views/errorTimetable.php");
} catch (\PDOException $exception) {
    $log->error($exception);
    $error = "Įvyko klaida";
    include("views/errorTimetable.php");
}
    