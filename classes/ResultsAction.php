<?php
include_once('./QueryManager.php');
include_once('MenuItems.php');
include_once('UssdUtils.php');
class ResultsAction {
    public function process($ussdSession) {
        $menuItems = new MenuItems();
        $menuSuffix = "\n00 Home";
        $params = explode("*", $ussdSession->ussdProcessString);
        $lastSelection = trim($params[count($params) - 1]);
            if(MenuItems::MAINMENU_REQ == $ussdSession->previousFeedbackType){
                $ussdSession = $menuItems->setViewResults($ussdSession);
                $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
            } elseif (MenuItems::VIEWRESULTS_REQ == $ussdSession->previousFeedbackType) {
                if (is_numeric($lastSelection) && $lastSelection >=1 && $lastSelection <= 3) {
                    $userParams = $ussdSession->userParams . UssdSession::VIEWRESULTS_LIST_IDS . "=" . $lastSelection . "*";
                    $ussdSession->userParams = $userParams;
                    if("1"==$lastSelection){//vL results
                        $ussdSession = $menuItems->setViewVLResults($ussdSession);
                        $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
                    }elseif("2"== $lastSelection){//eid results
                        $ussdSession = $menuItems->setViewIEDResults($ussdSession);    
                        $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
                    }else{
                        $ussdSession = $menuItems->setViewResults($ussdSession);
                        $reply = "CON INVALID INPUT. Only number 1-2 allowed.\n" . $ussdSession->currentFeedbackString . $menuSuffix;              
                    }
                } else {
                    $reply = "END Connection error. Please try again.";
                }
                $ussdSession->currentFeedbackString = $reply;
                return $ussdSession;
          
            } elseif (MenuItems::VIEWVLRESULTS_REQ == $ussdSession->previousFeedbackType) {          
                    $userParams = $ussdSession->userParams . UssdSession::VIEWVLRESULTS_LIST_IDS . "=" . $lastSelection . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setViewVLResults($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
            } elseif (MenuItems::VIEWEIDRESULTS_REQ == $ussdSession->previousFeedbackType) {             
                    $userParams = $ussdSession->userParams . UssdSession::VIEWEIDRESULTS_LIST_IDS . "=" . $lastSelection . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setViewIEDResults($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
            } else {
                    $reply = "END Connection error. Please try again.";
            }
                $ussdSession->currentFeedbackString = $reply;
                return $ussdSession;  
            
           
      
    }
}