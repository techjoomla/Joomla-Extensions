
DROP TABLE IF EXISTS `#__banners_details`;
CREATE TABLE IF NOT EXISTS `#__banners_details` (
  `banner_id` int(11) NOT NULL auto_increment,
  `banner_name` varchar(20) NOT NULL,
  `banner_code` varchar(200) NOT NULL,
  `banner_ips` varchar(400) NOT NULL,
  PRIMARY KEY  (`banner_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

DROP TABLE IF EXISTS `#__banners_iprange`;
CREATE TABLE IF NOT EXISTS `#__banners_iprange` (
  `banner_id` int(10) NOT NULL,
  `banner_iplower` int(10) default NULL,
  `banner_iphigher` int(50) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

