<?php

class WelcomeController {

    function welcomeAction() {

        if (!empty($_SESSION['specialistName']) || !empty($_SESSION['specialistSurname'])) {
            header("Location: specialist.php?controller=registration&action=select");
        } else {
            header("Location: specialist.php?controller=login&action=login");
        }
    }

}
