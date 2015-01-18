<?php

// Doodad Configuration

// Available variables:
// $docrootDir - the Apache DocumentRoot file directory
// $scriptDir  - the file directory containing the doodad.php script.
// $scriptURL  - the URL of the directory containing the doodad.php script.
// $iphone     - logical if the browser is an iPhone.
// $ipad       - logical if the browser is an iPad.

// PHP Markdown script location
// Could also use PHP Markdown Extra or PHP Markdown Extra Math
// Path relative to this config.php file or absolute file path

require '../php-markdown-extra-math/markdown.php';


// The web path to the document tree root
$homeWeb = $scriptURL . '/test/';

// The file path for the root of your document tree being served by this handler
$home = $docrootDir . $homeWeb; // $docroot is the Apache doument DocumentRoot

// Main CSS file (URL relative to script folder or starting with / from web root)
$cssURL = $scriptURL . '/css/main.css';

// Math support

$enableMath = true; // true or false
// MathJax script for math support. Must use PHP Markdown Extra Math (https://github.com/drdrang/php-markdown-extra-math).
$mathjaxScript = 'https://d3eoax9i5htok0.cloudfront.net/mathjax/latest/MathJax.js?config=TeX-MML-AM_HTMLorMML'; // Use the MathJax site
$mathjaxConfig = $scriptDir . '/mathjaxConfig.html'; // Filename of custom MathJax configuration.

// You could create separate templates for iPhone and iPad if you wanted

if ($iphone)
    {
    $templateHTML = './template.php';
    }
elseif ($ipad)
    {
    $templateHTML = './template.php';
    }
else
    {
    $templateHTML = './template.php';
    }

//require 'test.php';

?>
