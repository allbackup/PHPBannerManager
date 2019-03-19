<?
include('admin_design.php');
include('../application_config_file.php');
include('admin_auth.php');
include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);
include("header.php");

define ("DEBUG","0");
if(DEBUG)
	echo "<font style=\"color:#FF0000;font-family:verdana\"><center><b>*****************DEBUG mode*****************</b></center></font>".postvars($HTTP_POST_VARS, "\$HTTP_POST_VARS");

$database_table_name = $bx_db_table_banner_types;
$this_file_name = HTTP_SERVER_ADMIN.basename($_SERVER['PHP_SELF']);
$primary_id_name = "type_id";
$primary_id = $HTTP_GET_VARS[$primary_id_name];
$nr_of_cols = 5;
$page_title = "<u>Zones</u>";
$search_field_array = array('typename'.$slng);

isset($HTTP_GET_VARS['order_by']) ? ($order_by = $HTTP_GET_VARS['order_by']).($order_type = $HTTP_GET_VARS['order_type']) : ($order_by = $primary_id_name).($order_type = "asc");
isset($HTTP_POST_VARS['display_nr']) && $HTTP_POST_VARS['display_nr'] !='0' && is_numeric($HTTP_POST_VARS['display_nr']) ? ($display_nr = $HTTP_POST_VARS['display_nr']) : (isset($HTTP_GET_VARS['display_nr']) && $HTTP_GET_VARS['display_nr'] !=0 && is_numeric($HTTP_GET_VARS['display_nr']) ? ($display_nr = $HTTP_GET_VARS['display_nr']) : (isset($display_nr) && $display_nr != '0' && is_numeric($display_nr) ? ($display_nr = $display_nr) : ($display_nr = 10)));

