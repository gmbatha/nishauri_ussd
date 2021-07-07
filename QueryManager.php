<?php

include_once('Models.php');
/**
 * Returns an array with database results generated from $sql and $params
 */
function _select($sql, $params) {

    $username = 'USERNAME';
    $password = 'PASSWORD';
    $database = 'DATABASE';
    $host = 'HOST:PORT';

    $res = array();
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $res = $stmt->fetchAll();
    } catch (PDOException $error) {
       // var_dump($sql);
       // var_dump($params);
       // var_dump($error);        
       error_log("[ERROR : " . date("Y-m-d H:i:s") . "] _select error: " . $error . "\nSQL=" . $sql . "\nParams=" . print_r($params, true), 3, LOG_FILE);
    }
    return $res;
}

/**
 * Performs database insert, update and delete
 */
function _execute($sql, $params) {

    $username = 'USERNAME';
    $password = 'PASSWORD';
    $database = 'DATABASE';
    $host = 'HOST:PORT';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return TRUE;
    } catch (PDOException $error) {
       // var_dump($error); 
       // var_dump($sql);
       // var_dump($params);
        error_log("[ERROR : " . date("Y-m-d H:i:s") . "] _execute error: " . $error . "\nSQL=" . $sql . "\nParams=" . print_r($params, true), 3, LOG_FILE);
        return FALSE;
    }
}

function createNewUssdSession($ussdSession) {
    $sql = "INSERT INTO ussd_sessions (SessionId,Msisdn,UssdCode,UssdString,UssdProcessString,currentFeedbackString,currentFeedbackType,startTime,userParams)"
            . " VALUES(:sessionId,:msisdn,:ussdCode,:ussdString,:ussdProcessString,:currentFeedbackString,:currentFeedbackType,:startTime,:userParams)";
    $params = array(
        ':sessionId' => $ussdSession->sessionId,
        ':msisdn' => $ussdSession->msisdn,
        ':ussdCode' => $ussdSession->ussdCode,
        ':ussdString' => $ussdSession->ussdString,
        ':ussdProcessString' => $ussdSession->ussdProcessString,
        ':currentFeedbackString' => $ussdSession->currentFeedbackString,
        ':currentFeedbackType' => $ussdSession->currentFeedbackType,
        ':startTime' => date('Y-m-d H:i:s'),
        ':userParams' => $ussdSession->userParams,
    );
    return _execute($sql, $params);
}

function getUssdSessionList($sessionId){
    $ussdSessionList = array();
    $sql = "SELECT sessionId,msisdn,UssdCode,UssdString,UssdStringPrefix,UssdProcessString,previousFeedbackType,currentFeedbackString,currentFeedbackType,startTime,userParams"
            . " FROM ussd_sessions"
            . " WHERE sessionId=:sessionId LIMIT 1";
    $params = array(
        ':sessionId' => $sessionId,
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $ussdSession = new UssdSession();
        $ussdSession->sessionId = $record['sessionId'];
        $ussdSession->msisdn = $record['msisdn'];
        $ussdSession->ussdCode = $record['UssdCode'];
        $ussdSession->ussdString = $record['UssdString'];
        $ussdSession->ussdStringPrefix = $record['UssdStringPrefix'];
        $ussdSession->ussdProcessString = $record['UssdProcessString'];
        $ussdSession->previousFeedbackType = $record['previousFeedbackType'];
        $ussdSession->currentFeedbackString = $record['currentFeedbackString'];
        $ussdSession->currentFeedbackType = $record['currentFeedbackType'];
        $ussdSession->startTime = $record['startTime'];
        $ussdSession->userParams = $record['userParams'];
        $ussdSessionList[] = $ussdSession;
    }
    return $ussdSessionList;
}

function updateUssdSession($ussdSession) {
    $sql = "UPDATE ussd_sessions SET UssdString=:ussdString,UssdStringPrefix=:ussdStringPrefix, UssdProcessString=:ussdProcessString,"
            . "previousFeedbackType=:previousFeedbackType,currentFeedbackString=:currentFeedbackString,currentFeedbackType=:currentFeedbackType,userParams=:userParams"
            . " WHERE sessionId=:sessionId";
    $params = array(
        ':ussdString' => $ussdSession->ussdString,
        ':ussdStringPrefix' => $ussdSession->ussdStringPrefix,
        ':ussdProcessString' => $ussdSession->ussdProcessString,
        ':previousFeedbackType' => $ussdSession->previousFeedbackType,
        ':currentFeedbackString' => $ussdSession->currentFeedbackString,
        ':currentFeedbackType' => $ussdSession->currentFeedbackType,
        ':userParams' => $ussdSession->userParams,
        ':sessionId' => $ussdSession->sessionId,
    );
    return _execute($sql, $params);
}

