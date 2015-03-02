<?php
/* For licensing terms, see /license.txt */
/**
 *  This is the main database library for Chamilo.
 *  Include/require it in your code to use its functionality.
 *
 *  This library now uses a Doctrine DBAL Silex service provider
 *
 * @package chamilo.library
 */

/**
 * Database class definition
 * @package chamilo.database
 */
<<<<<<< HEAD
class Database
{
=======
class Database {

    /* Variable use only in the installation process to log errors. See the Database::query function */
    static $log_queries = false;

    /*
        Accessor methods
        Usually, you won't need these directly but instead
        rely on of the get_xxx_table methods.
    */

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    /**
     * The main connection
     *
     * @var \Doctrine\DBAL\Connection
     */
    private static $db;

    /**
     * Read connection
     *
     * @var \Doctrine\DBAL\Connection
     */
    private static $connectionRead;

    /**
     * Write connection
     *
     * @var \Doctrine\DBAL\Connection
     */
    private static $connectionWrite;

    /**
     * Constructor
     *
     * @param $db \Doctrine\DBAL\Connection
     * @param array $dbs
     */
    public function __construct($db, $dbs)
    {
        self::setDatabase($db, $dbs);
    }

    /**
     * @param $db
     * @param $dbs
     */
    public function setDatabase($db, $dbs)
    {
        self::$db = $db;

        // Using read/write connections see the services.php file
        self::$connectionRead = isset($dbs['db_read']) ? $dbs['db_read'] : $db;
        self::$connectionWrite = isset($dbs['db_write']) ? $dbs['db_write'] : $db;
    }

    /**
     * Return current connection
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection()
    {
        return self::$db;
    }

    /* Variable use only in the installation process to log errors. See the Database::query function */
    // static $log_queries = false;

    /**
     * Returns the name of the main database.
     * @return string
     */
    public static function get_main_database()
    {
        return self::$db->getDatabase();
    }

    /**
     *    The glue is the string needed between database and table.
     *    The trick is: in multiple databases, this is a period (with backticks).
     *    In single database, this can be e.g. an underscore so we just fake
     *    there are multiple databases and the code can be written independent
     *    of the single / multiple database setting.
     *    @return string
     */
    public static function get_database_glue()
    {
        return `.`;
    }

    /*
        Table name methods
        Use these methods to get table names for queries,
        instead of constructing them yourself.

        Backticks automatically surround the result,
        e.g. COURSE_NAME.link
        so the queries can look cleaner.

        Example:
        $table = Database::get_course_table(TABLE_DOCUMENT);
        $sql_query = "SELECT * FROM $table WHERE $condition";
        $sql_result = Database::query($sql_query);
        $result = Database::fetch_array($sql_result);
    */

    /**
<<<<<<< HEAD
     * This function returns the correct complete name of any table of the main
=======
     * A more generic method than the other get_main_xxx_table methods,
     * This one returns the correct complete name of any table of the main
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
     * database of which you pass the short name as a parameter.
     * Define table names as constants in this library and use them
     * instead of directly using magic words in your tool code.
     *
     * @param string $short_table_name, the name of the table
     * @return string
     */
<<<<<<< HEAD
    public static function get_main_table($short_table_name)
    {
        return self::format_table_name(self::get_main_database(), $short_table_name);
    }

    /**
     * This method returns the correct complete name of any course table of
=======
    public static function get_main_table($short_table_name) {
        return self::format_table_name(
          self::get_main_database(),
          $short_table_name);
    }

    /**

     * A more generic method than the older get_course_xxx_table methods,
     * This one can return the correct complete name of any course table of
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
     * which you pass the short name as a parameter.
     * Define table names as constants in this library and use them
     * instead of directly using magic words in your tool code.
     *
     * @param string $short_table_name, the name of the table
<<<<<<< HEAD
     * @return string
=======
     * @param string $database_name, optional, name of the course database
     * - if you don't specify this, you work on the current course.
     */
    public static function get_course_table($short_table_name, $extra = null) {
        //forces fatal errors so we can debug more easily
        if (!empty($extra)) {
            var_dump($extra);
            //@todo remove this
            echo "<h3>Dev Message: get_course_table() doesn't have a 2nd parameter</h3>";
            //exit;
        }
    	return self::format_table_name(self::get_main_database(), DB_COURSE_PREFIX.$short_table_name);
        //return self::format_glued_course_table_name(self::fix_database_parameter($database_name), $short_table_name);
    }

    /**
     * This generic method returns the correct and complete name of any
     * statistic table of which you pass the short name as a parameter.
     * Please, define table names as constants in this library and use them
     * instead of directly using magic words in your tool code.
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
     *
     */
    public static function get_course_table($short_table_name)
    {
        return self::format_table_name(self::get_main_database(), DB_COURSE_PREFIX.$short_table_name);
    }

<<<<<<< HEAD
=======
    public static function get_course_chat_connected_table($database_name = '') {
        return self::format_glued_course_table_name(self::fix_database_parameter($database_name), TABLE_CHAT_CONNECTED);
    }

    /*
        Query methods
        These methods execute a query and return the result(s).
    */

    /**
     *	@return a list (array) of all courses.
     * 	@todo shouldn't this be in the course.lib.php script?
     */
    public static function get_course_list() {
        $table = self::get_main_table(TABLE_MAIN_COURSE);
        return self::store_result(self::query("SELECT *, id as real_id FROM $table"));
    }

    /**
     *	Returns an array with all database fields for the specified course.
     *
     *	@param string The real (system) course code (main course table ID)
     * 	@todo shouldn't this be in the course.lib.php script?
     */
    public static function get_course_info($course_code) {
        $course_code = self::escape_string($course_code);
        $table = self::get_main_table(TABLE_MAIN_COURSE);
        $result = self::generate_abstract_course_field_names(
            self::fetch_array(self::query("SELECT *, id as real_id FROM $table WHERE code = '$course_code'")));
        return $result === false ? array('db_name' => '') : $result;
    }

