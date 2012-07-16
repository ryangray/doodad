<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- Some iOS size settings -->
<meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=1;" />
<!-- Make iPhone browser scroll the URL bar out of the way on page load -->
<script type="application/x-javascript">
if (navigator.userAgent.indexOf('iPhone') != -1) {
addEventListener("load", function() {
setTimeout(hideURLbar, 0);
}, false);
}
function hideURLbar() {
window.scrollTo(0, 1);
}
</script>

<?php

echo '<link rel="stylesheet" href="' . $cssURL . '" type="text/css" />' . PHP_EOL;

// $name is the name of the Markdown file without the file extension, or the 
// name of the folder if it is an index.md file.

echo '<title>' . $name . ' - My Website</title>' . PHP_EOL;

if ($enableMath and $mathjaxScript != '')
    {
    echo '<script src="' . $mathjaxScript . '"></script>';
    if ($mathjaxConfig != '')
        readfile($mathjaxConfig);
    }
?>

<!-- Banner for display (CSS hides this when printing) -->
</head><body>
<table id="banner" class="banner noprint" width=100%>
    <tr>
        <td style="border:none;vertical-align:middle;">
            <h3>Doodad</h3>
        </td>
        <td style="border:none;vertical-align:top;text-align:right;">
            <p><a href="?format=source">Source text</a>
            </p>
        </td>
    </tr>
</table>

<!-- Banner for printing (CSS hides this on screen) -->
<table id="letterhead" class="noscreen" width=100%>
    <tr>
        <td style="border:none;vertical-align:bottom;text-align:left;">
        </td>
        <td style="border:none;vertical-align:bottom;text-align:right;color:#888;text-transform:uppercase;font-size:10pt;">
        Doodad
        </td>
    </tr>
</table>

<?php

// $crumbs contains the HTML of the path to the document relative to the $homeWeb
// location with the folders on the path links to navigate to those folders and a 
// navigate link at the end as "...". 

if ($crumbs != '')
    echo '<p id="breadcrumbs" class="banner noprint">' . $crumbs . '</p>';
else
    echo '<hr />'; // Display an HR if at the $homeWeb location.

echo $mdHTML; // $mdHTML is the HTML conversion of the Markdown file.

?>

</body>
</html>
