<h1>Edit Book</h1>
<?php
echo $this->Form->create('Book');
echo $this->Form->input('name', array('rows'=> '3'));
echo $this->Form->input('id', array('type'=> 'hidden'));
echo $this->Form->end('Save Book');
?>
