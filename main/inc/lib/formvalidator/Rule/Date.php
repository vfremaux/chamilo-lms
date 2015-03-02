<?php
/* For licensing terms, see /license.txt */
<<<<<<< HEAD

/** @author Bart Mollet, Julio Montoya */

=======
/** @author Bart Mollet, Julio Montoya */
require_once 'HTML/QuickForm/Rule.php';

>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
/**
 * Class HTML_QuickForm_Rule_Date
 */
class HTML_QuickForm_Rule_Date extends HTML_QuickForm_Rule
{
<<<<<<< HEAD
        /**
        * Check a date
        * @see HTML_QuickForm_Rule
        * @param string $date example 2014-04-30
        * @param array $options
        *
        * @return boolean True if date is valid
        */
=======
	/**
	 * Check a date
	 * @see HTML_QuickForm_Rule
	 * @param string $date example 2014-04-30
     * @param array $options
     *
	 * @return boolean True if date is valid
	 */
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
	public function validate($date, $options)
	{
        return api_is_valid_date($date, 'Y-m-d');
	}
}
