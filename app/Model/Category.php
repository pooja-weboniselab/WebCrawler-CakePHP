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
        'Recipe' => array(
            'className' => 'Recipe' ,
            'foreignKey' => 'categoryId',
            'order' => 'Recipe.name DESC'
        )


    );

}
?>