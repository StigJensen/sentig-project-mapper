<?php
/*
 * Project: Project Mapper
 * Author: Stig Erik Jensen
 * Purpose: Generates a map of the directory structure and prints out
 *          all PHP, HTML, JS, CSS, and other relevant files into a single overview file.
 * Website: sentig.com
 * Date: 07 October 2024
 * Version: 1.6
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
$allowedExtensions = ['php', 'html', 'htm', 'js', 'css', 'py', 'sh', 'bash', 'htaccess', 'bashrc', 'bash_profile', 'Makefile', 'Dockerfile', 'dockerignore'];

// Maximum file size (1MB in bytes)
$maxFileSize = 1024 * 1024; // 1MB

// Open the output file for writing
$fileHandle = fopen($outputFile, 'w');

if (!$fileHandle) {
    die("Could not open file for writing.");
}

// Function to scan directory and build map, only listing allowed file types and skipping large files and hidden files/directories
function buildDirectoryMap($dir, $level = 0) {
    global $fileHandle, $currentScript, $allowedExtensions, $maxFileSize;
    $files = scandir($dir);

    // Create an indent for the directory level
    $indent = str_repeat('  ', $level);

    // Store directory map as a string
    $directoryMap = $indent . basename($dir) . "\n";

    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || $file === $currentScript || $file[0] === '.') {
            continue; // Skip current/parent directories, current script, and hidden files
        }

        $filePath = $dir . '/' . $file;

        if (is_dir($filePath)) {
            // Recurse into subdirectory and add to map
            $directoryMap .= buildDirectoryMap($filePath, $level + 1);
        } else {
            // Get the file extension and check if it’s in the allowed extensions
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $fileSize = filesize($filePath); // Get the file size
            if (($fileSize <= $maxFileSize) && (in_array($extension, $allowedExtensions) || in_array($file, ['.htaccess', 'Makefile', 'Dockerfile']))) {
                // Add file to the directory map
                $directoryMap .= $indent . '  - ' . $file . " (Size: " . round($fileSize / 1024, 2) . " KB)\n";
            }
        }
    }
    return $directoryMap;
}

// Function to scan directory and append file content, skipping large files and hidden files/directories
function appendFileContents($dir) {
    global $fileHandle, $currentScript, $allowedExtensions, $maxFileSize;
    $files = scandir($dir);

    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || $file === $currentScript || $file[0] === '.') {
            continue; // Skip current/parent directories, current script, and hidden files
        }

        $filePath = $dir . '/' . $file;

        if (is_dir($filePath)) {
            // Recurse into subdirectory
            appendFileContents($filePath);
        } else {
            // Get the file extension and size
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $fileSize = filesize($filePath); // Get the file size
            if (($fileSize <= $maxFileSize) && (in_array($extension, $allowedExtensions) || in_array($file, ['.htaccess', 'Makefile', 'Dockerfile']))) {
                // Add subheading and file content to the overview.txt file
                fwrite($fileHandle, "\n--- " . $filePath . " ---\n\n");

                $content = file_get_contents($filePath);
                fwrite($fileHandle, $content . "\n\n");
            }
        }
    }
}

// Start the scanning process and write the directory map
fwrite($fileHandle, "Directory Structure Map:\n\n");
$directoryMap = buildDirectoryMap($rootDir);
fwrite($fileHandle, $directoryMap);

// Append file contents
appendFileContents($rootDir);

// Close the file handle
fclose($fileHandle);

echo "Directory scan complete. Overview written to $outputFile\n";
