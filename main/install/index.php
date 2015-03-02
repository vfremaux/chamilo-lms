<?php
/* For licensing terms, see /license.txt */

/**
 * Chamilo installation
 * This script could be loaded via browser using the URL: main/install/index.php
 * or via CM
 *
 * @package chamilo.install
 */

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../inc/lib/api.lib.php';
require_once __DIR__.'/install.lib.php';
$versionData = require_once __DIR__.'/version.php';

error_reporting(-1);
ini_set('display_errors', '1');
set_time_limit(0);

use Symfony\Component\Console\Output\Output;
use Symfony\Component\HttpFoundation\Request;
use ChamiloLMS\Component\Console\Output\BufferedOutput;
use ChamiloLMS\Framework\Application;
use Chash\Command\Installation\InstallCommand;
use Chash\Command\Installation\UpgradeCommand;

$app = new Application();

// Setting paths
$app['path.base'] = dirname(dirname(__DIR__)).'/';
$app['path.app'] = $app['path.base'].'src/ChamiloLMS/';
$app['path.config'] = $app['path.base'].'config/';

$app->bindInstallPaths(require $app['path.app'].'paths.php');
$app->readConfigurationFiles();

$app['path.data'] = isset($_configuration['path.data']) ? $_configuration['path.data'] : $app['path.data'];
$app['path.courses'] = isset($_configuration['path.courses']) ? $_configuration['path.courses'] : $app['path.courses'];
$app['path.logs'] = isset($_configuration['path.logs']) ? $_configuration['path.logs'] : $app['path.logs'];
$app['path.temp'] = isset($_configuration['path.temp']) ? $_configuration['path.temp'] : $app['path.temp'];


// Registering services
$app['debug'] = true;
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
$app['translator'] = $app->share($app->extend('translator', function ($translator, $app) {

    /*$translator->addLoader('pofile', new PoFileLoader());
    $file = 'main/locale/'.$locale.'.po';
    $translator->addResource('pofile', $file, $locale);*/

    /*$translator->addLoader('yaml', new Symfony\Component\Translation\Loader\YamlFileLoader());
    $translator->addResource('yaml', __DIR__.'/lang/fr.yml', 'fr');
    $translator->addResource('yaml', __DIR__.'/lang/en.yml', 'en');
    $translator->addResource('yaml', __DIR__.'/lang/es.yml', 'es');*/

    return $translator;
}));

$app->register(
    new Silex\Provider\TwigServiceProvider(),
    array(
        'twig.path' => array(
            'templates'
        ),
        // twitter bootstrap form twig templates
        //'twig.form.templates' => array('form_div_layout.html.twig', '../template/default/form/form_custom_template.tpl'),
        'twig.options' => array(
            'debug' => $app['debug'],
            'charset' => 'utf-8',
            'strict_variables' => false,
            'autoescape' => true,
            //'cache' => $app['debug'] ? false : $app['twig.cache.path'],
            'cache' => false, // no cache during installation sorry
            'optimizations' => -1, // turn on optimizations with -1
        )
    )
);

use Knp\Provider\ConsoleServiceProvider;

$app->register(new ConsoleServiceProvider(), array(
    'console.name'              => 'Chamilo',
    'console.version'           => '1.0.0',
    'console.project_directory' => __DIR__.'/..'
));

// Adding commands.
/** @var Knp\Console\Application $console */
$console = $app['console'];

$console->addCommands(
    array(
        // DBAL Commands.
        new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),
        new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),

        // Migrations Commands.
        new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand(),
        new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand(),
        new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand(),
        new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand(),
        new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand(),
        new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand(),

        // Chash commands.
        new UpgradeCommand(),
        new InstallCommand(),

        new Chash\Command\Files\CleanCoursesFilesCommand(),
        new Chash\Command\Files\CleanTempFolderCommand(),
        new Chash\Command\Files\CleanConfigFilesCommand(),
        new Chash\Command\Files\MailConfCommand(),
        new Chash\Command\Files\SetPermissionsAfterInstallCommand(),
        new Chash\Command\Files\GenerateTempFileStructureCommand(),
    )
);

$helpers = array(
    'configuration' => new Chash\Helpers\ConfigurationHelper()
);

