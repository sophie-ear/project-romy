-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 27 avr. 2021 à 08:13
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `romyart`
--

-- --------------------------------------------------------

--
-- Structure de la table `details`
--

DROP TABLE IF EXISTS `details`;
CREATE TABLE IF NOT EXISTS `details` (
  `DetailsID` int(11) NOT NULL AUTO_INCREMENT,
  `OrderID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `DetailsQty` int(3) NOT NULL,
  PRIMARY KEY (`DetailsID`),
  KEY `OrderID` (`OrderID`),
  KEY `ProductID` (`ProductID`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `details`
--

INSERT INTO `details` (`DetailsID`, `OrderID`, `ProductID`, `DetailsQty`) VALUES
(1, 3, 1, 1),
(2, 3, 2, 2),
(3, 4, 2, 3),
(4, 4, 4, 4),
(5, 5, 1, 3),
(6, 6, 1, 9),
(7, 6, 2, 14),
(8, 7, 1, 2),
(9, 8, 1, 5),
(10, 8, 2, 2),
(19, 12, 8, 1),
(20, 13, 8, 1),
(22, 14, 6, 20),
(23, 15, 8, 2),
(24, 15, 1, 1),
(25, 16, 2, 1),
(26, 16, 11, 1),
(27, 16, 6, 2);

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `OrderID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `OrderDate` date NOT NULL,
  `OrderHT` float NOT NULL,
  `OrderTTC` float NOT NULL,
  `OrderStatus` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`OrderID`),
  KEY `UserID` (`UserID`),
  KEY `OrderStatus` (`OrderStatus`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`OrderID`, `UserID`, `OrderDate`, `OrderHT`, `OrderTTC`, `OrderStatus`) VALUES
(3, 3, '2020-11-23', 48, 60, 2),
(4, 3, '2020-11-23', 240, 300, 3),
(5, 3, '2020-11-23', 48, 60, 1),
(6, 3, '2020-12-03', 368, 460, 2),
(7, 3, '2020-12-03', 32, 40, 2),
(8, 3, '2020-12-03', 112, 140, 3),
(9, 3, '2020-12-07', 800, 1000, 1),
(11, 6, '2020-12-09', 80, 100, 1),
(12, 6, '2020-12-09', 16, 20, 2),
(13, 6, '2020-12-09', 16, 20, 1),
(14, 6, '2020-12-09', 320, 400, 2),
(15, 6, '2020-12-17', 80, 100, 1),
(16, 3, '2020-12-20', 64, 80, 1);

-- --------------------------------------------------------

--
-- Structure de la table `orderstatus`
--

