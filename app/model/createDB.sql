SET NAMES utf8;

DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `author_id` int(11) DEFAULT NULL,
  `redactor_id` int(11) NOT NULL,
  `modifier_id` int(11) NOT NULL,
  `date_published` datetime NOT NULL,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `perex_md` text,
  `perex_html` text,
  `text_html` text,
  `text_md` text,
  PRIMARY KEY (`id`),
  KEY `redactor_id` (`redactor_id`),
  KEY `modifier_id` (`modifier_id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `article_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`),
  CONSTRAINT `article_ibfk_2` FOREIGN KEY (`redactor_id`) REFERENCES `user` (`id`),
  CONSTRAINT `article_ibfk_3` FOREIGN KEY (`modifier_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `author`;
CREATE TABLE `author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `about_md` text,
  `about_html` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `resource`;
CREATE TABLE `resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `author_id` int(10) DEFAULT NULL,
  `description` text,
  `link_bin` varchar(250) DEFAULT NULL,
  `filetype_bin` varchar(10) DEFAULT 'unknown',
  `link_src` varchar(250) DEFAULT NULL,
  `filetype_src` varchar(10) DEFAULT 'unknown',
  `language` varchar(10) DEFAULT 'unknown',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modifier_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `author_id` (`author_id`),
  KEY `modifier_id` (`modifier_id`),
  CONSTRAINT `resource_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tag_rel_article`;
CREATE TABLE `tag_rel_article` (
  `article_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`article_id`,`tag_id`),
  KEY `article_id` (`article_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `tag_rel_article_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`),
  CONSTRAINT `tag_rel_article_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tag_rel_resource`;
CREATE TABLE `tag_rel_resource` (
  `resource_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`resource_id`,`tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `tag_rel_resource_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`id`),
  CONSTRAINT `tag_rel_resource_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `upload`;
CREATE TABLE `upload` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL COMMENT 'User name',
  `user_id` int(11) DEFAULT NULL COMMENT 'Uploaded by',
  `owner` enum('general','web','user') NOT NULL COMMENT 'Owner group',
  `description` varchar(160) NOT NULL COMMENT 'User description',
  `fileName` varchar(30) NOT NULL COMMENT 'File name like pic.jpg',
  `fileMime` varchar(30) NOT NULL COMMENT 'Mimetype of file',
  `size` int(11) NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last modified',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Created',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `upload_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `failed_sign_in` int(11) NOT NULL DEFAULT '0',
  `role` enum('member','redactor','admin') NOT NULL DEFAULT 'member',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;