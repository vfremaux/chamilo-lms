<?php
/* For licensing terms, see /license.txt */
/**
 *    This class provides methods for the notebook management.
 *    Include/require it in your code to use its features.
 * @package chamilo.library
 */
/**
 * Code
 */
/**
 * @package chamilo.library
 */

<<<<<<< HEAD
class GradeModel extends Model
{

    public $table;
    public $columns = array('id', 'name', 'description');

    public function __construct()
    {
        $this->table = Database::get_main_table(TABLE_GRADE_MODEL);
    }

    /**
     * @param array $where_conditions
     * @return array
     */
    public function get_all($where_conditions = array())
    {
        return Database::select('*', $this->table, array('where' => $where_conditions, 'order' => 'name ASC'));
    }

    /**
=======
require_once 'fckeditor/fckeditor.php';
/**
 * Class GradeModel
 */
class GradeModel extends Model
{
    public $table;
    public $columns = array('id', 'name', 'description');

	public function __construct()
    {
        $this->table =  Database::get_main_table(TABLE_GRADE_MODEL);
	}

    /**
     * @param array $where_conditions
     * @return array
     */
    public function get_all($where_conditions = array())
    {
        return Database::select('*',$this->table, array('where'=>$where_conditions,'order' =>'name ASC'));
    }

    /**
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
     * @return mixed
     */
    public function get_count()
    {
<<<<<<< HEAD
        $row = Database::select('count(*) as count', $this->table, array(), 'first');

=======
        $row = Database::select('count(*) as count', $this->table, array(),'first');
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        return $row['count'];
    }

    /**
     * Displays the title + grid
     */
<<<<<<< HEAD
    public function display()
    {
        // action links
        echo '<div class="actions" style="margin-bottom:20px">';
        echo '<a href="grade_models.php">'.Display::return_icon('back.png', get_lang('Back'), '', '32').'</a>';
        echo '<a href="'.api_get_self().'?action=add">'.Display::return_icon(
            'add.png',
            get_lang('Add'),
            '',
            '32'
        ).'</a>';
        echo '</div>';
        echo Display::grid_html('grade_model');
    }
=======
	public function display()
    {
		// action links
		echo '<div class="actions" style="margin-bottom:20px">';
        echo '<a href="grade_models.php">'.Display::return_icon('back.png',get_lang('Back'),'','32').'</a>';
		echo '<a href="'.api_get_self().'?action=add">'.Display::return_icon('add.png',get_lang('Add'),'','32').'</a>';
		echo '</div>';
        echo Display::grid_html('grade_model');
	}
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

