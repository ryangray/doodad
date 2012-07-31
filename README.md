# Doodad

By Ryan Gray (<u2mr2os2@me.com>)

16 July 2012

A dynamic [Markdown][] serving script.

You can have a whole site served on-the-fly with Markdown, including the index 
files.

## Requirements

* [Apache web server][Apache]

* [PHP][] 5.3 or later

* A PHP Markdown converter

    - [PHP Markdown][PHPM]
    - [PHP Markdown Extra][PHPME]
    - [PHP Markdown Extra Math][PHPMEM]
    - Other PHP converter with a function called `markdown()` that accepts the Markdown text string or change the call yourself.

## Optional

If you use [PHP Markdown Extra Math][PHPMEM], you can enable [MathJax][] support 
to render embedded math using LaTeX syntax.

[Apache]: http://projects.apache.org/projects/http_server.html
[PHP]: http://www.php.net/
[Markdown]: http://daringfireball.net/projects/markdown/
[PHPM]:   http://michelf.com/projects/php-markdown/
[PHPME]:  http://michelf.com/projects/php-markdown/extra/
[PHPMEM]: https://github.com/drdrang/php-markdown-extra-math
[MathJax]: http://www.mathjax.org

## Installation

1. Put the folder containing the files in a directory under your Apache webroot folder.

2. Get one of the PHP Markdown converters above, and put it under your webroot somewhere.

3. Edit the `config.php` file to set the `require` statement to point to the PHP Markdown script.

4. Browse to the `doodad/test` folder, and you should get the test page.

## Setup

- Edit the `config.php` file:

    - Set `$homeWeb` to the URL path from root to the folder you want to serve with this script.
    
        You should copy and modify the `.htaccess` file in the `test` folder to this location.
        You can alternatively define this folder to be handled by this script in your 
        Apache `httpd.conf` file by defining a `<Location>` for it and start with the 
        directives in the sample `.htaccess` file in the `test` folder, adjusting them 
        as needed.

        The directives for the doodad folder being just under the web root:
        
            DirectoryIndex index.php index.html index.md index.text 
            # Path for PHP script needs to be from web root
            Action markdown /doodad/doodad.php
            AddHandler markdown .text .md
    
    - You can change the CSS location
    
        The main CSS adds a lot of junk from my (unreleased) larger version of 
        this project, and it also includes my Markdown CSS called [Buttondown][], 
        which is optimized for [Pandoc][] HTML output, but should still work well 
        for basic output by other converters.
        
        You'll notice in the `main.css` file, it defines rules that look for 
        words in image title attributes. You can add these to an image title to 
        get some styling effects such as:

            ![](../css/linkTypeIcons/img/doc.png "box border shadow rounded")
        
        Gives:
        
        ![](../css/linkTypeIcons/img/doc.png "box border shadow rounded")
    
        It does co-opt the title attribute some, but you could just add these 
        words at the end.
        
        It also includes my `linkTypeIcons` CSS rules that will automatically 
        append an icon based on the file extension of a file you have linked to.
        You can add some of your own or just comment out the include in `main.css`.

        There is also a `iOS.css` that is included, but I'm still working on it.
        My CSS-*fu* is still weak.
        
    - You can enable the math option if you use [PHP Markdown Extra Math][PHPMEM] as the PHP Markdown processor.
        
    See `config.php` for more details in the comments.

- Edit the `template.php` file to your liking.

    This is the template for the HTML page. It has a little PHP in it for the 
    dynamic parts, but is mostly the static HTML for the generated HTML page.

- For use in user folders:

    If you put this in a folder under a user's web folder (to access with say 
    `http://localhost/~joe/doodad/test`, you will need to have configured 
    Apache to be able to browse there.
    
    For Mac OS X, at least, you will need to create a file in `/etc/apache2/users` 
    named `username.conf`, where `username` is the short username (e.g., "joe" 
    in the example URL above). In that file, you should have at least:
    
        <Directory "/Users/joe/Sites/">
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>

    replacing "joe" with the appropriate username.
    As I understand it, this file should have root:wheel ownership, so do:
    
        sudo chown root:wheel joe.conf
        
    As a note, it seems that upgrading from Mac OS X 10.7 to 10.8 removed these 
    user files I had previously set up, which made my user sites not work. 
    As usual, it also replaced my modified `httpd.conf` file and thus disabled 
    PHP, but at least that is normal to have to uncomment when upgrading. 
    
## URL Query Options

`format=`

- `format=text`

    Display the source Markdown text.
    
        `http://mydomain.com/mdstuff/page1.md?format=text`
        
- `format=nav`

    Display a navigation page relative to the current location.
    It has two options for recursing down directories and including files.
    
    - `recurse=yes`, `recurse=no`
    
    - `files=yes`, `files=no`
    
    A nav link is built into the breadcrumb tail's "...".
    You can change the options it uses or include nav links with different 
    options in your page or in your banner. 

    Example: `http://mydomain.com/mdstuff/page1.md?format=nav&recurse=yes&files=no`
    
A version of Downdraft that uses [Pandoc][] has other format options.

[Pandoc]: http://johnmacfarlane.net/pandoc/

## Using

Just edit and create files in the folder being served, and they will be available 
right away. You can use relative links to link pages easily:

    [Go up](..)
    
    [Sibling page](sibling.md)
    
    [Relative page](subfolder/foo.md)
    
Since the `.htaccess` file defines `index.md` and `index.text` as additional 
defaults for `DirectoryIndex`, you can use a Markdown file with one of these 
names (or adjust the `.htaccess` file to the extensions you prefer) as a 
directory index, so that you can make the whole site with just Markdown files.

This is useful if you make a folder for each page and make the Markdown file 
as `index.md`, then you can put any local images you need in the folder and just 
link to them relative with no path:

    ![](foo.png)
    
The page will then have a URL that doesn't (need to) give the file name with 
the markdown extension. This way, you can switch favorite extensions later, or 
even later switch to having the `.md` files statically converted to `index.html` 
files along side, and those would be served instead (since `index.html` comes 
before `index.md` in the `DirectoryIndex` definition. The URLs that people have 
linked to would not need to change.


[Buttondown]: https://github.com/ryangray/buttondown
