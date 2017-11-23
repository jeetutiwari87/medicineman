<?php
wp_enqueue_script('jquery');
wp_enqueue_script('thickbox');
wp_enqueue_style('thickbox');

wp_enqueue_script('media-upload');
wp_enqueue_script('wptuts-upload');

$ep = new HiringPage();	
if(isset($_REQUEST['pid']) && $_REQUEST['pid'] !=''){
$sql = "SELECT * FROM ".$table_prefix."hirings WHERE id = '".$_REQUEST['pid']."'";
$row=(array)$wpdb->get_row($sql);

$this->addHeader(); 

?>

<div id = "icon-options-general" class = "icon32"></div>
<div class="wrap custom-wrap">
<h1>View Hirings</h1>




                <table class="widefat striped_cus max-table" style="width:100%;">

                  <tr valign="top">

                    <th width="40%" class="botright"><label for="productName">Name</label></th>

                    <td width="60%" class="bottom">

                   <?php echo $row['name'];?>

                    </td>

                  </tr>
				   <tr valign="top">

                    <th width="40%" class="botright"><label for="productName">Address</label></th>

                    <td width="60%" class="bottom">

                   <?php echo $row['address'];?>

                    </td>

                  </tr>
				   <tr valign="top">

                    <th width="40%" class="botright"><label for="productName">Age</label></th>

                    <td width="60%" class="bottom">

                   <?php echo $row['age'];?>

                    </td>

                  </tr>
				  <tr valign="top">

                    <th width="40%" class="botright"><label for="productName">What position are you looking for</label></th>

                    <td width="60%" class="bottom">

                   <?php echo $row['what_position_looking_for'];?>

                    </td>

                  </tr>
				  
				  <tr valign="top">

                    <th width="40%" class="botright"><label for="productName">Tell us about yourself</label></th>

                    <td width="60%" class="bottom">

                   <?php echo $row['tell_us_yourself'];?>

                    </td>

                  </tr>
				  
				    <tr valign="top">

                    <th width="40%" class="botright"><label for="productName">Briefly tell us why you'd like this job and why you'd be a good fit</label></th>

                    <td width="60%" class="bottom">

                   <?php echo $row['briefly_tell_us_yourself'];?>

                    </td>

                  </tr>
				  
				  <tr valign="top">

                    <th width="40%" class="botright"><label for="productName">Resume</label></th>

                    <td width="60%" class="bottom">

                   <?php echo $row['resume'];?>

                    </td>

                  </tr>
				  <tr valign="top">

                    <th width="40%" class="botright"><label for="productName">Cover Letter</label></th>

                    <td width="60%" class="bottom">

                   <?php echo $row['cover_letter'];?>

                    </td>

                  </tr>
				  
				   <tr valign="top">

                    <th width="40%" class="botright"><label for="productName">Availability</label></th>

                    <td width="60%" class="bottom">

                   <?php echo date('m/d/Y h:i a',strtotime($row['availability']));?>

                    </td>

                  </tr>
				  
				  
                </table>
                
</div>

	
			
				<?php

} 
else {

 
 $total_query     = "SELECT COUNT(1) FROM ".$table_prefix."hirings";
 $total             = $wpdb->get_var( $total_query );
 $items_per_page = 50;
 $page             = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
 $offset         = ( $page * $items_per_page ) - $items_per_page;
 
 $sql = "SELECT * FROM ".$table_prefix."hirings WHERE 1 order by id DESC";
 $sql.= " LIMIT $offset, $items_per_page";
 
 $totalPage         = ceil($total / $items_per_page);
 $data=$wpdb->get_results($sql);

?>

<?php $this->addHeader(); ?>

<div id = "icon-options-general" class = "icon32"></div>
<div class="wrap custom-wrap">
<h1>Hirings</h1>




<table class="wp-list-table widefat fixed striped posts">

  <thead>
	<tr valign="top" class="padding15">
	 	<th><b>Name</b></th>
		<th><b>Address</b></th>
		<th><b>Age</b></th>
		<th><b>What position are you looking for</b></th>
		<th><b>Availability</b></th>
		
		<th><b>Created</b></th>
	</tr>
 </thead>
 <tbody>

  <?php

		$iItem = 0;

		foreach ($data as $row) {
			$row=(array)$row;
			$iItem++;

			$editURL = $_SERVER["REQUEST_URI"]."&amp;pid=".$row['id'];

  ?>

  <tr valign="top">


		  
	<td class="topright">
		<a href="<?php echo $editURL; ?>"><b><?php echo $row['name']; ?></b></a>
		<div class="row-actions"><span class="edit"><a title="View this item" href="<?php echo $editURL; ?>">View</a></div>

	
	</td>
  
   <td class="topright"><?php echo $row['address']; ?></td>
   <td class="topright"><?php echo $row['age']; ?></td>
   <td class="topright"><?php echo $row['what_position_looking_for']; ?></td>
   <td class="topright">
	<span class="abbr"><?php echo date('m/d/Y',strtotime($row['availability'])); ?></span>
    <br>
    <?php echo date('H:i:s',strtotime($row['availability'])); ?>
    </td>
   
    <td class="topright">
	<span class="abbr"><?php echo date('m/d/Y',strtotime($row['created_at'])); ?></span>
    <br>
    <?php echo date('H:i:s',strtotime($row['created_at'])); ?>
    </td>
  
  </tr>

  <?php

		}//end of while 



  ?>

</tbody>

</table>
</div>
<style type="text/css">
.abbr{border-bottom:1px dotted;}
.wrap.custom-wrap{
  font-family: "Open Sans",sans-serif;
}	
.wp-list-table .padding15 th{padding:15px 10px!important;}
.stn_pagi_new{ float:right; margin-top:10px; margin-right:20px;}
.stn_pagi_new .page-numbers {
   background: #00B01D !important;
  border: 1px solid #7BA235;
 /* border-radius: 16px;*/
  padding:6px 4px 6px 4px;
  margin-right: 5px;
	float: left;
  color: #000;
  width: auto;
  text-decoration: none;
  height: 25px;
  text-align: center;
  line-height: 24px;
  
}
.stn_pagi_new span.current{
	color:#FFF;
}

</style>
<div class="stn_pagi_new">
<?php
if($totalPage > 1){
	$customPagHTML     =  paginate_links( array(
							'base' => add_query_arg( 'cpage', '%#%' ),
							'format' => '',
							'prev_text' => __('&laquo;'),
							'next_text' => __('&raquo;'),
							'total' => $totalPage,
							'current' => $page
						  ));
	echo $customPagHTML;
	
}
?>
</div>
<script type="text/javascript">
function deleteProduct(formName){
	if(confirm("Are you sure you want to delete this record?")){
		document.getElementById(formName).submit();
	}
}
</script>

<?php

}
?>
<style type="text/css">
.striped_cus th{background-color:rgb(249, 249, 249);}
.striped_cus td.bottom.botright{background-color:rgb(249, 249, 249);}
.striped_cus tr{
	border-bottom:1px solid #eee;
	vertical-align: middle;
}
.striped_cus tr label{
	margin:0px; 
}
.wrap.custom-wrap{
  font-family: "Open Sans",sans-serif;
}	
.preview_image {
  display: block;
  margin: 10px 0;
}
.wp-core-ui .button-primary.btn-lg-cus {
  font-size: 16px;
  height: auto;
  padding: 6px 12px;
}
.striped_cus input.button{
	padding:6px 12px;
	height: auto;	
}
.striped_cus input[type="text"],
.striped_cus select{
	padding:10px;
	border-radius:0px;
	height: auto;
	min-width:200px 
}
.striped_cus.max-table{max-width:991px;}
.wrap.custom-wrap .widefat td {
  vertical-align: middle;
}
</style>
<?php
