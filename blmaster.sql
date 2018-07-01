CREATE TABLE `servers` (
 `ip` varchar(45) NOT NULL COMMENT 'The IP address of the server.',
 `port` smallint(6) NOT NULL COMMENT 'The port of the server.',
 `passworded` int(1) NOT NULL COMMENT 'Whether the server is passworded or not.',
 `dedicated` int(1) NOT NULL COMMENT 'Whether the server is dedicated or not.',
 `serverName` tinytext NOT NULL COMMENT 'The server''s name.',
 `players` smallint(6) NOT NULL COMMENT 'The players currently on the server.',
 `maxPlayers` smallint(6) NOT NULL COMMENT 'The maximum players allowed on the server.',
 `gamemode` tinytext NOT NULL COMMENT 'The gamemode (or map name) of the server.',
 `brickCount` int(11) NOT NULL COMMENT 'The bricks built on the server.',
 `version` int(64) NOT NULL COMMENT 'The version of Blockland the server is on.',
 `uptime` bigint(20) NOT NULL COMMENT 'A Unix timestamp of the last time the server posted to the masterserver.',
 `bl_id` mediumint(9) NOT NULL COMMENT 'The host''s BL_ID.',
 `key_id` tinytext NOT NULL COMMENT 'The first five characters of the host''s key.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
