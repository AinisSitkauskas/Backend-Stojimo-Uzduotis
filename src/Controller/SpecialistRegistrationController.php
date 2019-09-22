<?php

class SpecialistRegistrationController {

    /**
     *
     * @var PDO 
     */
    private $connection;

    /**
     *
     * @var ClientController
     */
    private $clientController;

    /**
     * 
     * @param PDO $connection
     * @param ClientController $clientController
     */
    function __construct($connection, $clientController) {
        $this->connection = $connection;
        $this->clientController = $clientController;
    }

    public function selectRegistration() {


        if (empty($_SESSION['specialistName']) || empty($_SESSION['specialistSurname'])) {
            header("Location: specialist.php");
            return;
        }

        $specialistName = $_SESSION['specialistName'];
        $specialistSurname = $_SESSION['specialistSurname'];

        $takenClient = $this->getTakenClient($specialistName, $specialistSurname);
        $waitingClients = $this->getWaitingClients($specialistName, $specialistSurname);

        if (!empty($waitingClients)) {
            $numberOfWaitingClients = count($waitingClients);
        }

        include("views/specialistRegistrations.php");
        return;
    }

    /**
     * 
     * @param string $specialistName
     * @param string $specialistSurname
     * @return array
     */
    private function getTakenClient($specialistName, $specialistSurname) {

        $sqlQuery = $this->connection->prepare("SELECT clients.clientName, clients.clientSurname, clients.clientStatus, clients.uniqueURL, servicetime.lineNumber,
        servicetime.registrationTime, servicetime.impliedTakeRegistration, specialist.specialistName, specialist.specialistSurname
        FROM servicetime
        INNER JOIN clients ON clients.id = servicetime.clientId 
        INNER JOIN specialist ON specialist.id = servicetime.specialistId
        WHERE clients.clientStatus = 'taken' AND specialist.specialistName = :specialistName AND 
        specialist.specialistSurname = :specialistSurname");
        $sqlQuery->bindParam(':specialistName', $specialistName, PDO::PARAM_STR);
        $sqlQuery->bindParam(':specialistSurname', $specialistSurname, PDO::PARAM_STR);
        $sqlQuery->execute();
        $sqlQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sqlQuery->fetchAll();
        return $result;
    }

    /**
     * 
     * @param string $specialistName
     * @param string $specialistSurname
     * @return array
     */
    private function getWaitingClients($specialistName, $specialistSurname) {

        $sqlQuery = $this->connection->prepare("SELECT clients.clientName, clients.clientSurname, clients.clientStatus, servicetime.lineNumber,
        servicetime.registrationTime, servicetime.impliedTakeRegistration, specialist.specialistName, specialist.specialistSurname
        FROM servicetime
        INNER JOIN clients ON clients.id = servicetime.clientId 
        INNER JOIN specialist ON specialist.id = servicetime.specialistId
        WHERE clients.clientStatus = 'waiting' AND specialist.specialistName = :specialistName AND 
        specialist.specialistSurname = :specialistSurname
        ORDER BY servicetime.lineNumber ASC");
        $sqlQuery->bindParam(':specialistName', $specialistName, PDO::PARAM_STR);
        $sqlQuery->bindParam(':specialistSurname', $specialistSurname, PDO::PARAM_STR);
        $sqlQuery->execute();
        $sqlQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sqlQuery->fetchAll();
        return $result;
    }

    public function takeClient() {

        if (empty($_SESSION['specialistName']) || empty($_SESSION['specialistSurname'])) {
            header("Location: specialist.php");
            return;
        }

        $specialistName = $_SESSION['specialistName'];
        $specialistSurname = $_SESSION['specialistSurname'];

        $firstWaitingClient = $this->getFirstWaitingClient($specialistName, $specialistSurname);

        if (!$firstWaitingClient) {
            throw new PrivateException("Įvyko klaida");
        }

        $client = new Client;
        $client->setUniqueURL($firstWaitingClient[0]["uniqueURL"]);
        $client->setCurrentClientStatus("waiting");
        $client->setNewClientStatus("taken");

        if (!$this->clientController->updateClientStatus($client)) {
            throw new PrivateException("Įvyko klaida");
        }

        $waitingTimeStamp = time() - strtotime($firstWaitingClient[0]["registrationTime"]);
        date_default_timezone_set('UTC');
        $waitingTime = date("H:i:s", $waitingTimeStamp);
        date_default_timezone_set('Europe/Vilnius');


        if (!$this->updateTakenRegistration($waitingTime, $specialistName, $specialistSurname)) {
            throw new PrivateException("Įvyko klaida");
        }
        header("Location: specialist.php");
        return;
    }

    /**
     * 
     * @param string $specialistName
     * @param string $specialistSurname
     * @return array
     */
    private function getFirstWaitingClient($specialistName, $specialistSurname) {

        $sqlQuery = $this->connection->prepare("SELECT clients.clientName, clients.clientSurname, clients.uniqueURL, clients.clientStatus, servicetime.lineNumber,
       servicetime.registrationTime
       FROM servicetime
       INNER JOIN clients ON clients.id = servicetime.clientId 
       INNER JOIN specialist ON specialist.id = servicetime.specialistId
       WHERE clients.clientStatus = 'waiting' AND specialist.specialistName = :specialistName AND 
       specialist.specialistSurname = :specialistSurname
       ORDER BY servicetime.lineNumber ASC LIMIT 1");
        $sqlQuery->bindParam(':specialistName', $specialistName, PDO::PARAM_STR);
        $sqlQuery->bindParam(':specialistSurname', $specialistSurname, PDO::PARAM_STR);
        $sqlQuery->execute();
        $sqlQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sqlQuery->fetchAll();
        return $result;
    }

    /**
     * 
     * @param string $waitingTime
     * @param string $specialistName
     * @param string $specialistSurname
     * @return boolean
     */
    private function updateTakenRegistration($waitingTime, $specialistName, $specialistSurname) {

        $sqlQuery = $this->connection->prepare("UPDATE servicetime 
        INNER JOIN clients ON clients.id = servicetime.clientId 
        INNER JOIN specialist ON specialist.id = servicetime.specialistId
        SET registrationTaked = NOW(), waitingTime = :waitingTime
        WHERE specialist.specialistName = :specialistName AND 
        specialist.specialistSurname = :specialistSurname AND clientStatus = 'taken' ");
        $sqlQuery->bindParam(':specialistName', $specialistName, PDO::PARAM_STR);
        $sqlQuery->bindParam(':specialistSurname', $specialistSurname, PDO::PARAM_STR);
        $sqlQuery->bindParam(':waitingTime', $waitingTime, PDO::PARAM_STR);
        return $sqlQuery->execute() === TRUE;
    }

    public function serviceClient() {

        if (empty($_SESSION['specialistName']) || empty($_SESSION['specialistSurname'])) {
            header("Location: specialist.php");
            return;
        }

        $specialistName = $_SESSION['specialistName'];
        $specialistSurname = $_SESSION['specialistSurname'];

        $takenClient = $this->getTakenClient($specialistName, $specialistSurname);

        if (!$takenClient) {
            $error = $this->connection->errorInfo(2);
            throw new PrivateException($error);
        }

        $client = new Client;
        $client->setUniqueURL($takenClient[0]["uniqueURL"]);
        $client->setCurrentClientStatus("taken");
        $client->setNewClientStatus("serviced");


        if (!$this->updateServicedRegistration($specialistName, $specialistSurname)) {
            throw new PrivateException("Įvyko klaida");
        }

        if (!$this->clientController->updateClientStatus($client)) {
            throw new PrivateException("Įvyko klaida");
        }

        header("Location: specialist.php");
        return;
    }

    /**
     * 
     * @param string $specialistName
     * @param string $specialistSurname
     * @return boolean
     */
    private function updateServicedRegistration($specialistName, $specialistSurname) {

        $sqlQuery = $this->connection->prepare("UPDATE servicetime 
        INNER JOIN clients ON clients.id = servicetime.clientId 
        INNER JOIN specialist ON specialist.id = servicetime.specialistId
        SET registrationServiced = NOW()
        WHERE specialist.specialistName = :specialistName AND 
        specialist.specialistSurname = :specialistSurname AND clientStatus = 'taken'");
        $sqlQuery->bindParam(':specialistName', $specialistName, PDO::PARAM_STR);
        $sqlQuery->bindParam(':specialistSurname', $specialistSurname, PDO::PARAM_STR);
        return $sqlQuery->execute() === TRUE;
    }

    public function cancelClient() {

        if (empty($_SESSION['specialistName']) || empty($_SESSION['specialistSurname'])) {
            header("Location: specialist.php");
            return;
        }

        $specialistName = $_SESSION['specialistName'];
        $specialistSurname = $_SESSION['specialistSurname'];

        $takenClient = $this->getTakenClient($specialistName, $specialistSurname);

        if (!$takenClient) {
            $error = $this->connection->errorInfo(2);
            throw new PrivateException($error);
        }

        $client = new Client;
        $client->setUniqueURL($takenClient[0]["uniqueURL"]);
        $client->setCurrentClientStatus("taken");
        $client->setNewClientStatus("canceled");


        if (!$this->deleteClientRegistration($specialistName, $specialistSurname)) {
            throw new PrivateException("Įvyko klaida");
        }

        if (!$this->clientController->updateClientStatus($client)) {
            throw new PrivateException("Įvyko klaida");
        }

        header("Location: specialist.php");
        return;
    }

    /**
     * 
     * @param string $specialistName
     * @param string $specialistSurname
     * @return boolean
     */
    private function deleteClientRegistration($specialistName, $specialistSurname) {

        $sqlQuery = $this->connection->prepare("DELETE servicetime FROM servicetime 
        INNER JOIN clients ON clients.id = servicetime.clientId 
        INNER JOIN specialist ON specialist.id = servicetime.specialistId
        WHERE specialist.specialistName = :specialistName AND 
        specialist.specialistSurname = :specialistSurname AND clientStatus = 'taken'");
        $sqlQuery->bindParam(':specialistName', $specialistName, PDO::PARAM_STR);
        $sqlQuery->bindParam(':specialistSurname', $specialistSurname, PDO::PARAM_STR);
        return $sqlQuery->execute() === TRUE;
    }

}
