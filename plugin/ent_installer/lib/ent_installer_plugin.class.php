<?php

/**
 * Description of ENT Installer
 *
 * @copyright (c) 2014 VF Consulting
 * @license GNU General Public License - http://www.gnu.org/copyleft/gpl.html
 * @author Valery Fremaux <valery.fremaux@gmail.com>
 */
class ENTInstallerPlugin extends Plugin
{

    /**
     *
     * @return VChamiloPlugin 
     */
    static function create()
    {
        static $result = null;
        return $result ? $result : $result = new self();
    }
    
    function get_name()
    {
        return 'ent_installer';
    }

    protected function __construct()
    {
        parent::__construct('1.1', 'Valery Fremaux');
    }

	function pix_url($pixname, $size = 16){
		global $_configuration;
		
		if (file_exists($_configuration['root_sys'].'/plugin/ent_installer/pix/'.$pixname.'.png')){
			return $_configuration['root_web'].'/plugin/ent_installer/pix/'.$pixname.'.png';
		}
		if (file_exists($_configuration['root_sys'].'/plugin/ent_installer/pix/'.$pixname.'.jpg')){
			return $_configuration['root_web'].'/plugin/ent_installer/pix/'.$pixname.'.jpg';
		}
		if (file_exists($_configuration['root_sys'].'/plugin/ent_installer/pix/'.$pixname.'.gif')){
			return $_configuration['root_web'].'/plugin/ent_installer/pix/'.$pixname.'.gif';
		}
		
		return $_configuration['root_web'].'/main/img/icons/'.$size.'/'.$pixname.'.png';
		
	}
}