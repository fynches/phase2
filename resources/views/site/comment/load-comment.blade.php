@if(count($tree) > 0)	
    <?php 
    printCategory($tree, 0, Auth::guard('site')->id());
    ?>
@endif