    /**
     * Gets user details from the "user" table
     * @param $user_id (integer): the id of the user
     * @return $user_info (array): user_id, lname, fname, username, email, ...
     * @author Patrick Cool <patrick.cool@UGent.be>, expanded to get info for any user
     * @author Roan Embrechts, first version + converted to Database API
     * @version 30 September 2004
     * @deprecated use api_get_user_info();
     * @desc find all the information about a specified user. Without parameter this is the current user.
     * @todo shouldn't this be in the user.lib.php script?
     */
    public static function get_user_info_from_id($user_id = '') {
        if (empty($user_id)) {
            return $GLOBALS['_user'];
        }
        $table = self::get_main_table(TABLE_MAIN_USER);
        $user_id = self::escape_string($user_id);
        return self::generate_abstract_user_field_names(
            self::fetch_array(self::query("SELECT * FROM $table WHERE user_id = '$user_id'")));
    }

    /**
     * Returns course code from a given gradebook category's id
     * @param int  Category ID
     * @return string  Course code
     * @todo move this function in a gradebook-related library
     */
    public static function get_course_by_category($category_id) {
        $category_id = intval($category_id);
        $info = self::fetch_array(self::query('SELECT course_code FROM '.self::get_main_table(TABLE_MAIN_GRADEBOOK_CATEGORY).' WHERE id='.$category_id), 'ASSOC');
        return $info ? $info['course_code'] : false;
    }

    /**
     *	This method creates an abstraction layer between database field names
     *	and field names expected in code.
     *
     *	This approach helps when changing database names.
     *	It's also useful now to get rid of the 'franglais'.
     *
     *	@todo	add more array entries to abstract course info from field names
     *	@author	Roan Embrechts
     *
     * 	@todo What's the use of this method. I think this is better removed.
     * 		  There should be consistency in the variable names and the
     *            use throughout the scripts
     * 		  for the database name we should consistently use or db_name
     *            or database (db_name probably being the better one)
     */
    public static function generate_abstract_course_field_names($result_array) {
        $visual_code = isset($result_array['visual_code']) ? $result_array['visual_code'] : null;
        $code        = isset($result_array['code']) ? $result_array['code'] : null;
        $title       = isset($result_array['title']) ? $result_array['title'] : null;
        $db_name     = isset($result_array['db_name']) ? $result_array['db_name'] : null;
        $category_code = isset($result_array['category_code']) ? $result_array['category_code'] : null;
        $result_array['official_code'] = $visual_code;
        $result_array['visual_code']   = $visual_code;
        $result_array['real_code']     = $code;
        $result_array['system_code']   = $code;
        $result_array['title']         = $title;
        $result_array['database']      = $db_name;
        $result_array['faculty']       = $category_code;
        //$result_array['directory'] = $result_array['directory'];
        /*
        still to do: (info taken from local.inc.php)

        $_course['id'          ]         = $cData['cours_id'         ]; //auto-assigned integer
        $_course['name'        ]         = $cData['title'            ];
        $_course['official_code']        = $cData['visual_code'        ]; // use in echo
        $_course['sysCode'     ]         = $cData['code'             ]; // use as key in db
        $_course['path'        ]         = $cData['directory'        ]; // use as key in path
        $_course['dbName'      ]         = $cData['db_name'           ]; // use as key in db list
        $_course['dbNameGlu'   ]         = $_configuration['table_prefix'] . $cData['dbName'] . $_configuration['db_glue']; // use in all queries
        $_course['titular'     ]         = $cData['tutor_name'       ];
        $_course['language'    ]         = $cData['course_language'   ];
        $_course['extLink'     ]['url' ] = $cData['department_url'    ];
        $_course['extLink'     ]['name'] = $cData['department_name'];
        $_course['categoryCode']         = $cData['faCode'           ];
        $_course['categoryName']         = $cData['faName'           ];

        $_course['visibility'  ]         = (bool) ($cData['visibility'] == 2 || $cData['visibility'] == 3);
        $_course['registrationAllowed']  = (bool) ($cData['visibility'] == 1 || $cData['visibility'] == 2);
        */
        return $result_array;
    }

    /**
     *	This method creates an abstraction layer between database field names
     *	and field names expected in code.
     *
     *	This helps when changing database names.
     *	It's also useful now to get rid of the 'franglais'.
     *
     *	@todo add more array entries to abstract user info from field names
     *	@author Roan Embrechts
     *	@author Patrick Cool
     *
     * 	@todo what's the use of this function. I think this is better removed.
     * 		There should be consistency in the variable names and the use throughout the scripts
     */
    public static function generate_abstract_user_field_names($result_array) {
        $result_array['firstName'] 		= $result_array['firstname'];
        $result_array['lastName'] 		= $result_array['lastname'];
        $result_array['mail'] 			= $result_array['email'];
        #$result_array['picture_uri'] 	= $result_array['picture_uri'];
        #$result_array ['user_id']		= $result_array['user_id'];
        return $result_array;
    }

    /**
     * Counts the number of rows in a table
     * @param string $table The table of which the rows should be counted
     * @return int The number of rows in the given table.
     * @deprecated
     */
    public static function count_rows($table) {
        $obj = self::fetch_object(self::query("SELECT COUNT(*) AS n FROM $table"));   //
        return $obj->n;
    }

