<h1>Recipe List</h1>
<div class="row">
    <div class="span8"><?php
    echo $this->Form->create('CategoryRecipe');?>
    <select>
    <option id="filter" name="filter" value="category">Category</option>
    <option id="recipe" name="filter" value="recipe">Recipe</option>
    </select>
        <?php
        //echo $this->Form->input('name', array('row'=> '1'),);
        echo $this->Form->end('Search');?>
    </div>
    <div class="span4">

    </div>
</div>

<div class="table-responsive">
    <table class="table ">
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
        <?php foreach($categoryRecipes as $categoryRecipe) : ?>
        <tr>
            <td>
                <?php echo $categoryRecipe['Recipe']['id'];?>
            </td>
            <td>
                <?php echo $this->Html->link($categoryRecipe['Recipe']['name'],
                array('controller' => 'recipes','action' => 'view' , $categoryRecipe['Recipe']['id'])) ;
                ?>
            </td>
            <td>
                <?php if(!empty($categoryRecipe['Recipe']['imagepath'])){
                echo $this->Html->image($categoryRecipe['Recipe']['imagepath']);
            }
            else{
                echo "No Image";
            }
                ?>
            </td>
            <td>
                <?php echo $categoryRecipe['Category']['name'];
                ?>
            </td>
            <td>
                <?php echo $categoryRecipe['Recipe']['ingredients'];
                ?>
            </td>
            <td>
                <?php echo $categoryRecipe['Recipe']['preparation'];
                ?>
            </td>



            <td><?php echo $categoryRecipe['Recipe']['created'];?></td>
<tr>
<?php endforeach ;?>
        <?php unset($categoryRecipe); ?>
    </table>
</div>
