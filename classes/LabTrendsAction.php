<?php
include_once('./QueryManager.php');
include_once('MenuItems.php');
include_once('UssdUtils.php');
class LabTrendsAction {
    public function process($ussdSession) {
        $menuItems = new MenuItems();
        $menuSuffix = "\n00 Home";
        $params = explode("*", $ussdSession->ussdProcessString);
        $lastSelection = trim($params[count($params) - 1]);
            if(MenuItems::MAINMENU_REQ == $ussdSession->previousFeedbackType){
                $ussdSession = $menuItems->setLabTrends($ussdSession);
                $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
            } elseif (MenuItems::VIEWLABTRENDS_REQ == $ussdSession->previousFeedbackType) {
                if (is_numeric($lastSelection) && $lastSelection) {
                    $userParams = $ussdSession->userParams . UssdSession::VIEWLABTRENDS_LIST_IDS . "=" . $lastSelection . "*";
                    $ussdSession->userParams = $userParams;
                 
                        $ussdSession = $menuItems->setLabTrends($ussdSession);
                        $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
                       
                        // $reply = "CON INVALID INPUT. Only number 1-2 allowed.\n" . $ussdSession->currentFeedbackString . $menuSuffix;              
                    
                } else {
                    $reply = "END Connection error. Please try again.";
                }
                $ussdSession->currentFeedbackString = $reply;
                return $ussdSession;
          
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