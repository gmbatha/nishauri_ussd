<?php


class UssdSession {

    var $sessionId;
    var $msisdn;
    var $ussdCode;
    var $ussdString;
    var $ussdStringPrefix;
    var $ussdProcessString;
    var $previousFeedbackType;
    var $currentFeedbackString;
    var $currentFeedbackType;
    var $startTime;
    var $userParams;
    var $test;
    var $rate_cartegory;
    const LANGUAGE_ID = "LANGUAGE_ID";
    const USER_ID = "USER_ID";
    const FIRSTNAME = "FIRSTNAME";
    const LASTNAME = "LASTNAME";
    const SURNAME = "SURNAME";
    const CCCNUMBER = "CCCNUMBER";
    const HEINUMBER = "HEINUMBER";
    const DOB = "DOB";
    const NOT_FOUND = "NOT_FOUND";
    const VIEWRESULTS_LIST_IDS = "VIEWRESULTS_LIST_IDS";
    const VIEWMYACCOUNTS_LIST_IDS = "VIEWMYACCOUNTS_LIST_IDS";
    const VIEWVLRESULTS_LIST_IDS = "VIEWVLRESULTS_LIST_IDS";
    const VIEWEIDRESULTS_LIST_IDS = "VIEWEIDRESULTS_LIST_IDS";
    const VIEWAPPOINTMENT_LIST_IDS = "VIEWAPPOINTMENT_LIST_IDS";
    const VIEWOWNERAPPOINTMENT_LIST_IDS = "VIEWOWNERAPPOINTMENT_LIST_IDS";
    const VIEWDEPENDANTAPPOINTMENT_LIST_IDS = "VIEWDEPENDANTAPPOINTMENT_LIST_IDS";
    const VIEWTYPEAPPOINTMENT_LIST_IDS = "VIEWTYPEAPPOINTMENT_LIST_IDS";
    const VIEWTRENDSAPPOINTMENT_LIST_IDS = "VIEWTRENDSAPPOINTMENT_LIST_IDS";
    const VIEWVISITTYPEAPPOINTMENT_LIST_IDS = "VIEWVISITTYPEAPPOINTMENT_LIST_IDS";
    const VIEWAPPOINTMENTDATES_LIST_IDS = "VIEWAPPOINTMENTDATES_LIST_IDS";
    const VIEWLABTRENDS_LIST_IDS = "VIEWLABTRENDS_LIST_IDS";
    const VIEWDEPENDANTS_LIST_IDS = "VIEWDEPENDANTS_LIST_IDS";
    const MYPROFILE_LIST_IDS = "MYPROFILE_LIST_IDS";
    const MYACCOUNT_LIST_IDS = "MYACCOUNT_LIST_IDS";
    ////////////Dependants
    const DEPENDANT_FIRSTNAME = "DEPENDANT_FIRSTNAME";
    const DEPENDANT_LASTNAME = "DEPENDANT_LASTNAME";
    const DEPENDANT_SURNAME = "DEPENDANT_SURNAME";
    const DEPENDANT_CCCNUMBER = "DEPENDANT_CCCNUMBER";
    const DEPENDANT_HEINUMBER = "DEPENDANT_HEINUMBER";
    const DEPENDANT_DOB = "DEPENDANT_DOB";

    


    public static function getUserParam($paramName, $userParams) {
        $params = explode("*", $userParams);
        //get latest input
        for ($i = count($params) - 1; $i > -1; $i--) {
            $keyValue = explode("=", $params[$i]);
            if ($paramName == $keyValue[0]) {
                return $keyValue[1];
            }
        }
        return self::NOT_FOUND;
    }

}

class UssdUser {

    var $id;
    var $msisdn;
    var $first_name;
    var $last_name;
    var $CCCNo;
    var $created_at;

}

class UssdDependants {
    var $id;
    var $user_id;
    var $last_name;
    var $first_name;
    var $surname;
    var $CCCNo;
    var $heiNumber;
    var $dob;
}
class AddDependants {
    var $id;
    var $user_id;
    var $first_name;
    var $last_name;
    var $CCCNo;
 
}