<?php

//define("LOG_FILE", "/var/log/php/kiambuussd.log");

/**
 * Utils.php contains supporting functions
 *
 * @author kmacharia <kmacharia@onfonmedia.com>
 */
function generateMenu($menuArray) {
    $menu = "";
    for ($i = 1; $i <= count($menuArray); $i++) {
        $menu .= $i . ": " . $menuArray[($i - 1)];
        if ($i != count($menuArray)) {
            $menu .= "\n";
        }
    }
    return $menu;
}

function cleanUssdString($ussdString) {
    if (strpos($ussdString, "*98*") !== false) {
        $ussdString = str_replace("\\*98\\*", "*", $ussdString);
    }

    if (strpos($ussdString, "*0*") !== false) {
        $ussdString = str_replace("\\*0\\*", "*", $ussdString);
    }
    return $ussdString;
}

function isValidName($name) {
    if ($name == " ") {
        return false;
    } elseif (is_numeric($name)) {
        return false;
    } else {
        return true;
    }
}

function isRequiredMinimumSize($string, $requiredSize) {
    if (strlen($string) >= $requiredSize) {
        return true;
    } else {
        return false;
    }
}

function isValidCccNumber($cccNumber) {
    $cccNumber = str_replace(" ", "", $cccNumber);
    if (strlen($cccNumber) < 10) {
        return false;
    } else {
        return true;
    }
}
function isValidHeiNumber($heiNumber) {
    $heiNumber = str_replace(" ", "", $heiNumber);
    if (strlen($heiNumber) < 10) {
        return false;
    } else {
        return true;
    }
}
function isValidDateToYMD($dob){
     d ==$dob.getDate();
     m == $dob.getMonth() + 1;
     y == $dob.getFullYear();
     return '' + y + '-' + (m<=9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d : d);
}