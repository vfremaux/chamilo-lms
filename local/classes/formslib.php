<?php

require_once ('HTML/QuickForm.php');

abstract class ChamiloForm{

	var $_form;
	var $_mode;
	var $_cancelurl;
	
	function __construct($mode, $returnurl, $cancelurl){
		
		$this->_mode = $mode;
		$this->_cancelurl = $cancelurl;

		$attributes = array('style' => 'width: 60%; float: '.($text_dir == 'rtl' ? 'right;' : 'left;'));
		$this->_form = new FormValidator($mode.'_instance', 'post', $returnurl, '', $attributes);
	}
	
	abstract function definition();
	abstract function validation($data, $files = null);

	function definition_after_data(){
	}
	
	function return_form(){
		return $this->_form->return_form();
	}
	
	function is_in_add_mode(){
		return $this->_mode == 'add';
	}
	
    /**
     * Use this method to a cancel and submit button to the end of your form. Pass a param of false
     * if you don't want a cancel button in your form. If you have a cancel button make sure you
     * check for it being pressed using is_cancelled() and redirecting if it is true before trying to
     * get data with get_data().
     *
     * @param boolean $cancel whether to show cancel button, default true
     * @param string $submitlabel label for submit button, defaults to get_string('savechanges')
     */
    function add_action_buttons($cancel = true, $submitlabel = null, $cancellabel = null){
    	
    	// TODO : refine lang fetch to effective global strings.
        if (is_null($submitlabel)){
            $submitlabel = get_lang('save');
        }

        if (is_null($cancellabel)){
            $submitlabel = get_lang('cancel');
        }

        $cform =& $this->_form;
        if ($cancel){
            //when two elements we need a group
            $buttonarray = array();
            $buttonarray[] = &$cform->createElement('submit', 'submitbutton', $submitlabel);
            $buttonarray[] = &$cform->createElement('cancel', $cancellabel, $this->_cancelurl);
            $cform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        } else {
            //no group needed
            $cform->addElement('submit', 'submitbutton', $submitlabel);
        }
    }
    
    /**
     * Return submitted data if properly submitted or returns NULL if validation fails or
     * if there is no submitted data.
     *
     * @param bool $slashed true means return data with addslashes applied
     * @return object submitted data; NULL if not valid or not submitted
     */
    function get_data($slashed=true) {
        $cform =& $this->_form;

        if ($this->is_submitted() and $this->is_validated()) {
            $data = $cform->exportValues(null, $slashed);
            unset($data['sesskey']); // we do not need to return sesskey
            unset($data['_qf__'.$this->_formname]);   // we do not need the submission marker too
            if (empty($data)) {
                return NULL;
            } else {
                return (object)$data;
            }
        } else {
            return NULL;
        }
    }

    /**
     * Return submitted data without validation or NULL if there is no submitted data.
     *
     * @param bool $slashed true means return data with addslashes applied
     * @return object submitted data; NULL if not submitted
     */
    function get_submitted_data($slashed=true) {
        $cform =& $this->_form;

        if ($this->is_submitted()) {
            $data = $cform->exportValues(null, $slashed);
            unset($data['sesskey']); // we do not need to return sesskey
            unset($data['_qf__'.$this->_formname]);   // we do not need the submission marker too
            if (empty($data)) {
                return NULL;
            } else {
                return (object)$data;
            }
        } else {
            return NULL;
        }
    }

    /**
     * Check that form was submitted. Does not check validity of submitted data.
     *
     * @return bool true if form properly submitted
     */
    function is_submitted() {
        return $this->_form->isSubmitted();
    }

    /**
     * Check that form data is valid.
     * You should almost always use this, rather than {@see validate_defined_fields}
     *
     * @return bool true if form data valid
     */
    function is_validated() {
        //finalize the form definition before any processing
        if (!$this->_definition_finalized) {
            $this->_definition_finalized = true;
            $this->definition_after_data();
        }
        return $this->validate_defined_fields();
    }

