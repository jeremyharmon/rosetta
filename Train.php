<?PHP
 /******************************************************************************\
 * zordGit                                                                      *
 *  Written by Jeremy Harmon                                                    *
 ********************************************************************************
 * Copyright (c) 2013, Jeremy Harmon                                            *
 * See docs/License.txt for Licensing information                               *
 \******************************************************************************/

// We could disable the memory limit, but we don't want this to run
// away with system resources. The system this is ran on has plenty of RAM so I 
// set it to 1 gigabyte, but in reality I've never seen it go over 100MB with ~1300
// samples.
ini_set('memory_limit', '1G');

dl('redis.so');
require_once './NBC/NaiveBayesClassifier.php';

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

$Samples = readFiles('./Samples');

echo 'Training using ' . count($Samples) . ' samples...';

foreach( $Samples as $Sample )  {
    $nbc->train(file_get_contents($Sample['File']), $Sample['Type']);
}


function readFiles($Dir) {
    $dHandle = opendir($Dir);
    $Files = array();

    while( ($File = readdir($dHandle)) !== false ) {
        if( $File == '.' || $File == '..' )
            continue;
        $Path = "$Dir/$File";
        if( is_dir($Path) )
           $Files = array_merge($Files, readFiles($Path));
        else {
            $Type = pathinfo($Path)['dirname'];
            $Type = substr($Type, strrpos($Type, '/')+1);
            $Files[] = array('Type' => $Type, 'File' => $Path);
        }
    }

    return $Files;
}

echo "done.\n";

?>
