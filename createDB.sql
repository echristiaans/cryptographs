-- MySQL script to create a crypto database
-- The database is only created if it does not yet exist

CREATE DATABASE IF NOT EXISTS crypto;

USE crypto;

-- DROP TABLE IF EXISTS 'coins'

CREATE TABLE 'coins' (
 'coinId' int(15) unsigned zerofill NOT NULL AUTO_INCREMENT,
 'coinShortName' varchar(5) NOT NULL,
 'timeStamp' timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 'coinName' varchar(25) NOT NULL,
 'coinPurchaseValue' decimal(10,5) NOT NULL,
 'coinPurchaseAmount' decimal(15,8) NOT NULL,
 'coinCurrentPrice' decimal(10,5) NOT NULL,
 'coinCurrentValue' decimal(10,5) NOT NULL,
 'coinPercentage' int(5) NOT NULL,
 'coinProfit' decimal(10,2) DEFAULT NULL,
 PRIMARY KEY ('coinId')
) ENGINE=InnoDB AUTO_INCREMENT=75587 DEFAULT CHARSET=utf8

-- DROP TABLE IF EXISTS 'totals'

CREATE TABLE 'totals' (
'timeStamp' timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
'totalProfit' decimal(12,2) DEFAULT NULL,
'totalInvestment' decimal(12,2) DEFAULT NULL,
'totalCurrentValue' decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8

GRANT SELECT, INSERT, UPDATE, DELETE
       ON crypto.*
       TO 'cryptouser'@'localhost' IDENTIFIED BY 'password';