$helperSet = $console->getHelperSet();
foreach ($helpers as $name => $helper) {
    $helperSet->set($helper, $name);
}

$blockInstallation = function () use ($app) {
    if (file_exists($app['path.config'].'configuration.php')) {
        return $app->abort(500, "A Chamilo installation was found. You can't reinstall.");
    }

    $defaultTimeZone = ini_get('date.timezone');
    if (empty($defaultTimeZone)) {
        $app->abort(500, "Please set your 'date.timezone' setting in your php.ini file");
    }

    // Check the PHP version.
    if (api_check_php_version() == false) {
        $app->abort(500, "Incorrect PHP version.");
    }

    if (api_check_php_version() == false) {
        $app->abort(500, "Incorrect PHP version.");
    }
    // @todo move this in the req page
    if (extension_loaded('json') == false) {
        $app->abort(500, "php5-json extension must be installed.");
    }
};

// Controllers

$app->match('/', function () use ($app) {
    // in order to get a list of countries
    //var_dump(Symfony\Component\Intl\Intl::getRegionBundle()->getCountryNames());
    $languages = array(
        'english' => 'english',
        'spanish' =>  'spanish',
        'french' => 'french'
    );
    $request = $app['request'];

    $form = $app['form.factory']->createBuilder('form')
        ->add('languages', 'choice', array(
            'choices'   => $languages,
            'required'  => true,
        ))
        ->add('continue', 'submit', array('attr' => array('class' => 'btn')))
        ->getForm();

    if ('POST' == $request->getMethod()) {
        $url = $app['url_generator']->generate('requirements');

        return $app->redirect($url);
    }

    return $app['twig']->render(
        'index.tpl',
        array('form' => $form->createView())
    );
})
->bind('root') // need because api_get_path()
->before($blockInstallation);

$app->match('/requirements', function () use ($app) {

    $allowedToContinue = checkRequiredSettings();

    $request = $app['request'];
    $builder = $app['form.factory']->createBuilder('form');
    if ($allowedToContinue) {
        $builder->add('continue', 'submit', array('attr' => array('class' => 'btn-default')));
    } else {
        $message = $app['translator']->trans("You need to check your server settings.");
        $app['session']->getFlashBag()->add('error', $message);
    }

    $form = $builder->getForm();

    //$req = display_requirements($app, 'new');

    if (phpversion() < REQUIRED_PHP_VERSION) {
        $phpError = '<strong><font color="red">'.translate('PHPVersionError').'</font></strong>';
    } else {
        $phpError = '<strong><font color="green">'.translate('PHPVersionOK').' '.phpversion().'</font></strong>';
    }

    if ('POST' == $request->getMethod()) {
         $url = $app['url_generator']->generate('check-database');

         return $app->redirect($url);
    }

    $requirements = drawRequirements($app['translator']);
    $options = drawOptions($app['translator']);
    $permissions = drawPermissionsSettings($app);

    return $app['twig']->render(
        'requirements.tpl',
        array(
            'form' => $form->createView(),
            'required_php_version' => REQUIRED_PHP_VERSION,
            'required_php_version_validation' => phpversion() < REQUIRED_PHP_VERSION,
            'php_version' => phpversion(),
            'requirements' => $requirements,
            'options' => $options,
            'permissions' => $permissions,
            'php_error' => $phpError,
            'allow_to_continue' => $allowedToContinue
        )
    );

})->bind('requirements');

