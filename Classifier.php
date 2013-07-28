<?PHP
 /******************************************************************************\
 * zordGit                                                                      *
 *  Written by Jeremy Harmon                                                    *
 ********************************************************************************
 * Copyright (c) 2013, Jeremy Harmon                                            *
 * See docs/License.txt for Licensing information                               *
 \******************************************************************************/

ini_set('memory_limit','512M');
require_once './NBC/NaiveBayesClassifier.php';

$Extensions[] = array('Lang' => 'php', 'Exts' => array('php', 'phtml', 'php4', 'php5', 'phps'));
$Extensions[] = array('Lang' => 'c++', 'Exts' => array('cc', 'cpp', 'hpp', '.hh', '.hxx', '.h++', '.tcc'));
$Extensions[] = array('Lang' => 'c', 'Exts' => array('c', 'h'));
$Extensions[] = array('Lang' => 'ruby', 'Exts' => array('rb'));
$Extensions[] = array('Lang' => 'python', 'Exts' => array('py'));
$Extensions[] = array('Lang' => 'perl', 'Exts' => array('pm', 'pl', '.t', '.pod'));
$Extensions[] = array('Lang' => 'java', 'Exts' => array('java', 'class'));

function Rosetta_getLanguage($fileName) {
    global $Extensions, $numExtensions;
    
    $nbc = new NaiveBayesClassifier(array(
            'store' => array(
                    'mode'	=> 'redis',
                    'db'	=> array(
                            'db_host'	=> '127.0.0.1',
                            'db_port'	=> '6379',
                            'namespace'	=> 'languages'
                    )
            ),
            'debug' => FALSE
    ));

    $Langs = $nbc->classify(file_get_contents($fileName));
    
    // Add to the score of a language based on file extension:
    $fileExt = strtolower(pathinfo($fileName)['extension']);
    
    foreach( $Extensions as $Ext ) {
        if( in_array($fileExt, $Ext['Exts'])) {
            $Langs[$Ext['Lang']] += 0.1;
            break;
        }
    }
    
    arsort($Langs);
    return array_keys($Langs)[0];
}

?>
