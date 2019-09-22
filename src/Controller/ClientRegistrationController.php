<?php

class ClientRegistrationController {

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

    public function createRegistration() {

        if (empty($_POST['submit'])) {

            $specialist = $this->getSpecialist();

            include("views/clientRegistration.php");
            return;
        }

        if (empty($_POST['clientName']) || empty($_POST['clientSurname']) || empty($_POST['specialist'])) {
            throw new PublicException("Užsiregistruoti nepavyko, ne visi registracijos laukeliai buvo užpildyti");
        }

        $clientName = $_POST['clientName'];
        $clientSurname = $_POST['clientSurname'];
        $specialist = $_POST['specialist'];
        $specialist = explode(" ", $specialist);

        $client = new Client;
        $client->setClientName($clientName);
        $client->setClientSurname($clientSurname);

        $this->clientController->clientRegistration($client);

        $uniqueURL = $client->getUniqueURL();

        $averageWaitingTime = $this->getAverageWaitingTime($specialist[0], $specialist[1]);

        if (!$averageWaitingTime) {
            $averageWaitingTime = 0;
        }

        $maxLineNumber = $this->getMaxLineNumber();
        $newLineNumber = $maxLineNumber + 1;

        if (!$this->createClientRegistration($clientName, $clientSurname, $specialist[0], $specialist[1], $averageWaitingTime, $newLineNumber)) {
            throw new PrivateException("Įvyko klaida");
        }

        include("views/succsesfulRegistration.php");
    }

    /**
     * 
     * @return array
     */
    private function getSpecialist() {
        $sqlQuery = $this->connection->prepare("SELECT specialistName, specialistSurname FROM specialist");
        $sqlQuery->execute();
        $sqlQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sqlQuery->fetchAll();
        return $result;
    }

