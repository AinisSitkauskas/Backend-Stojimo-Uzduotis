<?php

class Client {

    /**
     *
     * @var string 
     */
    private $clientName;

    /**
     *
     * @var string 
     */
    private $clientSurname;

    /**
     *
     * @var string
     */
    private $uniqueURL;

    /**
     *
     * @var string
     */
    private $currentClientStatus;

    /**
     *
     * @var string
     */
    private $newClientStatus;

    /**
     * 
     * @param string $clientName
     * @return $this
     */
    public function setClientName($clientName) {
        $this->clientName = $clientName;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getClientName() {
        return $this->clientName;
    }

    /**
     * 
     * @param string $clientSurname
     * @return $this
     */
    public function setClientSurname($clientSurname) {
        $this->clientSurname = $clientSurname;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getClientSurname() {
        return $this->clientSurname;
    }

    /**
     * 
     * @param string $uniqueURL
     * @return $this
     */
    public function setUniqueURL($uniqueURL) {
        $this->uniqueURL = $uniqueURL;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getUniqueURL() {
        return $this->uniqueURL;
    }

    /**
     * 
     * @param string $currrentClientStatus
     * @return $this
     */
    public function setCurrentClientStatus($currrentClientStatus) {
        $this->currentClientStatus = $currrentClientStatus;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getCurrentClientStatus() {
        return $this->currentClientStatus;
    }

    /**
     * 
     * @param string $newClientStatus
     * @return $this
     */
    public function setNewClientStatus($newClientStatus) {
        $this->newClientStatus = $newClientStatus;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getNewClientStatus() {
        return $this->newClientStatus;
    }

}
