<?php
require_once 'vendor/autoload.php';

use PngCleaner\PngCleaner;

PngCleaner::clean('/home/vmalygin/Documents/export/firewarks.png', '/home/vmalygin/Documents/export/new33.png', ['mkBS', 'mkBF'
    ]);

