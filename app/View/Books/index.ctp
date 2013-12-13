<!-- File: /app/View/Books/index.ctp -->
<h1>Blog Books</h1>
<table>
<tr>
<th>Id</th>
<th>Title</th>
<th>Action</th>
<th>Created</th>
</tr>
<!-- Here is where we loop through our $posts array, printing out post info -->
<?php foreach($books as $book) : ?>
<tr>
<td>
<?php echo $book['Book']['id'];?>
</td>
<td>
<?php echo $this->Html->link($book['Book']['name'],
array('controller' => 'books','action' => 'view' , $book['Book']['id'])) ; ?>
</td>
<td>
    <?php
    echo $this->Form->postLink(
        'Delete',
        array('action' => 'delete', $book['Book']['id']),
        array('confirm' => 'Are you sure?')
    );
    ?>
    <?php
    echo $this->Html->link(
        'Edit', array('action' => 'edit', $book['Book']['id'])
    );
    ?>
</td>


<td><?php echo $book['Book']['created_date'];?></td>
<tr>
<?php endforeach ;?>
<?php unset($book); ?>
</table>

<?php echo $this->Html->link('Add Book' , array('controller' => 'books','action' => 'add' )) ?>