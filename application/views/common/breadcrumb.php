<div class="container-fluid">
<?php
if(isset($breadcrumb)&& is_array($breadcrumb) && count($breadcrumb) > 0){
    ?>
    <div class="row-fluid">
        <div class="span12">
                    <ul class="breadcrumb">
                        <?php
                        foreach ($breadcrumb as $key=>$value) {
                            if($value!=''){
                                ?>
                                <li><a style="font-size:14px;" href="<?php echo $value; ?>"><?php echo $key; ?></a> </li>
                            <?php }else{?>
                                <li  style="font-size:14px;" class="active"><?php echo $key; ?></li>
                            <?php }
                        }
                        ?>
                    </ul>
        </div>
    </div>
<?php
}
?>
</div>
