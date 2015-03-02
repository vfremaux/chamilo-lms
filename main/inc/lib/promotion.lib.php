<?php
/* For licensing terms, see /license.txt */
/**
*	This class provides methods for the promotion management.
*	Include/require it in your code to use its features.
*	@package chamilo.library
*/
/**
 * Code
 */

require_once 'career.lib.php';

define ('PROMOTION_STATUS_ACTIVE',  1);
define ('PROMOTION_STATUS_INACTIVE', 0);
/**
 * @package chamilo.library
 */
class Promotion extends Model
{
<<<<<<< HEAD

    public $table;
    public $columns = array('id', 'name', 'description', 'career_id', 'status', 'created_at', 'updated_at');

    public function __construct()
=======
    public $table;
    public $columns = array('id','name','description','career_id','status','created_at','updated_at');

	public function __construct()
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    {
        $this->table =  Database::get_main_table(TABLE_PROMOTION);
	}

     /**
     * Get the count of elements
     */
    public function get_count()
    {
<<<<<<< HEAD
        $row = Database::select('count(*) as count', $this->table, array(),'first');
        return $row['count'];
    }


=======
        $row = Database::select('count(*) as count', $this->table, array(), 'first');
        return $row['count'];
    }

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
	/**
	* Copies the promotion to a new one
	* @param   integer     Promotion ID
	* @param   integer     Career ID, in case we want to change it
	* @param   boolean     Whether or not to copy the sessions inside
	* @return  integer     New promotion ID on success, false on failure
	*/
