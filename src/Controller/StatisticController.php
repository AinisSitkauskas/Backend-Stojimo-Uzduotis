<?php

class StatisticController {

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

    public function selectStatistic() {

        $allSpecialist = $this->getAllSpecialist();

        if (empty($_POST['specialist'])) {
            include("views/statistic.php");
            return;
        }

        $specialist = $_POST['specialist'];
        $specialist = explode(" ", $specialist);

        $weekDay = array("Pirmadienis", "Antradienis", "Trečiadienis", "Ketvirtadienis", "Penktadienis", "Šeštadienis", "Sekmadienis");
        $averageWaitingSeconds = $this->getAverageWaitingSeconds($specialist[0], $specialist[1]);

        $averageWaitingTime = $this->getAverageWaitingTime($averageWaitingSeconds);

        include("views/statistic.php");
    }

    /**
     * 
     * @return array
     */
    private function getAllSpecialist() {
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
     * @return array
     */
    private function getAverageWaitingSeconds($specialistName, $specialistSurname) {

        $sqlQuery = $this->connection->prepare("SELECT AVG(TIME_TO_SEC(servicetime.waitingTime)) AS waitingTime, DAYOFWEEK(servicetime.registrationTime) as dayOfWeek
       FROM servicetime
       INNER JOIN specialist ON specialist.id = servicetime.specialistId
       INNER JOIN clients ON clients.id = servicetime.clientId
       WHERE specialist.specialistName = :specialistName AND
       specialist.specialistSurname = :specialistSurname AND
       clients.clientStatus='serviced'
       GROUP BY dayOfWeek");

        $sqlQuery->bindParam(':specialistName', $specialistName, PDO::PARAM_STR);
        $sqlQuery->bindParam(':specialistSurname', $specialistSurname, PDO::PARAM_STR);
        $sqlQuery->execute();
        $sqlQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sqlQuery->fetchAll();
        return $result;
    }

    /**
     * 
     * @param array $averageWaitingSeconds
     * @return array
     */
    private function getAverageWaitingTime($averageWaitingSeconds) {

        date_default_timezone_set('UTC');

        $numberOfWaitingSeconds = count($averageWaitingSeconds);

        for ($i = 0; $i < 6; $i++) {

            for ($j = 0; $j < $numberOfWaitingSeconds; $j++) {

                if ($averageWaitingSeconds[$j]["dayOfWeek"] == $i + 2) {
                    $averageWaitingTime[$i] = date("H:i:s", $averageWaitingSeconds[$j]["waitingTime"]);
                }

                if ($averageWaitingSeconds[$j]["dayOfWeek"] == 1) {
                    $averageWaitingTime[6] = date("H:i:s", $averageWaitingSeconds[$j]["waitingTime"]);
                }
            }
        }

        for ($i = 0; $i < 7; $i++) {

            if (empty($averageWaitingTime[$i])) {
                $averageWaitingTime[$i] = "00:00:00";
            }
        }
        date_default_timezone_set('Europe/Vilnius');
        return $averageWaitingTime;
    }

}
