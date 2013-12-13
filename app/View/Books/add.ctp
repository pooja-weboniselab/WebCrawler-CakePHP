<h1>Add Book</h1>
<?php
  echo $this->Form->create('Book');
  echo $this->Form->input('name', array('rows'=> '3'));
  echo $this->Form->end('Save Book');
?>