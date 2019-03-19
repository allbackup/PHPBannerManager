<?php
include(DIR_LANGUAGES.$language.'/stat_details_form.php');
$tbl1 = ($HTTP_SESSION_VARS['pbm_userid'] ? '': '#F2F8FF');
$tbl2 = ($HTTP_SESSION_VARS['pbm_userid'] ?  LIST_BORDER_COLOR : '#9FCBFF');
$tbl3 = ($HTTP_SESSION_VARS['pbm_userid'] ? DISPLAY_LINE_BGCOLOR_SECOND : '#F2F8FF');
$tbl4 = ($HTTP_SESSION_VARS['pbm_userid'] ? TABLE_HEADING_BGCOLOR : '#FF3300');
$tbl5 = ($HTTP_SESSION_VARS['pbm_userid'] ? TABLE_HEADING_BGCOLOR : '#9ccbef');
?>
<table align="center" border="0" cellspacing="0" cellpadding="0" width="100%" bgcolor="<?php echo $tbl1; ?>">
<tr>
	<td bgcolor="<?php echo $tbl4; ?>" align="center"><font color="#ffffff"><b>Banner details</b></font>
		<table align="center" cellpadding="0" cellspacing="0" width="100%" bgcolor="<?php echo $tbl3; ?>" border="0"><tr><td align="left"><br>
