<?PHP
 /******************************************************************************\
 * zordGit                                                                      *
 *  Written by Jeremy Harmon                                                    *
 ********************************************************************************
 * Copyright (c) 2013, Jeremy Harmon                                            *
 * See docs/License.txt for Licensing information                               *
 \******************************************************************************/

ini_set('memory_limit','512M');

function Rosetta_getLanguage($fileName) {
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

    $Langs = $nbc->classify(file_get_contents($fileName));
    
    // Add to the score of a language based on file extension:
    $Ext = strtolower(pathinfo($fileName)['extension']);
    if( array_key_exists($Ext, $Langs) )
        $Langs[$Ext] += 0.1;
    
    arsort($Langs);
    return array_keys($Langs)[0];
}

?>
