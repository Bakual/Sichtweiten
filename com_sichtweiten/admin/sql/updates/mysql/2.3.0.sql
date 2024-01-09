UPDATE `#__sicht_sichtweiteneintrag` AS swe
    JOIN `#__sicht_sichtweite` AS s ON swe.sichtweite_id = s.id
    SET `sichtweite_id` = '0'
    WHERE s.id = '112' and (s.title = 'keine Angaben' OR s.title = '-');

DELETE FROM `#__sicht_sichtweite`
WHERE `id` = '112' and (`title` = 'keine Angaben' OR `title` = '-');

UPDATE `#__sicht_sichtweite`
SET `value` = `id`, `ordering` = `id`;
