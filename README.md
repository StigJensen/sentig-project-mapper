# sentig-project-mapper
Generates a map of the directory structure and prints out  all PHP, HTML, JS, CSS, and other relevant files into a single overview file.

Directory Scanner & Overview Generator
Author: Stig Erik Jensen
Version: 1.1
Date: 07 October 2024

Overview
This PHP script recursively scans a directory structure and generates a comprehensive map of all directories and relevant files. It also compiles the content of specified file types (like PHP, HTML, CSS, JS, and many others) into a single output file called overview.txt.

The script is designed to be lightweight and easy to use, making it ideal for developers who need a quick and clean overview of their project directory or want to document codebases.

Features
Directory Structure Map: A visual representation of the entire directory tree is generated, showing the nesting of directories and the files within them.
File Content Output: The content of all relevant file types is appended to the overview.txt file, with each file’s content separated by subheadings for easy reading.
Customizable File Types: By default, the script includes common web development files such as .php, .html, .css, .js, along with backend, scripting, and configuration files like .sql, .json, .yaml, .htaccess, and more. You can easily add or remove file types from the list.
Exclusion of Current Script: The script excludes itself from the scan, ensuring that it doesn't mistakenly include its own content in the output.
Supported File Types
Web and Code Files: .php, .html, .css, .js
Backend & Data Files: .sql, .json, .xml, .csv, .yml, .yaml, .ini
Scripting Files: .py, .sh, .bash, .pl, .rb, .go, .c, .cpp, .h, .java
Frontend & Template Files: .svg, .png, .jpg, .gif, .webp, .ico, .ttf, .otf, .woff, .woff2, .md, .twig, .hbs
System & Config Files: .log, .txt, .htaccess, Makefile, Dockerfile, .bashrc, .bash_profile
Usage
Download or Clone the repository.
Place the script in the root directory of your project or the directory you want to scan.
Run the script by accessing it via a web server or CLI (e.g., php yourscript.php).
The script will generate an overview.txt file in the same directory, containing the directory map and content of all relevant files.
How to Customize
To include or exclude specific file types, simply modify the $allowedExtensions array in the script.
You can change the root directory to scan by adjusting the $rootDir variable.