DROP TABLE IF EXISTS `orderstatus`;
CREATE TABLE IF NOT EXISTS `orderstatus` (
  `OrderStatus` int(1) NOT NULL DEFAULT '1',
  `StatusName` varchar(50) NOT NULL,
  PRIMARY KEY (`OrderStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `orderstatus`
--

INSERT INTO `orderstatus` (`OrderStatus`, `StatusName`) VALUES
(1, 'En cours'),
(2, 'Validée'),
(3, 'Envoyée');

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `ProductID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductName` varchar(50) NOT NULL,
  `ProductDescription` varchar(500) NOT NULL,
  `ProductImage` varchar(50) NOT NULL,
  `ProductPrice` float NOT NULL,
  `ProductQty` int(3) NOT NULL,
  PRIMARY KEY (`ProductID`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`ProductID`, `ProductName`, `ProductDescription`, `ProductImage`, `ProductPrice`, `ProductQty`) VALUES
(1, 'Tote Bag \"Amour\"', 'Tote bag en coton bio illustré et brodé main', 'amour.jpg', 20, 49),
(2, 'Tote Bag \"Snake\"', 'Tote bag en coton bio illustré et brodé main', 'snake.jpg', 20, 49),
(3, 'Tote Bag \"Blue Flowers\"', 'Tote bag en coton bio illustré et brodé main', 'blue_flowers.jpg', 20, 50),
(4, 'Tote Bag \"Clam\"', 'Tote bag en coton bio illustré et brodé main', 'clam.jpg', 20, 58),
(5, 'Tote Bag \"El Sol\"', 'Tote bag en coton bio illustré et brodé main', 'el_sol.jpg', 20, 50),
(6, 'Tote Bag \"Eyes\"', 'Tote bag en coton bio illustré et brodé main', 'eyes.jpg', 20, 27),
(7, 'Tote Bag \"Flower Crown\"', 'Tote bag en coton bio illustré et brodé main', 'flower_crown.jpg', 20, 27),
(8, 'Tote Bag \"Heart\"', 'Tote bag en coton bio illustré et brodé main', 'heart.jpg', 20, 46),
(9, 'Tote Bag \"Purple Heart\"', 'Tote bag en coton bio illustré et brodé main', 'heart2.jpg', 20, 40),
(10, 'Tote Bag \"High\"', 'Tote bag en coton bio illustré et brodé main', 'high.jpg', 20, 34),
(11, 'Tote Bag \"Kiss\"', 'Tote bag en coton bio illustré et brodé main', 'kiss.jpg', 20, 49),
(12, 'Tote Bag \"Parrot\"', 'Tote bag en coton bio illustré et brodé main', 'parrot.jpg', 20, 50),
(13, 'Tote Bag \"Poppy\"', 'Tote bag en coton bio illustré et brodé main', 'poppy.jpg', 20, 50),
(14, 'Tote Bag \"Touch\"', 'Tote bag en coton bio illustré et brodé main', 'touch.jpg', 20, 50),
(15, 'Tote Bag \"Vin\"', 'Tote bag en coton bio illustré et brodé main', 'vin.jpg', 20, 50),
(19, 'Premier sushi !!!', 'Petit maki au saumon, riz vinaigré algue et saumon frais !', 'sushi1.jpg', 6, 27),
(20, 'Deuxième sushi de la journée !', 'Tranche de poisson sur son lit de riz vinaigré, servi avec wasabi', 'sushi2.jpg', 3, 17),
(21, 'Sushi 3 sdkmjfsldkmfsdf<:', 'dfsdfpoieé``[@sq^f*ùmw', 'sushi3.jpg', 17.5, 5888),
(23, 'ghdhfghf', 'ghfghfgh', 'sushi1.jpg', 5324, 52452),
(24, 'dfsdvsd', 'vsdvsdvsd', 'sushi1.jpg', 14, 20);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `UserFirstname` varchar(50) NOT NULL,
  `UserLastname` varchar(50) NOT NULL,
  `UserBirth` date NOT NULL,
  `UserAddress` varchar(50) NOT NULL,
  `UserZipcode` varchar(10) NOT NULL,
  `UserCity` varchar(50) NOT NULL,
  `UserCountry` varchar(50) NOT NULL,
  `UserPhone` char(10) NOT NULL,
  `UserMail` varchar(50) NOT NULL,
  `UserPassword` varchar(64) NOT NULL,
  `UserStatus` int(1) NOT NULL DEFAULT '1',
  `AccountDate` date DEFAULT NULL,
  PRIMARY KEY (`UserID`),
  KEY `UserStatus` (`UserStatus`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`UserID`, `UserFirstname`, `UserLastname`, `UserBirth`, `UserAddress`, `UserZipcode`, `UserCity`, `UserCountry`, `UserPhone`, `UserMail`, `UserPassword`, `UserStatus`, `AccountDate`) VALUES
(1, 'Prenom', 'Nom', '2020-06-07', '1 rue du bac', '75005', 'paris', 'FR', '0185012345', 'test@3wa.fr', 'test', 1, '2020-12-01'),
(3, 'Romane', 'Jacquemin', '1987-01-02', '3 rue Blanche', '75000', 'Rennes', 'France', '0147852369', 'romyart@gmail.com', '$2y$11$2d50293765eee145992a9u/XAATlMWLI1ukLQeyqHRpwNOn8LET/S', 3, '2020-12-02'),
(6, 'Karine', 'T', '2005-01-01', 'aaa', 'aaa', 'aaa', 'aaa', '0147852369', 'karine@test.fr', '$2y$11$0fb12be35d0d96ae50a54O2ODw.6YNBTEOx7rN2fmeeeghNMEznvK', 1, '2020-12-03'),
(7, 'Ping', 'Pong', '1957-08-10', 'tes test', 'test', 'test', 'test', '0147852369', 'ping@test.fr', '$2y$11$6b7ec3249e5fe2aeb4e32uLXVFbnlYdmw2UqjqjVrYP0Kkf4Q1Rbi', 3, '2020-12-04'),
(12, 'zeaze', 'eaze', '2020-05-11', 'eazeaz', '75013', 'zeazeaz', 'eazea', '0147852369', 'aah@gmail.com', '$2y$11$4d7467399c0e619276181urSq9oav/z7dzZv/aCRLyG6gwbqu3qlS', 1, '2020-12-16');

-- --------------------------------------------------------

--
-- Structure de la table `userstatus`
--

DROP TABLE IF EXISTS `userstatus`;
CREATE TABLE IF NOT EXISTS `userstatus` (
  `UserStatus` int(1) NOT NULL,
  `StatusName` varchar(50) NOT NULL,
  PRIMARY KEY (`UserStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `userstatus`
--

INSERT INTO `userstatus` (`UserStatus`, `StatusName`) VALUES
(1, 'Client'),
(3, 'Admin');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `details`
--
ALTER TABLE `details`
  ADD CONSTRAINT `details_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`),
  ADD CONSTRAINT `details_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`);

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`OrderStatus`) REFERENCES `orderstatus` (`OrderStatus`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`UserStatus`) REFERENCES `userstatus` (`UserStatus`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