if ($HTTP_POST_VARS['delete_button'] == "Delete Checked" || $HTTP_GET_VARS['delete_this'])	//delete via ID
{
	$delete_test = 1;
	for( $i_delete = 0 ; $i_delete < sizeof($HTTP_POST_VARS[$primary_id_name]); $i_delete++ )
	{
		if ($HTTP_POST_VARS['cycle'.$i_delete] == "on")
		{
			$delete_SQL = "delete from $database_table_name where $primary_id_name='".$HTTP_POST_VARS[$primary_id_name][$i_delete]."'";

			if($delete_query = bx_db_query($delete_SQL))
				$message = "Deleted Successfully";
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

			$deleteSQL = "delete from $bx_db_table_planning where p_zone_id='".$HTTP_POST_VARS[$primary_id_name][$i_delete]."'";
			$delete_query = bx_db_query($deleteSQL);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		}
	}
	
	if ($HTTP_GET_VARS['delete_this'])
	{
		$delete_SQL = "delete from $database_table_name where $primary_id_name='".$HTTP_GET_VARS[$primary_id_name]."'";
		
		if($delete_query = bx_db_query($delete_SQL))
			$message = "Deleted Successfully";
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

		$deleteSQL = "delete from $bx_db_table_planning where p_zone_id='".$HTTP_GET_VARS[$primary_id_name]."'";
		$delete_query = bx_db_query($deleteSQL);
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	}
	$nr_count = bx_db_count_results("select * from $database_table_name");
	($HTTP_GET_VARS['from'] > floor(($nr_count-1)/$display_nr)*$display_nr) ? ($from = $HTTP_GET_VARS['from'] = $HTTP_GET_VARS['from'] - $display_nr) : (isset($HTTP_GET_VARS['from']) ? ($from = $HTTP_GET_VARS['from']) : (isset($HTTP_POST_VARS['from']) ? ($from = $HTTP_POST_VARS['from']) : ($from = 0)));
	$from1 = $HTTP_GET_VARS['from'] = $from;
}
elseif ($HTTP_GET_VARS[$primary_id_name] && $HTTP_POST_VARS['submit'] )	//update via ID
{
	$update_SQL = "update ".$database_table_name." set typename".$slng." = '".$HTTP_POST_VARS['typename_en']."', width = '".$HTTP_POST_VARS['width']."', height = '".$HTTP_POST_VARS['height']."', target = '".$HTTP_POST_VARS['target']."', filesize = '".$HTTP_POST_VARS['filesize']."', zone_email='".$HTTP_POST_VARS['zone_email']."', zone_report_type='".$HTTP_POST_VARS['zone_report_type']."' where ".$primary_id_name."='".$HTTP_GET_VARS[$primary_id_name]."'";

	if(DEBUG)
		echo $update_SQL;
	else
	{
		if($update_query = bx_db_query($update_SQL))
			$message = "Updated Successfully";
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	}
}
elseif ($HTTP_POST_VARS['submit'])
{
	bx_db_insert($database_table_name, "type_id, typename".$slng.", width, height, target, filesize,zone_email,zone_report_type", "'', '".$HTTP_POST_VARS['typename_en']."', '".$HTTP_POST_VARS['width']."', '".$HTTP_POST_VARS['height']."', '".$HTTP_POST_VARS['target']."', '".$HTTP_POST_VARS['filesize']."','".$HTTP_POST_VARS['zone_email']."','".$HTTP_POST_VARS['zone_report_type']."'");
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

	/*
	generate new planning lines
	*/
	$insert_id = bx_db_insert_id();
	$selectSQL = "select * from $bx_db_table_planning_months where m_active='1'";
	$select_query = bx_db_query($selectSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$nr_month = bx_db_num_rows($select_query);
	
	while ($month_res = bx_db_fetch_array($select_query))
	{
		bx_db_insert($bx_db_table_planning, "p_id, p_zone_id, p_max_banners, p_period_id, p_h_price, p_c_price, p_p_price, p_unit", "'', '".$insert_id."', '0', '".$month_res['m_id']."', '0', '0', '0', '0'");
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	}
	/*
	end generating new planning lines
	*/

	$message = "Added Successfully";

	$order_by = $primary_id_name;
	$order_type = "desc";
}
else{}

$pre_condition = " 1"." order by ".$order_by." ".$order_type;

if ($HTTP_POST_VARS['submit'] || $HTTP_POST_VARS['x']==1 || isset($HTTP_GET_VARS['from']) || $HTTP_GET_VARS['search']) 
{
	$SQL = "select * from $database_table_name";
	$search_condition="";
	
	if ($HTTP_POST_VARS['search']) 
	{
	    $search_text=regexp_search($HTTP_POST_VARS['search']);
		$search_text_print=$HTTP_POST_VARS['search'];
		$is_search = 1;
	}
	elseif($HTTP_GET_VARS['search']) 
	{
	    $search_text=regexp_search($HTTP_GET_VARS['search']);
		$search_text_print=$HTTP_GET_VARS['search'];
		$is_search = 1;
	}
	else{}
	if(!empty($search_text))
	{
		$search_keywords = preg_split("/[\s,]+/",trim($search_text));
		$condition = " where ";

		for ($s=0 ; $s < sizeof($search_field_array); $s++)
		{
			for ($i=0;$i<sizeof($search_keywords);$i++) 
			{
				$condition .= "(".$search_field_array[$s]." like '%".strtolower($search_keywords[$i])."%') or ";
			}
			

		}
		$SQL .= " ".substr($condition, 0, -3)." and ".$pre_condition;
	}
	else
		 $SQL .= " where ".$pre_condition;
}
else
{
	$SQL = "select * from $database_table_name where ".$pre_condition;
}

isset($HTTP_POST_VARS['search']) ? ($search = urlencode(stripslashes($HTTP_POST_VARS['search']))) : (isset($HTTP_GET_VARS['search']) ? ($search = urlencode(stripslashes($HTTP_GET_VARS['search']))) : ($search = ""));

$get_vars = "display_nr=".$display_nr."&search=".$search."&order_by=".$order_by."&order_type=".$order_type;
$title_get_vars = "?display_nr=".$display_nr."&search=".$search."&from=0";
$link_get_vars = $get_vars."&from=".$from."&search=".$search;


$select_position_SQL = "select * from $database_table_name";
$select_position_query = bx_db_query($select_position_SQL);
SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
($HTTP_GET_VARS['from'] > floor((bx_db_num_rows($select_position_query)-1)/$display_nr)*$display_nr) ? ($from = $HTTP_GET_VARS['from'] = $HTTP_GET_VARS['from'] - $display_nr) : (isset($HTTP_GET_VARS['from']) ? ($from = $HTTP_GET_VARS['from']) : (isset($HTTP_POST_VARS['from']) ? ($from = $HTTP_POST_VARS['from']) : ($from = 0)));

$from1 = $from;
$search_time1 = microtime();
$result_array = step( $HTTP_GET_VARS['from'], $item_back_from, $SQL, $display_nr);
$search_time2 = microtime(); 
$search_time1 = strchr($search_time1," ") .".". substr($search_time1 , 2,strpos($search_time1," ")-2); 
$search_time2 = strchr($search_time2," ") . "." . substr($search_time2 , 2,strpos($search_time2," ")-2); 
$search_time = number_format(($search_time2 - $search_time1), 4);

//echo $SQL;
?>
<table border="0" cellpadding="0" cellspacing="0" width="<?=BIG_TABLE_WIDTH?>" align="center">
<tr>
	<td align="center"><font face="helvetica"><b><?=$page_title?></b></font><br><br></td>
</tr>
<?
if($HTTP_GET_VARS['edit'] || $HTTP_GET_VARS['action']=='add')
{

?>	<script language="JavaScript"><!--
        function formCheck(form) {

			if (form.typename<?php echo $slng;?>.value == "") 
            {
             alert("Please include the zone name.");
             return false;
			}
			if (form.width.value == "") 
            {
             alert("Please include the width.");
             return false;
			}
			if (form.height.value == "") 
            {
             alert("Please include the height.");
             return false;
			}
			if (form.target.selectedIndex == "0") 
            {
             alert("Please include the target.");
             return false;
			}
			if (form.filesize.value == "") 
            {
             alert("Please include the file size.");
             return false;
			}
        } 
        // --></script>	
<tr>
	<td>
<?
	$select_entry_SQL = "select * from $database_table_name where ".$primary_id_name."='".$primary_id."'";
	$select_entry_query = bx_db_query($select_entry_SQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));	
	$entry_result = bx_db_fetch_array($select_entry_query);
/***************************************************************************/
//Form is here
/***************************************************************************/
?>

<form method="post" action="<?=basename($HTTP_SERVER_VARS['PHP_SELF'])?>?<?php echo $primary_id_name;?>=<?=$HTTP_GET_VARS[$primary_id_name]?>&from=<?=isset($from1) && $from1>0 ? $from1 : '0'?>&display_nr=<?=$HTTP_GET_VARS['display_nr']?><?=($HTTP_GET_VARS['action']=='add' ? '' : '&order_by='.$HTTP_GET_VARS['order_by'].'&order_type='.$HTTP_GET_VARS['order_type'].'&search='.$search)?>" name="form1" onSubmit="return formCheck(this)" enctype="multipart/form-data">
<input type="hidden" name="joke_form" value="1">
<table align="center" border="0" cellspacing="0" cellpadding="1" width="90%">
<tr>
	<td bgcolor="<?php echo TABLE_BORDERCOLOR;?>">
		<table border="0" cellpadding="4" cellspacing="0" align="center" bgcolor="<?php echo INSIDE_TABLE_BG_COLOR2;?>" width="100%">
		<tr>
			<td bgcolor="<?php echo TABLE_HEAD_BGCOLOR;?>" colspan="2" align="center">
			<font color="<?=TABLE_HEAD_FONT_COLOR?>" size="2" face="verdana"><b>
				<?php
					if($HTTP_GET_VARS['action']=='add') 
						echo 'Add New One';
					else	
						echo 'Edit #'.$entry_result['o_number'];
				?>
				</b></font>
			</td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;<font size="3" color="#FF0000">*</font>Name:</b>&nbsp;&nbsp;</td>
			<td width="70%"><INPUT type="text" name="typename<?php echo $slng;?>" size="30" value="<?php echo $entry_result['typename'.$slng];?>" class="input"></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;<font size="3" color="#FF0000">*</font>Width:</b>&nbsp;&nbsp;</td>
			<td width="70%"><INPUT type="text" name="width" size="30" value="<?php echo $entry_result['width'];?>" class="input"></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;<font size="3" color="#FF0000">*</font>Height:</b>&nbsp;&nbsp;</td>
			<td width="70%"><INPUT type="text" name="height" size="30" value="<?php echo $entry_result['height'];?>" class="input"></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;<font size="3" color="#FF0000">*</font>Target:</b>&nbsp;&nbsp;</td>
			<td width="70%"><select name="target"><?php echo generate_options($entry_result['target'],$url_target);?></select></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;<font size="3" color="#FF0000">*</font>Banner size (maximum size):</b>&nbsp;&nbsp;</td>
			<td width="70%"><INPUT type="text" name="filesize" size="30" value="<?php echo $entry_result['filesize'];?>" class="input"> (Kbytes)</td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;Zone email:</b>&nbsp;&nbsp;</td>
			<td width="70%"><INPUT type="text" name="zone_email" size="30" value="<?php echo $entry_result['zone_email'];?>" class="input"></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;Receive report to zone email:</b>&nbsp;&nbsp;</td>
			<td width="70%">
				<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<input type="radio" style="border:0" name="zone_report_type" value="0" class="noborder" <?=$entry_result['zone_report_type']=='0' ? " checked" : ""?>>None<br>
						<input type="radio" style="border:0" name="zone_report_type" value="1" class="noborder" <?=$entry_result['zone_report_type']=='1' ? " checked" : ""?>>Daily<br>
						<input type="radio" style="border:0" name="zone_report_type" value="2" class="noborder"<?=$entry_result['zone_report_type']=='2' ? " checked" : ""?>>Weekly<br>
						<input type="radio" style="border:0" name="zone_report_type" value="3" class="noborder"<?=$entry_result['zone_report_type']=='3' ? " checked" : ""?>>Monthly<br>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2"><br><input type="submit" name="submit" value="<?
			switch($HTTP_GET_VARS['edit'])
			{
				case "edit_delete": echo "Update"; break;
				default : echo "Submit New"; break;
				}
			?>" class="button"></td>
		</tr>
		</table>
	</td>
</tr>
</table>
</form>
<!--**************************************************************************************************-->
	</td>
</tr>
<?
		if ($HTTP_GET_VARS['action']=='add')
		{
			exit;
			include(DIR_SERVER_ADMIN. 'admin_footer.php'); 
		}
}
?>
<?php
if($message) 
{?>
<tr>
	<td align="center">
		<?php echo bx_msg($message);?><br>
	</td>
</tr>
<?}?>
<tr>
	<td>
	<table align="center" border="0" cellspacing="0" cellpadding="0" width="98%">
	<tr>
		<td>
