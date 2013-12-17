<?php
/**
 * Created by JetBrains PhpStorm.
 * User: webonise
 * Date: 13/12/13
 * Time: 12:02 PM
 * To change this template use File | Settings | File Templates.
 */
class RecipesController extends AppController {
    public $helpers = array ('Html' , 'Form','Session');
    public $component = array('Session');
    public function index() {
        $value = $this->Recipe->find('all');
        $this->set('recipes', $value);
        //pr($value );
    }

    public function view($id = null) {
        if(!$id){
            throw new NotFoundException(_('Invalid post'));

        }
        $recipe =    $this->Recipe->findById($id);
        if(!$recipe){
            throw new NotFoundException(_('Invalid post'));
        }
        $this->set('recipe',$recipe);

    }
}