<?
	$job_user_id = $HTTP_SESSION_VARS['pbm_userid'];
	$stats_width = 250;

	if ($HTTP_GET_VARS['display_year']!='' and $HTTP_GET_VARS['display_month']!='')
	{
		$date_sql = " and YEAR(i_start_date)=".$HTTP_GET_VARS['display_year']." and MONTH(i_start_date)=".$HTTP_GET_VARS['display_month']." ";
	}

	$select_pre_SQL = "select *,".$bx_db_table_banner_stats.".views as day_views,".$bx_db_table_banner_stats.".clicks as day_clicks, DATE_ADD(i_start_date, INTERVAL i_period MONTH) AS expire_date  from ".$bx_db_table_banner_stats.", ".$bx_db_table_banner_banners.",".$bx_db_table_banner_invoices."  where ".$bx_db_table_banner_banners.".type_id= CONCAT('|',".$bx_db_table_banner_invoices.".i_zone,'|') and ".$bx_db_table_banner_stats.".banner_id='".$HTTP_GET_VARS['banner_id']."' and ".$bx_db_table_banner_stats.".user_id='".$job_user_id."' and ".$bx_db_table_banner_stats.".banner_id=".$bx_db_table_banner_banners.".banner_id and ".$bx_db_table_banner_stats.".user_id=compid ".$date_sql." ORDER by day";
	$select_pre_query = bx_db_query($select_pre_SQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$max_views = 0;
	while ($pre_result = bx_db_fetch_array($select_pre_query))
	{
		if ($pre_result['day_views'] > $max_views)
			$max_views = $pre_result['day_views'];
		$total_views = $total_views + $pre_result['day_views'];
		$total_clicks = $total_clicks + $pre_result['day_clicks'];
		$start_year = substr($pre_result['i_start_date'],0,4);
		$start_month = substr($pre_result['i_start_date'],5,2);
		$stats_width_nr = round($max_views*1.5);

		$stats_added = $pre_result['i_start_date'];
		$stats_expire = bx_format_date($pre_result['expire_date'], DATE_FORMAT);
		$stats_purchased = $pre_result['i_purchased_nr'];
		$stats_type = $pre_result['i_type'];
	}

	if ($HTTP_GET_VARS['start_year'])
		$start_year = $HTTP_GET_VARS['start_year'];
	
	$select_banner_SQL = "select *, ".$bx_db_table_banner_stats.".views as day_views, ".$bx_db_table_banner_stats.".clicks as day_clicks from ".$bx_db_table_banner_stats.", ".$bx_db_table_banner_banners.",".$bx_db_table_banner_invoices."  where ".$bx_db_table_banner_banners.".type_id= CONCAT('|',".$bx_db_table_banner_invoices.".i_zone,'|') and ".$bx_db_table_banner_stats.".banner_id='".$HTTP_GET_VARS['banner_id']."' and ".$bx_db_table_banner_stats.".user_id='".$job_user_id."' and ".$bx_db_table_banner_stats.".banner_id=".$bx_db_table_banner_banners.".banner_id and ".$bx_db_table_banner_stats.".user_id=compid ".$date_sql." ORDER by day DESC";
	$select_banner_query = bx_db_query($select_banner_SQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

?>
<form method=get action="<?php echo HTTP_SERVER;?>stat_details.php">
<input type="hidden" name="banner_id" value="<?php echo $HTTP_GET_VARS['banner_id'];?>">
<input type="hidden" name="start_year" value="<?php echo $start_year;?>">
<?php
	if (bx_db_num_rows($select_banner_query)!=0)
	{
		switch ($stats_type)
		{
			case '1':$purchased_text=$stats_purchased.' '.TEXT_NR_CLICKS;break;
			case '2':$purchased_text=$stats_purchased.' '.TEXT_NR_VIEWS;break;
			case '3':$purchased_text=' '.TEXT_FLAT;break;
		}
		?>
		<table align="center" border="0" cellspacing="0" cellpadding="0" width="90%">
		<tr><td width="15%"><font style="font-size:12px"><?php echo TEXT_START_DATE; ?> : </font></td><td><font style="font-size:12px"><b><?php echo bx_format_date($stats_added,DATE_FORMAT);?></b></font></td></tr>
		<tr><td><font style="font-size:12px"><?php echo TEXT_EXPIRE_DATE; ?> : </font></td><td><font style="font-size:12px"><b><?php echo $stats_expire; ?></b></font></td></tr>
		<tr><td><font style="font-size:12px"><?php echo TEXT_PURCHASED; ?> : </font></td><td><font style="font-size:12px"><b><?php echo $purchased_text; ?></b></font></td></tr>
		</table>
<br>	
	<?} 
?>

</td></tr>
	<tr>
		<td bgcolor="<?php echo $tbl4; ?>" height="1"></td>
	</tr>
<tr><td align="center"><br>
<font style="font-size:12px"><b><?php echo TEXT_YEAR; ?> : </b></font><select name="display_year" style="font-size:12px">
<?
	for ($year_nr=$start_year; $year_nr<=date('Y'); $year_nr++)
	{
		if ($HTTP_GET_VARS['display_year']==$year_nr or $start_year==$year_nr)
			$is_selected = " selected ";
		else $is_selected = " ";
		echo "<option value=\"$year_nr\" ".$is_selected.">$year_nr</option>";
	}	
?>
</select>&nbsp;&nbsp;
<font style="font-size:12px"><b><?php echo TEXT_MONTH; ?> : </b></font><select name="display_month" style="font-size:12px">
<?
	for ($month_nr=1; $month_nr<=12; $month_nr++)
	{
		if ($HTTP_GET_VARS['display_month']==$month_nr or $start_month==$month_nr)
			$is_selected = " selected ";
		else $is_selected = " ";
		echo "<option value=\"$month_nr\" ".$is_selected.">$month_nr</option>";
	}	
?>
</select>&nbsp;&nbsp;
<input type="submit" name="" value="Go" style="font-size:12px; border: solid 1px #000000;">
</form>
<?php
if (bx_db_num_rows($select_banner_query)!=0)
{ 
?>
		<table align="center" cellpadding="0" cellspacing="0" width="90%" bgcolor="<?php echo $tbl3; ?>" border="0">
<?

		while ($banner_res = bx_db_fetch_array($select_banner_query))
		{
	?>
			<tr>
				<td><font style="font-size:12px" color="#000000">&nbsp;<?php echo $banner_res['day']; ?></font></td>
				<td><table align="left" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="<?php echo $stats_width; ?>" bgcolor="#efefef"><img src="images/stats_clicks.gif" border="0" width="<?php echo $banner_res['day_clicks']/$stats_width_nr*$stats_width; ?>" height="10"></td>
					<td rowspan="2" width="25%"><font style="font-size:12px" color="">&nbsp;&nbsp;<?php echo TEXT_AD_CLICKS; ?> : <b><?php echo $banner_res['day_clicks']?></b></font></td>
					<td rowspan="2" width="25%"><font style="font-size:12px" color="">&nbsp;&nbsp;<?php echo TEXT_AD_VIEWS; ?> : <b><?php echo $banner_res['day_views']?></font></td>
				</tr></b>
				<tr>
					<td width="200" bgcolor="#efefef"><img src="images/stats_views.gif" border="0" width="<?php echo $banner_res['day_views']/$stats_width_nr*$stats_width; ?>" height="10"></td>
				</tr>
				</table></td>
			</tr>
	<?php
		} 
	?>

				</td></tr></table><br>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" bgcolor="<?php echo $tbl4; ?>" height="1"></td>
	</tr>
	<tr>
		<td align="center">&nbsp;
			<font face="verdana" style="font-size:12px" color="#000000"><b><?php echo TEXT_TOTAL_ADVIEWS?> :	</b></font><font face="verdana" style="font-size:12px" color="#000000"><b>
	<?
		echo $total_views;
	?>
			,&nbsp;&nbsp;&nbsp;&nbsp;<font face="verdana" style="font-size:12px" color="#000000"><b><?php echo TEXT_TOTAL_ADCLICKS?> :</b></font>&nbsp;<font face="verdana" style="font-size:12px" color="#000000"><b>
	<?
		echo $total_clicks;
	?>
			</b></font><br>
		</td>
	</tr>
	<?php 
	}else{
	?>
	<tr>
		<td align="center"><b><?php echo TEXT_NO_STATISTICS_AVAILABLE; ?></b><br><br></td>
	</tr>
	<?php
	} 
	?>
<tr>
	<td bgcolor="<?php echo $tbl4; ?>" height="1"></td>
</tr>
</table>

	</td>
</tr>
</table>
