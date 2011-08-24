CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`Attachment` (
  `attachmentID` int(10) NOT NULL auto_increment,
  `licenseID` int(10) default NULL,
  `sentDate` date default NULL,
  `attachmentText` text,
  PRIMARY KEY  USING BTREE (`attachmentID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`AttachmentFile` (
  `attachmentFileID` int(10) unsigned NOT NULL auto_increment,
  `attachmentID` int(10) unsigned NOT NULL,
  `attachmentURL` varchar(200) NOT NULL,
  PRIMARY KEY  USING BTREE (`attachmentFileID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`Consortium` (
  `consortiumID` int(10) unsigned NOT NULL auto_increment,
  `shortName` tinytext NOT NULL,
  PRIMARY KEY  (`consortiumID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`DocumentType` (
  `documentTypeID` int(10) unsigned NOT NULL auto_increment,
  `shortName` tinytext NOT NULL,
  PRIMARY KEY  (`documentTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`Expression` (
  `expressionID` int(10) unsigned NOT NULL auto_increment,
  `documentID` int(10) unsigned NOT NULL,
  `expressionTypeID` int(10) unsigned NOT NULL,
  `documentText` text,
  `simplifiedText` text,
  `lastUpdateDate` timestamp NOT NULL default '0000-00-00 00:00:00',
  `productionUseInd` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`expressionID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`ExpressionNote` (
  `expressionNoteID` int(10) NOT NULL auto_increment,
  `expressionID` int(10) default NULL,
  `note` varchar(2000) default NULL,
  `displayOrderSeqNumber` int(10) default NULL,
  `lastUpdateDate` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`expressionNoteID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`ExpressionType` (
  `expressionTypeID` int(10) unsigned NOT NULL auto_increment,
  `shortName` tinytext NOT NULL,
  `noteType` varchar(45) default NULL,
  PRIMARY KEY  (`expressionTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`License` (
  `licenseID` int(10) unsigned NOT NULL auto_increment,
  `consortiumID` int(10) unsigned default NULL,
  `organizationID` int(10) unsigned default NULL,
  `shortName` tinytext NOT NULL,
  `statusID` int(10) unsigned default NULL,
  `statusDate` datetime default NULL,
  `createDate` datetime default NULL,
  PRIMARY KEY  (`licenseID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`Privilege` (
  `privilegeID` int(10) unsigned NOT NULL auto_increment,
  `shortName` varchar(50) default NULL,
  PRIMARY KEY  USING BTREE (`privilegeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `_DATABASE_NAME_`.`Organization`;
CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`Organization` (
  `organizationID` int(10) unsigned NOT NULL auto_increment,
  `shortName` tinytext NOT NULL,
  PRIMARY KEY  USING BTREE (`organizationID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`Qualifier` (
  `qualifierID` int(10) unsigned NOT NULL auto_increment,
  `expressionTypeID` INTEGER UNSIGNED NOT NULL,
  `shortName` varchar(45) NOT NULL,
  PRIMARY KEY  (`qualifierID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE  IF NOT EXISTS  `_DATABASE_NAME_`.`ExpressionQualifierProfile` (
  `expressionID` INTEGER UNSIGNED NOT NULL,
  `qualifierID` INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY (`expressionID`, `qualifierID`)
)ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`SFXProvider` (
  `sfxProviderID` int(10) unsigned NOT NULL auto_increment,
  `documentID` int(10) unsigned NOT NULL,
  `shortName` varchar(245) NOT NULL,
  PRIMARY KEY  (`sfxProviderID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`Signature` (
  `signatureID` int(10) unsigned NOT NULL auto_increment,
  `documentID` int(10) unsigned NOT NULL,
  `signatureTypeID` int(10) unsigned NOT NULL,
  `signatureDate` date default NULL,
  `signerName` tinytext,
  PRIMARY KEY  (`signatureID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`SignatureType` (
  `signatureTypeID` int(10) unsigned NOT NULL auto_increment,
  `shortName` tinytext NOT NULL,
  PRIMARY KEY  (`signatureTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`Status` (
  `statusID` int(10) unsigned NOT NULL auto_increment,
  `shortName` varchar(45) NOT NULL,
  PRIMARY KEY  (`statusID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS  `_DATABASE_NAME_`.`User` (
  `loginID` varchar(50) NOT NULL,
  `lastName` varchar(45) default NULL,
  `firstName` varchar(45) default NULL,
  `privilegeID` int(10) unsigned default NULL,
  `emailAddressForTermsTool` varchar(150) default NULL,
  PRIMARY KEY  USING BTREE (`loginID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



DELETE FROM `_DATABASE_NAME_`.DocumentType;
INSERT INTO `_DATABASE_NAME_`.DocumentType (shortName) values ('SERU');
INSERT INTO `_DATABASE_NAME_`.DocumentType (shortName) values ('Internal Acknowledgment');
INSERT INTO `_DATABASE_NAME_`.DocumentType (shortName) values ('Agreement');
INSERT INTO `_DATABASE_NAME_`.DocumentType (shortName) values ('Countersigned Agreement');
INSERT INTO `_DATABASE_NAME_`.DocumentType (shortName) values ('Amendment');
INSERT INTO `_DATABASE_NAME_`.DocumentType (shortName) values ('Consortium Authorization Statement');
INSERT INTO `_DATABASE_NAME_`.DocumentType (shortName) values ('Order Form');

DELETE FROM `_DATABASE_NAME_`.ExpressionType;
INSERT INTO `_DATABASE_NAME_`.ExpressionType (expressionTypeID, shortName, noteType) values (1, 'Authorized Users','Internal');
INSERT INTO `_DATABASE_NAME_`.ExpressionType (expressionTypeID, shortName, noteType) values (2, 'Interlibrary Loan','Display');
INSERT INTO `_DATABASE_NAME_`.ExpressionType (expressionTypeID, shortName, noteType) values (3, 'Coursepacks','Display');
INSERT INTO `_DATABASE_NAME_`.ExpressionType (expressionTypeID, shortName, noteType) values (4, 'eReserves','Display');
INSERT INTO `_DATABASE_NAME_`.ExpressionType (expressionTypeID, shortName, noteType) values (5, 'Post Cancellation Access','Internal');
INSERT INTO `_DATABASE_NAME_`.ExpressionType (expressionTypeID, shortName, noteType) values (6, 'General Notes','Internal');
INSERT INTO `_DATABASE_NAME_`.ExpressionType (expressionTypeID, shortName, noteType) values (7, 'Jurisdiction (Choice of Forum)','Internal');
INSERT INTO `_DATABASE_NAME_`.ExpressionType (expressionTypeID, shortName, noteType) values (8, 'Third Party Archiving','Internal');
INSERT INTO `_DATABASE_NAME_`.ExpressionType (expressionTypeID, shortName, noteType) values (9, 'Confidentiality Clause','Internal');
INSERT INTO `_DATABASE_NAME_`.ExpressionType (expressionTypeID, shortName, noteType) values (10, 'Multi-year Term','Internal');


DELETE FROM `_DATABASE_NAME_`.Qualifier;
INSERT INTO `_DATABASE_NAME_`.Qualifier (expressionTypeID, shortName) values (2, 'Not Clear');
INSERT INTO `_DATABASE_NAME_`.Qualifier (expressionTypeID, shortName) values (2, 'Not Reviewed');
INSERT INTO `_DATABASE_NAME_`.Qualifier (expressionTypeID, shortName) values (2, 'Prohibited');
INSERT INTO `_DATABASE_NAME_`.Qualifier (expressionTypeID, shortName) values (2, 'Permitted');
INSERT INTO `_DATABASE_NAME_`.Qualifier (expressionTypeID, shortName) values (3, 'Not Clear');
INSERT INTO `_DATABASE_NAME_`.Qualifier (expressionTypeID, shortName) values (3, 'Not Reviewed');
INSERT INTO `_DATABASE_NAME_`.Qualifier (expressionTypeID, shortName) values (3, 'Prohibited');
INSERT INTO `_DATABASE_NAME_`.Qualifier (expressionTypeID, shortName) values (3, 'Permitted');


DELETE FROM `_DATABASE_NAME_`.Privilege;
INSERT INTO `_DATABASE_NAME_`.Privilege (privilegeID, shortName) values (1, 'admin');
INSERT INTO `_DATABASE_NAME_`.Privilege (privilegeID, shortName) values (2, 'add/edit');
INSERT INTO `_DATABASE_NAME_`.Privilege (privilegeID, shortName) values (3, 'view only');
INSERT INTO `_DATABASE_NAME_`.Privilege (privilegeID, shortName) values (4, 'restricted');

DELETE FROM `_DATABASE_NAME_`.Organization;
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Accessible Archives Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("ACCU Weather Sales and Services, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Adam Matthew Digital Ltd");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Agricultural History Society");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Agricultural Institute of Canada");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("AICPA");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Akademiai Kiado");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Albert C. Muller");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Alexander Street Press, LLC");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Allen Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Alliance for Children and Families");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Academy of Religion");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Association for Cancer Research (AACR)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Association for the Advancement of Science (AAAS)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Association of Immunologists, Inc.");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Concrete Institute (ACI)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Council of Learned Societies (ACLS)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Counseling Association");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Economic Association (AEA)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Fisheries Society");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Geophysical Union");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Insitute of Physics (AIP)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Institute of Aeronautics and Astronautics (AIAA)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Library Association (ALA)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Mathematical Society (AMS)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Medical Association (AMA)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Meteorological Society (AMS)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Physical Society (APS)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Physiological Society");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Phytopathological Society");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Psychiatric Publishing");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Psychological Association (APA)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Society for Cell Biology");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Society for Clinical Investigation");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Society for Horticultural Science");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Society for Nutrition");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Society for Testing and Materials (ASTM)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Society of Agronomy");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Society of Civil Engineers (ASCE)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Society of Limnology and Oceanography (ASLO)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Society of Plant Biologists");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Society of Tropical Medicine and Hygiene");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("American Statistical Association");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Ammons Scientific Limited");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Annual Reviews");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Antiquity Publications Limited");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Applied Probability Trust");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Army Times Publishing Company");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("ARTstor Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Asempa Limited");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Association of Research Libraries (ARL)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Atypon Systems Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Augustine Institute");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Barkhuis Publishing");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Begell House, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Beilstein");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Belser Wissenschaftlicher Dienst Ltd");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Berg Publishers");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Berghahn Books");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Berkeley Electronic Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("BIGresearch LLC");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("BioMed Central");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("BioOne");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Blackwell Publishing");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("BMJ Publishing Group Limited");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Boopsie, INC.");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Botanical Society of America");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Boyd Printing");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Brepols Publishers");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Brill");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Bulletin of the Atomic Scientists");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Bureau of National Affairs, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Business Monitor International");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("CABI Publishing");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Cambridge Crystallographic Data Centre");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Cambridge Scientific Abstracts");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Cambridge University Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Canadian Association of African Studies");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Canadian Mathematical Society");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Carbon Disclosure Project");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("CareerShift LLC");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("CCH Incorporated");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Centro de Investigaciones Sociologicas");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Chemical Abstracts Service (CAS)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Chiniquy Collection");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Chorus America");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Chronicle of Higher Education");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Colegio de Mexico");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("College Art Association");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Company of Biologists Ltd");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Competitive Media Reporting, LLC (TNS Media Intelligence TNSMI)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Consejo Superior de Investigaciones Cientificas (CSIC)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Consumer Electronics Association");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Cornell University Library");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Corporacion Latinobarometro");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Corporation for National Research Initiatives (CNRI)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("CQ Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("CSIRO Publishing");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Current History, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Dialog");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Dialogue Foundation");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Digital Distributed Community Archive");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Digital Heritage Publishing Limited");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Duke University Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Dun and Bradstreet Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Dunstans Publishing Ltd");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("East View Information Services");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("EBSCO");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Ecological Society of America");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Edinburgh University Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("EDP Sciences");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Elsevier");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Encyclopaedia Britannica Online");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Endocrine Society");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Entomological Society of Canada");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Equinox Publishing Ltd");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("European Mathematical Society Publishing House");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("European Society of Endocrinology");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Evolutionary Ecology Ltd");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("ExLibris");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Experian Marketing Solutions, Inc.");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("FamilyLink.com, Inc.");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("FamilyLink.com, Inc.");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Faulkner Information Services");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Federation of American Societies for Experimental Biology");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Forrester Research, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Franz Steiner Verlag");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Genetics Society of America");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Geographic Research, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("GeoScienceWorld");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Global Science Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Grove Dictionaries, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("GuideStar USA, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("H.W. Wilson Company");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("H1 Base, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Hans Zell Publishing");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Haworth Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Heldref Publications");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("HighWire Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Histochemical Society");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Human Kinetics Inc.");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("IBISWorld USA");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Idea Group Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("IEEE");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Incisive Media Ltd");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Indiana University Mathematics Journal");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Informa Healthcare USA, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Information Resources, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("INFORMS");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Ingentaconnect");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Institute of Mathematics of the Polish Academy of Sciences");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Institute of Physics (IOP)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Institution of Engineering and Technology (IET)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Institutional Shareholder Services Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("InteLex");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Intellect");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Intelligence Research Limited");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("International Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("IOS Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("IPA Source, LLC");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Irish Newspaper Archives Ltd");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("ITHAKA");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("IVES Group, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Japan Focus");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("John Benjamins Publishing Company");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("JSTOR");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Karger");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Keesings Worldwide, LLC");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("KLD Research and Analytics Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Landes Bioscience");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("LexisNexis");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Librairie Droz");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Library of Congress, Cataloging Distribution Service");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Lipper Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Liverpool University Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Lord Music Reference Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("M.E. Sharpe, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Manchester University Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Marine Biological Laboratory");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("MarketResearch.com, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Marquis Who's Who LLC");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Mary Ann Liebert, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Massachusetts Medical Society");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Mathematical Sciences Publishers");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Mediamark Research and Intelligence, LLC");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Mergent, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Metropolitan Opera");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Mintel International Group Limited");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("MIT Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("MIT");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Morningstar Inc.");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("National Academy of Sciences");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("National Gallery Company Ltd");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("National Research Council of Canada");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Nature Publishing Group");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Naxos Digital Services Limited");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Neilson Journals Publishing");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("New York Review of Books");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("NewsBank, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("OCLC");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Otto Harrassowitz");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Ovid");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Oxford Centre of Hebrew and Jewish Studies");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Oxford University Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Paradigm Publishers");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Paratext");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Peeters Publishers");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Philosophy Documentation Center");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Portland Press Limited");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Preservation Technologies LP");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Project Muse");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("ProQuest LLC");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Psychoanalytic Electronic Publishing Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("R.R. Bowker");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Religious and Theological Abstracts, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Reuters Loan Pricing Corporation");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Risk Management Association (RMA)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Rivista di Studi italiani");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Robert Blakemore");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Rockefeller University Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Roper Center for Public Opinion Research");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Royal Society of Chemistry");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Royal Society of London");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("SAGE Publications");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Scholarly Digital Editions");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Seminario Matematico of the University of Padua");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Simmons Market Research Bureau Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("SISMEL - Edizioni del Galluzzo");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Social Explorer");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Societe Mathematique de France");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Society for Endocrinology");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Society for Experimental Biology and Medicine");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Society for General Microbiology");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Society for Industrial and Applied Mathematics (SIAM)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Society for Leukocyte Biology");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Society for Neuroscience");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Society for Reproduction and Fertility");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Society of Antiquaries of Scotland");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Society of Environmental Toxicology and Chemistry");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("SPIE");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Springer");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Standard and Poor's");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Stanford University");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Swank Motion Pictures, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Swiss Chemical Society");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Tablet Publishing (London)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Taylor and Francis");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Teachers College Record");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Terra Scientific Publishing Company");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Tetrad Computer Applications Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("The Academy of the Hebrew Language");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Thesaurus Linguae Graecae");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Thomas Telford Ltd");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Thomson Financial Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Thomson Gale");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Thomson RIA");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Thomson Scientific Inc. (Institute for Scientific Information, Inc.)");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Trans Tech Publications");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Transportation Research Board");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("U.S. Department of Commerce");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("UCLA Chicano Studies Research Center Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("University of Barcelona");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("University of Buckingham Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("University of California Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("University of Chicago Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("University of Houston Department of Mathematics");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("University of Illinois Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("University of Iowa");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("University of Pittsburgh");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("University of Toronto Press Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("University of Toronto");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("University of Virginia Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("University of Wisconsin Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Universum USA");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Uniworld Business Publications, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Value Line, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Vanderbilt University");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Vault, Inc");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Verlag C.H. Beck");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Verlag der Zeitschrift fur Naturforschung ");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("W.S. Maney and Son Ltd");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Walter de Gruyter");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("White Horse Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Wiley");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("World Scientific");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("World Trade Press");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Worldwatch Institute");
INSERT INTO `_DATABASE_NAME_`.Organization (shortName) values ("Yankelovich Inc");


