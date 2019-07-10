SELECT zakdet1.ID, zakdet1.NAME, zak1.NAME, zakdet1.ID_zak, zakdet2.ID, zakdet2.NAME, zak2.NAME, zakdet2.ID_zak
FROM `okb_db_zakdet` AS zakdet1
LEFT JOIN `okb_db_zakdet` AS zakdet2 ON zakdet2.LID = zakdet1.ID
LEFT JOIN `okb_db_zak` AS zak1 ON zak1.ID = zakdet1.ID_zak
LEFT JOIN `okb_db_zak` AS zak2 ON zak2.ID = zakdet2.ID_zak
WHERE 
zakdet2.ID <> 0
AND
zakdet1.ID_zak <> zakdet2.ID_zak