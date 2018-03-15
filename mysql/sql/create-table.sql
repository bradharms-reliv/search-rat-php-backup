DROP TABLE if exists search_rat_documents;
CREATE TABLE `search_rat_documents` (
  `id` varchar(512) COLLATE utf8_bin NOT NULL,
  `databaseId` varchar(255) COLLATE utf8_bin NOT NULL,
  `title` text CHARACTER SET utf8 NOT NULL,
  `text` text CHARACTER SET utf8 NOT NULL,
  `metaData` json NOT NULL,
  PRIMARY KEY (`id`,`databaseId`),
  KEY `databaseId` (`databaseId`),
  FULLTEXT KEY `titleAndText` (`title`, `text`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
