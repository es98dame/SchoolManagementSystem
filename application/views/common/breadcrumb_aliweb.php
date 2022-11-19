<?php
    if(isset($breadcrumb)&& is_array($breadcrumb) && count($breadcrumb) > 0){
        ?><div style="margin:-20px 0px 0px 0px; padding:0px;">
                        <ul class="breadcrumb" style="background-color: #ffffff;">
                            <?php
                            foreach ($breadcrumb as $key=>$value) {
                                if($value!=''){
                                    ?>
                                    <li><a href="<?php echo $value; ?>"><?php echo $key; ?></a> </li>
                                <?php }else{?>
                                    <li class="active"><?php echo $key; ?></li>
                                <?php }
                            }
                            ?>
                        </ul>
          </div><?php    }    ?>
