<div class="container">
<div class="row"><h3><?php echo h($recipe['Recipe']['name']);?></h3></div>
<div class = "row">
    <?php echo h($recipe['Category']['name']);?>
</div>

    <div class="row">
     <?php echo $this->Html->image($recipe['Recipe']['imagepath'],array('alt' => 'RecipeImage'));

        ?>
    </div>
    <div class="row">
            <h4>Ingredients</h4>
    </div>
    <div class="row">
            <?php echo h($recipe['Recipe']['ingredients']); ?>
    </div>
   <div class="row">
        <h4>Preparation</h4>
    </div>
    <div class="row">
        <?php echo h($recipe['Recipe']['preparation']); ?>
    </div>


</div>



