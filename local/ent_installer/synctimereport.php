<?php

require_once('../../main/inc/global.inc.php');
require_once($_configuration['root_sys'].'/local/ent_installer/locallib.php');
require_once($_configuration['root_sys'].'/local/ent_installer/getid_form.php');
require_once($_configuration['root_sys'].'/local/classes/database.class.php');
require_once($_configuration['root_sys'].'/local/classes/mootochamlib.php');

$DB = new DatabaseManager();

// Security.
api_protect_admin_script();

// Process controller
$reset = 0 + @$_REQUEST['reset'];
if ($reset) {
    $DB->delete_records('local_ent_installer', array());
}

// check install
$table = 'local_ent_installer';
$tablename = Database::get_main_table($table);
$sql = "CREATE TABLE IF NOT EXISTS $tablename (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestart` int(11) NOT NULL,
  `timerun` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  `inserterror` int(11) NOT NULL,
  `updateerror` int(11) NOT NULL,

  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
Database::query($sql);

require_once $_configuration['root_sys'].'/local/ent_installer/js/jqplotlib.php';
require_jqplot_libs();

$titlestr = get_string('synctimetitle', 'local_ent_installer');

// Three month horizon.

$horizon = time() - DAYSECS * 90;

$sumduration = 0;
$minduration = null;
$maxduration = 0;
$suminserts = 0;
$sumupdates = 0;
$suminserterrors = 0;
$sumupdateerrors = 0;
$overtime = 0;
$meantime = 0;
$normalmeantime = 0;
$sumdurationwovertimes = 0;

$timegrid = array(array(array(date('d-M-Y', time()),'0')));
if ($benchrecs = $DB->get_records_select('local_ent_installer', " timestart > $horizon ")) {
    $i = 0;
    $iwo = 0;
    foreach ($benchrecs as $b) {
        $sumduration += $b->timerun;
        if ($b->timerun > $maxduration) $maxduration = $b->timerun;
        if (is_null($minduration)) {
            $minduration = $b->timerun;
        } else {
            if ($b->timerun < $minduration) {
                $minduration = $b->timerun;
            }
        }
        $suminserts += $b->added;
        $sumupdates += $b->updated;
        $suminserterrors += $b->inserterrors;
        $sumupdateerrors += $b->updateerrors;
        if ($b->timerun > OVERTIME_THRESHOLD) {
            $overtime++;
        } else {
            $iwo++;
            $sumdurationwovertimes += $b->timerun;
        }
        $timegrid[0][] = array(date('d-M-Y', $b->timestart), $b->timerun);
        $i++;
    }
    $meantime = $sumduration / $i;
    $normalmeantime = $sumdurationwovertimes / $iwo;
}

// The header.
Display::display_header('');

echo '<div id="ent-installer-curve">';
$jqplot = array(
    'title' => array(
        'text' => get_string('syncbench', 'local_ent_installer'),
        'fontSize' => '1.3em',
        'color' => '#000080',
        ),
    'legend' => array(
        'show' => true, 
        'location' => 'e', 
        'placement' => 'outsideGrid',
        'marginLeft' => '10px',
        'border' => '1px solid #808080',
        'labels' => array(get_string('synctime', 'local_ent_installer')),
    ),
    'axesDefaults' => array('labelRenderer' => '$.jqplot.CanvasAxisLabelRenderer'),
    'axes' => array(
        'xaxis' => array(
            'label' => get_string('day', 'local_ent_installer'),
            'renderer' => '$.jqplot.DateAxisRenderer',
            'tickOptions' => array('formatString' => '%b&nbsp;%#d'),
            ),
        'yaxis' => array(
            'autoscale' => true,
            'tickOptions' => array('formatString' => '%.2f'),
            'label' => get_string('seconds', 'local_ent_installer'),
            'labelRenderer' => '$.jqplot.CanvasAxisLabelRenderer',
            'labelOptions' => array('angle' => 90)
            )
        ),
    'series' => array(
        array('color' => '#C00000'),
    ),
    );
echo '</div>';

jqplot_print_graph('plot1', $jqplot, $timegrid, 750, 250, 'margin:20px;');

echo '<div class="ent-installer-report-globals">';

$table = new StdClass();
$table->head = array('', '');
$table->align = array('right', 'left');
$table->size = array('60%', '40%');
$table->colstyles = array('head', 'value');
$table->data[] = array(get_string('inserts', 'local_ent_installer'), $suminserts);
$table->data[] = array(get_string('updates', 'local_ent_installer'), $sumupdates);
$table->data[] = array(get_string('inserterrors', 'local_ent_installer'), $suminserterrors);
$table->data[] = array(get_string('updateerrors', 'local_ent_installer'), $sumupdateerrors);
$table->data[] = array(get_string('overtimes', 'local_ent_installer'), $overtime);
$table->data[] = array(get_string('minduration', 'local_ent_installer'), sprintf('%0.2f', $minduration));
$table->data[] = array(get_string('maxduration', 'local_ent_installer'), sprintf('%0.2f', $maxduration));
$table->data[] = array(get_string('meantime', 'local_ent_installer'), sprintf('%0.2f', $meantime));
$table->data[] = array(get_string('normalmeantime', 'local_ent_installer'), sprintf('%0.2f', $normalmeantime));

echo Display::table($table->head, $table->data);

echo '</div>';

echo '<center>';
$url = $_configuration['root_web'].'/local/ent_installer/synctimereport.php?reset=1';
echo '<form name="resetform" method="GET" action="'.$url.'" >';
echo Display::button(get_string('reset', 'local_ent_installer'));
echo '</form>';
echo '</center>';

Display::display_footer();
