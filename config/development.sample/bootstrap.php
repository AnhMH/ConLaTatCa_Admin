<?php
/* 
 * Development's Config
 */

use Cake\Core\Configure;

define('USE_SUB_DIRECTORY', '/baroque/admin');

Configure::write('API.Host', 'http://api.baroque.localhost/');
Configure::write('Config.HTTPS', false);

Configure::write('Config.CKeditor', array(
    'basel_dir'=>'path/upload/',
    'basel_url'=>'http://img.baroque.dev/'
));
