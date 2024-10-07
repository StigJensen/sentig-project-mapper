<?php
/*
 * Project: Project Mapper
 * Author: Stig Erik Jensen
 * Purpose: Generates a map of the directory structure and prints out
 *          all PHP, HTML, JS, CSS, and other relevant files into a single overview file.
 * Website: sentig.com
 * Date: 07 October 2024
 * Version: 1.2
 * 
 * --- A PHP Poem ---
 * 
 * In lines of code, a tale unfolds,
 * Directories deep, with secrets untold.
 * PHP whispers, the server obeys,
 * Files in the dark, now brought to day.
 * 
 * Loops and conditions, functions that gleam,
 * Turning data to action, a coder's dream.
 * From structure to output, a map we create,
 * A programmer’s journey, through each file's fate.
 */

// Define the root directory to scan
$rootDir = __DIR__; // You can change this to any directory you want to scan

// Get the current date and time
$dateTime = date('Y-m-d_H:i');

// Define the output file with current date and time
$outputFile = $rootDir . "/overview_$dateTime.txt"; // The output file for the results
$currentScript = basename(__FILE__); // Get the name of this script

// File types to include in the scan
$allowedExtensions = ['php', 'html', 'js', 'css', 'sql', 'json', 'xml', 'yml', 'yaml', 'ini', 'csv', 'py', 'sh', 'bash', 'pl', 'rb', 'go', 'c', 'cpp', 'h', 'java', 'svg', 'png', 'jpg', 'gif', 'webp', 'ico', 'ttf', 'otf', 'woff', 'woff2', 'md', 'twig', 'hbs', 'log', 'txt', 'htaccess', 'bashrc', 'bash_profile', 'Makefile', 'Dockerfile', 'dockerignore'];

// Open the output file for writing
$fileHandle = fopen($outputFile, 'w');

if (!$fileHandle) {
    die("Could not open file for writing.");
}

// Function to scan directory recursively
function scanDirectory($dir, $level = 0) {
    global $fileHandle, $currentScript, $allowedExtensions;
    $files = scandir($dir);
    
    // Create an indent for the directory level
    $indent = str_repeat('  ', $level);
    
    // Print directory name in the map
    fwrite($fileHandle, $indent . basename($dir) . "\n");

    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || $file === $currentScript) {
            continue;
        }
        
        $filePath = $dir . '/' . $file;

        if (is_dir($filePath)) {
            // Recurse into subdirectory
            scanDirectory($filePath, $level + 1);
        } else {
            // Get the file extension or handle special files like .htaccess and Dockerfile
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            if (in_array($extension, $allowedExtensions) || in_array($file, ['.htaccess', 'Makefile', 'Dockerfile'])) {
                // Add the file to the directory map
                fwrite($fileHandle, $indent . '  - ' . $file . "\n");
                
                // Add subheading and file content to the overview.txt file
                fwrite($fileHandle, "\n--- " . $filePath . " ---\n\n");
                
                $content = file_get_contents($filePath);
                fwrite($fileHandle, $content . "\n\n");
            }
        }
    }
}

// Start the scanning process from the root directory
fwrite($fileHandle, "Directory Structure Map:\n\n");
scanDirectory($rootDir);

// Close the file handle
fclose($fileHandle);

echo "Directory scan complete. Overview written to $outputFile\n";
