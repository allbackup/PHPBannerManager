<?php
include ('application_config_file.php');

//set i_expired to 1 if end_date > current_date
$updateSQL = "update $bx_db_table_banner_invoices set i_expired=1 where compid='".$HTTP_SESSION_VARS['employerid']."' and DATE_ADD(i_start_date, INTERVAL i_period MONTH)<='".date('Y-m-d')."'";
$update_query = bx_db_query($updateSQL);
SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

include(DIR_LANGUAGES.$language."/".FILENAME_MEMBERSHIP);
$jsfile="membership.js";
if ($HTTP_SESSION_VARS['employerid']) {

	   if ($HTTP_POST_VARS['action']=='buy_banner')
	   {
		   $select_canbuy_SQL = "select * from ".$bx_table_prefix."_invoices where compid='".$HTTP_SESSION_VARS['employerid']."' and paid='N' and (i_expired=0 or DATE_ADD(i_start_date, INTERVAL i_period MONTH)<'".date('Y-m-d')."')";
		   $select_canbuy_query = bx_db_query($select_canbuy_SQL);
		   SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		   if (bx_db_num_rows($select_canbuy_query)>0) //cannot buy banners
		   {
		   	$error_message=CAN_UPGRADE_ERROR;
            $back_url=bx_make_url(HTTP_SERVER.FILENAME_MEMBERSHIP, "auth_sess", $bx_session);
            include(DIR_SERVER_ROOT."header.php");
            include(DIR_FORMS.FILENAME_ERROR_FORM);
            include(DIR_SERVER_ROOT."footer.php");
			exit;
		   }

		   $banner_nr=0;
		   $banner_exist_nr=0;
		   for ($j_nr=1; $j_nr<=$HTTP_POST_VARS['banner_planning_total']; $j_nr++)
		   {
			   for ($k_nr=0; $k_nr<sizeof($HTTP_POST_VARS['banner_p_id']); $k_nr++)
			   {
			   		if ($HTTP_POST_VARS['banner_planning'.$j_nr]!='' and $HTTP_POST_VARS['banner_p_desact'][$j_nr]=='0' and substr($HTTP_POST_VARS['banner_planning'.$j_nr],0,-3)==$HTTP_POST_VARS['banner_p_id'][$k_nr] and ((substr($HTTP_POST_VARS['banner_planning'.$j_nr],-1)!='3' and $HTTP_POST_VARS['banner_purchase'][$k_nr]!='0') or (substr($HTTP_POST_VARS['banner_planning'.$j_nr],-1)=='3')))
			   		{
						$p_banner_id[$banner_nr][0]=$HTTP_POST_VARS['banner_p_id'][$k_nr];
						$p_banner_id[$banner_nr][1]=$HTTP_POST_VARS['banner_purchase'][$k_nr];

						$sel_exist_SQL = "select * from ".$bx_table_prefix."_invoices,$bx_db_table_banner_types where i_zone=type_id and i_zone='".$HTTP_POST_VARS['banner_p_zone'][$k_nr]."' and compid='".$HTTP_SESSION_VARS['employerid']."' and i_expired=0";
						$select_exist_query = bx_db_query($sel_exist_SQL);
						SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
						if (bx_db_num_rows($select_exist_query)>0)
						{ //user already has banner in this zone
							$p_banner_exist_id[$banner_exist_nr][0]=$HTTP_POST_VARS['banner_p_id'][$k_nr];
							$p_banner_exist_id[$banner_exist_nr][1]=$HTTP_POST_VARS['banner_purchase'][sizeof($HTTP_POST_VARS['banner_p_id'])/$HTTP_POST_VARS['banner_planning_total']+$k_nr];
							$p_banner_exist_id[$banner_exist_nr][2]=$HTTP_POST_VARS['banner_p_zone'][$k_nr];
							$banner_exist_nr++; 
						}
						else
						{ //user hasn't banner in this zone, can insert
							$p_banner_id[$banner_nr][0]=$HTTP_POST_VARS['banner_p_id'][$k_nr];		//planning id
							$p_banner_id[$banner_nr][1]=$HTTP_POST_VARS['banner_purchase'][$k_nr];	//purchased nr
							$p_banner_id[$banner_nr][2]=$HTTP_POST_VARS['banner_p_zone'][$k_nr];	//banner zone id

							if (substr($HTTP_POST_VARS['banner_planning'.$j_nr],-1)=='1')
							{
								$p_banner_id[$banner_nr][3] = '1';								//banner zone serial
							}
							elseif (substr($HTTP_POST_VARS['banner_planning'.$j_nr],-1)=='2')
							{
								$p_banner_id[$banner_nr][3] = '2';								//banner zone serial
							}
							elseif (substr($HTTP_POST_VARS['banner_planning'.$j_nr],-1)=='3')
							{
								$p_banner_id[$banner_nr][3] = '3';								//banner zone serial
							}

							$p_banner_id[$banner_nr][4]=$HTTP_POST_VARS['banner_p_desact'][$j_nr];	//desactivated
							$banner_nr++;
						}
			   		}
			   }
		   }

		   if ($banner_exist_nr==0 and $banner_nr==0)
		   { // no banner planning selected
		   	$banner_planning_error = ERROR_NO_BANNER_SELECTED;
			include(DIR_SERVER_ROOT."header.php");
			include(DIR_FORMS.FILENAME_MEMBERSHIP_FORM);
			include(DIR_SERVER_ROOT."footer.php");
		   }
		   elseif ($banner_nr!=0)
		   {
			   for ($n_nr=0; $n_nr<$banner_nr; $n_nr++)
			   {
				if ($p_banner_id[$n_nr][4]==0)
				{
					$select_new_SQL = "select * from $bx_db_table_planning,$bx_db_table_planning_months,$bx_db_table_banner_types where p_id='".$p_banner_id[$n_nr][0]."' and p_period_id=m_id and p_zone_id=type_id";
					$select_new_query = bx_db_query($select_new_SQL);
					SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
					if (bx_db_num_rows($select_new_query)!=0)
					{
						$res_new_query = bx_db_fetch_array($select_new_query);

						$error_minimum = 0;
						if ($p_banner_id[$n_nr][3] == '1')
						{
							$insert_price = round(($res_new_query['p_h_price']/$res_new_query['p_unit'])*$p_banner_id[$n_nr][1] *100)/100;
							$insert_purchased_nr = $p_banner_id[$n_nr][1];
							if ($insert_purchased_nr < $res_new_query['p_unit'])
							{
								$error_minimum = 1;
								$error_minimum_message .= $res_new_query['typename'.$slng]." : ".ERROR_MINIMUM1.$res_new_query['p_unit']."<br>";
							}else{
								$insert_price = round(($res_new_query['p_h_price']/$res_new_query['p_unit'])*$p_banner_id[$n_nr][1] *100)/100;
							}
						}
						elseif ($p_banner_id[$n_nr][3] == '2')
						{
							$insert_purchased_nr = $p_banner_id[$n_nr][1];
							if ($insert_purchased_nr < $res_new_query['p_unit'])
							{
								$error_minimum = 1;
								$error_minimum_message .= $res_new_query['typename'.$slng]." : ".ERROR_MINIMUM1.$res_new_query['p_unit']."<br>";
							}else{
								$insert_price = round(($res_new_query['p_c_price']/$res_new_query['p_unit'])*$p_banner_id[$n_nr][1] *100)/100;
							}
						}
						elseif ($p_banner_id[$n_nr][3] == '3')
						{
							$insert_price = round($res_new_query['p_p_price']*100)/100;	
							$insert_purchased_nr = '-1';
						}
					}
				}
				
			if ($error_minimum == 1)
				{
					$banner_planning_error = $error_minimum_message;
					include(DIR_SERVER_ROOT."header.php");
					include(DIR_FORMS.FILENAME_MEMBERSHIP_FORM);
					include(DIR_SERVER_ROOT."footer.php");
					exit;
				}
			if ($error_minimum == 0)
				{
					bx_db_insert($bx_table_prefix."_invoices", "compid,currency,totalprice,vat,paid,updated,validated,i_zone,i_period,i_max_banners,i_unit,i_purchased_nr,i_type", "'".$employerid."','".PRICE_CURENCY."','".$insert_price."','".VAT_PROCENT."','N','N','N','".$res_new_query['p_zone_id']."','".$res_new_query['m_number']."','".$res_new_query['p_max_banners']."','".$res_new_query['p_unit']."',".$insert_purchased_nr.",'".$p_banner_id[$n_nr][3]."'");
					SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
					$new_invoice_id = bx_db_insert_id();
				}
			}
			refresh(bx_make_url(HTTP_SERVER."myinvoices.php", "auth_sess", $bx_session));
			exit;
		   }else
		   {
				$banner_planning_error = ERROR_CANTBUY_MESSAGE;
				include(DIR_SERVER_ROOT."header.php");
				include(DIR_FORMS.FILENAME_MEMBERSHIP_FORM);
				include(DIR_SERVER_ROOT."footer.php");
				exit;
		   }
			
	   } //end if action=buy_banner
       else
       {   
           include(DIR_SERVER_ROOT."header.php");
           include(DIR_FORMS.FILENAME_MEMBERSHIP_FORM);
           include(DIR_SERVER_ROOT."footer.php");
       } //end else not exist action
}
else
{
        include(DIR_SERVER_ROOT."header.php");
        include(DIR_FORMS.FILENAME_LOGIN_FORM);
        include(DIR_SERVER_ROOT."footer.php");
}
?>