function getUssdUserList($msisdn) {
    $ussdUserList = array();
    $sql = "SELECT id,msisdn,first_name,last_name,CCCNo,created_at"
            . " FROM User"
            . " WHERE msisdn=:msisdn LIMIT 1";
    $params = array(
        ':msisdn' => $msisdn,
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $ussdUser = new UssdUser();
        $ussdUser->id = $record['id'];
        $ussdUser->msisdn = $record['msisdn'];
        $ussdUser->first_name = $record['first_name'];
        $ussdUser->last_name = $record['last_name'];
        $ussdUser->CCCNo = $record['CCCNo'];
        $ussdUser->created_at = $record['created_at'];
        $ussdUserList[] = $ussdUser;
    }
    return $ussdUserList;
}
function createUssdUser($ussdUser) {
    $sql = "INSERT INTO User (msisdn,first_name,last_name,CCCNo)"
            . " VALUES(:msisdn,:first_name,:last_name,:CCCNo)";
    $params = array(
        ':msisdn' => $ussdUser->msisdn,
        ':first_name' => $ussdUser->first_name,
        ':last_name' => $ussdUser->last_name,
        ':CCCNo' => $ussdUser->CCCNo,
    );
    return _execute($sql, $params);
}
function getUssdVlResults($msisdn){
    $ussdVlResultsList = array();

    $sql = "SELECT VLResults.result_content,VLResults.CCCNo,VLResults.owner,User.msisdn"
    . " FROM User "
    . " INNER JOIN VLResults ON user.id = VLResults.user_id"
    . " LIMIT 5";
    $params = array(
        ':msisdn' => $msisdn,
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $ussdvlResults = new VlResults();
        $ussdvlResults->id = $record['id'];
        $ussdvlResults->result_content = $record['result_content'];
        $ussdvlResults->owner = $record['owner'];
        $ussdvlResults->msisdn = $record['msisdn'];
        $ussdvlResults->CCCNo = $record['CCCNo'];
        $ussdVlResultsList[] = $ussdvlResults;
    }
    return $ussdVlResultsList;
}
function getUssdEIDResults($msisdn){
    $ussdEidResultsList = array();    
    $sql = "SELECT EidResults.result_content,EidResults.CCCNo,EidResults.owner,user.msisdn"
    . " FROM user "
    . "JOIN Dependants "
    . "ON user.id = Dependants.user_id"
    . "JOIN EidResults"
    . " ON Dependants.id = EidResults.dependant_id"
    . " LIMIT 5";
    $params = array(
        ':msisdn' => $msisdn,
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $ussdEidResults = new EIDResults();
        $ussdEidResults->id = $record['id'];
        $ussdEidResults->result_content = $record['result_content'];
        $ussdEidResults->heiNumber = $record['owner'];
        $ussdEidResults->msisdn = $record['msisdn'];
        $ussdEidResults->CCCNo = $record['CCCNo'];
        $ussdEidResultsList[] = $ussdEidResults;
    }
    return $ussdEidResultsList;
}
function getAppointmentType($msisdn){
    $typeAppointmentList = array();

    $sql = "SELECT Appointments.id,Appointments.visit_type,Appointments.owner,Appointments.app_type,User.msisdn"
    . " FROM User "
    . " INNER JOIN Appointments ON User.id = Appointments.user_id"
    . " ORDER BY id DESC"
    . " LIMIT 15";
    $params = array(
        ':msisdn' => $msisdn,
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $ussdtypeappointment = new AppointmentType();
        $ussdtypeappointment->id = $record['id'];
        $ussdtypeappointment->visit_type = $record['visit_type'];
        $ussdtypeappointment->app_type = $record['app_type'];
        $ussdtypeappointment->owner = $record['owner'];
        $ussdtypeappointment->msisdn = $record['msisdn'];
        $typeAppointmentList[] = $ussdtypeappointment;
    }
    return $typeAppointmentList;
}
function getAppointmentVisitType($msisdn){
    $visitTypeAppointmentList = array();

    $sql = "SELECT Appointments.id,Appointments.visit_type,Appointments.owner,Appointments.app_type,User.msisdn"
    . " FROM User "
    . " INNER JOIN Appointments ON User.id = Appointments.user_id"
    . " ORDER BY id DESC"
    . " LIMIT 15";
    $params = array(
        ':msisdn' => $msisdn,
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $ussdvisittypeappointment = new AppointmentVisitType();
        $ussdvisittypeappointment->id = $record['id'];
        $ussdvisittypeappointment->visit_type = $record['visit_type'];
        $ussdvisittypeappointment->app_type = $record['app_type'];
        $ussdvisittypeappointment->owner = $record['owner'];
        $ussdvisittypeappointment->msisdn = $record['msisdn'];
        $visitTypeAppointmentList[] = $ussdvisittypeappointment;
    }
    return $visitTypeAppointmentList;
}
function getLabTrends($msisdn){
    $labTrendsList = array();

    $sql = "SELECT Appointments.id,Appointments.visit_type,Appointments.app_status,Appointments.owner,Appointments.app_type,User.msisdn"
    . " FROM User "
    . " INNER JOIN Appointments ON user.id = Appointments.user_id"
    . " ORDER BY id DESC"
    . " LIMIT 15";
    $params = array(
        ':msisdn' => $msisdn,
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $ussdLabtrends = new LabTrends();
        $ussdLabtrends->id = $record['id'];
        $ussdLabtrends->visit_type = $record['visit_type'];
        $ussdLabtrends->app_status = $record['app_status'];
        $ussdLabtrends->app_type = $record['app_type'];
        $ussdLabtrends->owner = $record['owner'];
        $ussdLabtrends->msisdn = $record['msisdn'];
        $labTrendsList[] = $ussdLabtrends;
    }
    return $labTrendsList;
}
function getOwnerAppointments($msisdn){
    $ownerAppointmentList = array();

    $sql = "SELECT Appointments.id,Appointments.visit_type,Appointments.owner,Appointments.app_type,User.msisdn"
    . " FROM User "
    . " INNER JOIN Appointments ON user.id = Appointments.user_id"
    . " ORDER BY id DESC"
    . " LIMIT 5";
    $params = array(
        ':msisdn' => $msisdn,
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $ussdownerappointment = new OwnerAppointments();
        $ussdownerappointment->id = $record['id'];
        $ussdownerappointment->visit_type = $record['visit_type'];
        $ussdownerappointment->app_type = $record['app_type'];
        $ussdownerappointment->owner = $record['owner'];
        $ussdownerappointment->msisdn = $record['msisdn'];
        $ownerAppointmentList[] = $ussdownerappointment;
    }
    return $ownerAppointmentList;
}
function getDependantAppointments($msisdn){
    $dependantAppointmentList = array();

    $sql = "SELECT Appointments.id,Appointments.visit_type,Appointments.dependant,Appointments.app_type,User.msisdn"
    . " FROM User "
    . " INNER JOIN Appointments ON User.id = Appointments.user_id"
    . " ORDER BY id DESC"
    . " LIMIT 5";
    $params = array(
        ':msisdn' => $msisdn,
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $ussddependantappointment = new DependantAppointment();
        $ussddependantappointment->id = $record['id'];
        $ussddependantappointment->visit_type = $record['visit_type'];
        $ussddependantappointment->app_type = $record['app_type'];
        $ussddependantappointment->dependant = $record['dependant'];
        $ussddependantappointment->msisdn = $record['msisdn'];
        $dependantAppointmentList[] = $ussddependantappointment;
    }
    return $dependantAppointmentList;
}
function getAppointmentsDates($msisdn){
    $appointmentDatesList = array();

    $sql = "SELECT Booked_Appointments.appntmnt_date,Booked_Appointments.id,Booked_Appointments.app_type,Booked_Appointments.approval_status,user.msisdn"
    . " FROM user "
    . " INNER JOIN Booked_Appointments ON user.id = Booked_Appointments.user_id"
    . " ORDER BY id DESC"
    . " LIMIT 5";
    $params = array(
        ':msisdn' => $msisdn,
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $ussdappointmentdates = new AppointmentsDates();
        $ussdappointmentdates->id = $record['id'];
        $ussdappointmentdates->appntmnt_date = $record['appntmnt_date'];
        $ussdappointmentdates->app_type = $record['app_type'];
        $ussdappointmentdates->approval_status = $record['approval_status'];
        $ussdappointmentdates->msisdn = $record['msisdn'];
        $appointmentDatesList[] = $ussdappointmentdates;
    }
    return $appointmentDatesList;
}

