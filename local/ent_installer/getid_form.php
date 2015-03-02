<?php

require_once ('HTML/QuickForm.php');
require_once ($_configuration['root_sys'].'local/classes/formslib.php');

class GetIdForm extends ChamiloForm {

    function definition() {
    
        $mform = $this->_form;
        
        $mform->addElement('text', 'search', get_string('search', 'local_ent_installer'), '' );
        
        $radioarray = array();
        $radioarray[] = & $mform->createElement('radio', 'searchby', '', get_string('byname', 'local_ent_installer'), 1);
        $radioarray[] = & $mform->createElement('radio', 'searchby', '', get_string('bycity', 'local_ent_installer'), 1);
        $mform->addGroup($radioarray, 'radioar', '', array(' '), false);        

        $this->add_action_buttons();
    }
    
    function validation($data, $files = array()) {
    }
}