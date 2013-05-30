CREATE TABLE IF NOT EXISTS `_DATABASE_NAME_`.CalendarSettings (
  `calendarSettingsID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shortName` tinytext NOT NULL,
  `value` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`calendarSettingsID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DELETE FROM `_DATABASE_NAME_`.CalendarSettings;
INSERT INTO `_DATABASE_NAME_`.CalendarSettings VALUES (1,'Days Before Subscription End','730');
INSERT INTO `_DATABASE_NAME_`.CalendarSettings VALUES (2,'Days After Subscription End','90');
INSERT INTO `_DATABASE_NAME_`.CalendarSettings VALUES (3,'Resource Type(s)','1');
INSERT INTO `_DATABASE_NAME_`.CalendarSettings VALUES (4,'Authorized Site(s)','1');