function getUssdDependant($msisdn){
    $ussdDefaulterList = array();

    $sql = "SELECT Dependants.heiNumber,Dependants.first_name,Dependants.last_name,Dependants.CCCNo"
    . " FROM Dependants "
    . " WHERE user_id = (SELECT User.id FROM User JOIN ussd_sessions ON ussd_sessions.msisdn = User.msisdn LIMIT 1)"
    . " ORDER BY id DESC"
    . " LIMIT 15";
    $params = array(
        ':msisdn' => $msisdn,
    );
    $resultset = _select($sql, $params);
    foreach ($resultset as $record) {
        $ussdDependant = new UssdDependants();
        //$ussdDependant->id = $record['id'];
        $ussdDependant->heiNumber = $record['heiNumber'];
        $ussdDependant->first_name = $record['first_name'];
        $ussdDependant->last_name = $record['last_name'];
        $ussdDependant->CCCNo = $record['CCCNo'];
        $ussdDefaulterList[] = $ussdDependant;
    }
    return $ussdDefaulterList;
}

function createDependants($ussdDependant) {   
    $sql = "INSERT INTO dependants (user_id,first_name,last_name,surname,CCCNo,heiNumber,dob)"
    . " VALUES((SELECT user.id FROM user JOIN ussd_sessions ON ussd_sessions.msisdn = user.msisdn LIMIT 1),:first_name,:last_name,:surname,:CCCNo,:heiNumber,:dob)";
   // SELECT id FROM user JOIN dependants ON dependants.user_id = dependants.id LIMIT 1
   
    $params = array(
        //':user_id' => $ussdDependant->user_id,
        ':first_name' => $ussdDependant->first_name,
        ':last_name' => $ussdDependant->last_name,
        ':surname' => $ussdDependant->surname,
        ':CCCNo' => $ussdDependant->CCCNo,
        ':heiNumber' => $ussdDependant->heiNumber,
        ':dob' => $ussdDependant->dob,
    );
    return _execute($sql, $params);
}


