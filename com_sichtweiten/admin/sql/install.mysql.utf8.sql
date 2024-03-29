DROP TABLE IF EXISTS `#__sicht_adresse`;
DROP TABLE IF EXISTS `#__sicht_bezeichnung`;
DROP TABLE IF EXISTS `#__sicht_gewaesser`;
DROP TABLE IF EXISTS `#__sicht_land`;
DROP TABLE IF EXISTS `#__sicht_ort`;
DROP TABLE IF EXISTS `#__sicht_sichtweite`;
DROP TABLE IF EXISTS `#__sicht_sichtweiteneintrag`;
DROP TABLE IF EXISTS `#__sicht_sichtweitenmeldung`;
DROP TABLE IF EXISTS `#__sicht_tauchpartner`;
DROP TABLE IF EXISTS `#__sicht_tauchplatz`;
DROP TABLE IF EXISTS `#__sicht_tiefenbereich`;
DROP TABLE IF EXISTS `#__sicht_user`;

CREATE TABLE `#__sicht_adresse` (
  `id`           INT(11) NOT NULL AUTO_INCREMENT,
  `land_id`      INT(11)          DEFAULT NULL,
  `strasse`      VARCHAR(255)     DEFAULT NULL,
  `hausnummer`   VARCHAR(255)     DEFAULT NULL,
  `plz`          VARCHAR(255)     DEFAULT NULL,
  `ort`          VARCHAR(255)     DEFAULT NULL,
  `tel_festnetz` VARCHAR(255)     DEFAULT NULL,
  `tel_mobile`   VARCHAR(255)     DEFAULT NULL,
    PRIMARY KEY (`id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8;

CREATE TABLE `#__sicht_bezeichnung` (
  `id`            INT(11) NOT NULL AUTO_INCREMENT,
  `name`          VARCHAR(255)     DEFAULT NULL,
  `tauchplatz_id` INT(11)          DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tauchplatz_id` (`tauchplatz_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8;

CREATE TABLE `#__sicht_gewaesser` (
  `id`             INT(11) NOT NULL AUTO_INCREMENT,
  `land_id`        INT(11)      DEFAULT NULL,
  `name`           VARCHAR(255) DEFAULT NULL,
  `displayName`    VARCHAR(255) DEFAULT NULL,
  `meterUeberMeer` INT(11)      DEFAULT NULL,
  `maxTiefe`       INT(11)      DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_sicht_gewaesser_name` (`name`),
  KEY `land_id` (`land_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8;

CREATE TABLE `#__sicht_land` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `bezeichnung` VARCHAR(255)     DEFAULT NULL,
  `kurzzeichen` VARCHAR(255)     DEFAULT NULL,
  `flag`        VARCHAR(255)     DEFAULT NULL,
  `displaynr`   INT(11)          DEFAULT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8;

CREATE TABLE `#__sicht_ort` (
  `id`      INT(11) NOT NULL AUTO_INCREMENT,
  `land_id` INT(11)          DEFAULT NULL,
  `name`    VARCHAR(255)     DEFAULT NULL,
  `plz`     INT(11)          DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_sicht_ort` (`name`),
  KEY `land_id` (`land_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8;

CREATE TABLE `#__sicht_sichtweite`
(
    `id`             INT(11)                 NOT NULL AUTO_INCREMENT,
    `title`          VARCHAR(255) DEFAULT '' NOT NULL,
    `value`          TINYINT      DEFAULT 0  NOT NULL,
    `ordering`       TINYINT      DEFAULT 0  NOT NULL,
    `languagestring` TINYTEXT                NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = MyISAM
    DEFAULT CHARSET = utf8;

CREATE TABLE `#__sicht_sichtweiteneintrag` (
  `id`                    INT(11) NOT NULL AUTO_INCREMENT,
  `sichtweite_id`         INT(11)          DEFAULT NULL,
  `sichtweitenmeldung_id` INT(11)          DEFAULT NULL,
  `tiefenbereich_id`      INT(11)          DEFAULT NULL,
  `temperatur`            INT(11)          DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sichtweite_id` (`sichtweite_id`),
  KEY `tiefenbereich_id` (`tiefenbereich_id`),
  KEY `sichtweitenmeldung_id` (`sichtweitenmeldung_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8;

CREATE TABLE `#__sicht_sichtweitenmeldung` (
  `id`            INT(11) NOT NULL AUTO_INCREMENT,
  `tauchplatz_id` INT(11)          DEFAULT NULL,
  `user_id`       INT(11)          DEFAULT NULL,
  `datum`         DATETIME         DEFAULT NULL,
  `meldedatum`    DATETIME         DEFAULT NULL,
  `kommentar`     VARCHAR(510)     DEFAULT NULL,
  `kommentar_old` VARCHAR(510)     DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tauchplatz_id` (`tauchplatz_id`),
  KEY `user_id` (`user_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8;

CREATE TABLE `#__sicht_tauchpartner` (
  `id`                    INT(11) NOT NULL AUTO_INCREMENT,
  `sichtweitenmeldung_id` INT(11)          DEFAULT NULL,
  `name`                  VARCHAR(255)     DEFAULT NULL,
  `email`                 VARCHAR(255)     DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sichtweitenmeldung_id` (`sichtweitenmeldung_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8;

CREATE TABLE `#__sicht_tauchplatz`
(
    `id`               INT(11)                 NOT NULL AUTO_INCREMENT,
    `title`            VARCHAR(255) DEFAULT '' NOT NULL,
    `alias`            VARCHAR(400) DEFAULT '' NOT NULL,
    `alt_names`        TEXT                    NULL,
    `gewaesser_id`     INT(11)      DEFAULT NULL,
    `ort_id`           INT(11)      DEFAULT NULL,
    `modified_by`      INT UNSIGNED            NULL,
    `created_by`       INT UNSIGNED            NULL,
    `longitude`        DOUBLE       DEFAULT NULL,
    `latitude`         DOUBLE       DEFAULT NULL,
    `einschraenkungen` TEXT,
    `spezielles`       VARCHAR(255) DEFAULT NULL,
    `bemerkungen`      TEXT,
    `state`            TINYINT      DEFAULT 0  NOT NULL,
    PRIMARY KEY (`id`),
    KEY `gewaesser_id` (`gewaesser_id`),
    KEY `ort_id` (`ort_id`)
)
    ENGINE = MyISAM
    DEFAULT CHARSET = utf8;

CREATE TABLE `#__sicht_tiefenbereich`
(
    `id`             INT(11)                 NOT NULL AUTO_INCREMENT,
    `title`          VARCHAR(255) DEFAULT '' NOT NULL,
    `ordering`       TINYINT      DEFAULT 0  NOT NULL,
    `languagestring` TINYTEXT                NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = MyISAM
    DEFAULT CHARSET = utf8;

CREATE TABLE `#__sicht_user` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `adresse_id` INT(11)          DEFAULT NULL,
  `name`       VARCHAR(255)     DEFAULT NULL,
  `email`      VARCHAR(255)     DEFAULT NULL,
  `passwort`   VARCHAR(255)     DEFAULT NULL,
  `nickname`   VARCHAR(255)     DEFAULT NULL,
  `joomla_id`  INT(11)          DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `adresse_id` (`adresse_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8;