<?
if($is_search)
{
	echo '<font size="2">Searched for ';
	for ($i_search=0;$i_search<sizeof($search_keywords);$i_search++) 
		$string_search_keywords .= '<a href="'.$this_file_name.'?search='.$search_keywords[$i_search].'&order_by='.$primary_id_name.'&order_type=asc&display_nr='.$display_nr.'" style="text-decoration:underline;font-weight:bold">'.$search_keywords[$i_search].'</a>&nbsp;&nbsp;';
	echo $string_search_keywords.'</font>';
}
?>
		</td>
		<td align="right">
<?
$count_query = bx_db_query($SQL);
$couted_total = bx_db_num_rows($count_query);

echo "<font size=\"2\">Results <b>".number_format(($from+1),0, ',', ',')." - ".(($from+$display_nr)<$couted_total ? number_format(($from+$display_nr),0, ',', ',') : number_format(($couted_total),0, ',', ','))."</b> of <b>".number_format($couted_total,0, ',', ',')."</b>.".($is_search ? "Search took <b>".$search_time."</b> seconds" : "")."</font><br>";
?>
				
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td align="right">
		<?php echo make_next_previous_with_number( $from, $SQL, basename($HTTP_SERVER_VARS['PHP_SELF']), $get_vars ,$display_nr);?>
	</td>
