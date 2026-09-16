CREATE TABLE IF NOT EXISTS `tbl_form_leads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_type` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(64) NOT NULL DEFAULT '',
  `exam_code` varchar(128) NOT NULL DEFAULT '',
  `message` text,
  `source_page` varchar(255) NOT NULL DEFAULT '',
  `source_id` int(11) NOT NULL DEFAULT '0',
  `email_sent` tinyint(1) NOT NULL DEFAULT '0',
  `ip` varchar(64) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `form_type` (`form_type`),
  KEY `email` (`email`),
  KEY `created_at` (`created_at`)
);
