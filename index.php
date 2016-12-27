<?php

define('ODIN_ROOT', dirname(__FILE__));

require ODIN_ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

require ODIN_ROOT . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'Init.php';

Init::odinInit();