    /**
     * Returns a Form validator Obj
     * @param   string  url
     * @param   string  action add, edit
     * @return  obj     form validator obj
     */
    public function return_form($url, $action)
    {
<<<<<<< HEAD
=======
        $oFCKeditor = new FCKeditor('description') ;
        $oFCKeditor->ToolbarSet = 'grade_model';
        $oFCKeditor->Width		= '100%';
        $oFCKeditor->Height		= '200';
        $oFCKeditor->Value		= '';
        $oFCKeditor->CreateHtml();

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        $form = new FormValidator('grades', 'post', $url);

        // Setting the form elements
        $header = get_lang('Add');

        if ($action == 'edit') {
            $header = get_lang('Modify');
        }

        $form->addElement('header', $header);
        $id = isset($_GET['id']) ? intval($_GET['id']) : '';
        $form->addElement('hidden', 'id', $id);

        $form->addElement('text', 'name', get_lang('Name'), array('size' => '70'));
<<<<<<< HEAD
        $form->add_html_editor(
            'description',
            get_lang('Description'),
            false,
            false,
            array('ToolbarSet' => 'careers', 'Width' => '100%', 'Height' => '250')
        );
=======
        $form->add_html_editor('description', get_lang('Description'), false, false, array('ToolbarSet' => 'careers','Width' => '100%', 'Height' => '250'));
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

        $form->addElement('label', get_lang('Components'));

        //Get components
        $nr_items = 2;
        $max      = 10;

        // Setting the defaults

        $defaults = $this->get($id);

        $components = $this->get_components($defaults['id']);

        if ($action == 'edit') {
            if (!empty($components)) {
<<<<<<< HEAD
                $nr_items = count($components) - 1;
=======
                $nr_items = count($components) -1;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
            }
        }

        $form->addElement('hidden', 'maxvalue', '100');
<<<<<<< HEAD
        $form->addElement('hidden', 'minvalue', '0');
=======
		$form->addElement('hidden', 'minvalue', '0');
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

        $renderer = & $form->defaultRenderer();

        $component_array = array();

        for ($i = 0; $i <= $max; $i++) {
            $counter = $i;
            $form->addElement('text', 'components['.$i.'][percentage]', null, array('class' => 'span1'));
<<<<<<< HEAD
            $form->addElement(
                'text',
                'components['.$i.'][acronym]',
                null,
                array('class' => 'span1', 'placeholder' => get_lang('Acronym'))
            );
            $form->addElement(
                'text',
                'components['.$i.'][title]',
                null,
                array('class' => 'span2', 'placeholder' => get_lang('Title'))
            );
            $form->addElement(
                'text',
                'components['.$i.'][prefix]',
                null,
                array('class' => 'span1', 'placeholder' => get_lang('Prefix'))
            );

            $options = array(0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5);
            $form->addElement('select', 'components['.$i.'][count_elements]', null, $options);

            $options = array(0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5);
            $form->addElement('select', 'components['.$i.'][exclusions]', null, $options);


            $form->addElement('hidden', 'components['.$i.'][id]');

            $template_percentage =
                '<div id='.$i.' style="display: '.(($i <= $nr_items) ? 'inline' : 'none').';" class="control-group">
=======
            $form->addElement('text', 'components['.$i.'][acronym]',    null, array('class' => 'span1'));
            $form->addElement('text', 'components['.$i.'][title]',      null, array('class' => 'span3'));
            $form->addElement('hidden', 'components['.$i.'][id]',       null, array('class' => 'span3'));

             $template_percentage =
            '<div id=' . $i . ' style="display: '.(($i<=$nr_items)?'inline':'none').';" class="control-group">
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
                <p>
                <label class="control-label">{label}</label>
                <div class="controls">
                    {element} <!-- BEGIN error --><span class="form_error">{error}</span><!-- END error --> % = ';

            $template_acronym = '
<<<<<<< HEAD
            <!-- BEGIN required -->
=======
            <!-- BEGIN required --><span class="form_required">*</span> <!-- END required -->
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
            {element} {label} <!-- BEGIN error --><span class="form_error">{error}</span> <!-- END error -->';

            $template_title =
                '&nbsp{element} <!-- BEGIN error --> <span class="form_error">{error}</span><!-- END error -->
                 <a href="javascript:plusItem('.($counter + 1).')">
                 '.Display::return_icon('add.png', get_lang('Add'), array('style' => 'display: '.(($counter >= $nr_items) ? 'inline' : 'none'), 'id' => 'plus-'.($counter + 1))).'
                 </a>
            <a href="javascript:minItem('.($counter).')">
                '.Display::return_icon('delete.png', get_lang('Delete'), array('style' => 'display: '.(($counter >= $nr_items) ? 'inline' : 'none'), 'id' => 'min-'.$counter)).'
            </a>
<<<<<<< HEAD
            </div></p></div>';

            $renderer->setElementTemplate($template_acronym, 'components['.$i.'][title]');
            $renderer->setElementTemplate($template_percentage, 'components['.$i.'][percentage]');
            $renderer->setElementTemplate($template_acronym, 'components['.$i.'][acronym]');

            $renderer->setElementTemplate($template_acronym, 'components['.$i.'][prefix]');
            $renderer->setElementTemplate($template_title, 'components['.$i.'][exclusions]');
            $renderer->setElementTemplate($template_acronym, 'components['.$i.'][count_elements]');
=======
            <a href="javascript:minItem(' . ($counter) . ')">
                <img style="display: '.(($counter>=$nr_items)?'inline':'none').';" id="min-' . $counter . '" src="../img/delete.png" alt="'.get_lang('Delete').'" title="'.get_lang('Delete').'"></img>
            </a>
            </div></p></div>';

            $renderer->setElementTemplate($template_title, 'components['.$i.'][title]');
            $renderer->setElementTemplate($template_percentage ,  'components['.$i.'][percentage]');
            $renderer->setElementTemplate($template_acronym , 'components['.$i.'][acronym]');
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

            if ($i == 0) {
                $form->addRule('components['.$i.'][percentage]', get_lang('ThisFieldIsRequired'), 'required');
                $form->addRule('components['.$i.'][title]', get_lang('ThisFieldIsRequired'), 'required');
                $form->addRule('components['.$i.'][acronym]', get_lang('ThisFieldIsRequired'), 'required');
            }
            $form->addRule('components['.$i.'][percentage]', get_lang('OnlyNumbers'), 'numeric');

            $form->addRule(array('components['.$i.'][percentage]', 'maxvalue'), get_lang('Over100'), 'compare', '<=');
            $form->addRule(array('components['.$i.'][percentage]', 'minvalue'), get_lang('UnderMin'), 'compare', '>=');

            $component_array[] = 'components['.$i.'][percentage]';
        }

        //New rule added in the formvalidator compare_fields that filters a group of fields in order to compare with the wanted value
        $form->addRule($component_array, get_lang('AllMustWeight100'), 'compare_fields', '==@100');

<<<<<<< HEAD
        $form->addElement('label', null, get_lang('AllMustWeight100'));
=======
        $form->addElement('advanced_settings', get_lang('AllMustWeight100'));
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

        if ($action == 'edit') {
            $form->addElement('style_submit_button', 'submit', get_lang('Modify'), 'class="save"');
        } else {
            $form->addElement('style_submit_button', 'submit', get_lang('Add'), 'class="save"');
        }

        if (!empty($components)) {
            $counter = 0;
            foreach ($components as $component) {
                foreach ($component as $key => $value) {
                    $defaults['components['.$counter.']['.$key.']'] = $value;
                }
                $counter++;
            }
        }

        $form->setDefaults($defaults);

        // Setting the rules
        $form->addRule('name', get_lang('ThisFieldIsRequired'), 'required');
<<<<<<< HEAD

        return $form;
    }

