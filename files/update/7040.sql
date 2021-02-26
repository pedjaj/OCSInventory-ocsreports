DROP TABLE IF EXISTS `unixusers`;
DROP TABLE IF EXISTS `local_groups`;
DROP TABLE IF EXISTS `local_users`;

CREATE TABLE IF NOT EXISTS `local_users` (
    `ID` INT(11) NOT NULL AUTO_INCREMENT,
    `HARDWARE_ID` INT(11) NOT NULL,
    `ID_USER` VARCHAR(255) DEFAULT NULL,
    `GID` VARCHAR(255) DEFAULT NULL,
    `NAME` VARCHAR(255) DEFAULT NULL,
    `HOME` VARCHAR(255) DEFAULT NULL,
    `SHELL` VARCHAR(255) DEFAULT NULL,
    `LOGIN` VARCHAR(255) DEFAULT NULL,
    `MEMBER` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY  (`ID`,`HARDWARE_ID`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `local_groups` (
    `ID` INT(11) NOT NULL AUTO_INCREMENT,
    `HARDWARE_ID` INT(11) NOT NULL,
    `ID_GROUP` VARCHAR(255) DEFAULT NULL,
    `NAME` VARCHAR(255) DEFAULT NULL,
    `MEMBER` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY  (`ID`,`HARDWARE_ID`)
) ENGINE=InnoDB;