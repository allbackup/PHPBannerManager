<?
include('admin_design.php');
include('../application_config_file.php');
include('admin_auth.php');
include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);
include("header.php");

$database_table_name = $bx_db_table_banner_users;
$this_file_name = HTTP_SERVER_ADMIN.basename($_SERVER['PHP_SELF']);
$primary_id_name = "user_id";
$primary_id = $HTTP_GET_VARS[$primary_id_name];
$nr_of_cols = 5;
$page_title = "<u>Users</u>";
$search_field_array = array('username','name');

isset($HTTP_GET_VARS['order_by']) ? ($order_by = $HTTP_GET_VARS['order_by']).($order_type = $HTTP_GET_VARS['order_type']) : ($order_by = $primary_id_name).($order_type = "asc");
isset($HTTP_POST_VARS['display_nr']) && $HTTP_POST_VARS['display_nr'] !='0' && is_numeric($HTTP_POST_VARS['display_nr']) ? ($display_nr = $HTTP_POST_VARS['display_nr']) : (isset($HTTP_GET_VARS['display_nr']) && $HTTP_GET_VARS['display_nr'] !=0 && is_numeric($HTTP_GET_VARS['display_nr']) ? ($display_nr = $HTTP_GET_VARS['display_nr']) : (isset($display_nr) && $display_nr != '0' && is_numeric($display_nr) ? ($display_nr = $display_nr) : ($display_nr = 10)));

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

