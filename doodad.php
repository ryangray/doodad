<?php 

// Doodad 1.0
// Ryan Gray (u2mr2os2@me.com)
// 16 July 2012
//
// A handler script for dynamically serving Markdown files.
//

function getGetBool ($name, $default = false)
{
    // Return true if the $name exists in the $_GET query string with the value 'yes', else returns $default which defaults to false.
    
    if (array_key_exists($name, $_GET))
    
        return($_GET[$name] == 'yes');
        
    else
    
        return($default);
}
        
function getGetString ($name, $default = '')
{
    // Return the value of $name in the $_GET query string as a string. If it doesn't exist, then it returns the default string, which defaults to the empty string.
    
    if (array_key_exists($name, $_GET))
    
        return($_GET[$name]);
        
    else
    
        return($default);
}
        
function simpleHeader ($name, $headerExtras, $css = '')
{
    echo '<html><head>' . PHP_EOL;
    echo '<title>' . $name . '</title>' . PHP_EOL;
    echo '<style type="text/css">.banner form{display:none;}</style>';
    if ($css != '')
        echo '<link rel="stylesheet" href="' . $css . '" type="text/css" />';
    if ($headerExtras != '')
        readfile($headerExtras);
    echo '</head><body>' . PHP_EOL;
}

function simpleFooter ()
{
    echo '</body></html>';
}

function genCrumbString ($upath, $isIndex, $homeWeb, $crumbShowHome, $navDown, $truncate, $crumbSep = ' &gt; ')
{
    if (substr($upath,-1,1) == '/')
        $crumbPath = substr($upath, 0, -1);
    else
        $crumbPath = $upath;
    $crumbs = '';
    $crumbCount = 0;
    
    if ($crumbPath != $homeWeb)
        {
        $crumbName = urldecode(basename($crumbPath)); // this crumb is next dir name on end of path
        $crumbPath = dirname($crumbPath); // consume this dir from end of path
        $crumbCount = 1;
        if ($isIndex) // The crumb is the folder name, but it's not a link
            $crumbs = "<span class=crumbCurrent>" . $crumbName . "</span>";
        else // We started with a file, not an index, so first crumb is the dir it is in.
            $crumbs = "<a href='.'>" . $crumbName . "</a>";
        }
    elseif (!$isIndex) // we are on a page in the home dir
        {
        $crumbShowHome = true;
        }
    // Now all the rest are the same up one dir level crumbs
    $crumbLink = '';
    while ($crumbPath != $homeWeb and ($crumbCount < 4 or !$truncate))
        {
        $crumbCount = $crumbCount + 1;
        $crumbName = urldecode(basename($crumbPath)); // this crumb is next dir name on end of path
        $crumbPath = dirname($crumbPath); // consume this dir from end of path
        $crumbLink = '../' . $crumbLink;
        $crumb = "<a class=crumbLink href='" . $crumbLink . "'>" . $crumbName . "</a>";
        if ($crumbs == '')
            $crumbs = $crumb;
        else
            $crumbs = $crumb . $crumbSep . $crumbs;
        }
    $crumb = "<a href='" . $homeWeb . "'>Home</a>";
    if ($crumbPath == $homeWeb)
        {
        if ($crumbs == '' and $crumbShowHome)
            $crumbs = $crumb;
        elseif ($crumbs != '')
            $crumbs = $crumb . $crumbSep . $crumbs;
        }
    else
        {
        $crumbLink = '../' . $crumbLink;
        $crumbs = $crumb . $crumbSep . "<a class=crumbLink href='" . $crumbLink . "'>...</a>" . $crumbSep . $crumbs;
        }

    if ($navDown)
        {
        // We are using single quotes in the HTML because this string is passed as 
        // an argument on the command line enclosed in double-quotes since it can
        // contain spaces and ampersands.
        if ($crumbs != '')
            $crumbs .= $crumbSep . "<a title='Navigate' href='?format=nav&files=yes'>...</a>";
        elseif ($crumbShowHome)
            $crumbs = "<a href='?format=nav&files=yes'>Browse ...</a>";
        //else
        //    $crumbs = "&nbsp;"; // Forces an empty crumb banner to appear
        }
        
    return($crumbs);
}

