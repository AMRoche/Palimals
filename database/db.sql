SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `palimals` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;

CREATE  TABLE IF NOT EXISTS  `palimal` (
  `name` varchar(20) NOT NULL,
  `type` varchar(15) NOT NULL,
  `fullness` float NOT NULL,
  `happiness` float NOT NULL,
  `max_fullness` float NOT NULL,
  `max_happiness` float NOT NULL,
  `playfulness` float NOT NULL,
  `glutton` float NOT NULL,
  `level` float NOT NULL,
  `username` float NOT NULL,
  `last_interacted` datetime NOT NULL,
  `bornon` datetime NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS  `user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password` char(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_bool` binary(1) NOT NULL,
  `last_login` datetime NOT NULL,
  `salt` varchar(20) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;