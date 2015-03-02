<?php
require_once $_configuration['root_sys'].'plugin/vchamilo/lib.php';
require_once $_configuration['root_sys'].'local/classes/mootochamlib.php';

global $SHOWSQL; // for debugging purpose;
$SHOWSQL = false;

/* For licensing terms, see /license.txt */

/**
 * Database class definition
 * @package chamilo.database
 */
class DatabaseManager {

    /* Variable use only in the installation process to log errors. See the Database::query function */
    static $log_queries = false;

    protected $_configuration;

    protected $_cnx;

    protected $_database;

    /*
     * Query methods
     * These methods execute a query and return the result(s).
     */

    /**
     * Constructor
     *
     */
     public function __construct(&$configuration = null, $database = 'main') {
        global $_configuration;

        if (!is_null($configuration)) {
            if (is_object($configuration)) {
                $this->_configuration = (array)$configuration;
            } else {
                $this->_configuration = $configuration;
            }
        } else {
            $this->_configuration = $_configuration; 
        }

        if ($this->_cnx = vchamilo_boot_connection($this->_configuration, $database)) {
            // register actually bound database
            $this->_database = $this->_configuration[$database.'_database'];
        } else {
            throw(new Exception('Could not connect to database '.$database));
        }
    }
    
    function get_info() {

        $str = '';

        $str .= 'DB Host: '.$this->_configuration['db_host']."\n";
        $str .= 'Database: '.$this->_database."\n";
        $str .= 'DBUser: '.$this->_configuration['db_user']."\n";
        if ($this->_cnx) {
            $str .= 'State: Connected'."\n";
        } else {
            $str .= 'State: Not Connected'."\n";
        }
        $str .= "\n";

        return $str;
    }

    /**
     * frees external connexions
     *
     */
    public function dismount() {
        if (!is_null($this->_cnx)) {
            mysql_close($this->_cnx);
        }
    }

    /**
     * get one record in database
     * @param string $table        table name
     * @param array $params        where params table
     * @param string $fields    fieldset
     * @return array    a single object
     * @author Valery Fremaux (valery.fremaux@gmail.com)
     */
    public function get_record($table, $params, $fields = '*', $database = '') {
        global $SHOWSQL;

        $tablename = $this->format_table_name($table, $database);

        if (empty($fields)) {
            debugging('DB fields cannot be empty in a select', 1);
        }

        $paramsql[] = " 1 = 1 ";
        foreach ($params as $key => $value) {
            if (empty($value)) {
                $paramsql[] = " (`$key` = '' OR `$key` IS NULL) ";
            } elseif (is_numeric($value)) {
                $paramsql[] = " `$key` = $value ";
            } else {
                $paramsql[] = " `$key` = '$value' ";
            }
        }
        $where = implode(' AND ', $paramsql);

        $sql = "
            SELECT 
                $fields
            FROM
                $tablename
            WHERE
                $where
        ";
        if ($SHOWSQL) echo "get_record SQL : $sql <br/>";
        $result = Database::query($sql, $this->_cnx);
        if ($result) {
            if (mysql_num_rows($result) > 1) {
                debugging("DB ERROR : DB query should ask for unique result in $sql ", 1);
            } else {
                $rec = Database::fetch_object($result);
                return $rec;
            }
        } else {
            debugging('SQL ERROR IN '.$sql, 1);
        }
        return false;
    }

    /**
     * get one record in database
     * @param string $table        table name
     * @param array $params        where params table
     * @param string $sort        sort colons
     * @param string $fields    fieldset
     * @return array    a single object
     * @author Valery Fremaux (valery.fremaux@gmail.com)
     */
    public function get_field($table, $field, $params, $database = '') {
        $rec = $this->get_record($table, $params, '*', $database);
        if (is_object($rec)) {
            if (!isset($rec->$field)) {
                debugging("DB Error : missing field $field in object ", 1);
            }
            return $rec->$field;
        }
        return false;
    }

