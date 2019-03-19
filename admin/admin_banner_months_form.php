<?php
	$selectSQL = "select * from $bx_db_table_planning_months";
	$select_query = bx_db_query($selectSQL);	
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
?>

<table width="600" cellspacing="0" cellpadding="2" border="0">
 <tr>
     <td align="center"><font face="Verdana, Arial" size="2" color="#000000"><b>Banner Planning Periodes</b></font><br><br><br></td>
 </tr>
 <tr>
   <td bgcolor="#9FCBFF">
<TABLE border="0" cellpadding="4" cellspacing="0" bgcolor="#9FCBFF" width="100%" nowrap>
<tr>
	<td align="center" width="20%"><b>Month ID</b></td>
	<td align="center" width="30%"><b>Number of Month(s)</b></td>
	<td align="center" width="20%"><b>Active</b></td>
	<td align="center" width="33%"><b>Action</b></td>
</tr>

<?php

while ($pl_res = bx_db_fetch_array($select_query) )
{
?>
<form method=post action="<?php echo HTTP_SERVER_ADMIN;?>admin_banner_months.php" style="margin-width:0px;margin-height:0px" name="form<?=$pl_res['m_id']?>">
<tr bgcolor="#ffffff">
	<td align="center">
		<?php echo $pl_res['m_id'];?>
	</td>
	<td align="center"><select name="m_number">
		<?php 
			for ($i=1; $i<=12; $i++)
			{
				if ($pl_res['m_number']==$i)
				{
					$selected = 'selected';
				}else
				{
					$selected = ' ';
				}
				echo "<option value=".$i." ".$selected.">".$i."</option>";				
			}
		?></select> month(s)
		
	</td>
	<td align="center">
		<input type="checkbox" name="m_active" value="1" <?php echo (($pl_res['m_active']=='1')?'checked':'');?>>
	</td>
	<td align="center">
		<a href="<?=HTTP_SERVER_ADMIN?>admin_banner_months.php?del=1&id=<?php echo $pl_res['m_id']?>" onclick="return confirm('Are you sure you want to delete Month with ID=<?php echo $pl_res['m_id']?>');">Delete</a>&nbsp;&nbsp;/&nbsp;&nbsp;<input type="submit" name="submit" value="Submit"><input type="hidden" name="upd" value="1"><input type="hidden" name="m_id" value="<?php echo $pl_res['m_id']?>">
	</td>
</tr>
<tr>
	<td></td>
</tr>
</form>
<?
}
?>
<form method=post action="<?php echo HTTP_SERVER_ADMIN;?>admin_banner_months.php">
<tr>
	<td bgcolor="#ffffff" colspan="4" height="40" align="center"><b>Add new item : </b>&nbsp;&nbsp;<select name="new_number">
		<?php 
			for ($i=1; $i<=12; $i++)
			{
				echo "<option value=".$i." ".$selected.">".$i."</option>";				
			}
		?></select> month(s)&nbsp;&nbsp;&nbsp;Active : <input type="checkbox" name="new_active" value="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="new_add" value="Add" class=""><input type="hidden" name="new" value="1">
		
	</td>
</tr>
</form>
</table>
</td></tr></table>
