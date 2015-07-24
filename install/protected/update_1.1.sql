ALTER TABLE `Qualification` RENAME TO `Qualifier`,
 CHANGE COLUMN `qualificationID` `qualifierID` INTEGER UNSIGNED NOT NULL DEFAULT NULL AUTO_INCREMENT,
 DROP PRIMARY KEY,
 ADD PRIMARY KEY  USING BTREE(`qualifierID`);


DELETE FROM `Qualifier`;
ALTER TABLE `Qualifier` 
ADD COLUMN `expressionTypeID` INTEGER UNSIGNED NOT NULL AFTER `qualifierID`;


CREATE TABLE IF NOT EXISTS  `ExpressionQualifierProfile` (
  `expressionID` INTEGER UNSIGNED NOT NULL,
  `qualifierID` INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY (`expressionID`, `qualifierID`)
)ENGINE=MyISAM DEFAULT CHARSET=latin1;



ALTER TABLE `Expression` DROP COLUMN `qualificationID`;