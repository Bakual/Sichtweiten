ALTER TABLE `#__sicht_tauchplatz`
    ADD COLUMN `alias` VARCHAR(400) DEFAULT '' NOT NULL,
    ADD COLUMN `alt_names` TEXT NULL,
    CHANGE COLUMN `name` `title` VARCHAR(255) DEFAULT '' NOT NULL,
    CHANGE COLUMN `active` `state` TINYINT DEFAULT 0 NOT NULL,
    CHANGE COLUMN `ersetztdurch_id` `modified_by` INT UNSIGNED NULL,
    CHANGE COLUMN `erfasstvon_id` `created_by` INT UNSIGNED NULL,
    DROP COLUMN `oldKey`,
    DROP COLUMN `fuelllstation_id`
;
ALTER TABLE `#__sicht_sichtweite`
    ADD COLUMN `value` TINYINT DEFAULT 0 NOT NULL,
    ADD COLUMN `ordering` TINYINT DEFAULT 0 NOT NULL,
    ADD COLUMN `languagestring` TINYTEXT NULL,
    CHANGE COLUMN `bezeichnung` `title` VARCHAR(255) DEFAULT '' NOT NULL
;
ALTER TABLE `#__sicht_tiefenbereich`
    ADD COLUMN `ordering` TINYINT DEFAULT 0 NOT NULL,
    ADD COLUMN `languagestring` TINYTEXT NULL,
    CHANGE COLUMN `bezeichnung` `title` VARCHAR(255) DEFAULT '' NOT NULL
;
