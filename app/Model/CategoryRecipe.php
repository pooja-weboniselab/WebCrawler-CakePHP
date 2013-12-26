<?php
/**
 * Created by JetBrains PhpStorm.
 * User: webonise
 * Date: 19/12/13
 * Time: 6:37 PM
 * To change this template use File | Settings | File Templates.
 */
class CategoryRecipe extends AppModel {
    public $belongsTo = array(
        'Category', 'Recipe'
    );

    public function fetchRecords() {
        $data = $this->find('all',array('limit' => 50));
        return $data ;
    }

   /* public function saveRelation($insertJoin){
        return $this->saveMany($insertJoin ) ;
    }*/

}