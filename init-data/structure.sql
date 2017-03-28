-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 28. Mrz 2017 um 13:30
-- Server-Version: 5.7.17-0ubuntu0.16.04.1
-- PHP-Version: 7.0.15-0ubuntu0.16.04.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `evaluator`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `MediaObject`
--

CREATE TABLE `MediaObject` (
  `mediaObjectId` int(11) NOT NULL,
  `mediaTypeId` int(11) NOT NULL,
  `filename` varchar(128) COLLATE utf8_bin NOT NULL,
  `time` int(11) NOT NULL,
  `checksum` varchar(128) COLLATE utf8_bin NOT NULL
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
-- Tabellenstruktur für Tabelle `Query`
--

CREATE TABLE `Query` (
  `queryId` int(11) NOT NULL,
  `isClosed` tinyint(11) NOT NULL,
  `time` int(11) NOT NULL,
  `displayName` varchar(50) COLLATE utf8_bin NOT NULL,
  `userId` int(11) NOT NULL,
  `meta` text COLLATE utf8_bin NOT NULL,
  `priority` int(11) NOT NULL
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
  `similarity` int(11) NOT NULL,
  `certainty` int(11) NOT NULL
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

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `MediaObject`
--
ALTER TABLE `MediaObject`
  ADD PRIMARY KEY (`mediaObjectId`);

--
-- Indizes für die Tabelle `MediaType`
--
ALTER TABLE `MediaType`
  ADD PRIMARY KEY (`mediaTypeId`);

--
-- Indizes für die Tabelle `Query`
--
ALTER TABLE `Query`
  ADD PRIMARY KEY (`queryId`);

--
-- Indizes für die Tabelle `QueryResultTuple`
--
ALTER TABLE `QueryResultTuple`
  ADD PRIMARY KEY (`queryResultTupleId`);

--
-- Indizes für die Tabelle `ResultTuple`
--
ALTER TABLE `ResultTuple`
  ADD PRIMARY KEY (`resultTupleId`);

--
-- Indizes für die Tabelle `Session`
--
ALTER TABLE `Session`
  ADD PRIMARY KEY (`sessionId`);

--
-- Indizes für die Tabelle `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `MediaObject`
--
ALTER TABLE `MediaObject`
  MODIFY `mediaObjectId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `MediaType`
--
ALTER TABLE `MediaType`
  MODIFY `mediaTypeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `Query`
--
ALTER TABLE `Query`
  MODIFY `queryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `QueryResultTuple`
--
ALTER TABLE `QueryResultTuple`
  MODIFY `queryResultTupleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT für Tabelle `ResultTuple`
--
ALTER TABLE `ResultTuple`
  MODIFY `resultTupleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `Session`
--
ALTER TABLE `Session`
  MODIFY `sessionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT für Tabelle `User`
--
ALTER TABLE `User`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
