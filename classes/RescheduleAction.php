<?php


include_once('./QueryManager.php');
include_once('MenuItems.php');

class RescheduleAction {
    public function process($ussdSession) {
        $menuItems = new MenuItems();
        $menuSuffix = "\n00 Home";
        $params = explode("*", $ussdSession->ussdProcessString);
        $lastSelection = trim($params[count($params) - 1]);
    }
    
}