$app->match('/check-database', function () use ($app) {
    /** @var Request $request */
    $request = $app['request'];

    $command = $app['console']->get('chamilo:install');
    $data = $command->getDatabaseSettingsParams();

    $builder  = $app['form.factory']->createBuilder('form');
    foreach ($data as $key => $value) {
        $value['attributes'] = isset($value['attributes']) && is_array($value['attributes']) ? $value['attributes'] : array();
        $builder->add($key, $value['type'], $value['attributes']);
    }

    $builder->add('check', 'submit', array('attr' => array('class' => 'btn')));
    $form = $builder->getForm();

    if ('POST' == $request->getMethod()) {
        $form->bind($request);

<<<<<<< HEAD
        if ($form->isValid()) {
            $parameters = $form->getData();
=======
// Upgrading from any subversion of 1.6 is just like upgrading from 1.6.5
$update_from_version_6 = array('1.6', '1.6.1', '1.6.2', '1.6.3', '1.6.4', '1.6.5');
// Upgrading from any subversion of 1.8 avoids the additional step of upgrading from 1.6
$update_from_version_8 = array('1.8', '1.8.2', '1.8.3', '1.8.4', '1.8.5', '1.8.6', '1.8.6.1', '1.8.6.2','1.8.7','1.8.7.1','1.8.8','1.8.8.2', '1.8.8.4', '1.8.8.6', '1.9.0', '1.9.2','1.9.4','1.9.6', '1.9.6.1');
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

            /** @var InstallCommand $command */
            $command = $app['console']->get('chamilo:install');
            $command->setDatabaseSettings($parameters);

            $connection = $command->getUserAccessConnectionToHost();

            try {
                $sm = $connection->getSchemaManager();
                $databases = $sm->listDatabases();

                if (in_array($parameters['dbname'], $databases)) {
                    $message = $app['translator']->trans(
                        'The database "%s" being used and is going to be deleted!!',
                        array('%s' => $parameters['dbname'])
                    );
                    $app['session']->getFlashBag()->add('warning', $message);
                } else {
                    $message = $app['translator']->trans(
                        'A database "%s" is going to be created',
                        array('%s' => $parameters['dbname'])
                    );
                    $app['session']->getFlashBag()->add('warning', $message);
                }

                $app['session']->getFlashBag()->add('success', 'Connection ok!');
                $app['session']->set('database_settings', $parameters);
                $url = $app['url_generator']->generate('portal-settings');

                return $app->redirect($url);
            } catch (Exception $e) {
                $app['session']->getFlashBag()->add(
                    'success',
                    'Connection error !'.$e->getMessage()
                );
            }
        }
    }

    return $app['twig']->render(
        'check-database.tpl',
        array('form' => $form->createView())
    );

})->bind('check-database');

