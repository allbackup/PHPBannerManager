<?
include ("application_config_file.php");
if($HTTP_SESSION_VARS['pbm_userid'])
{
$selectvaliduserSQL = "select * from $bx_db_table_banner_banners where banner_id='".($HTTP_GET_VARS['banner_id'] ? $HTTP_GET_VARS['banner_id'] : $HTTP_POST_VARS['banner_id'])."' and user_id='".$HTTP_SESSION_VARS['pbm_userid']."'";
$selectvaliduserSQLquery = bx_db_query($selectvaliduserSQL);
SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

$selectInvoiceSQL = "select * from ".$bx_db_table_banner_invoices." where i_zone='".($HTTP_GET_VARS['zid'] ? $HTTP_GET_VARS['zid'] : $HTTP_POST_VARS['type'])."' and compid='".$HTTP_SESSION_VARS['employerid']."'";
$invoice_query = bx_db_query($selectInvoiceSQL);
SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
$inv_res = bx_db_fetch_array($invoice_query);

$selectSQL = "select * from $bx_db_table_banner_banners where INSTR(type_id, '|".($HTTP_GET_VARS['zid'] ? $HTTP_GET_VARS['zid'] : $HTTP_POST_VARS['type'])."|') and user_id='".$user_id."'";
$ban_query = bx_db_query($selectSQL);
SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
$nr_ban = bx_db_num_rows($ban_query);

/*postvars($HTTP_POST_VARS, "<b>\$HTTP_POST_VARS[ ]</b>");
echo $inv_res['i_max_banners']." ". $nr_ban ;// $inv_res['i_max_banners']==''
exit;*/


include (DIR_SERVER_ROOT."header.php");

if($HTTP_GET_VARS['format'] == "image" || $HTTP_GET_VARS['format']=="swf")
{
	if($HTTP_POST_VARS['image_insert'])
	{
		if ($HTTP_POST_VARS['url']=='')
			$url_error = $image_insert_error = 1;
	}
	if($HTTP_GET_VARS['banner_id'] && $HTTP_POST_VARS['image_insert'])
	{
		if ($HTTP_POST_FILES['banner_file']['tmp_name'] == '' || $HTTP_POST_FILES['banner_file']['tmp_name'] =='none')
		{
			$selectSQL = "select * from $bx_db_table_banner_types where type_id='".$HTTP_POST_VARS['banner_type']."'";
			$select_query = bx_db_query($selectSQL);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			$select_result = bx_db_fetch_array($select_query);
			$width = $select_result['width'];
			$height = $select_result['height'];
		
			$bannerSQL = "select banner_name from $bx_db_table_banner_banners where banner_id='".$HTTP_POST_VARS['banner_id']."'";
			$banner_query = bx_db_query($bannerSQL);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			$banner_result = bx_db_fetch_array($banner_query);

			if(bx_image_compare(DIR_BANNERS.$banner_result['banner_name'], "=", $width, $height, 1))
			{}
			else
			{
				$image_size_error = 1;
				$image_insert_error = 1;
			}
		}
		elseif($HTTP_POST_VARS['image_insert'])
		{	
			$selectSQL = "select * from $bx_db_table_banner_types where type_id='".$HTTP_POST_VARS['banner_type']."'";
			$select_query = bx_db_query($selectSQL);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			$select_result = bx_db_fetch_array($select_query);
			$width = $select_result['width'];
			$height = $select_result['height'];
			$filesize = $select_result['filesize']*1024;

			if(bx_image_compare($HTTP_POST_FILES['banner_file'], "=", $width, $height, 0, $filesize))
			{}
			else
			{
				$image_size_error = 1;
				$image_insert_error = 1;
			}
		}
		else{}
	}
	elseif($HTTP_POST_VARS['image_insert'])
	{
		if ($HTTP_POST_FILES['banner_file']['tmp_name'] != '' && $HTTP_POST_FILES['banner_file']['tmp_name'] !='none')
		{
			$selectSQL = "select * from $bx_db_table_banner_types where type_id='".$HTTP_POST_VARS['banner_type']."'";
			$select_query = bx_db_query($selectSQL);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			$select_result = bx_db_fetch_array($select_query);
			$width = $select_result['width'];
			$height = $select_result['height'];
			$filesize = $select_result['filesize']*1024;

			if(bx_image_compare($HTTP_POST_FILES['banner_file'], "=", $width, $height, 0, $filesize))
			{}
			else
			{
				$image_size_error = 1;
				$image_insert_error = 1;
			}
		}
		else
		{
			$image_size_error = 1;
			$image_insert_error = 1;
		}
	}

	if ($image_insert_error == '1')
	{
		$banner_select_result['url'] = $HTTP_POST_VARS['url'];
		$banner_select_result['alt'] = $HTTP_POST_VARS['alt'];
		if(is_admin())
			$banner_select_result['weight'] = $HTTP_POST_VARS['banner_weight'];
		
		include (BANNER_FORMS."add_banner_form.php");
		include ("footer.php");
		exit;
	}

	if(strstr($HTTP_POST_VARS['url'], "http://"))
		$url = $HTTP_POST_VARS['url'];
	else
		$url = "http://" . $HTTP_POST_VARS['url'];

	if($HTTP_GET_VARS['banner_id'] && $HTTP_POST_VARS['image_insert'])
	{
		if ($HTTP_POST_FILES['banner_file']['tmp_name'] != '' && $HTTP_POST_FILES['banner_file']['tmp_name'] !='none')
		{
			$bannerSQL = "select banner_name, banner_id from $bx_db_table_banner_banners where banner_id='".$HTTP_POST_VARS['banner_id']."'";
			$banner_query = bx_db_query($bannerSQL);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			$banner_result = bx_db_fetch_array($banner_query);

			if (file_exists(DIR_BANNERS.$banner_result['banner_name']))
			{
				unlink(DIR_BANNERS.$banner_result['banner_name']);
			}
			
			copy($HTTP_POST_FILES['banner_file']['tmp_name'], DIR_BANNERS.$banner_result['banner_id']."_".$HTTP_POST_FILES['banner_file']['name']);
			$is_banner = "banner_name='".$banner_result['banner_id']."_".$HTTP_POST_FILES['banner_file']['name']."', ";
		}

		$selectSQL = "select * from $bx_db_table_banner_users where user_id='".($HTTP_SESSION_VARS['pbm_userid'] ? $HTTP_SESSION_VARS['pbm_userid'] : $HTTP_SESSION_VARS['pbm_adminid'])."'";
		$select_client_query = bx_db_query($selectSQL);
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		$select_client_result = bx_db_fetch_array($select_client_query);
		if ($select_client_result['without_review'] == "yes")
			$permission = "allow";
		else
			$permission = "deny";

		if(is_admin())
			$weight_update = ", weight='".$HTTP_POST_VARS['banner_weight']."' ";

		$updateSQL = "update $bx_db_table_banner_banners set ".$is_banner." type_id='|".$HTTP_POST_VARS['banner_type']."|', url='".$url."', alt='".$HTTP_POST_VARS['alt']."', permission='".$permission."' ". $weight_update."  where banner_id='".$HTTP_POST_VARS['banner_id']."'";
		$update_query = bx_db_query($updateSQL);
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		
		if(is_admin())
		{
			$updateCounterSQL = "update $bx_db_table_banner_banners set weight_counter='0' where 1";
			$updateCounter_query = bx_db_query($updateCounterSQL);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		}

		refresh(bx_make_url(HTTP_SERVER."banners.php?user_id=".$HTTP_SESSION_VARS['pbm_userid'], "auth_sess", $bx_session));
		exit;
	}
	elseif($HTTP_POST_VARS['image_insert'])
	{
		if(!is_admin())
			$HTTP_POST_VARS['banner_weight'] = '1';

		bx_db_insert($bx_db_table_banner_banners, "banner_id,user_id,banner_name,format,type_id,url,alt,active,views,clicks,permission,weight,weight_counter", "'', '".$HTTP_SESSION_VARS['pbm_userid']."', '', '".$HTTP_GET_VARS['format']."', '|".$HTTP_POST_VARS['banner_type']."|', '".$url."', '".$HTTP_POST_VARS['alt']."', 'true','0','0', '', '".$HTTP_POST_VARS['banner_weight']."', '0'");
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		
		$id = bx_db_insert_id();

		$updateCounterSQL = "update $bx_db_table_banner_banners set weight_counter='0' where 1";
		$updateCounter_query = bx_db_query($updateCounterSQL);
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

		$selectSQL = "select without_review from $bx_db_table_banner_users where user_id='".$HTTP_SESSION_VARS['pbm_userid']."'";
		$select_client_query = bx_db_query($selectSQL);
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		$select_client_result = bx_db_fetch_array($select_client_query);

		$selectSQL = "select * from $bx_db_table_banner_users where user_id='".($HTTP_SESSION_VARS['pbm_userid'] ? $HTTP_SESSION_VARS['pbm_userid'] : $HTTP_SESSION_VARS['pbm_adminid'])."'";
		$select_client_query = bx_db_query($selectSQL);
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		$select_client_result = bx_db_fetch_array($select_client_query);
		if ($select_client_result['without_review'] == "yes")
			$permission = "allow";
		else
			$permission = "deny";

		copy($HTTP_POST_FILES['banner_file']['tmp_name'], DIR_BANNERS.$id."_".$HTTP_POST_FILES['banner_file']['name']);

		$updateSQL = "update $bx_db_table_banner_banners set banner_name='".$id."_".$HTTP_POST_FILES['banner_file']['name']."', permission='".$permission."' where banner_id='".$id."'";
		$update_query = bx_db_query($updateSQL);
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

		refresh(bx_make_url(HTTP_SERVER."banners.php?user_id=".$HTTP_SESSION_VARS['pbm_userid'], "auth_sess", $bx_session));
		exit();	
	}
}
elseif($HTTP_POST_VARS['banner_id'] && $HTTP_GET_VARS['format'] == "html")
{
	if ($HTTP_POST_VARS['html_source']=='')
	{
		$html_source_error = 1;
		$html_insert_error = 1;
	}

	if(strstr($HTTP_POST_VARS['url'], "http://"))
		$url = $HTTP_POST_VARS['url'];
	else
		$url = "http://" . $HTTP_POST_VARS['url'];
		
	if ($html_insert_error == '1')
	{
		$banner_select_result['url'] = $HTTP_POST_VARS['url'];
		$banner_select_result['banner_name'] = $HTTP_POST_VARS['html_source'];
		if(is_admin())
			$banner_select_result['weight'] = $HTTP_POST_VARS['banner_weight'];

		include (BANNER_FORMS."add_banner_form.php");
		include ("footer.php");
		exit;
	}

	$selectSQL = "select without_review from $bx_db_table_banner_users where user_id='".$HTTP_SESSION_VARS['pbm_userid']."'";
	$select_client_query = bx_db_query($selectSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$select_client_result = bx_db_fetch_array($select_client_query);

	$selectSQL = "select * from $bx_db_table_banner_users where user_id='".($HTTP_SESSION_VARS['pbm_userid'] ? $HTTP_SESSION_VARS['pbm_userid'] : $HTTP_SESSION_VARS['pbm_adminid'])."'";
	$select_client_query = bx_db_query($selectSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$select_client_result = bx_db_fetch_array($select_client_query);
	if ($select_client_result['without_review'] == "yes")
		$permission = "allow";
	else
		$permission = "deny";

	$id = $HTTP_POST_VARS['banner_id'];
	$banner_code = $HTTP_POST_VARS['html_source'];
	if(is_admin())
		$weight_update = ", weight='".$HTTP_POST_VARS['banner_weight']."' ";

	$updateSQL = "update $bx_db_table_banner_banners set banner_name='".addslashes($banner_code)."', format='".$HTTP_GET_VARS['format']."', type_id='|".$HTTP_POST_VARS['banner_type']."|', url='".$url."', permission='".$permission."' ".$weight_update." where banner_id='".$HTTP_POST_VARS['banner_id']."'";
	
	$update_query = bx_db_query($updateSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

	$updateCounterSQL = "update $bx_db_table_banner_banners set weight_counter='0' where 1";
	$updateCounter_query = bx_db_query($updateCounterSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

	refresh(bx_make_url(HTTP_SERVER."banners.php?user_id=".$HTTP_SESSION_VARS['pbm_userid'], "auth_sess", $bx_session));
	exit();	
	
}
elseif ($HTTP_GET_VARS['format']=="remote")
{
    if (!$HTTP_POST_VARS['banner_id'] && $HTTP_POST_VARS['remote_insert'])
    {
        $insert_error = 0;
    	if ($HTTP_POST_VARS['banner_name']=='')
            $insert_error = $remote_banner_url_error = 1;
        if($HTTP_POST_VARS['url']=='')
            $insert_error = $linked_url_error = 1;
        if ($HTTP_POST_VARS['remote_banner_type']=='')
            $insert_error = $remote_banner_type_error = 1;
        if($insert_error == 1)
        {
            include (BANNER_FORMS."add_banner_form.php");
		    include ("footer.php");
		    exit;
        }
        else
        {
        	
            $selectSQL = "select without_review from $bx_db_table_banner_users where user_id='".$HTTP_SESSION_VARS['pbm_userid']."'";
            $select_client_query = bx_db_query($selectSQL);
            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
            $select_client_result = bx_db_fetch_array($select_client_query);

            $selectSQL = "select * from $bx_db_table_banner_users where user_id='".($HTTP_SESSION_VARS['pbm_userid'] ? $HTTP_SESSION_VARS['pbm_userid'] : $HTTP_SESSION_VARS['pbm_adminid'])."'";
            $select_client_query = bx_db_query($selectSQL);
            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
            $select_client_result = bx_db_fetch_array($select_client_query);
            if ($select_client_result['without_review'] == "yes")
                $permission = "allow";
            else
                $permission = "deny";

            if(!is_admin())
                $HTTP_POST_VARS['banner_weight'] = '1';
            
            bx_db_insert($bx_db_table_banner_banners ,"banner_id,user_id,banner_name,format,type_id,url,alt,active,views,clicks,permission,weight,weight_counter", "'', '".$HTTP_SESSION_VARS['pbm_userid']."', '".$HTTP_POST_VARS['banner_name']."', '".$HTTP_GET_VARS['format']."', '|".$HTTP_POST_VARS['banner_type']."|', '".$url."', '".$HTTP_POST_VARS['remote_banner_type']."', 'true','0','0', '".$permission."', '".$HTTP_POST_VARS['banner_weight']."', '0'");

            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
            
            $id = bx_db_insert_id();

            $updateCounterSQL = "update $bx_db_table_banner_banners set weight_counter='0' where 1";
            $updateCounter_query = bx_db_query($updateCounterSQL);
            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

            $banner_code = $HTTP_POST_VARS['html_source'];

            refresh(bx_make_url(HTTP_SERVER."banners.php?user_id=".$HTTP_SESSION_VARS['pbm_userid'], "auth_sess", $bx_session));
            exit();	
        } //end else
    }
    elseif ($HTTP_POST_VARS['banner_id'] && $HTTP_POST_VARS['remote_insert'])
    {
        if(strstr($HTTP_POST_VARS['url'], "http://"))
		    $url = $HTTP_POST_VARS['url'];
	    else
		    $url = "http://" . $HTTP_POST_VARS['url'];

        if(strstr($HTTP_POST_VARS['banner_name'], "http://"))
		    $HTTP_POST_VARS['banner_name'] = $HTTP_POST_VARS['banner_name'];
	    else
		    $HTTP_POST_VARS['banner_name'] = "http://" . $HTTP_POST_VARS['banner_name'];

    	if ($HTTP_POST_VARS['banner_name']=='')
            $insert_error = $remote_banner_url_error = 1;
        if($HTTP_POST_VARS['url']=='')
            $insert_error = $linked_url_error = 1;
        if ($HTTP_POST_VARS['remote_banner_type']=='')
            $insert_error = $remote_banner_type_error = 1;
        if($insert_error == 1)
        {
            include (BANNER_FORMS."add_banner_form.php");
		    include ("footer.php");
		    exit;
        }
        else
        {
            $selectSQL = "select without_review from $bx_db_table_banner_users where user_id='".$HTTP_SESSION_VARS['pbm_userid']."'";
            $select_client_query = bx_db_query($selectSQL);
            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
            $select_client_result = bx_db_fetch_array($select_client_query);

            $selectSQL = "select * from $bx_db_table_banner_users where user_id='".($HTTP_SESSION_VARS['pbm_userid'] ? $HTTP_SESSION_VARS['pbm_userid'] : $HTTP_SESSION_VARS['pbm_adminid'])."'";
            $select_client_query = bx_db_query($selectSQL);
            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
            $select_client_result = bx_db_fetch_array($select_client_query);
            if ($select_client_result['without_review'] == "yes")
                $permission = "allow";
            else
                $permission = "deny";

            $id = $HTTP_POST_VARS['banner_id'];

            if(is_admin())
                $weight_update = ", weight='".$HTTP_POST_VARS['banner_weight']."' ";

            $updateSQL = "update $bx_db_table_banner_banners set banner_name='".$HTTP_POST_VARS['banner_name']."', format='".$HTTP_GET_VARS['format']."', type_id='|".$HTTP_POST_VARS['banner_type']."|', url='".$url."', alt='".$HTTP_POST_VARS['remote_banner_type']."', permission='".$permission."' ".$weight_update." where banner_id='".$HTTP_POST_VARS['banner_id']."'";
            
            $update_query = bx_db_query($updateSQL);
            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

            $updateCounterSQL = "update $bx_db_table_banner_banners set weight_counter='0' where 1";
            $updateCounter_query = bx_db_query($updateCounterSQL);
            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

            refresh(bx_make_url(HTTP_SERVER."banners.php?user_id=".$HTTP_SESSION_VARS['pbm_userid'], "auth_sess", $bx_session));
            exit();	
        }
    } //end if
    else{}
} //end elseif
else{}


if (!$HTTP_GET_VARS['format'])
{
	include (BANNER_FORMS."select_banner_type_form.php");
}
elseif($HTTP_GET_VARS['format'] && $HTTP_POST_VARS['html_insert']=="yes" && !$HTTP_GET_VARS['banner_id']) 
{

	if ($HTTP_POST_VARS['banner_type']=='' || $HTTP_POST_VARS['banner_type']=='0')	
		$banner_type_error = $html_insert_error = 1;

	if ($HTTP_POST_VARS['html_source']=='')
		$html_source_error = $html_insert_error = 1;
	
	if(!empty($HTTP_POST_VARS['url']))
	{
		if(strstr($HTTP_POST_VARS['url'], "http://"))
			$url = $HTTP_POST_VARS['url'];
		else
			$url = "http://" . $HTTP_POST_VARS['url'];
	}

	if ($html_insert_error == '1')
	{
		$banner_select_result['url'] = $HTTP_POST_VARS['url'] = $url;
		$banner_select_result['banner_name'] = $HTTP_POST_VARS['html_source'];
		$banner_select_result['weight'] = $HTTP_POST_VARS['banner_weight'];

		include (BANNER_FORMS."add_banner_form.php");
		include ("footer.php");
		exit;
	}


		
	$selectSQL = "select without_review from $bx_db_table_banner_users where user_id='".$HTTP_SESSION_VARS['pbm_userid']."'";
	$select_client_query = bx_db_query($selectSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$select_client_result = bx_db_fetch_array($select_client_query);

	$selectSQL = "select * from $bx_db_table_banner_users where user_id='".($HTTP_SESSION_VARS['pbm_userid'] ? $HTTP_SESSION_VARS['pbm_userid'] : $HTTP_SESSION_VARS['pbm_adminid'])."'";
	$select_client_query = bx_db_query($selectSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$select_client_result = bx_db_fetch_array($select_client_query);
	if ($select_client_result['without_review'] == "yes")
		$permission = "allow";
	else
		$permission = "deny";

	if(!is_admin())
		$HTTP_POST_VARS['banner_weight'] = '1';
	bx_db_insert($bx_db_table_banner_banners ,"banner_id,user_id,banner_name,format,type_id,url,alt,active,views,clicks,permission,weight,weight_counter", "'', '".$HTTP_SESSION_VARS['pbm_userid']."', '', '".$HTTP_GET_VARS['format']."', '|".$HTTP_POST_VARS['banner_type']."|', '".$url."', '".$HTTP_POST_VARS['alt']."', 'true','0','0', '".$permission."', '".$HTTP_POST_VARS['banner_weight']."', '0'");

	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	
	$id = bx_db_insert_id();

	$updateCounterSQL = "update $bx_db_table_banner_banners set weight_counter='0' where 1";
	$updateCounter_query = bx_db_query($updateCounterSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

	$banner_code = $HTTP_POST_VARS['html_source'];

	$upd = bx_db_query("update $bx_db_table_banner_banners set banner_name = '".addslashes($banner_code)."' where banner_id = '$id'");
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

	refresh(bx_make_url(HTTP_SERVER."banners.php?user_id=".$HTTP_SESSION_VARS['pbm_userid'], "auth_sess", $bx_session));
	exit();	
	
}
else
{
	include (BANNER_FORMS."add_banner_form.php");
}
?>

<?
include ("footer.php");
}
else
{
	include('header.php');
	include(DIR_FORMS. 'login_form.php');
	include('footer.php');
} //end else
?>