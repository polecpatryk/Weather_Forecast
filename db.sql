CREATE DATABASE `weather_forecast` ;

USE `weather_forecast` ;

CREATE TABLE IF NOT EXISTS `weather_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` int(4) unsigned NOT NULL,
  `city` varchar(64) NOT NULL,
  `from_date` datetime NOT NULL,
  `to_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;