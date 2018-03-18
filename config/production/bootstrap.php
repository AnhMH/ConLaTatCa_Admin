<?php
/* 
 * Production's Config
 */

use Cake\Core\Configure;

define('USE_SUB_DIRECTORY', '');

Configure::write('API.Host', 'http://api.conlatatca.info/public/');
Configure::write('Config.HTTPS', false);

Configure::write('Config.CKeditor', array(
    'basel_dir'=>'/home/hoangan1/img.conlatatca.info/',
    'basel_url'=>'https://img.conlatatca.info/'
));