    /**
     * get one record in database
     * @param string $table        table name
     * @param string $field        field name
     * @param string $value        value
     * @param array $params        where params table
     * @param string $pkfield       an explicit primary key field name if unormalized
     * @return int                boolean status
     * @author Valery Fremaux (valery.fremaux@gmail.com)
     */
    public function set_field($table, $field, $value, $params, $pkfield = 'id', $database = '') {
        global $SHOWSQL;

        $result = false;

        $rec = $this->get_record($table, $params, '*', $database);
        if (is_object($rec)) {
            if (!isset($rec->$field)) {
                debugging("DB Error : missing field $field in object ", 1);
            }
            $rec->$field = $value;
            $result = $this->update_record($table, $rec, $pkfield, $database);
        } else {
            if ($SHOWSQL) echo "Existing record not found ";
        }
        return $result;
    }

    /**
     * get all records from a result (not in a very scalable way though)
     * @param string $table        table name
     * @param array $params        where params table
     * @param string $sort        sort colons
     * @param string $fields    fieldset
     * @param string $database     extra database name if user has access to
     * @return array    Array of objects keyed by first field value
     * @author Valery Fremaux (valery.fremaux@gmail.com)
     */
    public function get_records($table, $params, $sort = '', $fields = '*', $database = '') {
        global $SHOWSQL;
        
        $tablename = $this->format_table_name($table, $database);

        if (empty($fields)) {
            debugging('DB fields cannot be empty in a select', 1);
            die;
        }

        $sortclause = (!empty($sort)) ? " ORDER BY $sort " : '';

        $paramsql[] = " 1 = 1 ";
        foreach ($params as $key => $value) {
            if (is_numeric($value)) {
                $paramsql[] = " `$key` = $value ";
            } else {
                $paramsql[] = " `$key` = '$value' ";
            }
        }
        $where = implode(' AND ', $paramsql);

        $sql = "
            SELECT 
                $fields
            FROM
                $tablename
            WHERE
                $where
            $sortclause
        ";
        if ($SHOWSQL) echo "get_records SQL : $sql <br/>";
        $result = Database::query($sql, $this->_cnx);
        if ($result) {
            while ($rec = Database::fetch_assoc($result)) {
                $fields = array_keys($rec);
                $key = array_shift($fields);
                $records[$rec[$key]] = (object)$rec;
            }
            return $records;
        } else {
            debugging('SQL ERROR IN '.$sql, 1);
        }
        return false;
    }

    /**
     * get all records from a result (not in a very scalable way though)
     * @param string $table        table name
     * @param string $select       select statement
     * @param array $fooparams     not used
     * @param string $sort         sort colons
     * @param string $fields       output fieldset
     * @param string $database     extra database name if user has access to
     * @return array    Array of objects keyed by first field value
     * @author Valery Fremaux (valery.fremaux@gmail.com)
     */
    public function get_records_select($table, $select, $fooparams, $sort = '', $fields = '*', $database = '') {
        global $SHOWSQL;
        
        $tablename = $this->format_table_name($table, $database);

        if (empty($fields)) {
            debugging('DB fields cannot be empty in a select', 1);
            die;
        }

        $sortclause = (!empty($sort)) ? " ORDER BY $sort " : '' ;

        $sql = "
            SELECT 
                $fields
            FROM
                $tablename
            WHERE
                $select
            $sortclause
        ";
        if ($SHOWSQL) echo "get_records_select SQL : $sql <br/>";
        $result = Database::query($sql, $this->_cnx);
        if ($result) {
            while ($rec = Database::fetch_assoc($result)) {
                $fields = array_keys($rec);
                $key = array_shift($fields);
                $records[$rec[$key]] = (object)$rec;
            }
            return $records;
        } else {
            debugging('SQL ERROR IN '.$sql, 1);
        }
        return false;
    }

    /**
     * get all records from a result (not in a very scalable way though)
     * @param string $table        table name
     * @param array $params        where params table
     * @param string $sort        sort colons
     * @param string $fields    fieldset
     * @param string $database     extra database name if user has access to
     * @return array    Array of objects keyed by first field value
     * @author Valery Fremaux (valery.fremaux@gmail.com)
     */
    public function get_records_sql($sql, $database = '') {
        global $SHOWSQL;

        if (empty($sql)) {
            debugging('DB Error : Empty query', 1);
            die;
        }

        // process query table name inserts (as {tablename} in query)

        preg_match_all('/\{(\w+?)\}/', $sql, $matches);

        if (!empty($matches[1])) {
            foreach ($matches[1] as $table) {
                $tablename = $this->format_table_name($table, $database);
                $sql = str_replace('{'.$table.'}', $tablename, $sql);
            }
        }
        if ($SHOWSQL) echo "get_records_sql SQL : $sql <br/>";
        $result = Database::query($sql, $this->_cnx);
        if ($result) {
            $records = array();
            while ($rec = Database::fetch_assoc($result)) {
                $keys = array_keys($rec);
                $fkey = array_shift($keys);
                $records[$rec[$fkey]] = (object)$rec;
            }
            return $records;
        }
        return false;
    }

