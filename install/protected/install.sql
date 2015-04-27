CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`Attachment` (
  `attachmentID` int(10) NOT NULL auto_increment,
  `licenseID` int(10) default NULL,
  `sentDate` date default NULL,
  `attachmentText` text,
  PRIMARY KEY  USING BTREE (`attachmentID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`AttachmentFile` (
  `attachmentFileID` int(10) unsigned NOT NULL auto_increment,
  `attachmentID` int(10) unsigned NOT NULL,
  `attachmentURL` varchar(200) NOT NULL,
  PRIMARY KEY  USING BTREE (`attachmentFileID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`Consortium` (
  `consortiumID` int(10) unsigned NOT NULL auto_increment,
  `shortName` tinytext NOT NULL,
  PRIMARY KEY  (`consortiumID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`Document` (
  `documentID` int(10) unsigned NOT NULL auto_increment,
  `shortName` tinytext NOT NULL,
  `documentTypeID` int(10) unsigned NOT NULL,
  `licenseID` int(10) unsigned NOT NULL,
  `effectiveDate` date default NULL,
  `expirationDate` date default NULL,
  `documentURL` varchar(200) default NULL,
  `parentDocumentID` int(10) unsigned default NULL,
  PRIMARY KEY  (`documentID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`DocumentType` (
  `documentTypeID` int(10) unsigned NOT NULL auto_increment,
  `shortName` tinytext NOT NULL,
  PRIMARY KEY  (`documentTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`Expression` (
  `expressionID` int(10) unsigned NOT NULL auto_increment,
  `documentID` int(10) unsigned NOT NULL,
  `expressionTypeID` int(10) unsigned NOT NULL,
  `documentText` text,
  `simplifiedText` text,
  `lastUpdateDate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `productionUseInd` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`expressionID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`ExpressionNote` (
  `expressionNoteID` int(10) NOT NULL auto_increment,
  `expressionID` int(10) default NULL,
  `note` varchar(2000) default NULL,
  `displayOrderSeqNumber` int(10) default NULL,
  `lastUpdateDate` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`expressionNoteID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`ExpressionType` (
  `expressionTypeID` int(10) unsigned NOT NULL auto_increment,
  `shortName` tinytext NOT NULL,
  `noteType` varchar(45) default NULL,
  PRIMARY KEY  (`expressionTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`License` (
  `licenseID` int(10) unsigned NOT NULL auto_increment,
  `consortiumID` int(10) unsigned default NULL,
  `organizationID` int(10) unsigned default NULL,
  `shortName` tinytext NOT NULL,
  `statusID` int(10) unsigned default NULL,
  `statusDate` datetime default NULL,
  `createDate` datetime default NULL,
  PRIMARY KEY  (`licenseID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`Privilege` (
  `privilegeID` int(10) unsigned NOT NULL auto_increment,
  `shortName` varchar(50) default NULL,
  PRIMARY KEY  USING BTREE (`privilegeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Organization`;
CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`Organization` (
  `organizationID` int(10) unsigned NOT NULL auto_increment,
  `shortName` tinytext NOT NULL,
  PRIMARY KEY  USING BTREE (`organizationID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`Qualifier` (
  `qualifierID` int(10) unsigned NOT NULL auto_increment,
  `expressionTypeID` INTEGER UNSIGNED NOT NULL,
  `shortName` varchar(45) NOT NULL,
  PRIMARY KEY  (`qualifierID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE  IF NOT EXISTS  `_DATABASE_NAME_`.`ExpressionQualifierProfile` (
  `expressionID` INTEGER UNSIGNED NOT NULL,
  `qualifierID` INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY (`expressionID`, `qualifierID`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`SFXProvider` (
  `sfxProviderID` int(10) unsigned NOT NULL auto_increment,
  `documentID` int(10) unsigned NOT NULL,
  `shortName` varchar(245) NOT NULL,
  PRIMARY KEY  (`sfxProviderID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`Signature` (
  `signatureID` int(10) unsigned NOT NULL auto_increment,
  `documentID` int(10) unsigned NOT NULL,
  `signatureTypeID` int(10) unsigned NOT NULL,
  `signatureDate` date default NULL,
  `signerName` tinytext,
  PRIMARY KEY  (`signatureID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`SignatureType` (
  `signatureTypeID` int(10) unsigned NOT NULL auto_increment,
  `shortName` tinytext NOT NULL,
  PRIMARY KEY  (`signatureTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`Status` (
  `statusID` int(10) unsigned NOT NULL auto_increment,
  `shortName` varchar(45) NOT NULL,
  PRIMARY KEY  (`statusID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`User` (
  `loginID` varchar(50) NOT NULL,
  `lastName` varchar(45) default NULL,
  `firstName` varchar(45) default NULL,
  `privilegeID` int(10) unsigned default NULL,
  `emailAddressForTermsTool` varchar(150) default NULL,
  PRIMARY KEY  USING BTREE (`loginID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `_DATABASE_NAME_`.CalendarSettings (
  `calendarSettingsID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shortName` tinytext NOT NULL,
  `value` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`calendarSettingsID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

