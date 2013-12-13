<?php
/**
 * Created by JetBrains PhpStorm.
 * User: webonise
 * Date: 13/12/13
 * Time: 12:02 PM
 * To change this template use File | Settings | File Templates.
 */
class BooksController extends AppController {
    public $helpers = array ('Html' , 'Form','Session');
    public $component = array('Session');
    public function index() {
        $value = $this->Recipe->find('all');
        $this->set('recipes', $value);
        //pr($value );
    }
}