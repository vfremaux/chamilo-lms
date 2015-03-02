 <?php
/**
 * HTML class for static data
 * @example  $form->addElement('label', 'My label', 'Content');
 */
//require_once 'HTML/QuickForm/static.php';

/**
 * A pseudo-element used for adding raw HTML to form
 *
 * Intended for use with the default renderer only, template-based
 * ones may (and probably will) completely ignore this
 *
 * @category    HTML
 * @package     HTML_QuickForm
 * @author      Alexey Borzov <avb@php.net>
 * @version     Release: 3.2.11
 * @since       3.0
 * @deprecated  Please use the templates rather than add raw HTML via this element
 */
class HTML_QuickForm_label extends HTML_QuickForm_static
{
    // {{{ constructor

<<<<<<< HEAD
    /**
     * Class constructor
     *
     * @param string $text   raw HTML to add
     * @access public
     * @return void
     */
    function HTML_QuickForm_label($label = null, $text = null, $attributes = null)
    {
=======
   /**
    * Class constructor
    *
    * @param string $text   raw HTML to add
    * @access public
    * @return void
    */
    function HTML_QuickForm_label($label = null, $text = null, $attributes = null) {
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        $this->HTML_QuickForm_static(null, $label, $text, $attributes);
        $this->_type = 'html';
    }

    // }}}
    // {{{ accept()

    /**
     * Accepts a renderer
     *
     * @param HTML_QuickForm_Renderer    renderer object (only works with Default renderer!)
     * @access public
     * @return void
     */
    function accept(&$renderer, $required = false, $error = null)
    {
        $renderer->renderHtml($this);
    }
<<<<<<< HEAD
=======
    
    function toHtml() {
         $for = $this->getLabelFor();
         return '<div class="control-group ">
                    <label class="control-label"'.(empty($for)?'':' for="'.$for.'"').'>'.$this->getLabel().'</label>
                    <div class="controls">
                    '.HTML_QuickForm_static::toHtml().'
                        </div>
                 </div>
                                        
                ';
    } //end func toHtml
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

    function toHtml()
    {
        $id = $this->getAttribute('id');
        $idCondition = null;
        if (!empty($id)) {
            $idCondition = 'id="'.$id.'"';
        }
        return '<div class="form-group" '.$idCondition.' >
                    <label class="col-sm-2 control-label">'.$this->getLabel().'</label>
                    <div class="col-sm-10">
                        '.HTML_QuickForm_static::toHtml().'
                    </div>
                </div>';
    }
}
