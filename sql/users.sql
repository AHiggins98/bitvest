CREATE TABLE IF NOT EXISTS `users` (
`id` bigint(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verify_code` varchar(255) DEFAULT NULL,
  `btc_address` varchar(255) DEFAULT NULL,
  `btc_password` varchar(255) DEFAULT NULL,
  `created_ts` datetime NOT NULL,
  `updated_ts` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `users`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

-- Test users: 
-- a@b.com / pass123
-- b@c.com / pass123

INSERT INTO `users` (`id`, `email`, `password`, `verify_code`, `btc_address`, `btc_password`, `created_ts`, `updated_ts`) VALUES
(1, 'a@b.com', '*FB6E1F205D675BC29B052DB14CCEFE7759C5FF7E', 'pass123', NULL, NULL, '0000-00-00 00:00:00', NULL),
(2, 'b@c.com', '*FB6E1F205D675BC29B052DB14CCEFE7759C5FF7E', 'pass123', NULL, NULL, '0000-00-00 00:00:00', NULL);