</tr>
<tr>
	<td bgcolor="<?=TABLE_BORDERCOLOR?>" align="center"> 
		<table width="100%" border="0" cellspacing="1" cellpadding="3" align="center">
		<tr bgcolor="<?=TABLE_HEAD_BGCOLOR?>">
			<td align="center" width="5%"><font color="<?=TABLE_HEAD_FONT_COLOR?>">#</b></font></td>
			<td align="center" width="15%"><font color="<?=TABLE_HEAD_FONT_COLOR?>"><b><a href="<?=$this_file_name.$title_get_vars?>&order_by=o_number&order_type=<?=$HTTP_GET_VARS['order_by'] == "typename" ? ($HTTP_GET_VARS['order_type'] == "asc" ? "desc" : "asc") :  "asc"; ?>" style="color:#ffffff">Name</a></b></font></td>
			<td align="center" width="20%"><font color="<?=TABLE_HEAD_FONT_COLOR?>" size="1">Width x Height</font></td>
			<td align="center" width="17%"><font color="<?=TABLE_HEAD_FONT_COLOR?>"><b>Action</b></font></td>
			<td align="center" width="10%"><font color="<?=TABLE_HEAD_FONT_COLOR?>"><b>Select</b></font></td>
		</tr>
		<form method=post action="<?=$this_file_name?>?<?=$link_get_vars?>" name="forms">
