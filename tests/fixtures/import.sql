CREATE TABLE IF NOT EXISTS `test_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `column_1` varchar(50) NOT NULL DEFAULT '',
  `column_2` varchar(50) NOT NULL DEFAULT '',
  `column_3` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO `test_table` (`column_1`, `column_2`, `column_3`) VALUES
  ('c1.1', 'c1.2', 'c3.1'),
  ('c2.1', 'c2.2', 'c3.2'),
  ('c3.1', 'c3.2', 'c3.3');
