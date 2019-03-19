#
# Table structure for table `banner_banners`
#

CREATE TABLE banner2_banners (
  banner_id mediumint(9) NOT NULL auto_increment,
  user_id mediumint(9) NOT NULL default '0',
  banner_name text NOT NULL,
  format varchar(10) NOT NULL default '',
  type_id blob NOT NULL,
  url varchar(255) NOT NULL default '',
  alt varchar(255) NOT NULL default '',
  active enum('true','false') NOT NULL default 'true',
  views int(9) default NULL,
  clicks int(9) default NULL,
  permission enum('allow','deny') NOT NULL default 'allow',
  weight smallint(2) NOT NULL default '1',
  weight_counter int(10) NOT NULL default '0',
  notify smallint(1) NOT NULL default '0',
  PRIMARY KEY  (banner_id)
) TYPE=MyISAM;

#
# Table structure for table `banner_deleted_banners`
#

CREATE TABLE banner2_deleted_banners (
  banner_id mediumint(9) NOT NULL auto_increment,
  user_id mediumint(9) NOT NULL default '0',
  banner_name text NOT NULL,
  format varchar(10) NOT NULL default '',
  type_id blob NOT NULL,
  url varchar(255) NOT NULL default '',
  alt varchar(255) NOT NULL default '',
  active enum('true','false') NOT NULL default 'true',
  views int(9) default NULL,
  clicks int(9) default NULL,
  permission enum('allow','deny') NOT NULL default 'allow',
  weight smallint(2) NOT NULL default '1',
  weight_counter int(10) NOT NULL default '0',
  notify smallint(1) NOT NULL default '0',
  PRIMARY KEY  (banner_id)
) TYPE=MyISAM;

#
# Table structure for table `banner_planning`
#

CREATE TABLE banner2_planning (
  p_id int(10) NOT NULL auto_increment,
  p_zone_id int(10) NOT NULL default '0',
  p_max_banners varchar(10) NOT NULL default '',
  p_period_id int(2) NOT NULL default '0',
  p_h_price float NOT NULL default '0',
  p_c_price float NOT NULL default '0',
  p_p_price float NOT NULL default '0',
  p_unit varchar(10) NOT NULL default '',
  PRIMARY KEY  (p_id)
) TYPE=MyISAM;

#
# Dumping data for table `banner_planning`
#

INSERT INTO banner2_planning VALUES (181, 27, '1', 2, '0.4', '0.5', '0.6', '1000');
INSERT INTO banner2_planning VALUES (153, 19, '5', 2, '7', '8', '9', '1000');
INSERT INTO banner2_planning VALUES (151, 19, '5', 1, '1', '2', '3', '1000');
INSERT INTO banner2_planning VALUES (152, 19, '5', 3, '4', '5', '6', '1000');
INSERT INTO banner2_planning VALUES (149, 18, '3', 2, '1.7', '1.8', '1.9', '1000');
INSERT INTO banner2_planning VALUES (148, 18, '3', 3, '1.4', '1.5', '1.6', '1000');
INSERT INTO banner2_planning VALUES (147, 18, '3', 1, '1.11', '1.21', '1.31', '1000');
INSERT INTO banner2_planning VALUES (180, 27, '1', 3, '0.2', '0.3', '0.4', '1000');
INSERT INTO banner2_planning VALUES (179, 27, '1', 1, '0.1', '0.2', '0.3', '1000');
# --------------------------------------------------------

#
# Table structure for table `banner_planning_months`
#

CREATE TABLE banner2_planning_months (
  m_id int(10) NOT NULL auto_increment,
  m_number int(10) NOT NULL default '0',
  m_active smallint(1) NOT NULL default '0',
  PRIMARY KEY  (m_id)
) TYPE=MyISAM;

#
# Dumping data for table `banner_planning_months`
#

INSERT INTO banner2_planning_months VALUES (1, 1, 1);
INSERT INTO banner2_planning_months VALUES (3, 3, 1);
INSERT INTO banner2_planning_months VALUES (2, 6, 1);
INSERT INTO banner2_planning_months VALUES (4, 9, 0);
INSERT INTO banner2_planning_months VALUES (11, 11, 0);
# --------------------------------------------------------

#
# Table structure for table `banner_stats`
#

CREATE TABLE banner2_stats (
  views int(11) NOT NULL default '0',
  clicks int(11) NOT NULL default '0',
  day date NOT NULL default '0000-00-00',
  banner_id smallint(6) NOT NULL default '0',
  user_id int(10) NOT NULL default '0',
  PRIMARY KEY  (day,banner_id)
) TYPE=MyISAM;