<?
if (sizeof($result_array) == 0)
{
?>
		<tr bgcolor="<?=INSIDE_TABLE_BG_COLOR?>" align="center">
			<td colspan="<?=$nr_of_cols?>" align="center">
				<b>There Are No Result!</b>
			</td>
		</tr>
<?
}
?>
<?
for( $i = 0 ; $i < sizeof($result_array) ; $i++ )
{
?>
		<tr bgcolor="<?php echo ($i%2==0 ? INSIDE_TABLE_BG_COLOR : INSIDE_TABLE_BG_COLOR2)?>">
			<td class="text">
				<?php echo (($from)+$i+1);?>
			</td>
			<td class="text">
				&nbsp;<?=$result_array[$i]['typename'.$slng];?>
				<input type="hidden" name="<?=$primary_id_name?>[]" value="<?=$result_array[$i][$primary_id_name]?>">
			</td>
			<td class="text">
				<?=$result_array[$i]['width']?> x <?=$result_array[$i]['height'];?></a>
			</td>
			<td align="center" nowrap>
				&nbsp;[<a  href="<?=$this_file_name?>?<?=$link_get_vars?>&<?=$primary_id_name?>=<?=$result_array[$i][$primary_id_name];?>&edit=edit_delete">View/Edit</a>]&nbsp;&nbsp;
				
				
				[<a href="<?=$this_file_name?>?<?=$link_get_vars?>&<?=$primary_id_name?>=<?=$result_array[$i][$primary_id_name];?>&delete_this=1" onClick="return confirm('Are you sure you want delete this entry?')">Delete</a>]&nbsp;

			</td>
			<td align="center">
				<input type="checkbox" name="cycle<?=$i?>">
			</td>
		 </tr>
<?
}
?>
		<tr bgcolor="<?=INSIDE_TABLE_BG_COLOR?>">
			<td colspan="<?=$nr_of_cols?>" height="40">
				<table align="center" border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
				<td width="17%">
					&nbsp;
				</td>
				<td>
				</td>
				<td align="right">
				</td>
				<td align="right">
					<input type="submit" name="delete_button" value="Delete Checked" class="button" onClick="return confirm('Are you sure want delete these entries?\n');" style="width:112">
				</td>
				</tr>
				</table>
			</td>
			</form>
		</tr>
		</table>
	</td>
</tr>
<?php
if($is_search)
?>
<tr>
	<td align="right">
		<?=make_next_previous_with_number( $from, $SQL, basename($HTTP_SERVER_VARS['PHP_SELF']), $get_vars ,$display_nr);?>
	</td>
</tr>
<tr>
	<td align="right">
		<?
if ($search)
	echo "<a href=\"".$this_file_name."?display_nr=".$display_nr."\" style=\"background:#C5DCF5;font-weight:bold\">&nbsp;Show All&nbsp;</a>";
?>
	</td>
</tr>
<tr>
	<td>
		&nbsp;<a href="<?php echo $this_file_name?>?action=add&<?php echo $link_get_vars;?>" style="background:#C5DCF5;font-weight:bold">&nbsp;Add New One&nbsp;</a>
	</td>
</tr>
<tr>
	<td>
		<table border="0" cellspacing="1" cellpadding="3" width="400">
		<tr>
			<td width="34%">
			<form method=post action="<?=$this_file_name?>?order_by=<?=$primary_id_name?>&order_type=asc&display_nr=<?=$display_nr?>"  style="margin-width:0px;margin-height:0px">	
					<font size="2">Search For:</font>
			</td>
			<td width="20">
					<input type="hidden" name="x" value="1">
					<input type="text" name="search" value="<?=($HTTP_POST_VARS['search'] ? $HTTP_POST_VARS['search'] : $HTTP_GET_VARS['search'])?>" class="" size="20">
			</td>
			<td>
					<input type="submit" name="go" value="Go" class="button">
			</td>
			</form>																				
		</tr>
		<tr>
			<td>
				<form method=post action="<?=$this_file_name?>?order_by=<?=$primary_id_name?>&order_type=asc&search=<?=$search?>" style="margin-width:0px;margin-height:0px">
					<font size="2">Number Of Display:</font>&nbsp;
			</td>
			<td>
					<input type="text" name="display_nr" value="<?=$display_nr?>" class=""  size="7">
			</td>
			<td>
					<input type="submit" name="go" value="Go" class="button">
					<input type="hidden" name="x" value="1">
			</td>
			</form>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<?php
include('footer.php'); 
?>