$app->match('/portal-settings', function () use ($app) {
    /** @var Request $request */
    $request = $app['request'];

    /** @var InstallCommand $command */
    $command = $app['console']->get('chamilo:install');
    $builder = $app['form.factory']->createBuilder('form');

<<<<<<< HEAD
    $data = $command->getPortalSettingsParams();
    $data['institution_url']['attributes']['data'] = str_replace('main/install/', '', $request->getUriForPath('/'));
    $permissionNewDir = $app['session']->get('permissions_for_new_directories');
=======
if (!isset($_GET['running'])) {

	$dbHostForm		= 'localhost';
	$dbUsernameForm = 'root';
	$dbPassForm		= '';
 	$dbPrefixForm   = '';
	$dbNameForm		= 'chamilo';

	$dbStatsForm    = 'chamilo';
	$dbScormForm    = 'chamilo';
	$dbUserForm		= 'chamilo';

	// Extract the path to append to the url if Chamilo is not installed on the web root directory.
	$urlAppendPath  = api_remove_trailing_slash(api_get_path(REL_PATH));
  	$urlForm 		= api_get_path(WEB_PATH);
	$pathForm 		= api_get_path(SYS_PATH);

        $emailForm = 'webmaster@localhost';
        if (!empty($_SERVER['SERVER_ADMIN'])) {
            $emailForm      = $_SERVER['SERVER_ADMIN'];
        }
	$email_parts = explode('@', $emailForm);
	if (isset($email_parts[1]) && $email_parts[1] == 'localhost') {
		$emailForm .= '.localdomain';
	}
	$adminLastName	= 'Doe';
	$adminFirstName	= 'John';
	$loginForm		= 'admin';
	$passForm		= api_generate_password();

	$campusForm		= 'My campus';
	$educationForm	= 'Albert Einstein';
	$adminPhoneForm	= '(000) 001 02 03';
	$institutionForm    = 'My Organisation';
	$institutionUrlForm = 'http://www.chamilo.org';
	// TODO: A better choice to be tested:
	//$languageForm	    = 'english';
	$languageForm	    = api_get_interface_language();

	$checkEmailByHashSent	= 0;
	$ShowEmailnotcheckedToStudent = 1;
	$userMailCanBeEmpty		= 1;
	$allowSelfReg			= 1;
	$allowSelfRegProf		= 1;
	$enableTrackingForm		= 1;
	$singleDbForm			= 0;
	$encryptPassForm		= 'sha1';
	$session_lifetime		= 360000;
} else {
	foreach ($_POST as $key => $val) {
		$magic_quotes_gpc = ini_get('magic_quotes_gpc');
		if (is_string($val)) {
			if ($magic_quotes_gpc) {
				$val = stripslashes($val);
			}
			$val = trim($val);
			$_POST[$key] = $val;
		} elseif (is_array($val)) {
			foreach ($val as $key2 => $val2) {
				if ($magic_quotes_gpc) {
					$val2 = stripslashes($val2);
				}
				$val2 = trim($val2);
				$_POST[$key][$key2] = $val2;
			}
		}
		$GLOBALS[$key] = $_POST[$key];
	}
}
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84

    if ($permissionNewDir) {
        $data['permissions_for_new_directories']['attributes']['data'] = $permissionNewDir;
    }

    $permissionNewFiles = $app['session']->get('permissions_for_new_files');
    if ($permissionNewFiles) {
        $data['permissions_for_new_files']['attributes']['data'] = $permissionNewFiles;
    }

<<<<<<< HEAD
    foreach ($data as $key => $value) {
        $value['attributes'] = isset($value['attributes']) && is_array($value['attributes']) ? $value['attributes'] : array();
        $builder->add($key, $value['type'], $value['attributes']);
=======
				//document.getElementById('optional_param2').style.display = '';
				if (document.getElementById('optional_param3')) {
					document.getElementById('optional_param3').style.display = '';
				}

				//document.getElementById('optional_param5').style.display = '';
				//document.getElementById('optional_param6').style.display = '';
				init_visibility = 1;
				document.getElementById('optionalparameters').innerHTML='<img style="vertical-align:middle;" src="../img/div_hide.gif" alt="" /> <?php echo get_lang('OptionalParameters', ''); ?>';
			} else {
				document.getElementById('optional_param1').style.display = 'none';
				/*document.getElementById('optional_param2').style.display = 'none';
				if (document.getElementById('optional_param3')) {
					document.getElementById('optional_param3').style.display = 'none';
				}
				document.getElementById('optional_param4').style.display = 'none';
				*/
				document.getElementById('optional_param5').style.display = 'none';
				//document.getElementById('optional_param6').style.display = 'none';
				document.getElementById('optionalparameters').innerHTML='<img style="vertical-align:middle;" src="../img/div_show.gif" alt="" /> <?php echo get_lang('OptionalParameters', ''); ?>';
				init_visibility = 0;
			}
			return false;
		}

        $(document).ready( function() {
            $(".advanced_parameters").click(function() {
                if ($("#id_contact_form").css("display") == "none") {
                        $("#id_contact_form").css("display","block");
                        $("#img_plus_and_minus").html('&nbsp;<img src="<?php echo api_get_path(WEB_IMG_PATH) ?>div_hide.gif" alt="<?php echo get_lang('Hide') ?>" title="<?php echo get_lang('Hide')?>" style ="vertical-align:middle" >&nbsp;<?php echo get_lang('ContactInformation') ?>');
                } else {
                        $("#id_contact_form").css("display","none");
                        $("#img_plus_and_minus").html('&nbsp;<img src="<?php echo api_get_path(WEB_IMG_PATH) ?>div_show.gif" alt="<?php echo get_lang('Show') ?>" title="<?php echo get_lang('Show') ?>" style ="vertical-align:middle" >&nbsp;<?php echo get_lang('ContactInformation') ?>');
                }
            });
        });

        function send_contact_information() {
            var data_post = "";
            data_post += "person_name="+$("#person_name").val()+"&";
            data_post += "person_email="+$("#person_email").val()+"&";
            data_post += "company_name="+$("#company_name").val()+"&";
            data_post += "company_activity="+$("#company_activity option:selected").val()+"&";
            data_post += "person_role="+$("#person_role option:selected").val()+"&";
            data_post += "company_country="+$("#country option:selected").val()+"&";
            data_post += "company_city="+$("#company_city").val()+"&";
            data_post += "language="+$("#language option:selected").val()+"&";
            data_post += "financial_decision="+$("input[@name='financial_decision']:checked").val();

            $.ajax({
                    contentType: "application/x-www-form-urlencoded",
                    beforeSend: function(objeto) {},
                    type: "POST",
                    url: "<?php echo api_get_path(WEB_AJAX_PATH) ?>install.ajax.php?a=send_contact_information",
                    data: data_post,
                    success: function(datos) {
                        if (datos == 'required_field_error') {
                            message = "<?php echo get_lang('FormHasErrorsPleaseComplete') ?>";
                        } else if (datos == '1') {
                            message = "<?php echo get_lang('ContactInformationHasBeenSent') ?>";
                        } else {
                            message = "<?php echo get_lang('Error').': '.get_lang('ContactInformationHasNotBeenSent') ?>";
                        }
                        alert(message);
                    }
            });
        }
    </script>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo api_get_system_encoding(); ?>" />
</head>
<body dir="<?php echo api_get_text_direction(); ?>" class="install-chamilo">

<div id="wrapper">
<div id="main" class="container well-install">
    <header>
		<div class="row">
            <div id="header_left" class="span4">
                <div id="logo">
                    <img src="../css/chamilo/images/header-logo.png" hspace="10" vspace="10" alt="Chamilo" />
                </div>
            </div>
        </div>
        <div class="navbar subnav">
            <div class="navbar-inner">
                <div class="container">
                    <div class="nav-collapse">
                        <ul class="nav nav-pills">
                            <li id="current" class="active">
                                <a target="_top" href="index.php"><?php echo get_lang('Homepage'); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
	</header>
    <br />
    
    <?php 
    echo '<div class="page-header"><h1>'.get_lang('ChamiloInstallation').' &ndash; '.get_lang('Version_').' '.$new_version.'</h1></div>';
    ?>
    <div class="row">
        <div class="span3">
            <div class="well">
                <ol>
                    <li <?php step_active('1'); ?>><?php echo get_lang('InstallationLanguage'); ?></li>
                    <li <?php step_active('2'); ?>><?php echo get_lang('Requirements'); ?></li>
                    <li <?php step_active('3'); ?>><?php echo get_lang('Licence'); ?></li>
                    <li <?php step_active('4'); ?>><?php echo get_lang('DBSetting'); ?></li>
                    <li <?php step_active('5'); ?>><?php echo get_lang('CfgSetting'); ?></li>
                    <li <?php step_active('6'); ?>><?php echo get_lang('PrintOverview'); ?></li>
                    <li <?php step_active('7'); ?>><?php echo get_lang('Installing'); ?></li>
                </ol>
            </div>
            <div id="note">
				<a class="btn" href="../../documentation/installation_guide.html" target="_blank">
                    <?php echo get_lang('ReadTheInstallationGuide'); ?>
                </a>
			</div>
        </div>
        
        <div class="span9">
            
<form class="form-horizontal" id="install_form" style="padding: 0px; margin: 0px;" method="post" action="<?php echo api_get_self(); ?>?running=1&amp;installType=<?php echo $installType; ?>&amp;updateFromConfigFile=<?php echo urlencode($updateFromConfigFile); ?>">
<?php   

    $instalation_type_label = '';
    if ($installType == 'new'){
        $instalation_type_label  = get_lang('NewInstallation');
    }elseif ($installType == 'update') {
        $update_from_version = isset($update_from_version) ? $update_from_version : null;
        $instalation_type_label = get_lang('UpdateFromDokeosVersion').(is_array($update_from_version) ? implode('|', $update_from_version) : '');
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
    }

    $builder->add('continue', 'submit', array('attr' => array('class' => 'btn')));
    $form = $builder->getForm();

    if ('POST' == $request->getMethod()) {
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();

            /* Drive-by sanitizing of the site URL:
             * Remove excessive trailing slashes that could break the
             * RewriteBase in .htaccess.
             *
             * See writeHtaccess() in
             * vendor/chamilo/chash/src/Chash/Command/Installation/CommonCommand.php
             */
            $data['institution_url'] = rtrim($data['institution_url'], '/').'/';

            $app['session']->set('portal_settings', $data);
            $url = $app['url_generator']->generate('admin-settings');

            return $app->redirect($url);
        }
    }

    return $app['twig']->render('settings.tpl', array('form' => $form->createView()));

})->bind('portal-settings');