    /**
     * Validate the form.
     *
     * You almost always want to call {@see is_validated} instead of this
     * because it calls {@see definition_after_data} first, before validating the form,
     * which is what you want in 99% of cases.
     *
     * This is provided as a separate function for those special cases where
     * you want the form validated before definition_after_data is called
     * for example, to selectively add new elements depending on a no_submit_button press,
     * but only when the form is valid when the no_submit_button is pressed,
     *
     * @param boolean $validateonnosubmit optional, defaults to false.  The default behaviour
     *                is NOT to validate the form when a no submit button has been pressed.
     *                pass true here to override this behaviour
     *
     * @return bool true if form data valid
     */
    function validate_defined_fields($validateonnosubmit=false) {
        static $validated = null; // one validation is enough
        $cform =& $this->_form;

        if ($this->no_submit_button_pressed() && empty($validateonnosubmit)){
            return false;
        } elseif ($validated === null) {
            $internal_val = $cform->validate();

            $files = array();
            $file_val = $this->_validate_files($files);
            if ($file_val !== true) {
                if (!empty($file_val)) {
                    foreach ($file_val as $element => $msg) {
                        $cform->setElementError($element, $msg);
                    }
                }
                $file_val = false;
            }

            $data = $cform->exportValues(null, true);
            $chamilo_val = $this->validation($data, $files);
            if ((is_array($chamilo_val) && count($chamilo_val)!==0)) {
                // non-empty array means errors
                foreach ($chamilo_val as $element => $msg) {
                    $cform->setElementError($element, $msg);
                }
                $chamilo_val = false;

            } else {
                // anything else means validation ok
                $chamilo_val = true;
            }

            $validated = ($internal_val and $chamilo_val and $file_val);
        }
        return $validated;
    }

    function no_submit_button_pressed(){
        static $nosubmit = null; // one check is enough

        if (!is_null($nosubmit)){
            return $nosubmit;
        }

        $cform =& $this->_form;
        $nosubmit = false;
        if (!$this->is_submitted()){
            return false;
        }
        /*
        foreach ($cform->_noSubmitButtons as $nosubmitbutton){
            if (optional_param($nosubmitbutton, 0, PARAM_RAW)){
                $nosubmit = true;
                break;
            }
        }
        return $nosubmit;
        */
        return false;
    }

    /**
     * Load in existing data as form defaults. Usually new entry defaults are stored directly in
     * form definition (new entry form); this function is used to load in data where values
     * already exist and data is being edited (edit entry form).
     *
     * @param mixed $default_values object or array of default values
     * @param bool $slashed true if magic quotes applied to data values
     */
    function set_data($default_values, $slashed=false) {
        if (is_object($default_values)) {
            $default_values = (array)$default_values;
        }
        $filter = $slashed ? 'stripslashes' : NULL;
        $this->_form->setDefaults($default_values, $filter);
    }

    /**
     * Internal method. Validates all uploaded files.
     */
    function _validate_files(&$files) {
        $files = array();

        if (empty($_FILES)) {
            // we do not need to do any checks because no files were submitted
            // note: server side rules do not work for files - use custom verification in validate() instead
            return true;
        }
        $errors = array();
        $mform =& $this->_form;

        // check the files
        $status = $this->_upload_manager->preprocess_files();

        // now check that we really want each file
        foreach ($_FILES as $elname=>$file) {
            if ($mform->elementExists($elname) and $mform->getElementType($elname)=='file') {
                $required = $mform->isElementRequired($elname);
                if (!empty($this->_upload_manager->files[$elname]['uploadlog']) and empty($this->_upload_manager->files[$elname]['clear'])) {
                    if (!$required and $file['error'] == UPLOAD_ERR_NO_FILE) {
                        // file not uploaded and not required - ignore it
                        continue;
                    }
                    $errors[$elname] = $this->_upload_manager->files[$elname]['uploadlog'];

                } else if (!empty($this->_upload_manager->files[$elname]['clear'])) {
                    $files[$elname] = $this->_upload_manager->files[$elname]['tmp_name'];
                }
            } else {
                error('Incorrect upload attempt!');
            }
        }

        // return errors if found
        if ($status and 0 == count($errors)){
            return true;

        } else {
            $files = array();
            return $errors;
        }
    }

}