<?php


date_default_timezone_set('Africa/Nairobi');
define("LOG_FILE", "error.log");

$sessionId = isset($_GET['session_id']) ? $_GET['session_id'] : '';
$msisdn = isset($_GET['MSISDN']) ? $_GET['MSISDN'] : '';
$serviceCode = isset($_GET['service_code']) ? $_GET['service_code'] : '';
$ussdString = isset($_GET['ussd_string']) ? $_GET['ussd_string'] : '';

include_once("Models.php");
include_once ("./classes/MenuItems.php");
include_once ("./classes/RootMenuAction.php");
include_once ("./classes/ResultsAction.php");
include_once ("./classes/MyAccountAction.php");
include_once ("./classes/AppointmentAction.php");
include_once ("./classes/RegisterDependants.php");
include_once ("./classes/RescheduleAction.php");
include_once ("./classes/UssdUtils.php");

if ($ussdString == "") {
    $ussdSession = new UssdSession();
    $ussdSession->sessionId = $sessionId;
    $ussdSession->msisdn = $msisdn;
    $ussdSession->ussdCode = $serviceCode;
    $ussdSession->ussdString = $ussdString;
    $ussdSession->ussdProcessString = $ussdString;

    $rootMenu = new RootMenuAction();
    $ussdSession = $rootMenu->process($ussdSession);
    createNewUssdSession($ussdSession);
} else {
    $ussdString = cleanUssdString($ussdString);
    $ussdSessionList = getUssdSessionList($sessionId);
    if (count($ussdSessionList) > 0) {
        $ussdSession = $ussdSessionList[0];
        $ussdSession->ussdString = $ussdString;
        $ussdSession->ussdProcessString = $ussdString;
        $ussdSession->previousFeedbackType = $ussdSession->currentFeedbackType;

        if (MenuItems::FIRSTNAME_REQ == $ussdSession->previousFeedbackType ||
                MenuItems::LASTNAME_REQ == $ussdSession->previousFeedbackType ||
                MenuItems::CCCNUMBER_REQ == $ussdSession->previousFeedbackType) {
            $registration = new RegistrationAction();
            $ussdSession = $registration->process($ussdSession);
        } else {
            $menuItems = new MenuItems();
//            $menuSuffix = "\n00 Home";
            $params = explode("*", $ussdSession->ussdProcessString);
            $lastSelection = trim($params[count($params) - 1]);
            if ("" == $ussdSession->ussdProcessString || "00" === $lastSelection ||
                    MenuItems::PROFILE_REQ == $ussdSession->previousFeedbackType) {
                $ussdSession = $menuItems->setMainMenu($ussdSession);
                $reply = "CON " . $ussdSession->currentFeedbackString;
                $ussdSession->currentFeedbackString = $reply;
            } elseif (MenuItems::MAINMENU_REQ == $ussdSession->previousFeedbackType) {

                if ("1" == $lastSelection) {//View Results
                    $viewResults = new ResultsAction();
                    $ussdSession = $viewResults->process($ussdSession);
                } elseif ("2" == $lastSelection) {//check Appointments
                    $checkAppointment = new AppointmentAction();
                    $ussdSession = $checkAppointment->process($ussdSession);
                } elseif ("3" == $lastSelection) {//Reschedule Appointment
                    $rescheduleAppointment = new RegistrationDependantAction();
                    $ussdSession = $rescheduleAppointment->process($ussdSession);
                } elseif ("4" == $lastSelection) {//My Accounts
                    $myAccountAction = new MyAccountAction();
                    $ussdSession = $myAccountAction->process($ussdSession);
                } else {
                    $ussdSession = $menuItems->setMainMenu($ussdSession);
                    $reply = "CON INVALID INPUT. Only number 1-5 allowed.\n" . $ussdSession->currentFeedbackString;
                    $ussdSession->currentFeedbackString = $reply;
                }

            } elseif (MenuItems::VIEWRESULTS_REQ == $ussdSession->previousFeedbackType ) {
                $resultAction = new ResultsAction();
                $ussdSession = $resultAction->process($ussdSession);
            } elseif (MenuItems::VIEWAPPOINTMENT_REQ == $ussdSession->previousFeedbackType ) {
                $checkAppt = new AppointmentAction();
                $ussdSession = $checkAppt->process($ussdSession);
            } elseif (MenuItems::DEPENDANTS_FIRSTNAME_REQ == $ussdSession->previousFeedbackType ||
            MenuItems::DEPENDANTS_LASTNAME_REQ == $ussdSession->previousFeedbackType ||
            MenuItems::DEPENDANTS_SURNAME_REQ == $ussdSession->previousFeedbackType ||
            MenuItems::DEPENDANTS_CCCNUMBER_REQ == $ussdSession->previousFeedbackType ||
            MenuItems::DEPENDANTS_HEINUMBER_REQ == $ussdSession->previousFeedbackType ||
            MenuItems::DEPENDANTS_DOB_REQ == $ussdSession->previousFeedbackType) {
                $rescheduleAppt = new RegistrationDependantAction();
                $ussdSession = $rescheduleAppt->process($ussdSession);
            } elseif (MenuItems:: MYACCOUNT_CATEGORY_REQ == $ussdSession->previousFeedbackType ) {
                $myAccountReg = new MyAccountAction();
                $ussdSession = $myAccountReg->process($ussdSession);
            } else {
                $myAccountAction = new MyAccountAction();
                $ussdSession = $myAccountAction->process($ussdSession);
            }
//            $ussdSession->currentFeedbackString = $reply;
        }
    } else {
        $ussdSession = new UssdSession();
        $reply = "END Connection error. Please try again.";
        $ussdSession->currentFeedbackString = $reply;
    }
    updateUssdSession($ussdSession);
}

echo $ussdSession->currentFeedbackString;


