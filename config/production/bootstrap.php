<?php
/* 
 * Production's Config
 */

use Cake\Core\Configure;

define('USE_SUB_DIRECTORY', '');

Configure::write('API.Host', '');
Configure::write('Config.HTTPS', true);

Configure::write('Config.CKeditor', array(
    'basel_dir'=>'/var/www/img/uploads/',
    'basel_url'=>'https://ameeplus.com/img/uploads/'
));
