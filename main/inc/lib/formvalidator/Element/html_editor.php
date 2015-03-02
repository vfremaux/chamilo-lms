<?php
/* For licensing terms, see /license.txt */

/**
 * A html editor field to use with QuickForm
 */
class HTML_QuickForm_html_editor extends HTML_QuickForm_textarea
{
    /** @var \ChamiloLMS\Component\Editor\Editor */
    public $editor;

    /**
     * Class constructor
     * @param string  HTML editor name/id
     * @param string  HTML editor  label
     * @param array  Attributes for the textarea
     * @param array $config	Optional configuration settings for the online editor.
     * @return bool
     */
    public function HTML_QuickForm_html_editor($name = null, $label = null, $attributes = null, $config = null)
    {
        if (empty($name)) {
            return false;
        }

        HTML_QuickForm_element::HTML_QuickForm_element($name, $label, $attributes);
        $this->_persistantFreeze = true;
        $this->_type = 'html_editor';

        global $app, $fck_attribute;
        /** @var ChamiloLMS\Component\Editor\Editor $editor */
        $editor = $app['html_editor'];
        $this->editor = $editor;
        $this->editor->setName($name);
        $this->editor->processConfig($fck_attribute);
        $this->editor->processConfig($config);
    }

    /**
     * Return the HTML editor in HTML
     * @return string
     */
    public function toHtml()
    {
        $value = $this->getValue();
        if ($this->editor->getConfigAttribute('fullPage')) {
            if (strlen(trim($value)) == 0) {
                // TODO: To be considered whether here to be added DOCTYPE, language and character set declarations.
                $value = '<html><head><title></title></head><body></body></html>';
                $this->setValue($value);
            }
        }

        if ($this->isFrozen()) {
            return $this->getFrozenHtml();
        } else {
            return $this->buildEditor();
        }
    }

    /**
     * Returns the html area content in HTML
     * @return string
     */
    public function getFrozenHtml()
    {
        return $this->getValue();
    }

    /**
     * Build this element using an editor
     */
<<<<<<< HEAD
    public function buildEditor()
    {
        $this->editor->value = $this->getValue();
        $this->editor->setName($this->getName());
        $result = $this->editor->createHtml();
=======
    function build_FCKeditor() {
        if (!FCKeditor :: IsCompatible()) {
            return parent::toHTML();
        }
        $this->fck_editor->Value = $this->getValue();
        $result = $this->fck_editor->CreateHtml();

        if (isset($this->fck_editor->Config['LoadAsciiMath'])) {
            if (isset($_SESSION['ascii_math_loaded']) &&
                $_SESSION['ascii_math_loaded'] == false
            ) {
                $result .= $this->fck_editor->Config['LoadAsciiMath'];
                $_SESSION['ascii_math_loaded'] = true;
            }
        }

        //Add a link to open the allowed html tags window
        //$result .= '<small><a href="#" onclick="MyWindow=window.open('."'".api_get_path(WEB_CODE_PATH)."help/allowed_html_tags.php?fullpage=". ($this->fullPage ? '1' : '0')."','MyWindow','toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=500,height=600,left=200,top=20'".'); return false;">'.get_lang('AllowedHTMLTags').'</a></small>';
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        return $result;
    }
}
