<?php

include_once('./Models.php');
include_once('./QueryManager.php');
include_once('MenuItems.php');
include_once('UssdUtils.php');

class RegistrationDependantAction {

    public function process($ussdSession) {
        $menuItems = new MenuItems();
        $menuSuffix = "\n00 Home";
        $params = explode("*", $ussdSession->ussdProcessString);
        if (MenuItems::MAINMENU_REQ == $ussdSession->previousFeedbackType) {
            $ussdSession = $menuItems->setDependantFirstNameRequest($ussdSession);
            $reply = "CON Register Dependant. " . $ussdSession->currentFeedbackString;
        } else {
            $params = explode("*", $ussdSession->ussdProcessString);

            if (MenuItems::DEPENDANTS_FIRSTNAME_REQ == $ussdSession->previousFeedbackType) {
                $firstName = trim($params[count($params) - 1]);
                if (isValidName($firstName)) {
                    $userParams = UssdSession::DEPENDANT_FIRSTNAME . "=" . $firstName . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setDependantLastNameRequest($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString;
                } else {
                    $ussdSession = $menuItems->setDependantFirstNameRequest($ussdSession);
                        $reply = "CON The name you entered contains NUMBERS or INVALID characters.\n" . $ussdSession->currentFeedbackString;
                }
            } elseif (MenuItems::DEPENDANTS_LASTNAME_REQ == $ussdSession->previousFeedbackType) {
                $surName = trim($params[count($params) - 1]);
                if (isValidName($surName)) {
                    $userParams = $ussdSession->userParams . UssdSession::DEPENDANT_LASTNAME . "=" . $surName . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setDependantSurNameRequest($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString;
                } else {
                    $ussdSession = $menuItems->setDependantLastNameRequest($ussdSession);
                        $reply = "CON The name you entered contains NUMBERS or INVALID characters.\n" . $ussdSession->currentFeedbackString;
                }
            } elseif (MenuItems::DEPENDANTS_SURNAME_REQ == $ussdSession->previousFeedbackType) {
                $surName = trim($params[count($params) - 1]);
                if (isValidName($surName)) {
                    $userParams = $ussdSession->userParams . UssdSession::DEPENDANT_SURNAME . "=" . $surName . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setDependantCccNumberRequest($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString;
                } else {
                    $ussdSession = $menuItems->setDependantSurNameRequest($ussdSession);
                        $reply = "CON The name you entered contains NUMBERS or INVALID characters.\n" . $ussdSession->currentFeedbackString;
                }
            } elseif (MenuItems::DEPENDANTS_CCCNUMBER_REQ == $ussdSession->previousFeedbackType) {
                $surName = trim($params[count($params) - 1]);
                if (isValidCccNumber($surName)) {
                    $userParams = $ussdSession->userParams . UssdSession::DEPENDANT_CCCNUMBER . "=" . $surName . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setDependantHeiNumberRequest($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString;
                } else {
                    $ussdSession = $menuItems->setDependantCccNumberRequest($ussdSession);
                        $reply = "CON The name you entered contains NUMBERS or INVALID characters.\n" . $ussdSession->currentFeedbackString;
                }
            } elseif (MenuItems::DEPENDANTS_HEINUMBER_REQ == $ussdSession->previousFeedbackType) {
                $surName = trim($params[count($params) - 1]);
                if (isValidHeiNumber($surName)) {
                    $userParams = $ussdSession->userParams . UssdSession::DEPENDANT_HEINUMBER . "=" . $surName . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setDependantDobRequest($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString;
                } else {
                    $ussdSession = $menuItems->setDependantHeiNumberRequest($ussdSession);
                        $reply = "CON The name you entered contains NUMBERS or INVALID characters.\n" . $ussdSession->currentFeedbackString;
                }
            } elseif (MenuItems::DEPENDANTS_DOB_REQ == $ussdSession->previousFeedbackType) {
                $cccNumber = trim($params[count($params) - 1]);
                if (isValidHeiNumber($cccNumber)) {
                    $userParams = $ussdSession->userParams . UssdSession::DEPENDANT_DOB . "=" . $cccNumber . "*";
                    $ussdSession->userParams = $userParams;
                    $reply = "END " . self::registerNewUser($ussdSession);
                } else {
                    $ussdSession = $menuItems->setDependantDobRequest($ussdSession);
                        $reply = "CON You entered an INVALID Date of Birth.\n" . $ussdSession->currentFeedbackString;
                }
            }
        }
        $ussdSession->currentFeedbackString = $reply;
        return $ussdSession;
    }
    
    function registerNewUser($ussdSession){
        $ussdUser = new UssdDependants();
        $ussdUser->msisdn = $ussdSession->msisdn;
        $ussdUser->first_name = UssdSession::getUserParam(UssdSession::DEPENDANT_FIRSTNAME, $ussdSession->userParams);
        $ussdUser->last_name = UssdSession::getUserParam(UssdSession::DEPENDANT_LASTNAME, $ussdSession->userParams);
        $ussdUser->surname = UssdSession::getUserParam(UssdSession::DEPENDANT_SURNAME, $ussdSession->userParams);
        $ussdUser->CCCNo = UssdSession::getUserParam(UssdSession::DEPENDANT_CCCNUMBER, $ussdSession->userParams);
        $ussdUser->heiNumber = UssdSession::getUserParam(UssdSession::DEPENDANT_HEINUMBER, $ussdSession->userParams);
        $ussdUser->dob = UssdSession::getUserParam(UssdSession::DEPENDANT_DOB, $ussdSession->userParams);
        
        if(createDependants($ussdUser)){
                return "You have registered a dependant successfully!";
            
        } else {
                return "There was an error in dependantt registration. Please try again.";
            
        }
    }

}