/**
 * Adapted from Dirk McQuickly at http://stackoverflow.com/a/5883362
 *
 * function makeNavTree
 * iterates recursively through a directory and makes an unordered list of
 * the subfolders that are hyperlinked to jump to the folder clicked on.
 * 
 * usage: echo makeNavTree(relative/path/to/directory);
 * 
 * @param string $pathname
 * @return string
 */

 function makeNavTree($pathname, $includeFiles = false, $recurse = true)
{
   $path = realpath($pathname);

   if(!is_dir($path))
   {
      return "Path does not exist!";
   }

   $foldertree = new DOMDocument();

   /*
    * the rootelement of the tree 
    */
   $ul[""] = $foldertree->createElement('ul');
   $ul[""]->setAttribute('id', 'foldertree_root');
   $foldertree->appendChild($ul[""]);

   /*
    * iterate through the other folders
    */
    if ($recurse)
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
    else
        $objects = new IteratorIterator(new DirectoryIterator($path));

   foreach($objects as $name=>$value)
   {
      if($includeFiles or $value->isDir())
      {
         $relative_path = str_ireplace($path . DIRECTORY_SEPARATOR, "", $value->getPathname());

         $path_array = explode(DIRECTORY_SEPARATOR, $relative_path);

         $new_dir = array_pop($path_array);

         $directory_up = implode(DIRECTORY_SEPARATOR, $path_array);

         $random_id = md5(microtime());

         $li[$relative_path] = $foldertree->createElement('li');
         //$li[$relative_path]->setAttribute('id', 'li_' . str_ireplace(' ', '', $random_id));
         //$li[$relative_path]->setAttribute('class', 'folder'); //optional classname
         if ($new_dir[0] == '.' or $new_dir == 'cache')
         {
            $li[$relative_path]->setAttribute('style', 'display:none;'); 
         }
         if ($value->isDir())
            $a[$relative_path]  = $foldertree->createElement('a', $new_dir . '/');
         else
            $a[$relative_path]  = $foldertree->createElement('a', $new_dir);
         $relative_url  = str_ireplace(DIRECTORY_SEPARATOR, "/", $relative_path);
         $a[$relative_path]->setAttribute('href', $relative_url);
        $li[$relative_path]->appendChild($a[$relative_path]);
         $ul[$relative_path] = $foldertree->createElement('ul');
         //$ul[$relative_path]->setAttribute('id', 'ul_' . str_ireplace(' ', '', $random_id));

         $li[$relative_path]->appendChild($ul[$relative_path]);
         $ul[$directory_up]->appendChild($li[$relative_path]);

         if ($value->isDir())
            $iterator = new DirectoryIterator($value->getPathname());
      }
   }

   return $foldertree->saveHTML();
}

// ---------------------------------------------------------------------------- MAIN

