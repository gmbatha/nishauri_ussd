<?php


include_once('UssdUtils.php');
include_once('./QueryManager.php');

class MenuItems {
    const MYACCOUNT_CATEGORY_REQ = "MYACCOUNT_CATEGORY_REQ";
    const LANGUAGE_REQ = "LANGUAGE_REQ";
    const FIRSTNAME_REQ = "FIRSTNAME_REQ";
    const LASTNAME_REQ = "LASTNAME_REQ";
    const CCCNUMBER_REQ = "CCCNUMBER_REQ";
    const HEINUMBER_REQ = "HEINUMBER_REQ";
    const SURNAME_REQ = "SURNAME_REQ";
    const DOB_REQ = "DOB_REQ";
    const REGISTER_DEPENDANTS = "REGISTER_DEPENDANTS";
    const REGISTRATION_STATUS = "REGISTRATION_STATUS";
    const MAINMENU_REQ = "MAINMENU_REQ";
    const VIEWRESULTS_REQ = "VIEWRESULTS_REQ";
    const VIEWVLRESULTS_REQ = "VIEWVLRESULTS_REQ";
    const VIEWEIDRESULTS_REQ = "VIEWEIDRESULTS_REQ";
    const VIEWAPPOINTMENT_REQ = "VIEWAPPOINTMENT_REQ";
    const VIEWOWNERAPPOINTMENT_REQ = "VIEWOWNERAPPOINTMENT_REQ";
    const RESCHEDULEAPPOINTMENT_REQ = "RESCHEDULEAPPOINTMENT_REQ";
    const VIEWDEPENDANTAPPOINTMENT_REQ = "VIEWDEPENDANTAPPOINTMENT_REQ";
    const VIEWTYPEAPPOINTMENT_REQ = "VIEWTYPEAPPOINTMENT_REQ";
    const VIEWAPPOINTMENTVISITTYPE_REQ = "VIEWAPPOINTMENTVISITTYPE_REQ";
    const VIEWLABTRENDS_REQ = "VIEWLABTRENDS_REQ";
    const VIEWAPPOINTMENTD_REQ = "VIEWAPPOINTMENTDATES_REQ";
    const VIEWAPPOINTMENTDATES_REQ = "VIEWAPPOINTMENTDATES_REQ";
    const DEPENDANTS_REQ = "DEPENDANTS_REQ";
    const VIEWDEPENDANTS_REQ = "VIEWDEPENDANTS_REQ";
    const PROFILE_REQ = "PROFILE_REQ";
    //////////////////DEPENDANTS
    const DEPENDANTS_FIRSTNAME_REQ = "DEPENDANTS_FIRSTNAME_REQ";
    const DEPENDANTS_LASTNAME_REQ = "DEPENDANTS_LASTNAME_REQ";
    const DEPENDANTS_SURNAME_REQ = "DEPENDANTS_SURNAME_REQ";
    const DEPENDANTS_CCCNUMBER_REQ = "DEPENDANTS_CCCNUMBER_REQ";
    const DEPENDANTS_HEINUMBER_REQ = "DEPENDANTS_HEINUMBER_REQ";
    const DEPENDANTS_DOB_REQ = "DEPENDANTS_DOB_REQ";
    var $reply;
    var $userParams;

