<?php
// If you are using composer you will not need the autoloader
include __DIR__ . "/../autoloader.php";

// Initialise Dust PHP
$dust = new Dust\Dust();

// Use the current directory as the include path
$dust->includedDirectories = array(__DIR__ . "/");

// Set the substitutions for the template
$variables["version"] = $dust->getVersion();

// We are going to the the hello template that exists in the views directory
$template = $dust->compileFile("views/hello");

// Render the template applying the variables
$output = $dust->renderTemplate($template, $variables);

// Echo out the complete output
echo $output;
