<?php

class LoginController {

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

    public function loginAction() {

        if (empty($_POST['specialistName']) || empty($_POST['specialistSurname'])) {
            include("views/login.php");
            return;
        }

        $specialistName = $_POST['specialistName'];
        $specialistSurname = $_POST['specialistSurname'];

        if (!$this->checkSpecialist($specialistName, $specialistSurname)) {
            throw new PublicException("Prisijungti nepavyko, jūsų vartotojo vardas arba slaptažodis įvestas neteisingai!");
        }

        $_SESSION['specialistName'] = $specialistName;
        $_SESSION['specialistSurname'] = $specialistSurname;
        header("Location: specialist.php");
    }

    public function logoutAction() {

        session_unset();
        header("Location: specialist.php");
    }

    /**
     * 
     * @param string $specialistName
     * @param string  $specialistSurname
     * @return boolean
     */
    private function checkSpecialist($specialistName, $specialistSurname) {
        $sqlQuery = $this->connection->prepare("SELECT * FROM specialist
       WHERE specialistName = :specialistName AND
       specialistSurname = :specialistSurname");

        $sqlQuery->bindParam(':specialistName', $specialistName, PDO::PARAM_STR);
        $sqlQuery->bindParam(':specialistSurname', $specialistSurname, PDO::PARAM_STR);
        $sqlQuery->execute();
        $sqlQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sqlQuery->fetchColumn();
        return $result > 0;
    }

}
