<?php
#########################################
# MathToolboxHelper
# INTENT: various functions written for UCD Math for use from within concrete5.
#
defined('C5_EXECUTE') or die("Access Denied.");
class MathToolboxHelper {

#########################################
# mTxt();
# INTENT: return a plain text string safe for display on a web page
# Syntax: mTxt("plain_non_html_text_to_output");
    public function mTxt($text_to_clean=null)
    {
        return $text_to_clean;
    }

#########################################
# mTxt();
# INTENT: return a string safe for display on a web page, with simple HTML allowed
# Syntax: mHtml("simple_html_text_to_output");
    public function mHtml($text_to_clean=null)
    {
        return $text_to_clean;
    }

#########################################
# mathDefterm();
# INTENT: return default term codes for various things
# Syntax: mathDefterm("description");
    public function mathDefterm($default_desc=null)
    {
        static $_dterm;
        $mu = Loader::helper('mathUser');
        $db = $mu->mathUserDB();
        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        $query="select qtr_code from qtr_defaults where default_desc='$default_desc'";
        $recordSet = $db->Execute($query);
        if (!$recordSet) {
            print $conn->ErrorMsg();
        }
        else {
            $_dterm = $recordSet->fields["qtr_code"];
            return $_dterm;
        }
    }

#########################################
# mathConvTerm();
# INTENT: Turn UC Davis Term codes (201101, 201105, etc) into more human readable format.
# Syntax:  mathConvTerm("TermCode","Format")
# TermCode should be a six digit code.
# Format should be long (Winter 2008), short (W 2008), or acyear (2007-2008).
    public function mathConvTerm($termcode = null,$format = null) {
        static $_term;
        $yrCode = substr($termcode, 0, 4);
        $seCode = substr($termcode, 4, 2);
#        if (isset($format)) { } else {
#            $format="";
#        }
        switch ($format) {
        case "short":
            switch ($seCode) {
            case 01:
                $seHuman="W";
                break;
            case 02:
                $seHuman="SpSem";
                break;
            case 03:
                $seHuman="Sp";
                break;
            case 04:
                $seHuman="Xtra";
                break;
            case 05:
                $seHuman="SuI";
                break;
            case 06:
                $seHuman="SSu";
                break;
            case 07:
                $seHuman="SuII";
                break;
            case 08:
                $seHuman="SuQtr";
                break;
            case 09:
                $seHuman="FSem";
                break;
            case 10:
                $seHuman="F";
                break;
            default:
                $seHuman=$seCode;
            }
            $_term = $seHuman." ".$yrCode;
            return $_term;
            break;
        case "acyear":
            switch ($seCode) {
            case 09:
                $yrBegin=$yrCode;
                break;
            case 10:
                $yrBegin=$yrCode;
                break;
            default:
                $yrBegin=$yrCode-1;
            }
            $yrEnd = $yrBegin + 1;
            $_term = $yrBegin."-".$yrEnd;
            return $_term;
            break;
        case "long":
            switch ($seCode) {
            case 01:
                $seHuman="Winter";
                break;
            case 02:
                $seHuman="Spring Semester";
                break;
            case 03:
                $seHuman="Spring";
                break;
            case 04:
                $seHuman="Extra Session";
                break;
            case 05:
                $seHuman="Summer I";
                break;
            case 06:
                $seHuman="Special Summer";
                break;
            case 07:
                $seHuman="Summer II";
                break;
            case 08:
                $seHuman="Summer Quarter";
                break;
            case 09:
                $seHuman="Fall Semester";
                break;
            case 10:
                $seHuman="Fall";
                break;
            default:
                $seHuman=$seCode;
            }
            $_term = $seHuman." ".$yrCode;
            return $_term;
            break;
        case "":
            $_term = $yrCode;
            return $_term;
            break;
        }
    }

}
?>
