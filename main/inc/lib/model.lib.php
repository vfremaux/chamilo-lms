<?php
/* For licensing terms, see /license.txt */
/**
 *    This class provides basic methods to implement a CRUD for a new table in the database see examples in: career.lib.php and promotion.lib.php
 *    Include/require it in your code to use its features.
 * @package chamilo.library
 */
/**
 * Class
 * @package chamilo.library
 */
class Model
{
<<<<<<< HEAD

    public $table;
    public $columns;
    public $required;
    public $is_course_model = false;

    // var $pk; some day this will be implemented

    public function __construct()
    {
    }

    public function set($id)
    {
        /*$data = self::get($id);
        foreach ($data as $key => $value) {
            if (in_array($key, $this->columns)) {
                $this->$key = $value;
            }
        }*/
    }
=======
    public $table;
    public $columns;
    public $required;
    public $is_course_model =false;

	public function __construct()
    {
	}
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

    /**
     * Useful finder - experimental akelos like only use in notification.lib.php send function
     */
    public function find($type, $options = null)
    {
        switch ($type) {
            case 'all':
                return self::get_all($options);
                break;
            case (is_numeric($type)) :
                return self::get($type);
                break;
        }
    }

    /**
     * Deletes an item
     */
    public function delete($id)
    {
        if (empty($id) or $id != strval(intval($id))) {
            return false;
        }
        $params = array('id = ?' => $id);
        if ($this->is_course_model) {
            $course_id = api_get_course_int_id();
            $params    = array('id = ? AND c_id = ?' => array($id, $course_id));
        }
        // Database table definition
<<<<<<< HEAD
        $result = Database :: delete($this->table, $params);
        if ($result != 1) {
            return false;
        }

        return true;
    }

=======
        $result = Database::delete($this->table, $params);
        if ($result != 1) {
            return false;
        }
        return true;
    }

    /**
     * @param array $params
     * @return array
     */
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    private function clean_parameters($params)
    {
        $clean_params = array();
        if (!empty($params)) {
<<<<<<< HEAD
            foreach ($params as $key => $value) {
=======
            foreach ($params as $key=>$value) {
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
                if (in_array($key, $this->columns)) {
                    $clean_params[$key] = $value;
                }
            }
        }

        return $clean_params;
    }

    /**
     * Displays the title + grid
     */
<<<<<<< HEAD
    public function display()
    {
=======
    public function display() {
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    }

    /**
     * Gets an element
     */
    public function get($id)
    {
        if (empty($id)) {
            return array();
        }
<<<<<<< HEAD
        $params = array('id = ?' => intval($id));
=======
        $params = array('id = ?'=>intval($id));
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        if ($this->is_course_model) {
            $course_id = api_get_course_int_id();
            $params    = array('id = ? AND c_id = ?' => array($id, $course_id));
        }
        $result = Database::select('*', $this->table, array('where' => $params), 'first');

        return $result;
    }

<<<<<<< HEAD
=======
    /**
     * @param array $options
     * @return array
     */
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    public function get_all($options = null)
    {
        return Database::select('*', $this->table, $options);
    }

<<<<<<< HEAD
    public function get_first($options = null)
    {
        return Database::select('*', $this->table, $options, 'first');
    }

=======
    /**
     * @param array  $options
     * @return array
     */
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    public function get_all_for_export($options = null)
    {
        return Database::select('name, description', $this->table, $options);
    }

    /**
     * Get the count of elements
     */
    public function get_count()
    {
<<<<<<< HEAD
        $row = Database::select(
            'count(*) as count',
            $this->table,
            array('where' => array('parent_id = ?' => '0')),
            'first'
        );

=======
        $row = Database::select('count(*) as count', $this->table, array('where' => array('parent_id = ?' => '0')),'first');
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        return $row['count'];
    }

    /**
     * a little bit of javascript to display
     */
<<<<<<< HEAD
    public function javascript()
    {
    }

    /**
     * Saves an element into the DB
     *
     * @param array $values
     * @return bool
     *
     */
    public function save($params, $show_query = false)
=======
	public function javascript()
    {
	}

	/**
	 * Saves an element into the DB
	 *
	 * @param array $values
	 * @return bool
	 *
	 */
	public function save($params, $show_query = false)
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    {
        $params = $this->clean_parameters($params);

        if ($this->is_course_model) {
            if (!isset($params['c_id']) || empty($params['c_id'])) {
                $params['c_id'] = api_get_course_int_id();
            }
        }

        if (!empty($this->required)) {
            $require_ok = true;
            $kay_params = array_keys($params);
            foreach ($this->required as $field) {
                if (!in_array($field, $kay_params)) {
                    $require_ok = false;
                }
            }
            if (!$require_ok) {
                return false;
            }
        }

        if (in_array('created_at', $this->columns)) {
            $params['created_at'] = api_get_utc_datetime();
        }

        if (!empty($params)) {
            $id = Database::insert($this->table, $params, $show_query);
<<<<<<< HEAD
            if (is_numeric($id)) {
                return $id;
            }
=======
    		if (is_numeric($id)) {
    			return $id;
    		}
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        }

        return false;
<<<<<<< HEAD
    }
=======
	}
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

    /**
     * Updates the obj in the database. The $params['id'] must exist in order to update a record
     * @param array $values
     * @return bool
     *
     */
    public function update($params)
    {
        $params = $this->clean_parameters($params);

        if ($this->is_course_model) {
            if (!isset($params['c_id']) || empty($params['c_id'])) {
                $params['c_id'] = api_get_course_int_id();
            }
        }

        //If the class has the updated_at field we update the date
        if (in_array('updated_at', $this->columns)) {
            $params['updated_at'] = api_get_utc_datetime();
        }
        //If the class has the created_at field then we remove it
        if (in_array('created_at', $this->columns)) {
            unset($params['created_at']);
        }

        if (!empty($params) && !empty($params['id'])) {
            $id = intval($params['id']);
            unset($params['id']); //To not overwrite the id
            if (is_numeric($id)) {
<<<<<<< HEAD
                $result = Database::update($this->table, $params, array('id = ?' => $id));
=======
                $result = Database::update($this->table, $params, array('id = ?'=>$id));
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
                if ($result) {
                    return true;
                }
            }
        }

        return false;
    }
}
