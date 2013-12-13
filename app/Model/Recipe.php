<?php
/**
 * Created by JetBrains PhpStorm.
 * User: webonise
 * Date: 13/12/13
 * Time: 12:09 PM
 * To change this template use File | Settings | File Templates.
 */
class Recipe extends AppModel {
    public $belongsTo = array(
        'Category' => array(
            'className' => 'Category',
            'foreignKey' => 'categorie_id'
        )
    );
}
?>