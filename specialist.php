<?php

date_default_timezone_set('Europe/Vilnius');
define("SESSION_LIFETIME", "2678400");
session_set_cookie_params(SESSION_LIFETIME, "/");
session_start();

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

include_once("vendor/autoload.php");
include_once("src/Controller/WelcomeController.php");
include_once("src/Controller/LoginController.php");
include_once("src/Controller/ClientController.php");
include_once("src/Controller/SpecialistRegistrationController.php");
include_once("src/Entity/ClientEntity.php");
include_once("src/Exception/PrivateException.php");
include_once("src/Exception/PublicException.php");

try {

    $log = new Logger('name');
    $log->pushHandler(new StreamHandler('log.txt', Logger::WARNING));

    include_once('connection.php');

    $clientController = new ClientController($connection);

    if (empty($_GET['controller']) || empty($_GET['action'])) {
        $controller = new WelcomeController($connection);
        $controller->welcomeAction();
    } elseif ($_GET['controller'] == "login") {
        $controller = new LoginController($connection);
        switch ($_GET['action']) {
            case "login" :
                $controller->loginAction();
                break;
            case "logout" :
                $controller->logoutAction();
                break;
        }
    } elseif ($_GET['controller'] == "registration") {
        $controller = new SpecialistRegistrationController($connection, $clientController);
        switch ($_GET['action']) {
            case "take" :
                $controller->takeClient();
                break;
            case "service" :
                $controller->serviceClient();
                break;
            case "cancel":
                $controller->cancelClient();
                break;
            case "select" :
                $controller->selectRegistration();
                break;
        }
    }
} catch (PublicException $exception) {
    $error = $exception->getMessage();
    include("views/errorSpecialist.php");
} catch (PrivateException $exception) {
    $log->error($exception);
    $error = "Įvyko klaida";
    include("views/errorSpecialist.php");
} catch (\PDOException $exception) {
    $log->error($exception);
    $error = "Įvyko klaida";
    include("views/errorSpecialist.php");
}
