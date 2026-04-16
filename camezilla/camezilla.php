<?php
require_once __DIR__ . '/autoload.php';

require_once __DIR__ . '/session.php';
require_once __DIR__ . '/globals.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/log.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/texts.php';
require_once __DIR__ . '/mail.php';
require_once __DIR__ . '/authentication.php';

require_once __DIR__ . '/security.php';
require_once __DIR__ . '/path.php';
require_once __DIR__ . '/api.php';
require_once __DIR__ . '/actions.php';
require_once __DIR__ . '/pages.php';
require_once __DIR__ . '/resources.php';

init_config();
init_logger();
init_database();
init_texts();
init_mail();
