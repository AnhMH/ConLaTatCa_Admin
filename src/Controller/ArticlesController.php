<?php

namespace App\Controller;

use Cake\Event\Event;

/**
 * Articles page
 */
class ArticlesController extends AppController {
    
    /**
     * Articles page
     */
    public function index() {
        include ('Bus/Articles/index.php');
    }
    
    /**
     * Add/update info
     */
    public function update($id = '') {
        include ('Bus/Articles/update.php');
    }
}