    /**
     * get all records from a result (not in a very scalable way though)
     * @param string $table        table name
     * @param array $params        where params table
     * @param string $database     extra database name if user has access to
     * @return array    Array of objects keyed by first field value
     * @author Valery Fremaux (valery.fremaux@gmail.com)
     */
    public function delete_records($table, $params, $database = '') {
        global $SHOWSQL;
        
        $tablename = $this->format_table_name($table, $database);

        $paramsql[] = " 1 = 1 ";
        foreach ($params as $key => $value) {
            if (is_numeric($value)) {
                $paramsql[] = " `$key` = $value ";
            } else {
                $paramsql[] = " `$key` = '$value' ";
            }
        }
        $where = implode(' AND ', $paramsql);

        $sql = "
            DELETE FROM
                $tablename
            WHERE
                $where
        ";
        if ($SHOWSQL) echo "delete_records SQL : $sql <br/>";
        $result = Database::query($sql, $this->_cnx);
        if ($result) {
            return true;
        } else {
            debugging('SQL ERROR IN '.$sql, 1);
        }
        return false;
    }

    /**
     * get all records from a result (not in a very scalable way though)
     * @param string $table     table name
     * @param string select     select statement
     * @param array $fooparams  not used
     * @param string $database     extra database name if user has access to
     * @return boolean          true if success
     * @author Valery Fremaux (valery.fremaux@gmail.com)
     */
    public function delete_records_select($table, $select, $fooparams, $database = '') {
        global $SHOWSQL;

        $tablename = $this->format_table_name($table, $database);

        $sql = "
            DELETE 
            FROM
                $tablename
            WHERE
                $select
        ";
        if ($SHOWSQL) echo "delete_records_select SQL : $sql <br/>";
        $result = Database::query($sql, $this->_cnx);
        if ($result) {
            return true;
        } else {
            debugging('SQL ERROR IN '.$sql, 1);
        }
        return false;
    }

    /**
     * counts all records from an SQL statement
     * @param string $table        table name
     * @param array $params        where params table
     * @param string $sort        sort colons
     * @param string $fields    fieldset
     * @return array    Array of objects keyed by first field value
     * @author Valery Fremaux (valery.fremaux@gmail.com)
     */
    public function count_records_sql($sql, $database = '') {

        if (empty($sql)){
            debugging('DB Error : Empty query', 1);
        }

        // process query table name inserts (as {tablename} in query)

        preg_match_all('/\{(\w+?)\}/', $sql, $matches);
        
        if (!empty($matches[1])){
            foreach($matches[1] as $table){
                $tablename = $this->format_table_name($table, $database);
                $sql = str_replace('{'.$table.'}', $tablename, $sql);
            }
        }

        $result = Database::query($sql, $this->_cnx);
        if (!$result){
            debugging('SQL ERROR IN '.$sql, 1);
        }
        
        $counter = Database::fetch_array($result);
        
        return $counter[0];
    }

    /**
     * check existance (and unicity) of a record
     *
     */
    function record_exists($table, $params, $datafield = 'id', $database = '') {
        global $SHOWSQL;

        $tablename = $this->format_table_name($table, $database);

        $paramsql[] = " 1 = 1 ";
        foreach ($params as $key => $value) {
            if (is_numeric($value)) {
                $paramsql[] = " `$key` = $value ";
            } else {
                $paramsql[] = " `$key` = '$value' ";
            }
        }
        $where = implode(' AND ', $paramsql);

        $sql = "
            SELECT 
                $datafield
            FROM
                $tablename
            WHERE
                $where
        ";
        if ($SHOWSQL) echo "record_exists SQL : $sql <br/>";
        $result = Database::query($sql, $this->_cnx);
        if ($result) {
            $rows = mysql_num_rows($result);
            if ($rows == 1) {
                return true;
            }
        }

        return false;
    }

