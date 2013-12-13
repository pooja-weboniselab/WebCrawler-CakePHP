<?php
class Book extends AppModel {
public $validate = array(
    'name' => array(
        'rule' => 'notEmpty'
    )


);

}
?>