    /*
        An intermediate API-layer between the system and the dabase server.
    */

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    /**
     * Returns the number of affected rows in the last database operation.
     * @param \Doctrine\DBAL\Driver\Statement $result
     * @return int
     */
<<<<<<< HEAD
    public static function affected_rows(\Doctrine\DBAL\Driver\Statement $result = null)
    {
        return $result->rowCount();
        //return self::use_default_connection($connection) ? mysql_affected_rows() : mysql_affected_rows($connection);
=======
    public static function connect($parameters = array()) {
        // A MySQL-specific implementation.
        if (!isset($parameters['server'])) {
            $parameters['server'] = @ini_get('mysql.default_host');
            if (empty($parameters['server'])) {
                $parameters['server'] = 'localhost:3306';
            }
        }
        if (!isset($parameters['username'])) {
            $parameters['username'] = @ini_get('mysql.default_user');
        }
        if (!isset($parameters['password'])) {
            $parameters['password'] = @ini_get('mysql.default_password');
        }
        if (!isset($parameters['new_link'])) {
            $parameters['new_link'] = false;
        }
        if (!isset($parameters['client_flags'])) {
            $parameters['client_flags'] = 0;
        }

        $persistent = isset($parameters['persistent']) ? $parameters['persistent'] : null;
        $server = isset($parameters['server']) ? $parameters['server'] : null;
        $username = isset($parameters['username']) ? $parameters['username'] : null;
        $password = isset($parameters['password']) ? $parameters['password'] : null;
        $client_flag = isset($parameters['client_flags']) ? $parameters['client_flags'] : null;
        $new_link = isset($parameters['new_link']) ? $parameters['new_link'] : null;
        $client_flags = isset($parameters['client_flags']) ? $parameters['client_flags'] : null;
        return $persistent
            ? mysql_pconnect($server, $username, $password, $client_flags)
            : mysql_connect($server, $username, $password, $new_link, $client_flags);
    }

    /**
     * Returns error number from the last operation done on the database server.
     * @param resource $connection (optional)	The database server connection,
     * for detailed description see the method query().
     * @return int Returns the error number from the last database (operation, or 0 (zero) if no error occurred.
     */
    public static function errno($connection = null) {
        return self::use_default_connection($connection) ? mysql_errno() : mysql_errno($connection);
    }

    /**
     * Returns error text from the last operation done on the database server.
     * @param resource $connection (optional)	The database server connection, for detailed description see the method query().
     * @return string Returns the error text from the last database operation, or '' (empty string) if no error occurred.
     */
    public static function error($connection = null) {
        return self::use_default_connection($connection) ? mysql_error() : mysql_error($connection);
    }

    /**
     * Escape MySQL wildchars _ and % in LIKE search
     * @param string            The string to escape
     * @return string           The escaped string
     */
    public static function escape_sql_wildcards($in_txt) {
        $out_txt = api_preg_replace("/_/", "\_", $in_txt);
        $out_txt = api_preg_replace("/%/", "\%", $out_txt);
        return $out_txt;
    }

    /**
     * Escapes a string to insert into the database as text
     * @param string							The string to escape
     * @param resource $connection (optional)	The database server connection, for detailed description see the method query().
     * @return string							The escaped string
     * @author Yannick Warnier <yannick.warnier@dokeos.com>
     * @author Patrick Cool <patrick.cool@UGent.be>, Ghent University
     */
    public static function escape_string($string, $connection = null) {
        return get_magic_quotes_gpc()
            ? (self::use_default_connection($connection)
                ? mysql_real_escape_string(stripslashes($string))
                : mysql_real_escape_string(stripslashes($string), $connection))
            : (self::use_default_connection($connection)
                ? mysql_real_escape_string($string)
                : mysql_real_escape_string($string, $connection));
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    }

    /**
     * Gets the array from a SQL result (as returned by Database::query) - help achieving database independence
     * @param resource        The result from a call to sql_query (e.g. Database::query)
     * @param string        Optional: "ASSOC","NUM" or "BOTH", as the constant used in mysql_fetch_array.
     * @return array        Array of results as returned by php
     * @author Yannick Warnier <yannick.warnier@beeznest.com>
     */
    public static function fetch_array(\Doctrine\DBAL\Driver\Statement $result, $option = 'BOTH')
    {
        if ($result === false) {
            return array();
        }
        return $result->fetch(self::customOptionToDoctrineOption($option));

        /*return $option == 'ASSOC' ? mysql_fetch_array($result, MYSQL_ASSOC) : ($option == 'NUM' ? mysql_fetch_array(
            $result,
            MYSQL_NUM
        ) : mysql_fetch_array($result));*/
    }

    /**
     * Gets an associative array from a SQL result (as returned by Database::query).
     * This method is equivalent to calling Database::fetch_array() with 'ASSOC' value for the optional second parameter.
     * @param resource $result    The result from a call to sql_query (e.g. Database::query).
     * @return array            Returns an associative array that corresponds to the fetched row and moves the internal data pointer ahead.
     */
    public static function fetch_assoc(\Doctrine\DBAL\Driver\Statement $result)
    {
        return $result->fetch(PDO::FETCH_ASSOC);
        //return mysql_fetch_assoc($result);
    }

    /**
     * Gets the next row of the result of the SQL query (as returned by Database::query) in an object form
     * @param    \Doctrine\DBAL\Driver\Statement    The result from a call to Database::query())
     * @param    string        Optional class name to instanciate
     * @param    array        Optional array of parameters
     * @return    object        Object of class StdClass or the required class, containing the query result row
     * @author    Yannick Warnier <yannick.warnier@dokeos.com>
     */
    public static function fetch_object(\Doctrine\DBAL\Driver\Statement $result)
    {
        // Waiting for http://www.doctrine-project.org/jira/browse/DBAL-544 in order to know which constant use.
        //return $result->fetch(\Doctrine\ORM\Query::HYDRATE_OBJECT);
        return $result->fetch(PDO::FETCH_OBJ);

        /*return !empty($class) ? (is_array($params) ? mysql_fetch_object($result, $class, $params) : mysql_fetch_object(
            $result,
            $class
        )) : mysql_fetch_object($result);*/
    }

    /**
     * Gets the array from a SQL result (as returned by Database::query) - help achieving database independence
     * @param  \Doctrine\DBAL\Driver\Statement    The result from a call to Database::query())
     * @return array        Array of results as returned by php
     */
    public static function fetch_row(\Doctrine\DBAL\Driver\Statement $result)
    {
        return $result->fetch(PDO::FETCH_NUM);
        //return mysql_fetch_row($result);
    }

    /**
     * Gets the ID of the last item inserted into the database
     * @return int The last ID as returned by the DB function
     */
    public static function insert_id()
    {
        return self::$connectionWrite->lastInsertId();
    }

    /**
     * Gets the number of rows from the last query result - help achieving database independence
     * @param \Doctrine\DBAL\Driver\Statement
     * @return integer The number of rows contained in this result
     **/
    public static function num_rows(\Doctrine\DBAL\Driver\Statement $result)
    {
        return $result->rowCount();
    }

    /**
     * Acts as the relative *_result() function of most DB drivers and fetches a
     * specific line and a field
     * @param    \Doctrine\DBAL\Driver\Statement     The database resource to get data from
     * @param    integer        The row number
     * @param    string        Optional field name or number
     * @return    mixed        One cell of the result, or FALSE on error
     */
    public static function result(\Doctrine\DBAL\Driver\Statement $resource, $row, $field = 0)
    {
        if ($resource->rowCount() > 0) {
            $result = $resource->fetchAll(PDO::FETCH_BOTH);
            return $result[$row][$field];
        }

        return null;
    }

    /**
     * Frees all the memory associated with the provided result identifier.
     * @return bool        Returns TRUE on success or FALSE on failure.
     * Notes: Use this method if you are concerned about how much memory is being used for queries that return large result sets.
     * Anyway, all associated result memory is automatically freed at the end of the script's execution.
     */
    public static function free_result(\Doctrine\DBAL\Driver\Statement $result)
    {
        $result->closeCursor();
        //return mysql_free_result($result);
    }

    /**
     * Detects if a query is going to modify something in the database in order to use the write connection
     * @param string $query
     * @return bool
     */
    public static function isWriteQuery($query)
    {
        $isWriteQuery = preg_match("/UPDATE(.*) FROM/i", $query) ||
            preg_match("/INSERT INTO/i", $query) ||
            preg_match("/REPLACE INTO/i", $query) ||
            preg_match("/DELETE FROM/i", $query);
        return $isWriteQuery;
    }

    /**
     * Escapes a string to insert into the database as text
     * @param string The string to escape
     * @return string The escaped string
     */
    public static function escape_string($string)
    {
        /* The pdo::quote function adds a "'" character we need to remove that '
           because in Chamilo, developers builds a query like this:
           $sql = "SELECT * FROM $table WHERE id = 'Database::escape_string($id)'";
           otherwise we will have an error because the query will be:
           SELECT * FROM user WHERE id = ''1'' instead of
           SELECT * FROM user WHERE id = '1'
        */
        // $string = '_@_'.self::$db->quote($string).'_@_';

        $string = self::$db->quote($string);
        return trim($string, "'");
        return $string;
    }

    /**
     * Executes a query in the database
     * @author Julio Montoya
     * @param string $query The SQL query
     * @return \Doctrine\DBAL\Driver\Statement
     */
    public static function query($query)
    {
        $isWriteQuery = self::isWriteQuery($query);
        if ($isWriteQuery) {
            $connection = self::$connectionWrite;
        } else {
            $connection = self::$connectionRead;
        }
        /* The solution below does not work because there are some case where we use the "LIKE" option like this:
            $sql  = 'SELECT * FROM user WHERE id LIKE "%'.Database::escape_string($id).' %" ;

            Chamilo queries are formed in many ways:
            $sql  = "SELECT * FROM user WHERE id = '".Database::escape_string($id)."'; or
            $sql  = 'SELECT * FROM user WHERE id = '.Database::escape_string($id).';

<<<<<<< HEAD
            The problem here is that the function escape_string() calls the quote function that adds a "'" string.
            Instead of this we're adding a identifier __@__ so we can identify those cases and replace with a simple '
        */
        //var_dump($query);
        /*$query = str_replace(
            array(
                "\"_@_'",
                "'_@_\"",
                "'_@_'",
                "_@_'",
                "'_@_",
            ),
            "'",
            $query
        );*/
        //var_dump($query);
        return $connection->executeQuery($query);

    }

    public static function customOptionToDoctrineOption($option)
    {
        switch($option) {
            case 'ASSOC':
                return PDO::FETCH_ASSOC;
                break;
            case 'NUM':
                return PDO::FETCH_NUM;
                break;
            case 'BOTH':
            default:
                return PDO::FETCH_BOTH;
                break;
=======
    /**
     * This method returns a resource
     * Documentation has been added by Arthur Portugal
     * Some adaptations have been implemented by Ivan Tcholakov, 2009, 2010
     * @author Olivier Brouckaert
     * @param string $query						The SQL query
     * @param resource $connection (optional)	The database server (MySQL) connection.
     * 											If it is not specified, the connection opened by mysql_connect() is assumed.
     * 											If no connection is found, the server will try to create one as if mysql_connect() was called with no arguments.
     * 											If no connection is found or established, an E_WARNING level error is generated.
     * @param string $file (optional)			On error it shows the file in which the error has been trigerred (use the "magic" constant __FILE__ as input parameter)
     * @param string $line (optional)			On error it shows the line in which the error has been trigerred (use the "magic" constant __LINE__ as input parameter)
     * @return resource							The returned result from the query
     * Note: The parameter $connection could be skipped. Here are examples of this method usage:
     * Database::query($query);
     * $result = Database::query($query);
     * Database::query($query, $connection);
     * $result = Database::query($query, $connection);
     * The following ways for calling this method are obsolete:
     * Database::query($query, __FILE__, __LINE__);
     * $result = Database::query($query, __FILE__, __LINE__);
     * Database::query($query, $connection, __FILE__, __LINE__);
     * $result = Database::query($query, $connection, __FILE__, __LINE__);
     */
    public static function query($query, $connection = null, $file = null, $line = null) {
        $use_default_connection = self::use_default_connection($connection);
        if ($use_default_connection) {
            // Let us do parameter shifting, thus the method would be similar
            // (in regard to parameter order) to the original function mysql_query().
            $line = $file;
            $file = $connection;
            $connection = null;
        }
        //@todo remove this before the stable release

        //Check if the table contains a c_ (means a course id)
        if (api_get_setting('server_type')==='test' && strpos($query, 'c_')) {
        	//Check if the table contains inner joins
        	if (
                strpos($query, 'assoc_handle') === false &&
                strpos($query, 'olpc_peru_filter') === false &&
                strpos($query, 'allow_public_certificates') === false &&
                strpos($query, 'DROP TABLE IF EXISTS') === false &&
                strpos($query, 'thematic_advance') === false &&
                strpos($query, 'thematic_plan') === false &&
                strpos($query, 'track_c_countries') === false &&
                strpos($query, 'track_c_os') === false &&
                strpos($query, 'track_c_providers') === false &&
                strpos($query, 'track_c_referers') === false &&
                strpos($query, 'track_c_browsers') === false &&
                strpos($query, 'settings_current') === false &&
                strpos($query, 'dokeos_classic_2D') === false &&
                strpos($query, 'cosmic_campus') === false &&
                strpos($query, 'static_') === false &&
                strpos($query, 'public_admin') === false &&
                strpos($query, 'chamilo_electric_blue') === false &&
                strpos($query, 'wcag_anysurfer_public_pages') === false &&
                strpos($query, 'specific_field') === false &&
        	    strpos($query, 'down_doc_path') === false &&
        		strpos($query, 'INNER JOIN') === false &&
        		strpos($query, 'inner join') === false &&
        		strpos($query, 'left join') === false &&
        		strpos($query, 'LEFT JOIN') === false &&
        		strpos($query, 'insert') 	=== false &&
        		strpos($query, 'INSERT') === false &&
        		strpos($query, 'ALTER') === false &&
        		strpos($query, 'alter') === false &&
        		strpos($query, 'c_id') === false &&
        		strpos($query, 'create table') === false &&
        		strpos($query, 'CREATE TABLE') === false &&
        		strpos($query, 'AUTO_INCREMENT') === false
        	) {
                //@todo remove this
                echo '<pre>';
                $message = '<h4>Dev message: please add the c_id field in this query or report this error in support.chamilo.org </h4>';
                $message .= $query;
                echo $message;
                echo '</pre>';
                //error_log($message);
        	}
        }

        if (!($result = $use_default_connection ? mysql_query($query) : mysql_query($query, $connection))) {
            $backtrace = debug_backtrace(); // Retrieving information about the caller statement.
            if (isset($backtrace[0])) {
                $caller = & $backtrace[0];
            } else {
                $caller = array();
            }
            if (isset($backtrace[1])) {
                $owner = & $backtrace[1];
            } else {
                $owner = array();
            }
            if (empty($file)) {
                $file = $caller['file'];
            }
            if (empty($line) && $line !== false) {
                $line = $caller['line'];
            }
            $type = isset($owner['type']) ? $owner['type'] : null;
            $function = $owner['function'];
            $class = isset($owner['class']) ? $owner['class'] : null;
            $server_type = api_get_setting('server_type');
            if (!empty($line) && !empty($server_type) && $server_type != 'production') {
                $info = '<pre>' .
                    '<strong>DATABASE ERROR #'.self::errno($connection).':</strong><br /> ' .
                    self::remove_XSS(self::error($connection)) . '<br />' .
                    '<strong>QUERY       :</strong><br /> ' .
                    self::remove_XSS($query) . '<br />' .
                    '<strong>FILE        :</strong><br /> ' .
                    (empty($file) ? ' unknown ' : $file) . '<br />' .
                    '<strong>LINE        :</strong><br /> ' .
                    (empty($line) ? ' unknown ' : $line) . '<br />';
                if (empty($type)) {
                    if (!empty($function)) {
                        $info .= '<strong>FUNCTION    :</strong><br /> ' . $function;
                    }
                } else {
                    if (!empty($class) && !empty($function)) {
                        $info .= '<strong>CLASS       :</strong><br /> ' . $class . '<br />';
                        $info .= '<strong>METHOD      :</strong><br /> ' . $function;
                    }
                }
                $info .= '</pre>';
                echo $info;
            }

            if (isset(self::$log_queries) && self::$log_queries) {
                error_log("----------------  SQL error ---------------- ");
                error_log($query);

                error_log('error #'.self::errno($connection));
                error_log('error: '.self::error($connection));

                $info = 'FILE: ' .(empty($file) ? ' unknown ' : $file);
                $info .= ' +'.(empty($line) ? ' unknown ' : $line);
                error_log($info);

                if (empty($type)) {
                    if (!empty($function)) {
                        $info = 'FUNCTION: ' . $function;
                        error_log($info);
                    }
                } else {
                    if (!empty($class) && !empty($function)) {
                        $info = 'CLASS: ' . $class.' METHOD: '.$function;
                        error_log($info);
                    }
                }
                error_log("---------------- end ----------------");
            }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        }
    }

    /**
     * Stores a query result into an array.
     * @param  \Doctrine\DBAL\Driver\Statement $result - the return value of the query
     * @param  option BOTH, ASSOC, or NUM
     * @return array - the value returned by the query
     */
    public static function store_result(\Doctrine\DBAL\Driver\Statement $result, $option = 'BOTH')
    {
        return $result->fetchAll(self::customOptionToDoctrineOption($option));
        /*
        var_dump($a );
        $array = array();
        if ($result !== false) { // For isolation from database engine's behaviour.
            while ($row = self::fetch_array($result, $option)) {
                $array[] = $row;
            }
        }
        return $array;*/
    }

    /*
        Private methods
        You should not access these from outside the class
        No effort is made to keep the names / results the same.
    */

    /**
     *    Structures a database and table name to ready them
     *    for querying. The database parameter is considered not glued,
     *    just plain e.g. COURSE001
     *   @todo not sure if we need this now
     */
<<<<<<< HEAD
    private static function format_table_name($database, $table)
    {
        /*$glue = '`.`';
        $table_name = '`'.$database.$glue.$table.'`';
        */
        return $table;
        //return $table_name;
=======
    private static function format_glued_course_table_name($database_name_with_glue, $table) {
        return '`'.$database_name_with_glue.$table.'`';
    }

    /**
     *	Structures a database and table name to ready them
     *	for querying. The database parameter is considered not glued,
     *	just plain e.g. COURSE001
     */
    private static function format_table_name($database, $table) {
        global $_configuration;
        if ($_configuration['single_database']) {
            $table_name =  '`'.$database.'`.`'.$table.'`';
        } else {
            $table_name =  '`'.$database.$_configuration['db_glue'].$table.'`';
        }
        return $table_name;
    }

    /**
     * This private method is to be used by the other methods in this class for
     * checking whether the input parameter $connection actually has been provided.
     * If the input parameter connection is not a resource or if it is not FALSE (in case of error)
     * then the default opened connection should be used by the called method.
     * @param resource/boolean $connection	The checked parameter $connection.
     * @return boolean						TRUE means that calling method should use the default connection.
     * 										FALSE means that (valid) parameter $connection has been provided and it should be used.
     */
    private static function use_default_connection($connection) {
        return !is_resource($connection) && $connection !== false;
    }

    /**
     * This private method tackles the XSS injections. It is similar to Security::remove_XSS() and works always,
     * including the time of initialization when the class Security has not been loaded yet.
     * @param string	The input variable to be filtered from XSS, in this class it is expected to be a string.
     * @return string	Returns the filtered string as a result.
     */
    private static function remove_XSS(& $var) {
        return class_exists('Security') ? Security::remove_XSS($var) : @htmlspecialchars($var, ENT_QUOTES, api_get_system_encoding());
    }

    /**
     * This private method encapsulates a table with relations between
     * conventional and MuSQL-specific encoding identificators.
     * @author Ivan Tcholakov
     */
    private static function & get_db_encoding_map() {
        static $encoding_map = array(
            'ARMSCII-8'    => 'armscii8',
            'BIG5'         => 'big5',
            'BINARY'       => 'binary',
            'CP866'        => 'cp866',
            'EUC-JP'       => 'ujis',
            'EUC-KR'       => 'euckr',
            'GB2312'       => 'gb2312',
            'GBK'          => 'gbk',
            'ISO-8859-1'   => 'latin1',
            'ISO-8859-2'   => 'latin2',
            'ISO-8859-7'   => 'greek',
            'ISO-8859-8'   => 'hebrew',
            'ISO-8859-9'   => 'latin5',
            'ISO-8859-13'  => 'latin7',
            'ISO-8859-15'  => 'latin1',
            'KOI8-R'       => 'koi8r',
            'KOI8-U'       => 'koi8u',
            'SHIFT-JIS'    => 'sjis',
            'TIS-620'      => 'tis620',
            'US-ASCII'     => 'ascii',
            'UTF-8'        => 'utf8',
            'WINDOWS-1250' => 'cp1250',
            'WINDOWS-1251' => 'cp1251',
            'WINDOWS-1252' => 'latin1',
            'WINDOWS-1256' => 'cp1256',
            'WINDOWS-1257' => 'cp1257'
        );
        return $encoding_map;
    }

    /**
     * A helper language id translation table for choosing some collations.
     * @author Ivan Tcholakov
     */
    private static function & get_db_collation_map() {
        static $db_collation_map = array(
            'german' => 'german2',
            'simpl_chinese' => 'chinese',
            'trad_chinese' => 'chinese',
            'turkce' => 'turkish'
        );
        return $db_collation_map;
    }

    /**
     * Constructs a MySQL-specific collation and checks whether it is supported by the database server.
     * @param string $db_encoding	A MySQL-specific encoding id, i.e. 'utf8'
     * @param string $language		A MySQL-compatible language id, i.e. 'bulgarian'
     * @return string				Returns a suitable default collation, for example 'utf8_general_ci', or NULL if collation was not found.
     * @author Ivan Tcholakov
     */
    private static function check_db_collation($db_encoding, $language) {
        if (empty($db_encoding)) {
            return null;
        }
        if (empty($language)) {
            $result = self::fetch_array(self::query("SHOW COLLATION WHERE Charset = '".self::escape_string($db_encoding)."' AND  `Default` = 'Yes';"), 'NUM');
            return $result ? $result[0] : null;
        }
        $collation = $db_encoding.'_'.$language.'_ci';
        $query_result = self::query("SHOW COLLATION WHERE Charset = '".self::escape_string($db_encoding)."';");
        while ($result = self::fetch_array($query_result, 'NUM')) {
            if ($result[0] == $collation) {
                return $collation;
            }
        }
        return null;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    }

    /*
        New useful DB functions
    */

    /**
     * Executes an insert to in the database (dbal already escape strings)
     * @param string table name
     * @param array An array of field and values
     * @param bool show query
     * @return int the id of the latest executed query
     */
    public static function insert($table_name, $attributes, $show_query = false)
    {
<<<<<<< HEAD
        $result = self::$connectionWrite->insert($table_name, $attributes);
        if ($result) {
=======
        if (empty($attributes) || empty($table_name)) {
            return false;
        }
        $filtred_attributes = array();
        foreach($attributes as $key => $value) {
            $filtred_attributes[$key] = "'".self::escape_string($value)."'";
        }
        //@todo check if the field exists in the table we should use a describe of that table
        $params = array_keys($filtred_attributes);
        $values = array_values($filtred_attributes);
        if (!empty($params) && !empty($values)) {
            $sql    = 'INSERT INTO '.$table_name.' ('.implode(',',$params).') VALUES ('.implode(',',$values).')';
            self::query($sql);
            if ($show_query) {
            	var_dump($sql);
            }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
            return self::insert_id();
        }
        return false;
    }

    /**
     * Experimental useful database finder
     * @todo lot of stuff to do here
     * @todo known issues, it doesn't work when using LIKE conditions
     * @example array('where'=> array('course_code LIKE "?%"'))
     * @example array('where'=> array('type = ? AND category = ?' => array('setting', 'Plugins'))
     * @example array('where'=> array('name = "Julio" AND lastname = "montoya"))
     */
    public static function select($columns, $table_name, $conditions = array(), $type_result = 'all', $option = 'ASSOC')
    {
        //$qb = self::$db->createQueryBuilder();

<<<<<<< HEAD
=======
    public static function select($columns, $table_name, $conditions = array(), $type_result = 'all', $option = 'ASSOC')
    {
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        $conditions = self::parse_conditions($conditions);

        //@todo we could do a describe here to check the columns ...
        $clean_columns = '';
        if (is_array($columns)) {
            $clean_columns = implode(',', $columns);
        } else {
            if ($columns == '*') {
                $clean_columns = '*';
            } else {
                $clean_columns = (string)$columns;
            }
        }

<<<<<<< HEAD
        /*$qb->select($clean_columns);
        $qb->from($table_name, 'table');
        $qb->orderBy('table.' . $sort_order, 'ASC');*/

        $sql = "SELECT $clean_columns FROM $table_name $conditions";
        //var_dump($sql);

=======
        $sql    = "SELECT $clean_columns FROM $table_name $conditions";
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        $result = self::query($sql);
        $array = array();

        if ($type_result == 'all') {
            while ($row = self::fetch_array($result, $option)) {
                if (isset($row['id'])) {
                    $array[$row['id']] = $row;
                } else {
                    $array[] = $row;
                }
            }
        } else {
            $array = self::fetch_array($result, $option);
        }

        return $array;
    }

    /**
     * Parses WHERE/ORDER conditions i.e array('where'=>array('id = ?' =>'4'), 'order'=>'id DESC'))
     * @todo known issues, it doesn't work when using LIKE conditions example: array('where'=>array('course_code LIKE "?%"'))
     * @param array
     * @return string
     * @todo lot of stuff to do here
<<<<<<< HEAD
     */
    static function parse_conditions($conditions)
    {
=======
    */
    static function parse_conditions($conditions) {
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        if (empty($conditions)) {
            return '';
        }
        $return_value = $where_return = '';
        foreach ($conditions as $type_condition => $condition_data) {
            if ($condition_data == false) {
                continue;
            }
            $type_condition = strtolower($type_condition);
            switch ($type_condition) {
                case 'where':
<<<<<<< HEAD
                    foreach ($condition_data as $condition => $value_array) {
                        if (is_array($value_array)) {
                            $clean_values = array();
                            foreach ($value_array as $item) {
=======

                    foreach ($condition_data as $condition => $value_array) {
                        if (is_array($value_array)) {
                            $clean_values = array();
                            foreach($value_array as $item) {
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
                                $item = Database::escape_string($item);
                                $clean_values[] = $item;
                            }
                        } else {
                            $value_array = Database::escape_string($value_array);
                            $clean_values = $value_array;
                        }

                        if (!empty($condition) && $clean_values != '') {
<<<<<<< HEAD
                            $condition = str_replace('%', "'@percentage@'", $condition); //replace "%"
                            $condition = str_replace("'?'", "%s", $condition);
                            $condition = str_replace("?", "%s", $condition);
=======
                            $condition = str_replace('%',"'@percentage@'", $condition); //replace "%"
                            $condition = str_replace("'?'","%s", $condition);
                            $condition = str_replace("?","%s", $condition);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

                            $condition = str_replace("@%s@", "@-@", $condition);
                            $condition = str_replace("%s", "'%s'", $condition);
                            $condition = str_replace("@-@", "@%s@", $condition);

                            //Treat conditons as string
                            $condition = vsprintf($condition, $clean_values);
<<<<<<< HEAD
                            $condition = str_replace('@percentage@', '%', $condition); //replace "%"
=======
                            $condition = str_replace('@percentage@','%', $condition); //replace "%"
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
                            $where_return .= $condition;
                        }
                    }


                    if (!empty($where_return)) {
                        $return_value = " WHERE $where_return";
                    }
                    break;
                case 'order':
                    $order_array = $condition_data;

                    if (!empty($order_array)) {
                        // 'order' => 'id desc, name desc'
<<<<<<< HEAD
                        $order_array = $order_array;

=======
                        $order_array = self::escape_string($order_array);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
                        $new_order_array = explode(',', $order_array);
                        $temp_value = array();

                        foreach ($new_order_array as $element) {
                            $element = explode(' ', $element);
                            $element = array_filter($element);
                            $element = array_values($element);

                            if (!empty($element[1])) {
                                $element[1] = strtolower($element[1]);
                                $order = 'DESC';
                                if (in_array($element[1], array('desc', 'asc'))) {
                                    $order = $element[1];
                                }
<<<<<<< HEAD
                                $temp_value[] = $element[0].' '.$order.' ';
                            } else {
                                //by default DESC
                                $temp_value[] = $element[0].' DESC ';
=======
                                $temp_value[]= $element[0].' '.$order.' ';
                            } else {
                                //by default DESC
                                $temp_value[]= $element[0].' DESC ';
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
                            }
                        }
                        if (!empty($temp_value)) {
                            $return_value .= ' ORDER BY '.implode(', ', $temp_value);
<<<<<<< HEAD
                        }
                    }

=======
                        } else {
                            //$return_value .= '';
                        }
                    }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
                    break;
                case 'limit':
                    $limit_array = explode(',', $condition_data);

                    if (!empty($limit_array)) {
                        if (count($limit_array) > 1) {
                            $return_value .= ' LIMIT '.intval($limit_array[0]).' , '.intval($limit_array[1]);
                        } else {
                            $return_value .= ' LIMIT '.intval($limit_array[0]);
                        }
                    }
                    break;
            }
        }

        return $return_value;
    }

    /**
     * @param array $conditions
     * @return string
     */
    public static function parse_where_conditions($conditions)
    {
        return self::parse_conditions(array('where' => $conditions));
    }

    /**
     * Deletes an item depending of conditions
     * @param string $table_name
     * @param array $where_conditions
     * @param bool $show_query
     * @return int
     */
    public static function delete($table_name, $where_conditions, $show_query = false)
    {
        //return self::$connectionWrite->delete($table_name, $where_conditions);

        $where_return = self::parse_where_conditions($where_conditions);
        $sql = "DELETE FROM $table_name $where_return ";
        if ($show_query) {
            echo $sql;
            echo '<br />';
        }
        $result = self::query($sql);
        $affected_rows = self::affected_rows($result);

        //@todo should return affected_rows for
        return $affected_rows;
    }

    /**
     * Experimental useful database update
<<<<<<< HEAD
     * @param    string    table name use Database::get_main_table
     * @param    array    array with values to updates, keys are the fields in the database:
     * @example: $params['name'] = 'Julio'; $params['lastname'] = 'Montoya';
     * @param    array    where conditions i.e array('id = ?' =>'4')
     * @param bool show query
=======
     * @param	string	table name use Database::get_main_table
     * @param	array	array with values to updates, keys are the fields in the database: Example: $params['name'] = 'Julio'; $params['lastname'] = 'Montoya';
     * @param	array	where conditions i.e array('id = ?' =>'4')
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
     * @todo lot of stuff to do here
     */
    public static function update($table_name, $attributes, $where_conditions = array(), $show_query = false)
    {
        if (!empty($table_name) && !empty($attributes)) {
            $update_sql = '';
            //Cleaning attributes
            $count = 1;
<<<<<<< HEAD
            foreach ($attributes as $key => $value) {

                if (!is_array($value)) {
                    $value = self::escape_string($value);
                }
=======
            foreach ($attributes as $key=>$value) {

                if (!is_array($value))

                $value = self::escape_string($value);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
                $update_sql .= "$key = '$value' ";
                if ($count < count($attributes)) {
                    $update_sql .= ', ';
                }
                $count++;
            }
            if (!empty($update_sql)) {
                //Parsing and cleaning the where conditions
                $where_return = self::parse_where_conditions($where_conditions);
<<<<<<< HEAD
                $sql = "UPDATE $table_name SET $update_sql $where_return ";
                if ($show_query) {
                    var_dump($sql);
                }
=======
                $sql    = "UPDATE $table_name SET $update_sql $where_return ";
                if ($show_query) { var_dump($sql); }
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
                $result = self::query($sql);
                $affected_rows = self::affected_rows($result);

                return $affected_rows;
            }
        }

        return false;
    }

    /**
     * Counts the number of rows in a table
     * @param string $table The table of which the rows should be counted
     * @return int The number of rows in the given table.
     */
    public static function count_rows($table)
    {
        $obj = self::fetch_object(self::query("SELECT COUNT(*) AS n FROM $table"));
        return $obj->n;
    }

    /**
     * Returns a list of tables within a database. The list may contain all of the
     * available table names or filtered table names by using a pattern.
     * @param string $database (optional)        The name of the examined database.
     * @param string $pattern (optional)        A pattern for filtering table names as if it was needed for the SQL's LIKE clause, for example 'access_%'.
     * @deprecated
     * @return array                            Returns in an array the retrieved list of table names.
     */
    public static function get_tables($database = '', $pattern = '')
    {
        $result = array();
        $query = "SHOW TABLES";
        if (!empty($database)) {
            $query .= " FROM `".self::escape_string($database)."`";
        }
        if (!empty($pattern)) {
            $query .= " LIKE '".self::escape_string($pattern)."'";
        }
        $query_result = Database::query($query);
        while ($row = Database::fetch_row($query_result)) {
            $result[] = $row[0];
        }

        return $result;
    }

    /**
     * Returns a list of databases created on the server. The list may contain all of the
     * available database names or filtered database names by using a pattern.
     * @return array Returns in an array the retrieved list of database names.
     */
    public static function get_databases()
    {
        $sm = self::$db->getSchemaManager();
        return $sm->listDatabases();
    }
}
