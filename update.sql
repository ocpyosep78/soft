CREATE TABLE IF NOT EXISTS `withdraw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `withdraw_date` datetime NOT NULL,
  `value_rupiah` double NOT NULL,
  `value_dollar` double NOT NULL,
  `status` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;