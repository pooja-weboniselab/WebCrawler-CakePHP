<?php
/**
 * Created by JetBrains PhpStorm.
 * User: webonise
 * Date: 13/12/13
 * Time: 12:29 PM
 * To change this template use File | Settings | File Templates.
 */
class Category extends AppModel {
    public $hasMany = array(
       'CategoryRecipe' => array('className' => 'CategoryRecipe')

    );

    function saveData($insertData) {
       return $this->saveMany($insertData);
    }

    function fetchCategory(){
        $data = $this->find('all', array('recursive' => -1, 'limit' => 50));
        return $data ;
    }

    function saveRecipe($insertRecipe){
        return $this->Recipe->saveMany($insertRecipe) ;
    }




}
?>