    /**
    * Inserts a record in DB
    *
    *
    */
    public function insert_record($table, $record, $database = '') {
        return $this->insert_record_raw($table, (array)$record, $database);
    }

    /**
    * Inserts an assoc in DB
    *
    *
    */
    public function insert_record_raw($table, $recordarr, $database = '') {
        global $SHOWSQL;
        static $TABLEFIELDS;

        $tablename = $this->format_table_name($table, $database);

        // Add to some cache
        if (empty($TABLEFIELDS[$table])) {
            $TABLEFIELDS[$table] = Database::get_fields($table, '', '', false, $this->_cnx);
        }

        // purge ande filter record from extraneous fields
        foreach (array_keys($recordarr) as $key){
            if (!in_array($key, $TABLEFIELDS[$table])){
                unset($recordarr[$key]);
            }
        }

        if (empty($recordarr) || empty($tablename)) {
            return false;
        }
        $filtered_attributes = array();
        foreach($recordarr as $key => $value) {
            // Not Reliable chamilo API function. Unexpected results
            // $filtered_attributes[$key] = "'".Database::escape_string($value)."'";
            $filtered_attributes[$key] = "'".$value."'";
       }

        $params = array_keys($filtered_attributes); //@todo check if the field exists in the table we should use a describe of that table
        $values = array_values($filtered_attributes);
        if (!empty($params) && !empty($values)) {
            $sql = '
                INSERT INTO 
                    '.$tablename.' 
                        ('.implode(',',$params).') 
                VALUES 
                    ('.implode(',',$values).')
            ';
            if ($SHOWSQL) echo "insert_record_raw SQL : $sql <br/>";
            $result = Database::query($sql, $this->_cnx);
            return  mysql_insert_id($this->_cnx);
        }
        return false;
    }

   /**
    * Executes a raw SQL statement
    * @param string $sql
    */
    public function execute_sql($sql) {
        return Database::query($sql, $this->_cnx);
    }


    /**
    * Updates a record in DB naming the primary key field to be used
    * @param string $table
    * @param object $record
    * @param string $pkfield primary key field name
    */
    public function update_record($table, $record, $pkfield, $database = '') {
        global $SHOWSQL;

        $tablename = $this->format_table_name($table, $database);

        $recordarr = (array)$record;

        if (empty($record->$pkfield)) {
            debugging("DB Error : Cannot update record without primary key value $pkfield ", 1);
            print_r($record);
            return false;
        }

        // purge ande filter record from extraneous fields
        $tablefields = Database::get_fields($table, '', '', false, $this->_cnx);
        foreach (array_keys($recordarr) as $key) {
            if (!in_array($key, $tablefields)) {
                unset($recordarr[$key]);
            }
        }

        // remove key from field list to update
        unset($recordarr['$pkfield']); 

        $where_conditions = array(" $pkfield = ? " => array($record->$pkfield));
        if (!empty($tablename) && !empty($recordarr)) {
            $update_sql = '';
            //Cleaning attributes
            $count = 1;
            foreach ($recordarr as $key => $value) {
                $value = Database::escape_string($value);
                $update_sql .= "$key = '$value' ";
                if ($count < count($recordarr)) {
                    $update_sql.=', ';
                }
                $count++;
            }
            if (!empty($update_sql)) {
                // Parsing and cleaning the where conditions
                $where = Database::parse_where_conditions($where_conditions);
                $sql = "
                    UPDATE 
                        $tablename
                    SET 
                        $update_sql 
                    $where 
                ";
                if ($SHOWSQL) echo "update_record SQL : $sql<br/>";
                $result = Database::query($sql, $this->_cnx);
                return mysql_affected_rows($this->_cnx);
            }
        }
        return false;
    }

    /**
     *	Structures a database and table name to ready them
     *	for querying. The database parameter is considered not glued,
     *	just plain e.g. COURSE001
     */
    public function format_table_name($table, $database = '') {
        if (empty($database)) $database = $this->_database;
        $table_name =  '`'.$database.'`.`'.$this->_configuration['table_prefix'].$table.'`';
        return $table_name;
    }

}
//end class DatabaseManager
