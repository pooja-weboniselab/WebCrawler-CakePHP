<?php
/**
 * Created by JetBrains PhpStorm.
 * User: webonise
 * Date: 19/12/13
 * Time: 6:37 PM
 * To change this template use File | Settings | File Templates.
 */
class CategoryRecipesController extends AppController{

    public $uses = array('CategoryRecipe');
    public $helpers = array ('Html' , 'Form','Session');
    public $component = array('Session');
    public function index() {
        if($this->request->is('post')) {
            $search = $this->request->data;
            var_dump($search);
        }
        $value = $this->CategoryRecipe->fetchRecords();
        //pr($value);
        $this->set('categoryRecipes', $value);

    }
}