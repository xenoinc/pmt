/*
 * REMOVE THIS HEADER BEFORE USING FILE
 *
 * Tables Need Created:
 *
 * 
 */

CREATE TABLE IF NOT EXISTS `ticket_attachments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contents` longtext COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` bigint(20) NOT NULL,
  `uploaded` int(11) NOT NULL,
  `owner_id` bigint(20) NOT NULL,
  `owner_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ticket_id` bigint(20) NOT NULL,
  `project_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;