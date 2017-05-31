-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 31. Mai 2017 um 11:34
-- Server-Version: 10.0.29-MariaDB-0ubuntu0.16.04.1
-- PHP-Version: 7.0.15-0ubuntu0.16.04.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `artsimily`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Achievement`
--

CREATE TABLE `Achievement` (
  `achievementId` int(11) NOT NULL,
  `playerId` int(11) NOT NULL,
  `achievementName` varchar(50) COLLATE utf8_bin NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `AnswerSession`
--

CREATE TABLE `AnswerSession` (
  `answerSessionId` int(11) NOT NULL,
  `microworkerId` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `playerId` int(11) DEFAULT NULL,
  `currentValidity` float NOT NULL,
  `isOpen` tinyint(11) NOT NULL,
  `timeOpened` int(11) NOT NULL,
  `userAgentIp` varchar(50) COLLATE utf8_bin NOT NULL,
  `userAgentHeader` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Game`
--

CREATE TABLE `Game` (
  `gameId` int(11) NOT NULL,
  `playerId` int(11) NOT NULL,
  `answerSessionId` int(11) NOT NULL,
  `finishedTime` int(11) NOT NULL,
  `gameScore` int(11) NOT NULL,
  `fullScore` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `MediaObject`
--

CREATE TABLE `MediaObject` (
  `mediaObjectId` int(11) NOT NULL,
  `mediaTypeId` int(11) NOT NULL,
  `filename` varchar(128) COLLATE utf8_bin NOT NULL,
  `time` int(11) NOT NULL,
  `checksum` varchar(128) COLLATE utf8_bin NOT NULL,
  `source` varchar(256) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `MediaType`
--

CREATE TABLE `MediaType` (
  `mediaTypeId` int(11) NOT NULL,
  `typeName` varchar(50) COLLATE utf8_bin NOT NULL,
  `extension` varchar(20) COLLATE utf8_bin NOT NULL,
  `template` varchar(50) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Microworker`
--

CREATE TABLE `Microworker` (
  `microworkerId` int(11) NOT NULL,
  `microworkerBatchId` int(11) NOT NULL,
  `token` varchar(100) COLLATE utf8_bin NOT NULL,
  `isLocked` int(11) NOT NULL,
  `timeStarted` int(11) NOT NULL,
  `timeClosed` int(11) NOT NULL,
  `surveyCode` varchar(100) COLLATE utf8_bin NOT NULL,
  `isConfirmed` int(11) NOT NULL,
  `workerId` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `MicroworkerBatch`
--

CREATE TABLE `MicroworkerBatch` (
  `microworkerBatchId` int(11) NOT NULL,
  `timeCreated` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Oauth`
--

CREATE TABLE `Oauth` (
  `oauthId` int(11) NOT NULL,
  `playerId` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8_bin NOT NULL,
  `firstLogin` int(11) NOT NULL,
  `lastLogin` int(11) NOT NULL,
  `oauthIdentifier` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Player`
--

CREATE TABLE `Player` (
  `playerId` int(11) NOT NULL,
  `playerName` varchar(50) COLLATE utf8_bin NOT NULL,
  `isInitialName` int(11) NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `affiliateKey` varchar(50) COLLATE utf8_bin NOT NULL,
  `affiliatedBy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Query`
--

CREATE TABLE `Query` (
  `queryId` int(11) NOT NULL,
  `isClosed` tinyint(11) NOT NULL,
  `time` int(11) NOT NULL,
  `displayName` varchar(100) COLLATE utf8_bin NOT NULL,
  `userId` int(11) NOT NULL,
  `meta` text COLLATE utf8_bin NOT NULL,
  `priority` int(11) NOT NULL,
  `progress` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `QueryResultTuple`
--

CREATE TABLE `QueryResultTuple` (
  `queryResultTupleId` int(11) NOT NULL,
  `queryId` int(11) NOT NULL,
  `resultTupleId` int(11) NOT NULL,
  `score` float NOT NULL,
  `rank` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ResultTuple`
--

CREATE TABLE `ResultTuple` (
  `resultTupleId` int(11) NOT NULL,
  `objectId1` int(11) NOT NULL,
  `objectId2` int(11) NOT NULL,
  `sigma` float NOT NULL,
  `mu` float NOT NULL,
  `isFinal` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Session`
--

CREATE TABLE `Session` (
  `sessionId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `sessionStartDate` int(11) NOT NULL,
  `lastActionDate` int(11) NOT NULL,
  `isOpen` tinyint(4) NOT NULL,
  `sessionLifetime` int(11) NOT NULL,
  `sessionKey` varchar(100) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ThreeCompareAnswer`
--

CREATE TABLE `ThreeCompareAnswer` (
  `threeCompareAnswerId` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `answer` int(11) NOT NULL,
  `resultTupleId1` int(11) NOT NULL,
  `resultTupleId2` int(11) NOT NULL,
  `answerSessionId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `TwoCompareAnswer`
--

CREATE TABLE `TwoCompareAnswer` (
  `twoCompareAnswerId` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `resultTupleId` int(11) NOT NULL,
  `answer` int(11) NOT NULL,
  `answerSessionId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `User`
--

CREATE TABLE `User` (
  `userId` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `passwordHash` varchar(512) COLLATE utf8_bin NOT NULL,
  `passwordSalt` varchar(512) COLLATE utf8_bin NOT NULL,
  `isValid` tinyint(4) NOT NULL,
  `isComputedPassword` tinyint(4) NOT NULL,
  `lastLoginDate` int(11) NOT NULL,
  `registeredSince` int(11) NOT NULL,
  `sessionLifetime` int(11) NOT NULL DEFAULT '600'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Validation`
--

CREATE TABLE `Validation` (
  `validationId` int(11) NOT NULL,
  `answerSessionId` int(11) NOT NULL,
  `validator` varchar(100) COLLATE utf8_bin NOT NULL,
  `event` varchar(100) COLLATE utf8_bin NOT NULL,
  `bonus` float NOT NULL,
  `malus` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `Achievement`
--
ALTER TABLE `Achievement`
  ADD PRIMARY KEY (`achievementId`),
  ADD KEY `playerId` (`playerId`);

--
-- Indizes für die Tabelle `AnswerSession`
--
ALTER TABLE `AnswerSession`
  ADD PRIMARY KEY (`answerSessionId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `playerId` (`playerId`);

--
-- Indizes für die Tabelle `Game`
--
ALTER TABLE `Game`
  ADD PRIMARY KEY (`gameId`),
  ADD KEY `playerId` (`playerId`),
  ADD KEY `answerSessionId` (`answerSessionId`);

--
-- Indizes für die Tabelle `MediaObject`
--
ALTER TABLE `MediaObject`
  ADD PRIMARY KEY (`mediaObjectId`),
  ADD KEY `mediaTypeId` (`mediaTypeId`);

--
-- Indizes für die Tabelle `MediaType`
--
ALTER TABLE `MediaType`
  ADD PRIMARY KEY (`mediaTypeId`);

--
-- Indizes für die Tabelle `Microworker`
--
ALTER TABLE `Microworker`
  ADD PRIMARY KEY (`microworkerId`);

--
-- Indizes für die Tabelle `MicroworkerBatch`
--
ALTER TABLE `MicroworkerBatch`
  ADD PRIMARY KEY (`microworkerBatchId`);

--
-- Indizes für die Tabelle `Oauth`
--
ALTER TABLE `Oauth`
  ADD PRIMARY KEY (`oauthId`),
  ADD KEY `playerId` (`playerId`);

--
-- Indizes für die Tabelle `Player`
--
ALTER TABLE `Player`
  ADD PRIMARY KEY (`playerId`);

--
-- Indizes für die Tabelle `Query`
--
ALTER TABLE `Query`
  ADD PRIMARY KEY (`queryId`),
  ADD KEY `userId` (`userId`);

--
-- Indizes für die Tabelle `QueryResultTuple`
--
ALTER TABLE `QueryResultTuple`
  ADD PRIMARY KEY (`queryResultTupleId`),
  ADD KEY `queryId` (`queryId`),
  ADD KEY `resultTupleId` (`resultTupleId`);

--
-- Indizes für die Tabelle `ResultTuple`
--
ALTER TABLE `ResultTuple`
  ADD PRIMARY KEY (`resultTupleId`),
  ADD KEY `objectId1` (`objectId1`),
  ADD KEY `objectId2` (`objectId2`);

--
-- Indizes für die Tabelle `Session`
--
ALTER TABLE `Session`
  ADD PRIMARY KEY (`sessionId`),
  ADD KEY `userId` (`userId`);

--
-- Indizes für die Tabelle `ThreeCompareAnswer`
--
ALTER TABLE `ThreeCompareAnswer`
  ADD PRIMARY KEY (`threeCompareAnswerId`),
  ADD KEY `answerSessionId` (`answerSessionId`),
  ADD KEY `ThreeCompareAnswer_ibfk_2` (`resultTupleId1`),
  ADD KEY `ThreeCompareAnswer_ibfk_3` (`resultTupleId2`);

--
-- Indizes für die Tabelle `TwoCompareAnswer`
--
ALTER TABLE `TwoCompareAnswer`
  ADD PRIMARY KEY (`twoCompareAnswerId`),
  ADD KEY `answerSessionId` (`answerSessionId`),
  ADD KEY `TwoCompareAnswer_ibfk_1` (`resultTupleId`);

--
-- Indizes für die Tabelle `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`userId`);

--
-- Indizes für die Tabelle `Validation`
--
ALTER TABLE `Validation`
  ADD PRIMARY KEY (`validationId`),
  ADD KEY `answerSessionId` (`answerSessionId`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `Achievement`
--
ALTER TABLE `Achievement`
  MODIFY `achievementId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;
--
-- AUTO_INCREMENT für Tabelle `AnswerSession`
--
ALTER TABLE `AnswerSession`
  MODIFY `answerSessionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1377;
--
-- AUTO_INCREMENT für Tabelle `Game`
--
ALTER TABLE `Game`
  MODIFY `gameId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=832;
--
-- AUTO_INCREMENT für Tabelle `MediaObject`
--
ALTER TABLE `MediaObject`
  MODIFY `mediaObjectId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127018;
--
-- AUTO_INCREMENT für Tabelle `MediaType`
--
ALTER TABLE `MediaType`
  MODIFY `mediaTypeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `Microworker`
--
ALTER TABLE `Microworker`
  MODIFY `microworkerId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `MicroworkerBatch`
--
ALTER TABLE `MicroworkerBatch`
  MODIFY `microworkerBatchId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `Oauth`
--
ALTER TABLE `Oauth`
  MODIFY `oauthId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT für Tabelle `Player`
--
ALTER TABLE `Player`
  MODIFY `playerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT für Tabelle `Query`
--
ALTER TABLE `Query`
  MODIFY `queryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2338;
--
-- AUTO_INCREMENT für Tabelle `QueryResultTuple`
--
ALTER TABLE `QueryResultTuple`
  MODIFY `queryResultTupleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=441314;
--
-- AUTO_INCREMENT für Tabelle `ResultTuple`
--
ALTER TABLE `ResultTuple`
  MODIFY `resultTupleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156451;
--
-- AUTO_INCREMENT für Tabelle `Session`
--
ALTER TABLE `Session`
  MODIFY `sessionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;
--
-- AUTO_INCREMENT für Tabelle `ThreeCompareAnswer`
--
ALTER TABLE `ThreeCompareAnswer`
  MODIFY `threeCompareAnswerId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `TwoCompareAnswer`
--
ALTER TABLE `TwoCompareAnswer`
  MODIFY `twoCompareAnswerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61158;
--
-- AUTO_INCREMENT für Tabelle `User`
--
ALTER TABLE `User`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `Validation`
--
ALTER TABLE `Validation`
  MODIFY `validationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=447;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `Achievement`
--
ALTER TABLE `Achievement`
  ADD CONSTRAINT `Achievement_ibfk_1` FOREIGN KEY (`playerId`) REFERENCES `Player` (`playerId`);

--
-- Constraints der Tabelle `AnswerSession`
--
ALTER TABLE `AnswerSession`
  ADD CONSTRAINT `AnswerSession_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `User` (`userId`),
  ADD CONSTRAINT `AnswerSession_ibfk_2` FOREIGN KEY (`playerId`) REFERENCES `Player` (`playerId`);

--
-- Constraints der Tabelle `Game`
--
ALTER TABLE `Game`
  ADD CONSTRAINT `Game_ibfk_1` FOREIGN KEY (`playerId`) REFERENCES `Player` (`playerId`),
  ADD CONSTRAINT `Game_ibfk_2` FOREIGN KEY (`answerSessionId`) REFERENCES `AnswerSession` (`answerSessionId`);

--
-- Constraints der Tabelle `MediaObject`
--
ALTER TABLE `MediaObject`
  ADD CONSTRAINT `MediaObject_ibfk_1` FOREIGN KEY (`mediaTypeId`) REFERENCES `MediaType` (`mediaTypeId`);

--
-- Constraints der Tabelle `Oauth`
--
ALTER TABLE `Oauth`
  ADD CONSTRAINT `Oauth_ibfk_1` FOREIGN KEY (`playerId`) REFERENCES `Player` (`playerId`);

--
-- Constraints der Tabelle `Query`
--
ALTER TABLE `Query`
  ADD CONSTRAINT `Query_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `User` (`userId`);

--
-- Constraints der Tabelle `QueryResultTuple`
--
ALTER TABLE `QueryResultTuple`
  ADD CONSTRAINT `QueryResultTuple_ibfk_1` FOREIGN KEY (`queryId`) REFERENCES `Query` (`queryId`),
  ADD CONSTRAINT `QueryResultTuple_ibfk_2` FOREIGN KEY (`resultTupleId`) REFERENCES `ResultTuple` (`resultTupleId`);

--
-- Constraints der Tabelle `ResultTuple`
--
ALTER TABLE `ResultTuple`
  ADD CONSTRAINT `ResultTuple_ibfk_1` FOREIGN KEY (`objectId1`) REFERENCES `MediaObject` (`mediaObjectId`),
  ADD CONSTRAINT `ResultTuple_ibfk_2` FOREIGN KEY (`objectId2`) REFERENCES `MediaObject` (`mediaObjectId`);

--
-- Constraints der Tabelle `Session`
--
ALTER TABLE `Session`
  ADD CONSTRAINT `Session_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `User` (`userId`);

--
-- Constraints der Tabelle `ThreeCompareAnswer`
--
ALTER TABLE `ThreeCompareAnswer`
  ADD CONSTRAINT `ThreeCompareAnswer_ibfk_1` FOREIGN KEY (`answerSessionId`) REFERENCES `AnswerSession` (`answerSessionId`),
  ADD CONSTRAINT `ThreeCompareAnswer_ibfk_2` FOREIGN KEY (`resultTupleId1`) REFERENCES `ResultTuple` (`resultTupleId`),
  ADD CONSTRAINT `ThreeCompareAnswer_ibfk_3` FOREIGN KEY (`resultTupleId2`) REFERENCES `ResultTuple` (`resultTupleId`);

--
-- Constraints der Tabelle `TwoCompareAnswer`
--
ALTER TABLE `TwoCompareAnswer`
  ADD CONSTRAINT `TwoCompareAnswer_ibfk_1` FOREIGN KEY (`resultTupleId`) REFERENCES `ResultTuple` (`resultTupleId`),
  ADD CONSTRAINT `TwoCompareAnswer_ibfk_2` FOREIGN KEY (`answerSessionId`) REFERENCES `AnswerSession` (`answerSessionId`);

--
-- Constraints der Tabelle `Validation`
--
ALTER TABLE `Validation`
  ADD CONSTRAINT `Validation_ibfk_1` FOREIGN KEY (`answerSessionId`) REFERENCES `AnswerSession` (`answerSessionId`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
