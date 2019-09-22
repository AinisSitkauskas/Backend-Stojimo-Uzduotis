<?php

class ClientController {

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

    public function clientRegistration($client) {

        $clientName = $client->getClientName();
        $clientSurname = $client->getClientSurname();

        if ($this->getClientActiveRegistration($clientName, $clientSurname)) {

            throw new PublicException("Jūs jau esate užsiregistravęs!");
        }

        $uniqueURL = $this->getUniqueURL();

        if (!$this->registerClient($clientName, $clientSurname, $uniqueURL)) {
            throw new PrivateException("Įvyko klaida");
        }

        $client->setUniqueURL($uniqueURL);

        return;
    }

    /**
     * 
     * @param string $clientName
     * @param string $clientSurname
     * @return boolean
     */
    private function getClientActiveRegistration($clientName, $clientSurname) {
        $sqlQuery = $this->connection->prepare("SELECT * FROM clients WHERE clientName=:clientName AND clientSurname=:clientSurname AND clientStatus='waiting' ");
        $sqlQuery->bindParam(':clientName', $clientName, PDO::PARAM_STR);
        $sqlQuery->bindParam(':clientSurname', $clientSurname, PDO::PARAM_STR);
        $sqlQuery->execute();
        $sqlQuery->setFetchMode(PDO::FETCH_ASSOC);
        $result = $sqlQuery->fetchColumn();
        return $result > 0;
    }

    /**
     * 
     * @param string $clientName
     * @param string $clientSurname
     * @param string $uniqueURL
     * @return boolean
     */
    private function registerClient($clientName, $clientSurname, $uniqueURL) {

        $sqlQuery = $this->connection->prepare($sqlQuerry = "INSERT INTO clients (clientName, clientSurname, uniqueURL, clientStatus)
        VALUES (:clientName, :clientSurname, :uniqueURL, 'waiting')");

        $sqlQuery->bindParam(':clientName', $clientName, PDO::PARAM_STR);
        $sqlQuery->bindParam(':clientSurname', $clientSurname, PDO::PARAM_STR);
        $sqlQuery->bindParam(':uniqueURL', $uniqueURL, PDO::PARAM_STR);
        return $sqlQuery->execute() === TRUE;
    }

    /**
     * 
     * @return string
     */
    private function getUniqueURL() {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $uniqueURL = '';
        $n = rand(10, 20);

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $uniqueURL .= $characters[$index];
        }

        return $uniqueURL;
    }

    /**
     * 
     * @param Client $client
     * @return boolean
     */
    public function cancelClient($client) {

        $uniqueURL = $client->getUniqueURL();
        $sqlQuery = $this->connection->prepare("UPDATE clients SET clientStatus = 'canceled'  WHERE uniqueURL = :uniqueURL AND (
        clientStatus = 'waiting' OR
        clientStatus = 'taken')");
        $sqlQuery->bindParam(':uniqueURL', $uniqueURL, PDO::PARAM_STR);
        return $sqlQuery->execute() === TRUE;
    }

    /**
     * 
     * @param Client $client
     * @return boolean
     */
    public function updateClientStatus($client) {

        $currentClientStatus = $client->getCurrentClientStatus();
        $newClientStatus = $client->getnewClientStatus();
        $uniqueURL = $client->getUniqueURL();
        $sqlQuery = $this->connection->prepare("UPDATE clients SET clientStatus = :newClientStatus  WHERE uniqueURL = :uniqueURL AND 
        clientStatus = :currentClientStatus");
        $sqlQuery->bindParam(':uniqueURL', $uniqueURL, PDO::PARAM_STR);
        $sqlQuery->bindParam(':currentClientStatus', $currentClientStatus, PDO::PARAM_STR);
        $sqlQuery->bindParam(':newClientStatus', $newClientStatus, PDO::PARAM_STR);
        return $sqlQuery->execute() === TRUE;
    }

}
