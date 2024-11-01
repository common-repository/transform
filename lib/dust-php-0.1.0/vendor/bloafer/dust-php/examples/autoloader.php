<?php
spl_autoload_register(function ($class) {
    include __DIR__ . "/../src/" . implode(DIRECTORY_SEPARATOR, explode("\\", $class)) . ".php";
});
