<!-- File: /app/View/Books/index.ctp -->
<h1>Recipe List</h1>
    <div class="table-responsive">
<table class="table table-bordered">
    <tr><?php
        echo $this->Form->create('Filter');
        echo $this->Form->input('name', array('row'=> '1'));
        echo $this->Form->end('Search');?></tr>
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>ImagePath</th>
        <th>CategoryName</th>
        <th>Ingredients</th>
        <th>Preparation</th>
        <th>Created</th>
    </tr>
    <!-- Here is where we loop through our $posts array, printing out post info -->
    <?php foreach($recipes as $recipe) : ?>
    <tr>
        <td>
            <?php echo $recipe['Recipe']['id'];?>
        </td>
        <td>
            <?php echo $this->Html->link($recipe['Recipe']['name'],
            array('controller' => 'recipes','action' => 'view' , $recipe['Recipe']['id'])) ;
            ?>
        </td>
        <td>
            <?php if(!empty($recipe['Recipe']['imagepath'])){
            echo $this->Html->image($recipe['Recipe']['imagepath']);
             }
            else{
                echo "No Image";
              }
            ?>
        </td>
        <td>
            <?php echo $recipe['Category']['name'];
            ?>
        </td>
        <td>
            <?php echo $recipe['Recipe']['ingredients'];
            ?>
        </td>
        <td>
            <?php echo $recipe['Recipe']['preparation'];
            ?>
        </td>



        <td><?php echo $recipe['Recipe']['created'];?></td>
<tr>
<?php endforeach ;?>
    <?php unset($recipe); ?>
</table>
</div>