#
# Table structure for table `banner_users`
#

CREATE TABLE banner2_users (
  user_id mediumint(9) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  username varchar(64) NOT NULL default '',
  password varchar(64) NOT NULL default '',
  without_review char(3) NOT NULL default '',
  user_type varchar(20) NOT NULL default '',
  report varchar(10) NOT NULL default '0',
  last_report date NOT NULL default '0000-00-00',
  phone varchar(50) NOT NULL default '',
  fax varchar(50) NOT NULL default '',
  description varchar(250) NOT NULL default '',
  PRIMARY KEY  (user_id)
) TYPE=MyISAM;

#
# Table structure for table `banner_zones`
#

CREATE TABLE banner2_zones (
  type_id bigint(20) NOT NULL auto_increment,
  typename_en varchar(50) default NULL,
  width int(11) default NULL,
  height int(11) default NULL,
  target varchar(50) NOT NULL default '_self',
  filesize int(5) NOT NULL default '0',
  zone_email varchar(100) NOT NULL default '',
  zone_report_type smallint(1) NOT NULL default '0',
  last_report date NOT NULL default '0000-00-00',
  PRIMARY KEY  (type_id)
) TYPE=MyISAM;

#
# Dumping data for table `banner_zones`
#

INSERT INTO banner2_zones VALUES (1, 'header', 468, 60, '_blank', 10, '', 0, '0000-00-00');
INSERT INTO banner2_zones VALUES (2, 'footer', 468, 60, '_blank', 8, 'vi@bitmixs0ft.com', 2, '2003-08-25');
INSERT INTO banner2_zones VALUES (3, 'new_zone', 250, 32, '_parent', 11, 'steve@bitmixsoft.c0m', 2, '0000-00-00');
# --------------------------------------------------------

#
# Table structure for table `banner_cctransactions`
#

CREATE TABLE banner2_cctransactions (
  transid varchar(32) NOT NULL default '0',
  opid int(5) NOT NULL default '0',
  cc_name varchar(255) NOT NULL default '',
  cc_type varchar(255) NOT NULL default '',
  cc_num varchar(255) NOT NULL default '',
  cc_cvc varchar(255) NOT NULL default '',
  cc_exp varchar(255) NOT NULL default '',
  cc_street varchar(255) NOT NULL default '',
  cc_city varchar(255) NOT NULL default '',
  cc_state varchar(255) NOT NULL default '',
  cc_zip varchar(255) NOT NULL default '',
  cc_country varchar(255) NOT NULL default '',
  cc_phone varchar(255) NOT NULL default '',
  cc_email varchar(255) NOT NULL default '',
  PRIMARY KEY  (transid),
  KEY opid (opid)
) TYPE=MyISAM;

#
# Table structure for table `banner_invoices`
#

CREATE TABLE banner2_invoices (
  opid int(10) NOT NULL auto_increment,
  op_type int(1) NOT NULL default '0',
  compid int(5) NOT NULL default '0',
  info varchar(255) NOT NULL default '',
  currency varchar(5) NOT NULL default '',
  vat float(3,2) NOT NULL default '0.00',
  totalprice float(10,2) NOT NULL default '0.00',
  paid char(1) NOT NULL default '',
  payment_mode mediumint(2) NOT NULL default '0',
  payment_date date NOT NULL default '0000-00-00',
  description varchar(255) NOT NULL default '',
  updated char(1) NOT NULL default 'N',
  validated char(1) NOT NULL default 'N',
  i_zone int(5) NOT NULL default '0',
  i_period int(3) NOT NULL default '0',
  i_max_banners int(3) NOT NULL default '0',
  i_unit int(10) NOT NULL default '0',
  i_purchased_nr int(10) NOT NULL default '0',
  i_start_date date NOT NULL default '0000-00-00',
  i_type smallint(1) NOT NULL default '0',
  i_counted int(10) NOT NULL default '0',
  i_expired smallint(1) NOT NULL default '0',
  PRIMARY KEY  (opid)
) TYPE=MyISAM;

#
# Table structure for table `banner_ladmin`
#

CREATE TABLE banner2_ladmin (
  admin varchar(50) NOT NULL default '',
  passw varchar(50) NOT NULL default '',
  ipaddress varchar(32) NOT NULL default ''
) TYPE=MyISAM;