    public function setFirstNameRequest($ussdSession) {

        $ussdSession->currentFeedbackString = "Enter your First Name to register for this service:";

        $ussdSession->currentFeedbackType = self::FIRSTNAME_REQ;
        return $ussdSession;
    }
    public function setLastNameRequest($ussdSession) {
        $ussdSession->currentFeedbackString = "Enter your Last Name:";
        $ussdSession->currentFeedbackType = self::LASTNAME_REQ;
        return $ussdSession;
    }
    public function setCccNumberRequest($ussdSession) {
        $ussdSession->currentFeedbackString = "Enter your 10 digit ccc number:";
        $ussdSession->currentFeedbackType = self::CCCNUMBER_REQ;
        return $ussdSession;
    }
    public function setHeiNumberRequest($ussdSession) {
        $ussdSession->currentFeedbackString = "Enter Dependant Hei Number:";
        $ussdSession->currentFeedbackType = self::HEINUMBER_REQ;
        return $ussdSession;
}   
    public function setSurnameRequest($ussdSession) {
        $ussdSession->currentFeedbackString = "Enter Dependant Surname:";
        $ussdSession->currentFeedbackType = self::SURNAME_REQ;
        return $ussdSession;
    } 
    public function setDobRequest($ussdSession) {
        $ussdSession->currentFeedbackString = "Enter date of birth in (YYYY-MM-DD):";
        $ussdSession->currentFeedbackType = self::DOB_REQ;
        return $ussdSession;
    }           
    public function setMainMenu($ussdSession) {
        $userId = UssdSession::getUserParam(UssdSession::USER_ID, $ussdSession->userParams);
        $userParams = UssdSession::USER_ID . "=" . $userId . "*";
        $ussdSession->userParams = $userParams;
        $menuArray = array("View Results", "Check Appointments","Add Dependant","My Account");
        $ussdSession->currentFeedbackString = "Select one:\n" . generateMenu($menuArray);
        $ussdSession->currentFeedbackType = self::MAINMENU_REQ;
        return $ussdSession;
    }
    public function setViewResults($ussdSession) {
        $menuArray = array("Viral Load Results:","Early Infant Diagnosis(EID):");
        $ussdSession->currentFeedbackString = "Select one:\n" . generateMenu($menuArray);
        $ussdSession->currentFeedbackType = self::VIEWRESULTS_REQ;
        return $ussdSession;
    }
    public function setViewVLResults($ussdSession) {
        $vlResults = getUssdVlResults($ussdSession->msisdn);
        $reply = "Viral Load Results:";
        if (count($vlResults) > 0) {
                $displayedVlResults = "";
                for ($i = 1; $i <= count($vlResults); $i++) {
                    $reply .= "\n" . $i . ":" . $vlResults[$i - 1]->result_content . " - " . $vlResults[$i - 1]->item;
                if ($i != count($vlResults)) {
                    $displayedVlResults .= $vlResults[$i - 1]->id . "#";
                } else {
                    $displayedVlResults .= $vlResults[$i - 1]->id;
                }
            }
            $userParams = $ussdSession->userParams . UssdSession::VIEWVLRESULTS_LIST_IDS . "=" . $displayedVlResults . "*";
            $ussdSession->userParams = $userParams;
        } else {
            $reply = "\nNo Viral Results were found.";
        }
        $ussdSession->currentFeedbackString = $reply;
        $ussdSession->currentFeedbackType = self::VIEWVLRESULTS_REQ;
        return $ussdSession;
    }
    public function setViewIEDResults($ussdSession) {
        $eidResults = getUssdEIDResults($ussdSession->msisdn);
        $reply = "Early Infant Diagnosis(EID):";
        if (count($eidResults) > 0) {
            $displayedEIDResults = "";
            for ($i = 1; $i <= count($eidResults); $i++) {
                $reply .= "\n" . $i . ":" . $eidResults[$i - 1]->result_content . " - " . $eidResults[$i - 1]->item;
                if ($i != count($eidResults)) {
                    $displayedEIDResults .= $eidResults[$i - 1]->id . "#";
                } else {
                    $displayedEIDResults .= $eidResults[$i - 1]->id;
                }
            }
            $userParams = $ussdSession->userParams . UssdSession::VIEWEIDRESULTS_LIST_IDS . "=" . $displayedEIDResults . "*";
            $ussdSession->userParams = $userParams;
        } else {
            $reply = "\nNo EID Results were found.";   
        }
        $ussdSession->currentFeedbackString = $reply;
        $ussdSession->currentFeedbackType = self::VIEWEIDRESULTS_REQ;
        return $ussdSession;
    }
    public function setAppointment($ussdSession) {
        $menuArray = array("Owner Appointment:","Dependant Appointment:","Appointment Type:","Visit Type","Appointment Dates","Lab Trends");
        $ussdSession->currentFeedbackString = "Select one:\n" . generateMenu($menuArray);
        $ussdSession->currentFeedbackType = self::VIEWAPPOINTMENT_REQ;
        return $ussdSession;
    }  
    public function setOwnerAppointment($ussdSession) {
        $ownerAppointmentResults = getOwnerAppointments($ussdSession->msisdn);
        $reply = "Owner Appointment:";
        if (count($ownerAppointmentResults) > 0) {
            $displayedOwnerAppointment = "";
            for ($i = 1; $i <= count($ownerAppointmentResults); $i++) {
                $reply .= "\n" . $i . ":" . $ownerAppointmentResults[$i - 1]->owner . " - " . $ownerAppointmentResults[$i - 1]->item;
                if ($i != count($ownerAppointmentResults)) {
                    $displayedOwnerAppointment .= $ownerAppointmentResults[$i - 1]->id . "#";
                } else {
                    $displayedOwnerAppointment .= $ownerAppointmentResults[$i - 1]->id;
                }
            }
            $userParams = $ussdSession->userParams . UssdSession::VIEWOWNERAPPOINTMENT_LIST_IDS . "=" . $displayedOwnerAppointment . "*";
            $ussdSession->userParams = $userParams;
        } else {
            $reply = "\nNo Appointments found.";
        }
        $ussdSession->currentFeedbackString = $reply;
        $ussdSession->currentFeedbackType = self::VIEWOWNERAPPOINTMENT_REQ;
        return $ussdSession;
    }
    public function setDependantAppointment($ussdSession) {
                $dependantAppointmentResults = getDependantAppointments($ussdSession->msisdn);
                $reply = "Dependant Appointment:";
                if (count($dependantAppointmentResults) > 0) {
                    $displayedDependantAppointment = "";
                    for ($i = 1; $i <= count($dependantAppointmentResults); $i++) {
                        $reply .= "\n" . $i . ":" . $dependantAppointmentResults[$i - 1]->dependant . " - " . $dependantAppointmentResults[$i - 1]->item;
                        if ($i != count($dependantAppointmentResults)) {
                            $displayedDependantAppointment .= $dependantAppointmentResults[$i - 1]->id . "#";
                        } else {
                            $displayedDependantAppointment .= $dependantAppointmentResults[$i - 1]->id;
                        }
                    }
                    $userParams = $ussdSession->userParams . UssdSession::VIEWDEPENDANTAPPOINTMENT_LIST_IDS . "=" . $displayedDependantAppointment . "*";
                    $ussdSession->userParams = $userParams;
                } else {
                    $reply = "\nNo Appointments found."; 
                }
                $ussdSession->currentFeedbackString = $reply;
                $ussdSession->currentFeedbackType = self::VIEWDEPENDANTAPPOINTMENT_REQ;
                return $ussdSession;
    }
    public function setAppointmentType($ussdSession) {
        $typeAppointmentResults = getAppointmentType($ussdSession->msisdn);
        $reply = "Appointment Type:";
        if (count($typeAppointmentResults) > 0) {
            $displayedTypeAppointment = "";
            for ($i = 1; $i <= count($typeAppointmentResults); $i++) {
                $reply .= "\n" . $i . ":" . $typeAppointmentResults[$i - 1]->app_type . " - " . $typeAppointmentResults[$i - 1]->item;
                if ($i != count($typeAppointmentResults)) {
                    $displayedTypeAppointment .= $typeAppointmentResults[$i - 1]->id . "#";
                } else {
                    $displayedTypeAppointment .= $typeAppointmentResults[$i - 1]->id;
                }
            }
            $userParams = $ussdSession->userParams . UssdSession::VIEWTYPEAPPOINTMENT_LIST_IDS . "=" . $displayedTypeAppointment . "*";
            $ussdSession->userParams = $userParams;
        } else {
            $reply = "\nNo Appointments found.";
        }
        $ussdSession->currentFeedbackString = $reply;
        $ussdSession->currentFeedbackType = self::VIEWTYPEAPPOINTMENT_REQ;
        return $ussdSession;
    }
    public function setAppointmentVisitType($ussdSession) {
        $typeAppointmentResults = getAppointmentVisitType($ussdSession->msisdn);
        $reply = "Appointment Type:";
        if (count($typeAppointmentResults) > 0) {
            $displayedTypeAppointment = "";
            for ($i = 1; $i <= count($typeAppointmentResults); $i++) {
                $reply .= "\n" . $i . ":" . $typeAppointmentResults[$i - 1]->app_type . " - " . $typeAppointmentResults[$i - 1]->item;
                if ($i != count($typeAppointmentResults)) {
                    $displayedTypeAppointment .= $typeAppointmentResults[$i - 1]->id . "#";
                } else {
                    $displayedTypeAppointment .= $typeAppointmentResults[$i - 1]->id;
                }
            }
            $userParams = $ussdSession->userParams . UssdSession::VIEWVISITTYPEAPPOINTMENT_LIST_IDS . "=" . $displayedTypeAppointment . "*";
            $ussdSession->userParams = $userParams;
        } else {
            $reply = "\nNo Appointments found.";
        }
        $ussdSession->currentFeedbackString = $reply;
        $ussdSession->currentFeedbackType = self::VIEWAPPOINTMENTVISITTYPE_REQ;
        return $ussdSession;
    }
    public function setLabTrends($ussdSession) {
        $labTrendsResults = getLabTrends($ussdSession->msisdn);
        $reply = "Lab Trends:";
        if (count($labTrendsResults) > 0) {
            $displayedLabTrends = "";
            for ($i = 1; $i <= count($labTrendsResults); $i++) {
                $reply .= "\n" . $i . ":" . $labTrendsResults[$i - 1]->app_status . " - " . $labTrendsResults[$i - 1]->item;
                if ($i != count($labTrendsResults)) {
                    $displayedLabTrends .= $labTrendsResults[$i - 1]->id . "#";
                } else {
                    $displayedLabTrends .= $labTrendsResults[$i - 1]->id;
                }
            }
            $userParams = $ussdSession->userParams . UssdSession::VIEWLABTRENDS_LIST_IDS . "=" . $displayedLabTrends . "*";
            $ussdSession->userParams = $userParams;
        } else {
            $reply = "\nNo Lab Trends found."; 
        }
        $ussdSession->currentFeedbackString = $reply;
        $ussdSession->currentFeedbackType = self::VIEWLABTRENDS_REQ;
        return $ussdSession;
    }
    public function setAppointmentsDates($ussdSession) {
        $appointmentDatesResults = getLabTrends($ussdSession->msisdn);
        $reply = "Appointment Dates:";
        if (count($appointmentDatesResults) > 0) {
            $displayedApptDates = "";
            for ($i = 1; $i <= count($appointmentDatesResults); $i++) {
                $reply .= "\n" . $i . ":" . $appointmentDatesResults[$i - 1]->appntmnt_date . " - " . $appointmentDatesResults[$i - 1]->item;
                if ($i != count($appointmentDatesResults)) {
                    $displayedApptDates .= $appointmentDatesResults[$i - 1]->id . "#";
                } else {
                    $displayedApptDates .= $appointmentDatesResults[$i - 1]->id;
                }
            }
            $userParams = $ussdSession->userParams . UssdSession::VIEWAPPOINTMENTDATES_LIST_IDS . "=" . $displayedApptDates . "*";
            $ussdSession->userParams = $userParams;
        } else {
            $reply = "\nNo Appointment Dates found.";   
        }
        $ussdSession->currentFeedbackString = $reply;
        $ussdSession->currentFeedbackType = self::VIEWAPPOINTMENTDATES_REQ;
        return $ussdSession;
    }
    public function setMyAccountCategories($ussdSession) {
        $menuArray = array("My Profile:","Dependants List:");
        $ussdSession->currentFeedbackString = "Select one:\n" . generateMenu($menuArray);
        $ussdSession->currentFeedbackType = self::MYACCOUNT_CATEGORY_REQ;
        return $ussdSession;
    } 
    public function setDependants($ussdSession) {
        $menuArray = array("View Dependants:","Add Dependant:");
        $ussdSession->currentFeedbackString = "Select one:\n" . generateMenu($menuArray);
        $ussdSession->currentFeedbackType = self::DEPENDANTS_REQ;
        return $ussdSession;
    }     
    public function setViewDependants($ussdSession) {
        $dependantsResults = getUssdDependant($ussdSession->msisdn);
        $reply = "View Dependants:";
        if (count($dependantsResults) > 0) {
            $displayedDependants = "";
            for ($i = 1; $i <= count($dependantsResults); $i++) {
                $reply .= "\n" . $i . ":" . $dependantsResults[$i - 1]->user_id . " Name:  " . $dependantsResults[$i - 1]->first_name." ".$dependantsResults[$i - 1]->last_name."\n"."Hei Number:".$dependantsResults[$i - 1]->heiNumber."\n"."CCC Number:".$dependantsResults[$i - 1]->CCCNo;
                if ($i != count($dependantsResults)) {
                    $displayedDependants .= $dependantsResults[$i - 1]->id . "#";
                } else {
                    $displayedDependants .= $dependantsResults[$i - 1]->id;
                }
            }
            $userParams = $ussdSession->userParams . UssdSession::VIEWDEPENDANTS_LIST_IDS . "=" . $displayedDependants . "*";
            $ussdSession->userParams = $userParams;
        } else {
            $reply = "\nNo Dependants found.";  
        }
        $ussdSession->currentFeedbackString = $reply;
        $ussdSession->currentFeedbackType = self::VIEWDEPENDANTS_REQ;
        return $ussdSession;
    }
    public function setProfile($ussdSession) {
        $profileRequestsList = getUssdUserList($ussdSession->msisdn);
        $reply = "Profile:";
        if (count($profileRequestsList) > 0) {
            for ($i = 0; $i < count($profileRequestsList); $i++) {
                $reply .= "\n" . " Name: ." . $profileRequestsList[$i]->first_name . " " . $profileRequestsList[$i]->last_name ."\n "."CCC No: ". $profileRequestsList[$i]->CCCNo;
            }
        } else {
            $reply = "No Profilefound.";
        }
        $ussdSession->currentFeedbackString = $reply;
        $ussdSession->currentFeedbackType = self::PROFILE_REQ;
        return $ussdSession;
    }
  /////////Dependants
    public function setDependantFirstNameRequest($ussdSession) {
        $ussdSession->currentFeedbackString = "Enter Dependant First Name :";
        $ussdSession->currentFeedbackType = self::DEPENDANTS_FIRSTNAME_REQ;
        return $ussdSession;
    }
    public function setDependantLastNameRequest($ussdSession) {
        $ussdSession->currentFeedbackString = "Enter Dependant Last Name :";
        $ussdSession->currentFeedbackType = self::DEPENDANTS_LASTNAME_REQ;
        return $ussdSession;
    }
    public function setDependantSurNameRequest($ussdSession) {
        $ussdSession->currentFeedbackString = "Enter Dependant Surname :";
        $ussdSession->currentFeedbackType = self::DEPENDANTS_SURNAME_REQ;
        return $ussdSession;
    }
    public function setDependantCccNumberRequest($ussdSession) {
        $ussdSession->currentFeedbackString = "Enter Dependant CCCNumber :";
        $ussdSession->currentFeedbackType = self::DEPENDANTS_CCCNUMBER_REQ;
        return $ussdSession;
    }
    public function setDependantHeiNumberRequest($ussdSession) {
        $ussdSession->currentFeedbackString = "Enter Dependant Hei Number :";
        $ussdSession->currentFeedbackType = self::DEPENDANTS_HEINUMBER_REQ;
        return $ussdSession;
    }
    public function setDependantDobRequest($ussdSession) {
        $ussdSession->currentFeedbackString = "Enter Dependant Date of Birth in the format(YYYY-MM-DD) :";
        $ussdSession->currentFeedbackType = self::DEPENDANTS_DOB_REQ;
        return $ussdSession;
    }

}
