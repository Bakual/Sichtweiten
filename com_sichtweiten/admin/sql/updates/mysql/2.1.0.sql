ALTER TABLE `#__sicht_tauchplatz` ADD COLUMN `version` INT UNSIGNED DEFAULT '1' NOT NULL;
INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`, `content_history_options`)
VALUES
    ('Sichtweiten Dive Site',
     'com_sichtweiten.divesite',
     '{"special":{"dbtable":"#__sicht_tauchplatz","key":"id","type":"Tauchplatz","prefix":"SichtweitenTable","config":"array()"}}',
     '',
     '{"common":{"core_content_item_id":"id","core_title":"name","core_state":"active","core_body":"bemerkungen"},"special":{}}',
     'SichtweitenHelperRoute::getLocationRoute',
     '{"formFile":"administrator\/components\/com_sichtweiten\/models\/forms\/divesite.xml",
     "hideFields":["asset_id","checked_out","checked_out_time","version","lft","rgt","level","path"],
     "ignoreChanges":["checked_out", "checked_out_time", "path"],
     "convertToInt":[],
     "displayLookup":[{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}');