$get_vars = "display_nr=".$display_nr."&search=".$search."&order_by=".$order_by."&order_type=".$order_type.($HTTP_GET_VARS['action']=='val' ? '&action=val' : '').($HTTP_GET_VARS['action']=='rem' ? '&action=rem' : '').($HTTP_GET_VARS['action']=='stats' ? '&action=stats' : '');
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
			if (form.username.value == "") 
            {
             alert("Please include the username.");
             return false;
			}
			if (form.password.value == "") 
            {
             alert("Please include the password.");
             return false;
			}
			if (form.name.value == "") 
            {
             alert("Please include the name.");
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
<form method="post" action="<?=basename($HTTP_SERVER_VARS['PHP_SELF'])?>?<?php echo $primary_id_name;?>=<?=$HTTP_GET_VARS[$primary_id_name]?>&from=<?=isset($from1) ? $from1 : '0'?>&display_nr=<?=$HTTP_GET_VARS['display_nr']?><?=($HTTP_GET_VARS['action']=='add' ? '' : '&order_by='.$HTTP_GET_VARS['order_by'].'&order_type='.$HTTP_GET_VARS['order_type'].'&search='.$search)?>" name="form1" onSubmit="return formCheck(this)" enctype="multipart/form-data">
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
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;<font size="3" color="#FF0000">*</font>Username:</b>&nbsp;&nbsp;</td>
			<td width="70%"><INPUT type="text" name="username" size="30" value="<?php echo $entry_result['username'];?>" class="input"></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;<font size="3" color="#FF0000">*</font>Password:</b>&nbsp;&nbsp;</td>
			<td width="70%"><INPUT type="text" name="password" size="30" value="<?php echo $entry_result['password'];?>" class="input"></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;<font size="3" color="#FF0000">*</font>Name:</b>&nbsp;&nbsp;</td>
			<td width="70%"><INPUT type="text" name="name" size="30" value="<?php echo $entry_result['name'];?>" class="input"></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;Phone:</b>&nbsp;&nbsp;</td>
			<td width="70%"><INPUT type="text" name="phone" size="30" value="<?php echo $entry_result['phone'];?>" class="input"></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;Fax:</b>&nbsp;&nbsp;</td>
			<td width="70%"><INPUT type="text" name="fax" size="30" value="<?php echo $entry_result['fax'];?>" class="input"></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right"><b>Description:</b>&nbsp;</td>
			<td><TEXTAREA cols="55" id=txt_area1 name="description" rows="10" class="input"><?=stripslashes($entry_result['description']);?></TEXTAREA><br><font size="2" face="verdana">(max.250 char</font>)</td>
		</tr>
		<tr valign="top">
			<td align="right" width="30%"><br><input type="checkbox" name="without_review" value="yes" <?php echo $entry_result['without_review']=='yes' ? 'checked':'';?>>&nbsp;&nbsp;&nbsp;</td>
			<td class="lila_bold_text" width="70%"><br><b>Client can manage his banners without validation</b>&nbsp;&nbsp;</td>
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
			<td align="center" width="15%"><font color="<?=TABLE_HEAD_FONT_COLOR?>"><b><a href="<?=$this_file_name.$title_get_vars?>&order_by=o_number&order_type=<?=$HTTP_GET_VARS['order_by'] == "name" ? ($HTTP_GET_VARS['order_type'] == "asc" ? "desc" : "asc") :  "asc"; ?>" style="color:#ffffff">Name</a></b></font></td>
			<td align="center" width="20%"><font color="<?=TABLE_HEAD_FONT_COLOR?>"><b><a href="<?=$this_file_name.$title_get_vars?>&order_by=o_summary&order_type=<?=$HTTP_GET_VARS['order_by'] == "username" ? ($HTTP_GET_VARS['order_type'] == "asc" ? "desc" : "asc") :  "asc"; ?>" style="color:#ffffff">Email</a></b></font></td>
			<td align="center" width="17%"><font color="<?=TABLE_HEAD_FONT_COLOR?>"><b>Action</b></font></td>
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
				&nbsp;<?=($result_array[$i]['user_type']=='admin' ? '<font color="#FF0000">'.$result_array[$i]['name'].'</font>' : $result_array[$i]['name']);?>
				<input type="hidden" name="<?=$primary_id_name?>[]" value="<?=$result_array[$i][$primary_id_name]?>">
			</td>
			<td class="text">
				&nbsp;<a href="mailto:<?php echo ($result_array[$i]['user_type']=='admin' ? SITE_MAIL : $result_array[$i]['username']);?>"><?php echo ($result_array[$i]['user_type']=='admin' ? SITE_MAIL : $result_array[$i]['username']);?></a>
			</td>
			<td align="center" nowrap>
<?php if($HTTP_GET_VARS['action']==''){?>
				&nbsp;[<a  href="<?=HTTP_SERVER_ADMIN?>banners.php?user_id=<?=$result_array[$i]['user_id'];?>">Manage Banners</a>]&nbsp;&nbsp;
<?php }elseif($HTTP_GET_VARS['action']=='val'){
$selectValidateSQL = "select count(banner_id) as counted from $bx_db_table_banner_banners as banners, $bx_db_table_banner_users as users where banners.user_id=users.user_id and users.user_id='".$result_array[$i]['user_id']."' and permission='deny'";
$validate_query = bx_db_query($selectValidateSQL);
SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));	
$val_res = bx_db_fetch_array($validate_query);
if($val_res['counted'] > 0)
{
?>
				&nbsp;[<a  href="<?=HTTP_SERVER_ADMIN?>validate_banners.php?user_id=<?=$result_array[$i]['user_id'];?>">Validate Banners</a>]&nbsp;&nbsp;&nbsp;<font size="2" color="">( <?php echo $val_res['counted'];?> )</font>
<?php 
}
else
	echo '-';
}
elseif($HTTP_GET_VARS['action']=='rem'){
	$selectRemovedSQL = "select count(banner_id) as counted from $bx_db_table_banner_deleted_banners as banners, $bx_db_table_banner_users as users where banners.user_id=users.user_id and users.user_id='".$result_array[$i]['user_id']."'";
	$removed_query = bx_db_query($selectRemovedSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));	
	$rem_res = bx_db_fetch_array($removed_query);
	if($rem_res['counted'] > 0)
	{	
	?>
				&nbsp;[<a  href="<?=HTTP_SERVER_ADMIN?>removed_banners.php?user_id=<?=$result_array[$i]['user_id'];?>">Removed Banners</a>]&nbsp;&nbsp;&nbsp;<font size="2" color="">( <?php echo $rem_res['counted'];?> )</font>
<?}else
{
	echo '-';
}}
elseif($HTTP_GET_VARS['action']=='stats'){
?>
				&nbsp;[<a  href="<?=HTTP_SERVER_ADMIN?>client_stats.php?user_id=<?=$result_array[$i]['user_id'];?>">Statistics </a>]&nbsp;&nbsp;
<?}else{}
?>
			</td>
		 </tr>
<?
}
?>
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
</table>
<br>
<?php
include('footer.php'); 
?>