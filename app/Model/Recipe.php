<?php
/**
 * Created by JetBrains PhpStorm.
 * User: webonise
 * Date: 13/12/13
 * Time: 12:09 PM
 * To change this template use File | Settings | File Templates.
 */
class Recipe extends AppModel {
    public $hasMany = array(
        'CategoryRecipe' => array('className' => 'CategoryRecipe')
    );

    /*public function dataFilter($name){
        if(!empty($name)){
            $data = $this->Category->find('first',array('condition' => array('category.name' => $name),'recursive' => 1,'order' => array('Category.created', 'Category.Name DESC'),'page' => 20));
        }else{
            $data = $this->Category->find('all',array('recursive' => 1,'order' => array('Category.created', 'Category.Name DESC'),'page' => 20));
        }
        return $data ;

    }*/
     public function saveRecipe($insertRecipe){
        return $this->saveMany($insertRecipe);
     }

   /* public function afterSave($created,$joinRecord) {
        if($created){
              $newData = $this->find('all',array('recursive' => '-1'));
              var_dump($newData) ;

            }

    }*/

}
?>