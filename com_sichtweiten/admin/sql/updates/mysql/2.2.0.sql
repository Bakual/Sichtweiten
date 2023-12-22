UPDATE `#__content_types`
SET `content_history_options` = '{
     "formFile":"administrator\/components\/com_sichtweiten\/models\/forms\/divesite.xml",
     "hideFields":["asset_id","checked_out","checked_out_time","version","lft","rgt","level","path"],
     "ignoreChanges":["checked_out", "checked_out_time", "path"],
     "convertToInt":[],
     "displayLookup":[
            {"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},
            {"sourceColumn":"gewaesser_id","targetTable":"#__sicht_gewaesser","targetColumn":"id","displayColumn":"name"},
            {"sourceColumn":"ort_id","targetTable":"#__sicht_ort","targetColumn":"id","displayColumn":"name"}
    ]
}'
WHERE `type_alias` = 'com_sichtweiten.divesite';