DELETE FROM `_DATABASE_NAME_`.SignatureType;
INSERT INTO `_DATABASE_NAME_`.SignatureType (shortName) values ("Agent");
INSERT INTO `_DATABASE_NAME_`.SignatureType (shortName) values ("Consortium");
INSERT INTO `_DATABASE_NAME_`.SignatureType (shortName) values ("Internal");
INSERT INTO `_DATABASE_NAME_`.SignatureType (shortName) values ("Provider");

DELETE FROM `_DATABASE_NAME_`.Status;
INSERT INTO `_DATABASE_NAME_`.Status (shortName) values ("Awaiting Document");
INSERT INTO `_DATABASE_NAME_`.Status (shortName) values ("Complete");
INSERT INTO `_DATABASE_NAME_`.Status (shortName) values ("Document Only");
INSERT INTO `_DATABASE_NAME_`.Status (shortName) values ("Editing Expressions");
INSERT INTO `_DATABASE_NAME_`.Status (shortName) values ("NLR");



DELETE FROM `_DATABASE_NAME_`.Attachment;
DELETE FROM `_DATABASE_NAME_`.AttachmentFile;
DELETE FROM `_DATABASE_NAME_`.Consortium;
DELETE FROM `_DATABASE_NAME_`.Document;
DELETE FROM `_DATABASE_NAME_`.Expression;
DELETE FROM `_DATABASE_NAME_`.ExpressionNote;
DELETE FROM `_DATABASE_NAME_`.License;
DELETE FROM `_DATABASE_NAME_`.SFXProvider;
DELETE FROM `_DATABASE_NAME_`.Signature;