// Get some device info
// These detectors are probably very naive as I am not a big web developer.
$iphone  = (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")  != false);
$iOS     = (strpos($_SERVER['HTTP_USER_AGENT'],"Mobile/") != false) and (strpos($_SERVER['HTTP_USER_AGENT'],"Safari/") != false);
$ipad = $iOS and !$iphone;

// Get some server and script locations
$docrootDir = $_SERVER['DOCUMENT_ROOT'];
$scriptDir = __DIR__; //dirname($_SERVER['SCRIPT_FILENAME']);
$scriptURL = dirname($_SERVER['SCRIPT_NAME']);

// Defaults

// The web path to the document tree root
$homeWeb = '/'; // Probably not right, but makes crumbs stop at root.
// The file path for the root of your document tree being served by this handler
$home = $docrootDir . $homeWeb; // $docrootDir is the Apache DocumentRoot
$cssURL = $scriptURL . '/css/main.css';
$crumbOnHome = false;
$enableMath = false;
$mathjaxScript = 'https://d3eoax9i5htok0.cloudfront.net/mathjax/latest/MathJax.js?config=TeX-MML-AM_HTMLorMML'; // Use the MathJax site
$mathjaxConfig = $scriptDir . '/html/mathjaxConfig.html'; // Filename of custom MathJax configuration.

// Synonyms for ?format=source to get the raw Markdown text
$textFormatAliases = array('md', 'markdown', 'text', 'txt', 'source');

$templateHTML = './template.php';

// ---------------------------------------------------------------------------- Configuration

// Still lots of custom stuff below in the code, but eventually it should all
// be here or in external files.

// Var names starting with $_ are a complete pandoc command switch or series of 
// switches, whereas those without are filenames, strings or other options, some 
// of which will be formed into command switches.

// Should have these options not include the pandoc command switch name or quotes
// and have a section later that forms them. There should also be a common 
// command switch assembled to simplify the $command assembly for each format.

require 'config.php';

// ---------------------------------------------------------------------------- End Configuration

// ----

$mdFile = realpath($_SERVER['PATH_TRANSLATED']);
$format = getGetString('format','html');

if ($mdFile)
    {
    // Parse filename bits
    $finfo = pathinfo($mdFile);
    $path = $finfo['dirname']; // File system directory name
    $fname = $finfo['basename']; // File name with extension, but no path
    $noext = $finfo['filename']; // File name without extension and without path
    $extension = $finfo['extension']; // File extenstion without dot
	$isIndex = ($noext == 'index');

	// Get the URL path to this file from webroot '/', without the filename into $upath
	$uri = $_SERVER['REQUEST_URI']; // REQUEST_URL gives empty string
	//$upath = parse_url($uri, PHP_URL_PATH); // This includes filename if given, but not for a directory default index file
    $uinfo = parse_url($uri);
    $upath = $uinfo['path']; // URL path of file without filename. If $isIndex, this will be the path the index file is in.
	$parts = explode('/',$upath);
	$last_part = end($parts);
	if (!$isIndex or $last_part == $fname) // Strip of filename
		{
		$parts[count($parts)-1] = '';
		$upath = implode('/',$parts);
        $uri = $upath . $fname;
        $name = $noext;
		}
    else
        {
        $uri = $upath;
        $name = basename($upath);
        }
    $url = 'http://' . $_SERVER['HTTP_HOST'] . $uri;
    $urlq = $url . '?' . http_build_query($_GET);
	$uname = $upath . $fname; // URL pathname

    // Parse URL query options
    
    $debug  = getGetBool('debug',false); // Shows output of conversion command, including errors
     
    // Determine output format
    // There are several repeated patterns that could be made into subroutines.
    
    if (in_array(strtolower($format), $textFormatAliases)) // -------------------- TEXT handler
        {
        header('Content-type: text/plain; charset=utf-8');
        readfile($mdFile);
        }
     elseif ($format == 'nav') // ?format=nav
        {
        header('Content-type: text/html; charset=utf-8');
        simpleHeader('Navigating : ' . dirname($uname), $headerExtras, $cssURL);
        if (file_exists($bannerFile))
            {
            readfile($bannerFile);
            echo '<hr />';
            }
        $includeFiles = getGetBool('files', false);
        $recurse = getGetBool('recurse', false);
        $tree = makeNavTree(dirname($mdFile), $includeFiles, $recurse);
        echo '<h1>Navigate to:</h1>' . PHP_EOL;
        $crumbs = genCrumbString($upath, $isIndex, $homeWeb, true, false, false);
        echo '<p>' . $crumbs . ' &gt; </p>' .PHP_EOL;
        echo '<div id="tree">';
        echo $tree;
        echo '</div>';
        simpleFooter();
        exit;
        }
    else // format=html
        {        
        $mdText = file_get_contents($mdFile);
        $mdHTML = markdown($mdText);
        $crumbs = genCrumbString($upath, $isIndex, $homeWeb, $crumbOnHome, true, true);
        if ($debug)
            {
            header('Content-type: text/plain; charset=utf-8');
            echo 'genCrumbString() output: "' . $crumb . '"' . PHP_EOL . PHP_EOL;
            echo 'markdown() output:' . PHP_EOL . PHP_EOL;
            echo $mdHTML;
            }
        else
            {
            /* Generate breadcrumb trail */
            header('Content-type: text/html; charset=utf-8');
            require $templateHTML;
            }
        }
    }
else
    {
    header('Content-type: text/html; charset=utf-8');
    echo "<p>Bad filename given</p>";
    }
?>