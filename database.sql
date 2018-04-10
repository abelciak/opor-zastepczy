SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


--
-- Struktura tabeli dla tabeli `galezie`
--

CREATE TABLE IF NOT EXISTS `galezie` (
  `idGalaz` int(5) NOT NULL AUTO_INCREMENT,
  `projektGalaz` int(5) DEFAULT NULL,
  `glownaGalaz` int(5) DEFAULT '0',
  `pozycjaGalaz` varchar(5) NOT NULL,
  `polaczenieGalaz` varchar(10) NOT NULL,
  `dataGalaz` datetime DEFAULT NULL,
  PRIMARY KEY (`idGalaz`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=63 ;

--
-- Zrzut danych tabeli `galezie`
--

INSERT INTO `galezie` (`idGalaz`, `projektGalaz`, `glownaGalaz`, `pozycjaGalaz`, `polaczenieGalaz`, `dataGalaz`) VALUES
(1, 1, 0, '', 'szereg', '2016-02-17 11:14:27'),
(2, 1, 1, '', 'szereg', '2016-02-17 11:14:55'),
(3, 1, 0, '', 'szereg', '2016-02-17 11:15:06'),
(4, 1, 3, '', 'rownoleg', '2016-02-17 11:15:31'),
(5, 1, 0, 'gora', 'rownoleg', '2016-02-17 11:15:56'),
(6, 1, 0, 'dol', 'rownoleg', '2016-02-17 11:15:56'),
(7, 1, 5, '', 'szereg', '2016-02-17 11:16:11'),
(8, 1, 6, '', 'szereg', '2016-02-17 11:16:42'),
(9, 1, 0, 'gora', 'rownoleg', '2016-02-17 11:17:12'),
(10, 1, 0, 'dol', 'rownoleg', '2016-02-17 11:17:12'),
(11, 1, 9, '', 'szereg', '2016-02-17 11:17:26'),
(12, 1, 10, '', 'rownoleg', '2016-02-17 11:17:45'),
(13, 1, 0, 'gora', 'rownoleg', '2016-02-17 11:17:57'),
(14, 1, 0, 'dol', 'rownoleg', '2016-02-17 11:17:57'),
(15, 1, 13, '', 'rownoleg', '2016-02-17 11:18:12'),
(16, 1, 14, '', 'rownoleg', '2016-02-17 11:18:22'),
(17, 1, 0, 'gora', 'rownoleg', '2016-02-17 11:18:34'),
(18, 1, 0, 'dol', 'rownoleg', '2016-02-17 11:18:34'),
(19, 1, 17, '', 'rownoleg', '2016-02-17 11:18:47'),
(20, 1, 18, '', 'szereg', '2016-02-17 11:18:55');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `oporniki`
--

CREATE TABLE IF NOT EXISTS `oporniki` (
  `idOpornik` int(11) NOT NULL AUTO_INCREMENT,
  `galazOpornik` int(10) DEFAULT NULL,
  `opornikA` int(3) DEFAULT NULL,
  `opornikB` int(3) DEFAULT NULL,
  `polozenie` varchar(10) DEFAULT '',
  PRIMARY KEY (`idOpornik`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=114 ;

--
-- Zrzut danych tabeli `oporniki`
--

INSERT INTO `oporniki` (`idOpornik`, `galazOpornik`, `opornikA`, `opornikB`, `polozenie`) VALUES
(1, 1, 2, 50, ''),
(2, 1, 3, 0, ''),
(3, 2, 8, 0, ''),
(4, 2, 7, 90, ''),
(5, 3, 3, 0, ''),
(6, 3, 8, 0, ''),
(7, 4, 2, 40, 'gora'),
(8, 4, 3, 50, 'gora'),
(9, 4, 7, 0, 'dol'),
(10, 5, 8, 0, 'gora'),
(11, 6, 9, 0, 'dol'),
(12, 7, 7, 0, ''),
(13, 7, 6, 0, ''),
(14, 8, 2, 0, ''),
(15, 8, 3, 0, ''),
(35, 10, 2, 0, 'dol'),
(34, 9, 6, 0, 'gora'),
(18, 11, 8, 0, ''),
(19, 11, 2, 0, ''),
(20, 12, 3, 0, 'gora'),
(21, 12, 4, 0, 'gora'),
(22, 12, 5, 0, 'dol'),
(23, 13, 2, 0, 'gora'),
(24, 14, 3, 0, 'dol'),
(25, 15, 7, 0, 'gora'),
(26, 15, 5, 0, 'dol'),
(27, 16, 2, 0, 'gora'),
(28, 16, 3, 0, 'dol'),
(29, 17, 5, 0, 'gora'),
(30, 18, 3, 0, 'dol'),
(31, 19, 2, 0, 'gora'),
(32, 19, 4, 0, 'dol'),
(33, 20, 4, 0, ''),
(89, 45, 5, 0, 'dol'),
(110, 59, 53, 0, ''),
(88, 45, 64, 0, 'gora'),
(104, 54, 75, 0, ''),
(58, 29, 64, 0, ''),
(100, 51, 64, 0, 'gora'),
(101, 51, 2, 0, 'dol'),
(113, 62, 53, 0, '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `projekty`
--

CREATE TABLE IF NOT EXISTS `projekty` (
  `idProjekt` int(5) NOT NULL AUTO_INCREMENT,
  `dataProjekt` datetime DEFAULT NULL,
  PRIMARY KEY (`idProjekt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Zrzut danych tabeli `projekty`
--

INSERT INTO `projekty` (`idProjekt`, `dataProjekt`) VALUES
(1, '1970-01-01 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
