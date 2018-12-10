<?php
    /*
        This file is part of OhHi
        http://github.com/brandon-lockaby/OhHi
        
        (c) Brandon Lockaby http://about.me/brandonlockaby for http://oh-hi.info
        
        OhHi is free software licensed under Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0)
        http://creativecommons.org/licenses/by-nc-sa/3.0/
    */
    require_once(dirname(__FILE__) . '/helpers.php');
    require_once(dirname(__FILE__) . '/OhHiFileCache.php');
    require_once(dirname(__FILE__) . '/OhHiImage.php');
    require_once(dirname(__FILE__) . '/OhHiDir.php');
//  silence notifications when get is not specified and index isnt in the array
    error_reporting(E_ALL ^ E_NOTICE);
    class OhHi {
        static $firstImage = '';
        static $lastImage = '';
        static function renderImages() {
            $dir = new OhHiDir(dirname($_SERVER['SCRIPT_NAME']));
            $get = strtolower($_GET['get']);
            $from = isset($_GET['from']) ? $_GET['from'] : '';
            switch($get) {
                case 'next':
                case 'below':
                    $images = $dir->getBelow($from, 32);
                    break;
                case 'previous';
                case 'above':
                    $images = $dir->getAbove($from, 32);
                    break;
                default:
                    $images = $dir->getIndex($from, 32);
                    break;
            }
            OhHi::$firstImage = $images[0] ? $images[0] : '';
            print('<div id="images">' . "\n");
            foreach($images as $image) {
                print($image->getHtml());
                OhHi::$lastImage = $image;
            }
            print("\t\t" . '</div>' . "\n"); // id="images"
        }
        static function renderIndex() {
            require (dirname(__FILE__) . '/OhHiHtmlInclude.php');
            $svr = $_SERVER['SERVER_NAME'];
            $dir = dirname($_SERVER['SCRIPT_NAME']);
            print('<!-- INCLUDE  - for GENERATED IMAGE pages -->' . "\n");
            print("\t\t" . '<title>oh-hi.info' . $dir . '</title>' . "\n");
            print("\t\t" . '<meta property="og:title" content="oh-hi.info' . $dir . '"/>' . "\n");
//             print("\t\t" . '<meta name="Description" content="oh-hi.info' . $dir . '"/>' . "\n");
            print("\t\t" . '<link rel="bookmark" href="/" />' . "\n");
            print("\t\t" . '<link rel="shortcut icon" type="image/png" href="/favicon.ico"/>' . "\n");
            print("\t\t" . '<link rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/"/>' . "\n");
            print("\t\t" . '<link rel="stylesheet" href="/oh-hi/css/oh-hi.css" />' . "\n");
            print("\t\t" . '<link rel="author" href="/info/"/>' . "\n");
            print("\t\t" . '<link rel="help" href="/info/"/>' . "\n");
            print("\t\t" . '<link rel="index" href="/" />' . "\n");
            print("\t\t" . '<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>' . "\n");            
            print("\t\t" . '<meta name="Keywords" content="oh-hi,brisbane,queensland,australia,cbd,queenstreet,mall,photographer,interesting,people,candid,public,image,photograph,picture,reference"/>' . "\n");
            print("\t\t" . '<meta name="robots" content="INDEX, FOLLOW"> ' . "\n");
            print("\t\t" . '<meta property="og:url" content="http://' . $svr . $dir . '/"/>' . "\n");
            print("\t\t" . '<meta property="og:type" content="website"/>' . "\n");
            print('<!-- END include  - for GENERATED IMAGE pages -->' . "\n");    
            require (dirname(__FILE__) . '/OhHiMenuInclude.php');
            $y=substr($dir,1,4);
            if (is_numeric($y)) {
                $m1=substr($dir,6,1);
                $m2=substr($dir,8);            
                if (!is_numeric($m1)) {
                    $m1 = 1;
//                }
//                if (!is_numeric($m2)) {
                    $m2 = 12;
                }
                $mn1 = date('F', mktime(0, 0, 0, $m1, 10));            
                $mn2 = date('F', mktime(0, 0, 0, $m2, 10));                        
                print("\n\t\t" . '<h1>' . $y . '</h1>' . "\n");
                print("\n\t\t" . '<h2>' . $mn1 . '-' . $mn2 . '</h2>' . "\n");
            } else {
                if ($dir == "/tech") {
                    print("\n\t\t" . '<h1>' . 'tech' . '</h1>' . "\n");
                    print("\n\t\t" . '<h2>' . 'Things and stuff' . '</h2>' . "\n");
                }
            }
            print("\t\t" . '<meta name="Description" content="oh-hi.info - ' . $y . ' ' . $mn1 . '-' . $mn2 . '"/>' . "\n");
            
?>

<!--googleoff: all-->
        <?php OhHi::renderImages(); ?>
 <!--googleon: all-->
    </body>
</html>
<?php
        }
        
        static function run() {
            $get = strtolower($_GET['get']);
            if($get === 'below' || $get === 'above') {
                OhHi::renderImages();
            }
            else {
                OhHi::renderIndex();
            }
        }
    }
?>

