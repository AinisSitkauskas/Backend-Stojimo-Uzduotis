<?php

class TimetableController {

    /**
     *
     * @var PDO 
     */
    private $connection;

    /**
     * 
     * @param PDO $connection
     */
    function __construct($connection) {
        $this->connection = $connection;
    }

    public function registrations() {

        $takenClients = $this->getTakenClients();
        $waitingClients = $this->getWaitingClients();

        if (!empty($waitingClients)) {
            $numberOfWaitingClients = count($waitingClients);
            $waitingTime = $this->getWaitingTime($waitingClients, $numberOfWaitingClients);
        }

        if (!empty($takenClients)) {
            $numberOfTakenClients = count($takenClients);
        }

        include("views/clientsTimetable.php");
        return;
    }

    /**
     * 
     * @return array
     */
    private function getTakenClients() {

        $sqlQuery = $this->connection->prepare("SELECT clients.clientName, clients.clientSurname, clients.ClientStatus, servicetime.lineNumber,
       servicetime.registrationTime, servicetime.impliedTakeRegistration, specialist.specialistName, specialist.specialistSurname
       FROM servicetime
       INNER JOIN clients ON clients.id = servicetime.clientId 
       INNER JOIN specialist ON specialist.id = servicetime.specialistId
       WHERE clients.clientStatus = 'taken'");

        $sqlQuery->execute();
        $sqlQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sqlQuery->fetchAll();
        return $result;
    }

    /**
     * 
     * @return array
     */
    private function getWaitingClients() {

        $sqlQuery = $this->connection->prepare("SELECT clients.clientName, clients.clientSurname, clients.ClientStatus, servicetime.lineNumber,
       servicetime.registrationTime, servicetime.impliedTakeRegistration, specialist.specialistName, specialist.specialistSurname
       FROM servicetime
       INNER JOIN clients ON clients.id = servicetime.clientId 
       INNER JOIN specialist ON specialist.id = servicetime.specialistId
       WHERE clients.clientStatus = 'waiting' 
       ORDER BY servicetime.lineNumber ASC LIMIT 5");

        $sqlQuery->execute();
        $sqlQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sqlQuery->fetchAll();
        return $result;
    }

    /**
     * 
     * @param array $waitingClients
     * @param integer $numberOfWaitingClients
     * @return array
     */
    private function getWaitingTime($waitingClients, $numberOfWaitingClients) {

        for ($i = 0; $i < $numberOfWaitingClients; $i++) {

            $waitingTimestamp = strtotime($waitingClients[$i]['impliedTakeRegistration']) - time();

            if ($waitingTimestamp <= 0) {
                $waitingTimestamp = 0;
            }
            date_default_timezone_set('UTC');
            $waitingTime[$i] = date("H:i:s", $waitingTimestamp);
            date_default_timezone_set('Europe/Vilnius');
        }
        return $waitingTime;
    }

}
