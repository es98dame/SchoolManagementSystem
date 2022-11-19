<script language="JavaScript" >
	function godetail(bval){
		var frm = document.postform;
		frm.bno.value = bval;
		frm.submit();
	}
</script>

<div class="container">
	<div class="page-header">
		<h1>DOWNLOAD</h1>
	</div>


	<div class="row">
		<div class="col-md-12 text-center">

			<div style="float:left;">
			</div>
			<div style="float:right;">
				<img src="<?PHP echo base_url("assets/images/bbs/icon_total.gif");?>" align="absmiddle" border='0'>
				<span style="color:#888888; font-weight:bold;">Total <?php echo $total_count;?></span>
			</div>
		</div>
    </div>

	<div class="row">
		<div class="col-md-12">

			<table class="table table-striped table-hover">
				<tr>
					<th width="10%">No</th>
							<th width="50%">Subject</th>
					<th width="20%">Writer</th>
					<th width="20%">Date</th>
				</tr>
				<?php
				if($total_count == 0):?>
				<tr><td colspan='4' height=100 align=center>Thre is no data.</td></tr>
				<?php endif;
				$ss = $current_page+1;
				foreach ($result as $row)
				{
						$gap="";
						for($j=0; $j < $row->depth; $j++) {
							$gap .= "&nbsp;&nbsp;&nbsp;";
						}
						if($row->depth > 0 ) {
							$gap .= "<img src='" . base_url("assets/images/bbs/icon_reply.gif") . "' align='absmiddle'> ";
						}
				?>
				<tr>
				  <td><?php echo $ss;?></td>
				  <td><?php echo $gap;?>
				  <a href="#" title="<?php echo $row->board_no;?>" onClick="godetail('<?php echo $row->board_no;?>');"><?php echo $row->subject;?></a>
				  <?php if(!empty($row->uploadfile1) || !empty($row->uploadfile2)):?>
				  &nbsp;<img src='<?PHP echo base_url("assets/images/bbs/icon_file.gif");?>' align='absmiddle'>
				  <?php endif;?>
				  <?php if($row->commenttotal>0):?>
				  <span style="font-size: 10px; color: Silver;">(Reply<strong style="color: Red;"><?php echo $row->commenttotal;?></strong>)</span>
				  <?php endif;?>
				  </td>
				  <td><span class='member'><?php echo $row->writer;?></span></td>
				  <td><?php echo $row->regdate;?></td>
				</tr>
				<?php $ss++;
				};
				?>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 text-center">

			<div style="clear:both; margin-top:7px; height:31px;">
			<?php if( (isset($sfl) && isset($stx) ) || ($total_count == 0)): ?>
				<div style="float:left;"><?php echo anchor('apply/'.$tb_id.'/list','List',array('class'=>'btn btn-primary'));?></div>
			<?php endif;?>
			<?php if(!empty($session_userno)&&!empty($session_authno)):?>
				<div style="float:right;"><?php echo anchor('apply/'.$tb_id.'/write','Write',array('class'=>'btn btn-primary'));?></div>
			<?php endif;?>
			</div>

			 <div><?php echo $links; ?></div>

		</div>
	</div>

	<div class="row">
		<div class="col-md-12 text-center">


<?php 
$attSearchForm = array('name' => 'SearchForm', 'id' => 'SearchForm','class'=>'form-inline');
echo form_open('apply/'.$tb_id.'/list', $attSearchForm);
echo '<div class="form-group">';
echo form_hidden('current_page',''); 
$options = array('subject'=>'Subject','contents'=>'Contents','writer'=>'Writer');
echo form_dropdown('sfl', $options, 'subject', array('class'=>'form-control'));
echo form_input(array('name'=>'stx','class'=>'form-control','maxlength'=>'15','required'=>'required'), $stx);
echo form_submit('btn_submit', 'Search',array('class'=>'form-control'));
echo '</div>';
echo form_close(); 
?>



<?php 
$attpostform = array('name' => 'postform', 'id' => 'postform');
echo form_open('apply/'.$tb_id.'/read', $attpostform);
echo form_hidden('bno', ''); 
echo form_hidden('current_page', $current_page); 
echo form_close(); ?>
<div style="height: 20px;"></div>

		</div>
	</div>


</div>