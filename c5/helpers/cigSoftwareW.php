
<?php
defined('C5_EXECUTE') or die("Access Denied.");
class CigSoftwareWHelper {

# PHP Style guide cheat sheet
# FunctionNamesLike
# $localVariableName
# $objectVariable
# ClassNamesLike
# MethodNamesLike
# CONSTANTS_LIKE_THIS
# Global names (classes, functions, variables, defines) must be prefixed to prevent naming clashes with PHP itself.

    public function cigSoftwareWDB($server = null, $username = null, $password = null, $database = null, $create = false, $autoconnect = true) {
        static $_dba;
        if ((!isset($_dba) || $create) && ($autoconnect)) {
            if ($server == null && defined('DB_SERVER')) {
                $dsn = DB_TYPE . '://' . CIG_SOFTWARE_W_USERNAME . ':' . rawurlencode(CIG_SOFTWARE_W_PASSWORD) . '@' . rawurlencode(CIG_SOFTWARE_W_SERVER) . '/' . CIG_SOFTWARE_W_DATABASE;
            } else if ($server) {
                $dsn = DB_TYPE . '://' . $username . ':' . rawurlencode($password) . '@' . rawurlencode($server) . '/' . $database;
            }

            if (isset($dsn) && $dsn) {
                $_dba = @NewADOConnection($dsn);
                if (is_object($_dba)) {
                    $_dba->setFetchMode(ADODB_FETCH_ASSOC);
                    if (DB_CHARSET != '') {
                        $names = 'SET NAMES \'' . DB_CHARSET . '\'';
                        if (DB_COLLATE != '') {
                            $names .= ' COLLATE \'' . DB_COLLATE . '\'';
                        }
                        $_dba->Execute($names);
                    }

                    ADOdb_Active_Record::SetDatabaseAdapter($_dba);
                } else if (defined('DB_SERVER')) {
                    $v = View::getInstance();
                    $v->renderError(t('Unable to connect to database.'), t('A database error occurred while processing this request.'));
                }
            } else {
                return false;
            }
        }

        return $_dba;
    }

    public function getPackageTitle( $software_id = 0 ) {
        return $software_id;
    }


}

?>
