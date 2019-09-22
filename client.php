<?php

date_default_timezone_set('Europe/Vilnius');

session_start();

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

include_once("vendor/autoload.php");
include_once("src/Controller/ClientRegistrationController.php");
include_once("src/Controller/ClientController.php");
include_once("src/Controller/StatisticController.php");
include_once("src/Entity/ClientEntity.php");
include_once("src/Exception/PrivateException.php");
include_once("src/Exception/PublicException.php");

try {

    $log = new Logger('name');
    $log->pushHandler(new StreamHandler('log.txt', Logger::WARNING));


    include_once('connection.php');

    $clientController = new ClientController($connection);
    $controller = new ClientRegistrationController($connection, $clientController);


    if (!empty($_GET['clientCode'])) {

        $controller->selectRegistration();
    } else {
        if (empty($_GET['action'])) {

            $controller->createRegistration();
        } else if ($_GET['action'] == "delete") {

            $controller->deleteRegistration();
        } else if ($_GET['action'] == "delay") {

            $controller->delayRegistration();
        } else if ($_GET['action'] == "statistic") {
            $controller = new StatisticController($connection);
            $controller->selectStatistic();
        }
    }
} catch (PublicException $exception) {
    $error = $exception->getMessage();
    include("views/errorClient.php");
} catch (PrivateException $exception) {
    $log->error($exception);
    $error = "Įvyko klaida, kreipkitės telefonu";

    include("views/errorClient.php");
} catch (\PDOException $exception) {
    $log->error($exception);
    $error = "Įvyko klaida, kreipkitės telefonu";

    include("views/errorClient.php");
}
    