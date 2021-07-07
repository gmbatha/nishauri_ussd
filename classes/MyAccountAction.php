<?php
include_once('./QueryManager.php');
include_once('MenuItems.php');
include_once('UssdUtils.php');
class MyAccountAction {
    public function process($ussdSession) {
        $menuItems = new MenuItems();
        $menuSuffix = "\n00 Home";
        $params = explode("*", $ussdSession->ussdProcessString);
        $lastSelection = trim($params[count($params) - 1]);
            if(MenuItems::MAINMENU_REQ == $ussdSession->previousFeedbackType){
                $ussdSession = $menuItems->setMyAccountCategories($ussdSession);
                $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
            } elseif (MenuItems::MYACCOUNT_CATEGORY_REQ == $ussdSession->previousFeedbackType) {
                if (is_numeric($lastSelection) && $lastSelection >=1 && $lastSelection <= 3) {
                    $userParams = $ussdSession->userParams . UssdSession::MYACCOUNT_LIST_IDS . "=" . $lastSelection . "*";
                    $ussdSession->userParams = $userParams;
                    if("1"==$lastSelection){//vL results
                        $ussdSession = $menuItems->setProfile($ussdSession);
                        $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
                    }elseif("2"== $lastSelection){//eid results
                        $ussdSession = $menuItems->setViewDependants($ussdSession);    
                        $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
                    }else{
                        $ussdSession = $menuItems->setMyAccountCategories($ussdSession);
                        $reply = "CON INVALID INPUT. Only number 1-2 allowed.\n" . $ussdSession->currentFeedbackString . $menuSuffix;              
                    }
                } else {
                    $reply = "END Connection error. Please try again.";
                }
                $ussdSession->currentFeedbackString = $reply;
                return $ussdSession;
          
            } elseif (MenuItems::PROFILE_REQ == $ussdSession->previousFeedbackType) {          
                    $userParams = $ussdSession->userParams . UssdSession::MYPROFILE_LIST_IDS . "=" . $lastSelection . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setProfile($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
            } elseif (MenuItems::VIEWDEPENDANTS_REQ == $ussdSession->previousFeedbackType) {             
                    $userParams = $ussdSession->userParams . UssdSession::VIEWDEPENDANTS_LIST_IDS . "=" . $lastSelection . "*";
                    $ussdSession->userParams = $userParams;
                    $ussdSession = $menuItems->setViewDependants($ussdSession);
                    $reply = "CON " . $ussdSession->currentFeedbackString . $menuSuffix;
            } else {
                    $reply = "END Connection error. Please try again.";
            }
                $ussdSession->currentFeedbackString = $reply;
                return $ussdSession;          
      
    }
}