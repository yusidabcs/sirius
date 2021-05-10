<?php 
if(!empty($pageContentFileViewArray[$content_id]['image']))
{
?>

<!-- banner_top content_<?php echo $content_id; ?> -->

<div id="content_<?php echo $content_id; ?>" class="carousel slide mb-3 container" data-ride="carousel">
    
<?php
    
    $image_count = count($pageContentFileViewArray[$content_id]['image']);
    
    if( $image_count > 1)
    {
?>
    <ol class="carousel-indicators">
<?php   
        $x = 0;

        while($x < $image_count)
        {
            if($x === 0)
            {
                echo '      <li data-target="#content_'.$content_id.'" data-slide-to="'.$x.'" class="active"></li>'."\n";
            } else {
                echo '      <li data-target="#content_'.$content_id.'" data-slide-to="'.$x.'"></li>'."\n";
            }
            
            $x++;
            
        }
?>      
    </ol>
<?php
    }
?>
    <div class="carousel-inner">
<?php
    
    $count = 0;
    
    foreach($pageContentFileViewArray[$content_id]['image'] as $image)
    {
        if($count == 0)
        {
?>
            <div class="carousel-item active">
                <img class="d-block w-100" src="<?php echo $image['image_prefix'].'/'.$image['file_name']; ?>" alt="<?php echo $image['sdesc']; ?>">
            </div>
<?php
        } else {
?>
            <div class="carousel-item">
                <img class="d-block w-100" src="<?php echo $image['image_prefix'].'/'.$image['file_name']; ?>" alt="<?php echo $image['sdesc']; ?>">
            </div>
<?php
        }
        
        $count++;
    }
?>
    </div>
    
</div>
    
<!-- end of banner_top content_<?php echo $content_id; ?> -->

<?php
}
?>
