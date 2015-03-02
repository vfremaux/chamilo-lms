<!DOCTYPE html>
<?php
exit;
/*
 * fileopen.php
 * To be used with ext-server_opensave.js for SVG-edit
 *
 * Licensed under the MIT License
 *
 * Copyright(c) 2010 Alexis Deveria
 *
<<<<<<< HEAD
 * Integrate svg-edit with Chamilo
 * @author Juan Carlos Ra�a Trabado
 * @since 25/september/2010
*/

require_once '../../../../inc/global.inc.php'; //hack for chamilo

api_protect_course_script();
api_block_anonymous_users();

if (!isset($_FILES['svg_file']['tmp_name'])) {
    api_not_allowed(false); //from Chamilo
    die();
}


?>
<!doctype html>
<?php
// Very minimal PHP file, all we do is Base64 encode the uploaded file and
// return it to the editor

$file = $_FILES['svg_file']['tmp_name'];

$output = file_get_contents($file);

$type = $_REQUEST['type'];

$prefix = '';

// Make Data URL prefix for import image
if ($type == 'import_img') {
    $info = getimagesize($file);
    $prefix = 'data:'.$info['mime'].';base64,';
}

//check the extension
$extension = explode('.', $file);
$extension = strtolower($extension[sizeof($extension) - 1]);

//a bit title security
$filename = addslashes(trim($file));
$filename = Security::remove_XSS($filename);
$filename = api_replace_dangerous_char($filename, 'strict');
$filename = FileManager::disable_dangerous_file($filename);

//a bit mime security
$current_mime = $_FILES['svg_file']['type'];
$mime_svg = 'image/svg+xml';
$mime_xml = 'application/xml';//hack for svg-edit because original code return application/xml; charset=us-ascii.

if (strpos($current_mime, $mime_svg) === false && strpos($current_mime, $mime_xml) === false && $extension == 'svg') {
    // die();//File extension does not match its content disabled to check into chamilo dev campus TODO:enabled
}
=======
 */
	// Very minimal PHP file, all we do is Base64 encode the uploaded file and
	// return it to the editor

	$type = $_REQUEST['type'];
	if (!in_array($type, array('load_svg', 'import_svg', 'import_img'))) {
		exit;
	}
	require('allowedMimeTypes.php');

	$file = $_FILES['svg_file']['tmp_name'];

	$output = file_get_contents($file);

	$prefix = '';

	// Make Data URL prefix for import image
	if ($type == 'import_img') {
		$info = getimagesize($file);
		if (!in_array($info['mime'], $allowedMimeTypesBySuffix)) {
			exit;
		}
		$prefix = 'data:' . $info['mime'] . ';base64,';
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<script>

top.svgEditor.processFile("<?php
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

// This should be safe since SVG edit does its own filtering (e.g., if an SVG file contains scripts)
echo $prefix . base64_encode($output);

<<<<<<< HEAD
<script>
    window.top.window.svgEditor.processFile("<?php echo $prefix.base64_encode($output); ?>", "<?php echo $type ?>");
</script>
=======
?>", "<?php echo $type; ?>");
</script>
</head><body></body>
</html>
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