    /**
     * @param $id
     * @return array|null
     */
=======
		return $form;
    }

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    public function get_components($id)
    {
        $obj = new GradeModelComponents();
        if (!empty($id)) {
<<<<<<< HEAD
            return $obj->get_all(array('where' => array('grade_model_id = ?' => $id)));
        }

        return null;
    }

    /**
     * @param $params
     * @param bool $show_query
     * @return bool
     */
    public function save($params, $show_query = false)
    {
        $id = parent::save($params, $show_query);
        if (!empty($id)) {
=======
            return $obj->get_all(array('where'=> array('grade_model_id = ?' => $id)));
        }
        return null;
    }

    public function save($params, $show_query = false)
    {
	    $id = parent::save($params, $show_query);
	    if (!empty($id)) {
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
            foreach ($params['components'] as $component) {
                if (!empty($component['title']) && !empty($component['percentage']) && !empty($component['acronym'])) {
                    $obj                         = new GradeModelComponents();
                    $component['grade_model_id'] = $id;
                    $obj->save($component);
                }
            }
        }
<<<<<<< HEAD

        //event_system(LOG_CAREER_CREATE, LOG_CAREER_ID, $id, api_get_utc_datetime(), api_get_user_id());
        return $id;
    }

    /**
     * @param array $params
     * @return bool|void
     */
=======
        //event_system(LOG_CAREER_CREATE, LOG_CAREER_ID, $id, api_get_utc_datetime(), api_get_user_id());
   		return $id;
    }

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    public function update($params)
    {
        parent::update($params);

        if (!empty($params['id'])) {
            foreach ($params['components'] as $component) {
<<<<<<< HEAD
                $obj                         = new GradeModelComponents();
=======
                $obj = new GradeModelComponents();
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
                $component['grade_model_id'] = $params['id'];
                if (empty($component['title']) && empty($component['percentage']) && empty($component['acronym'])) {
                    $obj->delete($component['id']);
                } else {
                    $obj->update($component);
                }
            }
        }
        //$params['components']
    }

<<<<<<< HEAD
    /**
     * @param int $id
     * @return bool|void
     */
    public function delete($id)
    {
        parent::delete($id);
        //event_system(LOG_CAREER_DELETE, LOG_CAREER_ID, $id, api_get_utc_datetime(), api_get_user_id());
=======
    public function delete($id)
    {
	    parent::delete($id);
	    //event_system(LOG_CAREER_DELETE, LOG_CAREER_ID, $id, api_get_utc_datetime(), api_get_user_id());
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    }

    public function fill_grade_model_select_in_form(&$form, $name = 'gradebook_model_id', $default_value = null)
    {
        if (api_get_setting('gradebook_enable_grade_model') == 'false') {
            return false;
        }

        if (api_get_setting('teachers_can_change_grade_model_settings') == 'true' || api_is_platform_admin()) {
<<<<<<< HEAD
            $grade_models        = $this->get_all();
=======
            $grade_models = $this->get_all();
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
            $grade_model_options = array('-1' => get_lang('None'));
            if (!empty($grade_models)) {
                foreach ($grade_models as $item) {
                    $grade_model_options[$item['id']] = $item['name'];
                }
            }
            $form->addElement('select', $name, get_lang('GradeModel'), $grade_model_options);
            $default_platform_setting = api_get_setting('gradebook_default_grade_model_id');

            $default = -1;

            if ($default_platform_setting == -1) {
                if (!empty($default_value)) {
                    $default = $default_value;
                }
            } else {
                $default = $default_platform_setting;
            }

            if (!empty($default) && $default != '-1') {
                $form->setDefaults(array($name => $default));
            }
        }
    }
}

<<<<<<< HEAD
//@todo move in another file
class GradeModelComponents extends Model
{
    public $table;
    public $columns = array(
        'id',
        'title',
        'percentage',
        'acronym',
        'grade_model_id',
        'prefix',
        'exclusions',
        'count_elements'
    );

    public function __construct()
    {
        $this->table = Database::get_main_table(TABLE_GRADE_MODEL_COMPONENTS);
    }

    public function save($params, $show_query = false)
    {
        $id = parent::save($params, $show_query);

=======
/**
 * Class GradeModelComponents
 */
class GradeModelComponents extends Model
{
    public $table;
    public $columns = array('id', 'title', 'percentage', 'acronym', 'grade_model_id');

	public function __construct()
    {
        $this->table =  Database::get_main_table(TABLE_GRADE_MODEL_COMPONENTS);
	}

    public function save($params, $show_query = false)
    {
	    $id = parent::save($params, $show_query);
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        return $id;
    }
}