<<<<<<< HEAD
	public function copy($id, $career_id = null, $copy_sessions = false) {
        $pid = false;
        $promotion = $this->get($id);
=======
	public function copy($id, $career_id = null, $copy_sessions = false)
    {
		$pid = false;
		$promotion = $this->get($id);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        if (!empty($promotion)) {
            $new = array();
            foreach ($promotion as $key => $val) {
                switch ($key) {
                    case 'id':
                    case 'updated_at':
                        break;
                    case 'name':
                        $val .= ' '.get_lang('CopyLabelSuffix');
                        $new[$key] = $val;
                        break;
                    case 'created_at':
                        $val = api_get_utc_datetime();
                        $new[$key] = $val;
                        break;
                    case 'career_id':
                        if (!empty($career_id)) {
                            $val = (int)$career_id;
                        }
                        $new[$key] = $val;
                    default:
                        $new[$key] = $val;
                    break;
                }
            }

<<<<<<< HEAD
            if ($copy_sessions) {
                /**
                 * When copying a session we do:
                 * 1. Copy a new session from the source
                 * 2. Copy all courses from the session (no user data, no user list)
                 * 3. Create the promotion
                 */
                $session_list   = SessionManager::get_all_sessions_by_promotion($id);

                if (!empty($session_list)) {
                    $pid = $this->save($new);
                    if (!empty($pid)) {
                        $new_session_list = array();

                        foreach ($session_list as $item) {
                            $sid = SessionManager::copy_session($item['id'], true, false, false, true);
                            $new_session_list[] = $sid;
                        }
=======
			if ($copy_sessions) {
				/**
				 * When copying a session we do:
				 * 1. Copy a new session from the source
				 * 2. Copy all courses from the session (no user data, no user list)
				 * 3. Create the promotion
				 */
				$session_list   = SessionManager::get_all_sessions_by_promotion($id);

				if (!empty($session_list)) {
					$pid = $this->save($new);
					if (!empty($pid)) {
                        $new_session_list = array();

						foreach($session_list as $item) {
							$sid = SessionManager::copy_session($item['id'], true, false, false, true);
                            $new_session_list[] = $sid;
						}
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

                        if (!empty($new_session_list)) {
                            SessionManager::suscribe_sessions_to_promotion($pid, $new_session_list);
                        }
                    }
                }
            } else {
                $pid = $this->save($new);
            }
		}
		return $pid;
	}

    /**
     * Gets all promotions by career id
     * @param   int     career id
     * @param bool $order
     * @return  array   results
     */
    public function get_all_promotions_by_career_id($career_id, $order = false)
    {
        return Database::select('*', $this->table, array('where'=>array('career_id = ?'=>$career_id),'order' =>$order));
    }

<<<<<<< HEAD
    public function get_status_list() {
=======
    /**
     * @return array
     */
    public function get_status_list()
    {
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    	return array(PROMOTION_STATUS_ACTIVE => get_lang('Active'), PROMOTION_STATUS_INACTIVE => get_lang('Inactive'));
    }

    /**
     * Displays the title + grid
     * @return  string  html code
     */
	function display() {
		// action links
		echo '<div class="actions" style="margin-bottom:20px">';
        echo '<a href="career_dashboard.php">'.Display::return_icon('back.png',get_lang('Back'),'','32').'</a>';
		echo '<a href="'.api_get_self().'?action=add">'.Display::return_icon('new_promotion.png',get_lang('Add'),'','32').'</a>';
<<<<<<< HEAD
		echo '<a href="'.api_get_path(WEB_CODE_PATH).'session/session_add.php">'.Display::return_icon('new_session.png',get_lang('AddSession'),'','32').'</a>';
=======
		echo '<a href="'.api_get_path(WEB_CODE_PATH).'admin/session_add.php">'.Display::return_icon('new_session.png',get_lang('AddSession'),'','32').'</a>';
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
		echo '</div>';
        echo Display::grid_html('promotions');
	}

    /**
     * Update all session status by promotion
     * @param   int     promotion id
     * @param   int     status (1, 0)
    */
<<<<<<< HEAD
    public function update_all_sessions_status_by_promotion_id($promotion_id, $status) {
=======
    public function update_all_sessions_status_by_promotion_id($promotion_id, $status)
    {
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        $session_list   = SessionManager::get_all_sessions_by_promotion($promotion_id);
        if (!empty($session_list)) {
            foreach($session_list  as $item) {
                SessionManager::set_session_status($item['id'], $status);
            }
        }
    }

<<<<<<< HEAD
=======

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    /**
     * Returns a Form validator Obj
     * @param   string  url
     * @param   string  header name
     * @return  obj     form validator obj
     */

<<<<<<< HEAD
    function return_form($url, $action = 'add') {
=======
    function return_form($url, $action = 'add')
    {
		$oFCKeditor = new FCKeditor('description') ;
		$oFCKeditor->ToolbarSet = 'careers';
		$oFCKeditor->Width		= '100%';
		$oFCKeditor->Height		= '200';
		$oFCKeditor->Value		= '';
		$oFCKeditor->CreateHtml();

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
		$form = new FormValidator('promotion', 'post', $url);
        // Setting the form elements
        $header = get_lang('Add');
        if ($action == 'edit') {
        	$header = get_lang('Modify');
        }
        $id = isset($_GET['id']) ? intval($_GET['id']) : '';

        $form->addElement('header', '', $header);
        $form->addElement('hidden', 'id', $id);
        $form->addElement('text', 'name', get_lang('Name'), array('size' => '70','id' => 'name'));
        $form->add_html_editor('description', get_lang('Description'), false, false, array('ToolbarSet' => 'careers','Width' => '100%', 'Height' => '250'));
        $career = new Career();
        $careers = $career->get_all();
        $career_list = array();
        foreach($careers as $item) {
            $career_list[$item['id']] = $item['name'];
        }
        $form->addElement('select', 'career_id', get_lang('Career'), $career_list);

        $status_list = $this->get_status_list();
        $form->addElement('select', 'status', get_lang('Status'), $status_list);
        if ($action == 'edit') {
            $form->addElement('text', 'created_at', get_lang('CreatedAt'));
            $form->freeze('created_at');
        }
      	if ($action == 'edit') {
        	$form->addElement('style_submit_button', 'submit', get_lang('Modify'), 'class="save"');
        } else {
        	$form->addElement('style_submit_button', 'submit', get_lang('Add'), 'class="save"');
        }

        // Setting the defaults
        $defaults = $this->get($id);
        if (!empty($defaults['created_at'])) {
        	$defaults['created_at'] = api_convert_and_format_date($defaults['created_at']);
        }
        if (!empty($defaults['updated_at'])) {
        	$defaults['updated_at'] = api_convert_and_format_date($defaults['updated_at']);
        }
        $form->setDefaults($defaults);

        // Setting the rules
        $form->addRule('name', get_lang('ThisFieldIsRequired'), 'required');

        return $form;
    }

<<<<<<< HEAD
    public function save($params, $show_query = false) {
=======
    public function save($params, $show_query = false)
    {
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    	$id = parent::save($params, $show_query);
    	if (!empty($id)) {
    		event_system(LOG_PROMOTION_CREATE, LOG_PROMOTION_ID, $id, api_get_utc_datetime(), api_get_user_id());
    	}
    	return $id;
    }

    public function delete($id)
    {
    	if (parent::delete($id)) {
            SessionManager::clear_session_ref_promotion($id);
    	    event_system(LOG_PROMOTION_DELETE, LOG_PROMOTION_ID, $id, api_get_utc_datetime(), api_get_user_id());
        } else {
            return false;
        }
    }
<<<<<<< HEAD


=======
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
}
