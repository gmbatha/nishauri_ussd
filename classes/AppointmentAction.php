<?php
include_once('./QueryManager.php');
include_once('MenuItems.php');
include_once('UssdUtils.php');
class AppointmentAction {
    public function process($ussdSession) {
        $menuItems = new MenuItems();
        $menuSuffix = "\n00 Home";
        $params = explode("*", $ussdSession->ussdProcessString);
        $lastSelection = trim($params[count($params) - 1]);
            if(MenuItems::MAINMENU_REQ == $ussdSession->previousFeedbackType){
                $ussdSession = $menuItems->setAppointment($ussdSession);
                $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
            } elseif (MenuItems::VIEWAPPOINTMENT_REQ == $ussdSession->previousFeedbackType) {
                if (is_numeric($lastSelection) && $lastSelection >=1 && $lastSelection <= 7) {
                    $userParams = $ussdSession->userParams . UssdSession::VIEWAPPOINTMENT_LIST_IDS . "=" . $lastSelection . "*";
                    $ussdSession->userParams = $userParams;
                    if("1"==$lastSelection){//owner appointment
                        $ussdSession = $menuItems->setOwnerAppointment($ussdSession);
                        $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
                    }elseif("2"== $lastSelection){//dependant Appointment
                        $ussdSession = $menuItems->setDependantAppointment($ussdSession);
                        $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
                    }elseif("3"== $lastSelection){//appointment type
                        $ussdSession = $menuItems->setAppointmentType($ussdSession);
                        $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
                    }elseif("4"== $lastSelection){//lab trends
                        $ussdSession = $menuItems->setAppointmentVisitType($ussdSession);
                        $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
                    }elseif("5"== $lastSelection){//appointment dates
                        $ussdSession = $menuItems->setAppointmentsDates($ussdSession);
                        $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;  
                    }elseif("6"== $lastSelection){//lab trends
                        $ussdSession = $menuItems->setLabTrends($ussdSession);
                        $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;                
                    }else{
                        $ussdSession = $menuItems->setAppointment($ussdSession);
                        $reply = "CON INVALID INPUT. Only number 1-5 allowed.\n" . $ussdSession->currentFeedbackString . $menuSuffix;              
                    }
                } else {
                    $reply = "END Connection error. Please try again.";
                }
                $ussdSession->currentFeedbackString = $reply;
                return $ussdSession;         
            } elseif (MenuItems::VIEWOWNERAPPOINTMENT_REQ == $ussdSession->previousFeedbackType) {
                    $userParams = $ussdSession->userParams . UssdSession::VIEWOWNERAPPOINTMENT_LIST_IDS . "=" . $lastSelection . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setOwnerAppointment($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
            } elseif (MenuItems::VIEWDEPENDANTAPPOINTMENT_REQ == $ussdSession->previousFeedbackType) { 
                    $userParams = $ussdSession->userParams . UssdSession::VIEWDEPENDANTAPPOINTMENT_LIST_IDS . "=" . $lastSelection . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setDependantAppointment($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
            } elseif (MenuItems::VIEWTYPEAPPOINTMENT_REQ == $ussdSession->previousFeedbackType) {
                    $userParams = $ussdSession->userParams . UssdSession::VIEWTYPEAPPOINTMENT_LIST_IDS . "=" . $lastSelection . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setAppointmentType($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
            } elseif (MenuItems::VIEWAPPOINTMENTVISITTYPE_REQ == $ussdSession->previousFeedbackType) {
                    $userParams = $ussdSession->userParams . UssdSession::VIEWVISITTYPEAPPOINTMENT_LIST_IDS . "=" . $lastSelection . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setAppointmentVisitType($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
            } elseif (MenuItems::VIEWAPPOINTMENTDATES_REQ == $ussdSession->previousFeedbackType) { 
                    $userParams = $ussdSession->userParams . UssdSession::VIEWAPPOINTMENTDATES_LIST_IDS . "=" . $lastSelection . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setAppointmentsDates($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
            } elseif (MenuItems::VIEWLABTRENDS_REQ == $ussdSession->previousFeedbackType) {
                    $userParams = $ussdSession->userParams . UssdSession::VIEWLABTRENDS_LIST_IDS . "=" . $lastSelection . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setLabTrends($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
       
            } else {
                    $reply = "END Connection error. Please try again.";
            }
            $ussdSession->currentFeedbackString = $reply;
            return $ussdSession;  
    }
}