    /**
     * 
     * @param string $specialistName
     * @param string $specialistSurname
     * @return string
     */
    private function getAverageWaitingTime($specialistName, $specialistSurname) {

        $sqlQuery = $this->connection->prepare("SELECT SEC_TO_TIME(AVG(TIME_TO_SEC(servicetime.waitingTime))) FROM servicetime
        INNER JOIN specialist ON specialist.id = servicetime.specialistId
        INNER JOIN clients ON clients.id = servicetime.clientId
        WHERE specialist.specialistName = :specialistName AND
        specialist.specialistSurname = :specialistSurname AND
        clients.clientStatus='serviced'");

        $sqlQuery->bindParam(':specialistName', $specialistName, PDO::PARAM_STR);
        $sqlQuery->bindParam(':specialistSurname', $specialistSurname, PDO::PARAM_STR);
        $sqlQuery->execute();
        $sqlQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sqlQuery->fetchColumn();
        return $result;
    }

    /**
     * 
     * @return string
     */
    private function getMaxLineNumber() {

        $sqlQuery = $this->connection->prepare("SELECT MAX(lineNumber) FROM servicetime");
        $sqlQuery->execute();
        $sqlQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sqlQuery->fetchColumn();
        return $result;
    }

    /**
     * 
     * @param string $clientName
     * @param string $clientSurname
     * @param string $specialistName
     * @param string $specialistSurname
     * @param string $averageWaitingTime
     * @param string $lineNumber
     * @return boolean
     */
    private function createClientRegistration($clientName, $clientSurname, $specialistName, $specialistSurname, $averageWaitingTime, $lineNumber) {

        $sqlQuery = $this->connection->prepare($sqlQuerry = "INSERT INTO servicetime (clientId, specialistId, lineNumber, registrationTime, impliedTakeRegistration)
        VALUES ((SELECT id FROM clients WHERE clientName=:clientName AND clientSurname=:clientSurname AND clientStatus='waiting'), 
        (SELECT id FROM specialist WHERE specialistName=:specialistName AND specialistSurname=:specialistSurname),
        :lineNumber, NOW(), ADDTIME(NOW(), :averageWaitingTime))");

        $sqlQuery->bindParam(':clientName', $clientName, PDO::PARAM_STR);
        $sqlQuery->bindParam(':clientSurname', $clientSurname, PDO::PARAM_STR);
        $sqlQuery->bindParam(':specialistName', $specialistName, PDO::PARAM_STR);
        $sqlQuery->bindParam(':specialistSurname', $specialistSurname, PDO::PARAM_STR);
        $sqlQuery->bindParam(':averageWaitingTime', $averageWaitingTime, PDO::PARAM_STR);
        $sqlQuery->bindParam(':lineNumber', $lineNumber, PDO::PARAM_STR);
        return $sqlQuery->execute() === TRUE;
    }

    public function selectRegistration() {

        if (empty($_GET['clientCode'])) {
            header("Location: client.php");
            return;
        }

        $uniqueURL = $_GET['clientCode'];

        if (!$this->checkUniqueURL($uniqueURL)) {
            session_unset();
            header("Location: client.php");
        }

        $clientRegistration = $this->getClientRegistration($uniqueURL);

        if (!$clientRegistration) {
            header("Location: client.php");
        }

        $waitingTime = strtotime($clientRegistration[0]["impliedTakeRegistration"]) - time();

        if ($waitingTime <= 0) {
            $waitingTime = 0;
        }

        date_default_timezone_set('UTC');
        $waitingHours = date("H", $waitingTime);
        $waitingMinutes = date("i", $waitingTime);
        $waitingSeconds = date("s", $waitingTime);
        date_default_timezone_set('Europe/Vilnius');

        $_SESSION["clientCode"] = $uniqueURL;

        include("views/myRegistration.php");
        return;
    }

    /**
     * 
     * @param string $uniqueURL
     * @return boolean
     */
    private function checkUniqueURL($uniqueURL) {

        $sqlQuery = $this->connection->prepare("SELECT * FROM clients
        WHERE uniqueURL = :uniqueURL AND (clientStatus = 'waiting' OR clientStatus = 'taken')");
        $sqlQuery->bindParam(':uniqueURL', $uniqueURL, PDO::PARAM_STR);
        $sqlQuery->execute();
        $sqlQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sqlQuery->fetchColumn();
        return $result > 0;
    }

    /**
     * 
     * @param string $uniqueURL
     * @return array
     */
    private function getClientRegistration($uniqueURL) {

        $sqlQuery = $this->connection->prepare("SELECT clients.clientName, clients.clientSurname, clients.clientStatus, clients.uniqueURL, servicetime.lineNumber,
        servicetime.registrationTime, servicetime.impliedTakeRegistration, specialist.specialistName, specialist.specialistSurname
        FROM servicetime
        INNER JOIN clients ON clients.id = servicetime.clientId 
        INNER JOIN specialist ON specialist.id = servicetime.specialistId
        WHERE uniqueURL = :uniqueURL AND (
        clientStatus = 'waiting' OR
        clientStatus = 'taken')");

        $sqlQuery->bindParam(':uniqueURL', $uniqueURL, PDO::PARAM_STR);
        $sqlQuery->execute();
        $sqlQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sqlQuery->fetchAll();
        return $result;
    }

    public function delayRegistration() {

        if (empty($_SESSION['clientCode'])) {
            throw new PrivateException("Įvyko klaida");
        }

        $uniqueURL = $_SESSION['clientCode'];
        $clientRegistration = $this->getClientRegistration($uniqueURL);

        if (!$clientRegistration) {
            throw new PrivateException("Įvyko klaida");
        }

        if ($this->checkLastRegistration($clientRegistration[0]['lineNumber'], $clientRegistration[0]['specialistName'], $clientRegistration[0]['specialistSurname'])) {
            throw new PublicException("Pavėlinti nepavyko, jūs esate paskutinis klientas sąraše");
        }

        $clientList = $this->getClientList($clientRegistration[0]['specialistName'], $clientRegistration[0]['specialistSurname']);

        if (!$clientList) {
            throw new PrivateException("Įvyko klaida");
        }

        if (!$this->delayClientRegistration($uniqueURL, $clientRegistration, $clientList)) {
            throw new PrivateException("Įvyko klaida");
        }

        include("views/succsesfulDelay.php");
    }

    /**
     * 
     * @param integer $clientLineNumber
     * @param string $specialistName
     * @param string $specialistSurname
     * @return boolean
     */
    private function checkLastRegistration($clientLineNumber, $specialistName, $specialistSurname) {

        $sqlQuery = $this->connection->prepare("SELECT MAX(lineNumber)
        FROM servicetime 
        INNER JOIN specialist ON specialist.id = servicetime.specialistId
        WHERE specialistName = :specialistName AND
        specialistSurname = :specialistSurname");

        $sqlQuery->bindParam(':specialistName', $specialistName, PDO::PARAM_STR);
        $sqlQuery->bindParam(':specialistSurname', $specialistSurname, PDO::PARAM_STR);
        $sqlQuery->execute();
        $sqlQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sqlQuery->fetchAll();
        if ($clientLineNumber == $result[0]["MAX(lineNumber)"]) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * 
     * @param string $specialistName
     * @param string $specialistSurname
     * @return array
     */
    private function getClientList($specialistName, $specialistSurname) {

        $sqlQuery = $this->connection->prepare("SELECT clients.uniqueURL, servicetime.lineNumber,
        servicetime.impliedTakeRegistration
        FROM servicetime
        INNER JOIN clients ON clients.id = servicetime.clientId 
        INNER JOIN specialist ON specialist.id = servicetime.specialistId
        WHERE clientStatus = 'waiting' AND specialistName = :specialistName AND
        specialistSurname = :specialistSurname
        ORDER BY servicetime.lineNumber ASC");

        $sqlQuery->bindParam(':specialistName', $specialistName, PDO::PARAM_STR);
        $sqlQuery->bindParam(':specialistSurname', $specialistSurname, PDO::PARAM_STR);
        $sqlQuery->execute();
        $sqlQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sqlQuery->fetchAll();
        return $result;
    }

    /**
     * 
     * @param string $uniqueURL
     * @param array $clientRegistration
     * @param array $clientList
     * @return boolean
     */
    private function delayClientRegistration($uniqueURL, $clientRegistration, $clientList) {

        $numberofClients = count($clientList);

        for ($i = 0; $i < $numberofClients; $i++) {

            if ($clientList[$i]["uniqueURL"] == $clientRegistration[0]["uniqueURL"]) {

                $newClientLineNumber = $clientList[$i + 1]["lineNumber"];
                $newClientImpliedTakeRegistration = $clientList[$i + 1]["impliedTakeRegistration"];
                $swapedClientUniqueURL = $clientList[$i + 1]["uniqueURL"];
                $swapedClientLineNumber = $clientList[$i]["lineNumber"];
                $swapedClientImpliedTakeRegistration = $clientList[$i]["impliedTakeRegistration"];

                $sqlQuery = $this->connection->prepare("UPDATE servicetime 
                INNER JOIN clients ON clients.id = servicetime.clientID
                SET lineNumber = :lineNumber, impliedTakeRegistration = :impliedTakeRegistration 
                WHERE uniqueURL = :uniqueURL AND clientStatus = 'waiting' ");
                $sqlQuery->bindParam(':uniqueURL', $uniqueURL, PDO::PARAM_STR);
                $sqlQuery->bindParam(':lineNumber', $newClientLineNumber, PDO::PARAM_STR);
                $sqlQuery->bindParam(':impliedTakeRegistration', $newClientImpliedTakeRegistration);
                $sqlQuery->execute();

                $sqlQuery = $this->connection->prepare("UPDATE servicetime  
                INNER JOIN clients ON clients.id = servicetime.clientId 
                SET lineNumber = :lineNumber, impliedTakeRegistration = :impliedTakeRegistration
                WHERE uniqueURL = :uniqueURL AND clientStatus = 'waiting' ");
                $sqlQuery->bindParam(':uniqueURL', $swapedClientUniqueURL, PDO::PARAM_STR);
                $sqlQuery->bindParam(':lineNumber', $swapedClientLineNumber, PDO::PARAM_STR);
                $sqlQuery->bindParam(':impliedTakeRegistration', $swapedClientImpliedTakeRegistration);
                $sqlQuery->execute();

                return TRUE;
            }
        }

        return FALSE;
    }

    public function deleteRegistration() {

        if (empty($_SESSION['clientCode'])) {
            throw new PrivateException("Įvyko klaida");
        }

        $uniqueURL = $_SESSION['clientCode'];

        $client = new Client;
        $client->setUniqueURL($uniqueURL);

        if (!$this->clientController->cancelClient($client)) {
            throw new PrivateException("Įvyko klaida");
        }

        if (!$this->deleteClientRegistration($uniqueURL)) {
            throw new PrivateException("Įvyko klaida");
        }

        session_unset();
        include("views/registrationDeleted.php");
    }

    /**
     * 
     * @param string $uniqueURL
     * @return boolean
     */
    private function deleteClientRegistration($uniqueURL) {

        $sqlQuery = $this->connection->prepare("DELETE servicetime FROM servicetime INNER JOIN clients ON clients.id = servicetime.clientId 
        WHERE uniqueURL = :uniqueURL AND clientStatus = 'canceled' ");
        $sqlQuery->bindParam(':uniqueURL', $uniqueURL, PDO::PARAM_STR);
        return $sqlQuery->execute() === TRUE;
    }

}