// Admin settings.
$app->match('/admin-settings', function () use ($app) {
    $request = $app['request'];

    /** @var InstallCommand $command */
    $command = $app['console']->get('chamilo:install');

    $data = $command->getAdminSettingsParams();
    $builder  = $app['form.factory']->createBuilder('form', $data);
    foreach ($data as $key => $value) {
        $builder->add($key, $value['type'], $value['attributes']);
    }
    $builder->add('continue', 'submit', array('attr' => array('class' => 'btn')));

    $form = $builder->getForm();

    if ('POST' == $request->getMethod()) {
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $app['session']->set('admin_settings', $data);
            $url = $app['url_generator']->generate('resume');

            return $app->redirect($url);
        }
<<<<<<< HEAD
    }

    return $app['twig']->render('settings.tpl', array('form' => $form->createView()));

})->bind('admin-settings');

// Resume before installing.

$app->match('/resume', function () use ($app) {
    $request = $app['request'];
    $data = array();
    $portalSettings = $app['session']->get('portal_settings');
    $databaseSettings = $app['session']->get('database_settings');
    $adminSettings = $app['session']->get('admin_settings');

    if (!empty($portalSettings) && !empty($databaseSettings) && !empty($adminSettings)) {

        $form = $app['form.factory']->createBuilder('form', $data)
            ->add(
                'install',
                'submit',
                array(
                    'label' => 'Install',
                    'attr' => array('class' => 'btn btn-success')
                )
            )
            ->getForm();

        if ('POST' == $request->getMethod()) {
             $url = $app['url_generator']->generate('installing');

             return $app->redirect($url);
=======
        
        Log::notice("singledbForm: '$singleDbForm'");
        
		Database::query("SET storage_engine = MYISAM;");

		if (version_compare($my_old_version, '1.8.7', '>=')) {
			Database::query("SET SESSION character_set_server='utf8';");
			Database::query("SET SESSION collation_server='utf8_general_ci';");
			//Database::query("SET CHARACTER SET 'utf8';"); // See task #1802.
			Database::query("SET NAMES 'utf8';");
		}

		switch ($my_old_version) {
			case '1.6':
			case '1.6.0':
			case '1.6.1':
			case '1.6.2':
			case '1.6.3':
			case '1.6.4':
			case '1.6.5':
				include 'update-db-1.6.x-1.8.0.inc.php';
				include 'update-files-1.6.x-1.8.0.inc.php';
				//intentionally no break to continue processing
			case '1.8':
			case '1.8.0':
				include 'update-db-1.8.0-1.8.2.inc.php';
				//intentionally no break to continue processing
			case '1.8.2':
				include 'update-db-1.8.2-1.8.3.inc.php';
				//intentionally no break to continue processing
			case '1.8.3':
				include 'update-db-1.8.3-1.8.4.inc.php';
				include 'update-files-1.8.3-1.8.4.inc.php';
			case '1.8.4':
				include 'update-db-1.8.4-1.8.5.inc.php';
                include 'update-files-1.8.4-1.8.5.inc.php';
			case '1.8.5':
				include 'update-db-1.8.5-1.8.6.inc.php';
                include 'update-files-1.8.5-1.8.6.inc.php';
            case '1.8.6':
                include 'update-db-1.8.6-1.8.6.1.inc.php';
                include 'update-files-1.8.6-1.8.6.1.inc.php';
            case '1.8.6.1':
                include 'update-db-1.8.6.1-1.8.6.2.inc.php';
                include 'update-files-1.8.6.1-1.8.6.2.inc.php';
            case '1.8.6.2':
                include 'update-db-1.8.6.2-1.8.7.inc.php';
                include 'update-files-1.8.6.2-1.8.7.inc.php';
                // After database conversion to UTF-8, new encoding initialization is necessary
                // to be used for the next upgrade 1.8.7[.1] -> 1.8.8.
                Database::query("SET SESSION character_set_server='utf8';");
                Database::query("SET SESSION collation_server='utf8_general_ci';");
                //Database::query("SET CHARACTER SET 'utf8';"); // See task #1802.
                Database::query("SET NAMES 'utf8';");

            case '1.8.7':
            case '1.8.7.1':
                include 'update-db-1.8.7-1.8.8.inc.php';
                include 'update-files-1.8.7-1.8.8.inc.php';
            case '1.8.8':
            case '1.8.8.2':
                //Only updates the configuration.inc.php with the new version
                include 'update-configuration.inc.php';
            case '1.8.8.4':
            case '1.8.8.6':
                include 'update-db-1.8.8-1.9.0.inc.php';
                //include 'update-files-1.8.8-1.9.0.inc.php';
                //Only updates the configuration.inc.php with the new version
                include 'update-configuration.inc.php';

                break;
            case '1.9.0':
            case '1.9.2':
            case '1.9.4':
            case '1.9.6':
            case '1.9.6.1':
            default:
                break;
>>>>>>> 671b81dac4dc97d884c25abdb2468903ec20cf84
        }

        return $app['twig']->render(
            'resume.tpl',
            array(
                'form' => $form->createView(),
                'portal_settings' => $portalSettings,
                'database_settings' => $databaseSettings,
                'admin_settings' => $adminSettings
            )
        );
    } else {
        $url = $app['url_generator']->generate('check-database');

        return $app->redirect($url);
    }
})->bind('resume');

// Installation process.

$app->match('/installing', function () use ($app, $versionData) {

    $portalSettings = $app['session']->get('portal_settings');
    $adminSettings = $app['session']->get('admin_settings');
    $databaseSettings = $app['session']->get('database_settings');

    /** @var InstallCommand $command */
    $command = $app['console']->get('chamilo:install');

    $def = $command->getDefinition();
    $input = new Symfony\Component\Console\Input\ArrayInput(
        array(
            'name',
            'path' => realpath(__DIR__.'/../../').'/',
            'version' => $versionData['version']
        ),
        $def
    );

    $output = new BufferedOutput();
    $command->setPortalSettings($portalSettings);
    $command->setDatabaseSettings($databaseSettings);
    $command->setAdminSettings($adminSettings);

    $result = $command->run($input, $output);

    if ($result == 1) {
        $output = $output->getBuffer();
        $app['session']->getFlashBag()->add('success', 'Installation finished');
        $app['session']->set('output', $output);
        $url = $app['url_generator']->generate('finish');

        return $app->redirect($url);
    } else {
        $app['session']->getFlashBag()->add(
            'error',
            'There was an error during installation, please check your settings.'
        );
        $app['session']->getFlashBag()->add('error', $output->lastMessage);

        $url = $app['url_generator']->generate('check-database');

        return $app->redirect($url);
    }
})->bind('installing');

// Finish installation.
$app->get('/finish', function () use ($app) {
    $output = $app['session']->get('output');
    $message = $app['translator']->trans(
        'To protect your site, make the whole %s directory read-only (chmod 0555 on Unix/Linux)',
        array('%s' => $app['path.config'])
    );
    $app['session']->getFlashBag()->add('warning', $message);

    $message = $app['translator']->trans(
        'Delete the %s directory.',
        array('%s' => $app['path.base'].'install')
    );
    $app['session']->getFlashBag()->add('warning', $message);

    return $app['twig']->render('finish.tpl', array('output' => $output));
})->bind('finish');

// Middlewares.
$app->before(
    function () use ($app) {
    }
);

// Errors
/*
$app->error(function (\Exception $e, $code) use ($app) {
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            // $message = 'We are sorry, but something went terribly wrong.';
            $message = $e->getMessage();
    }
    $app['twig']->addGlobal('code', $code);
    $app['twig']->addGlobal('message', $message);

    return $app['twig']->render('error.tpl');
});
*/
if (PHP_SAPI == 'cli') {
    $console->run();
} else {
    $app->run();
}


