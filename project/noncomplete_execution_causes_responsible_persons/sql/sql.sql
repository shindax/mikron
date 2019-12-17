# Сбор информации по id ресурса
SELECT 
          precedents.id,
          precedents.zadan_id,
          precedents.cause,

          zakdet.NAME AS dse_name,
          zakdet.OBOZ AS dse_draw,
          zakdet.ID AS dse_id,

          zadan.NORM AS norm_plan,
          zadan.NORM_FACT AS norm_fact,

          zadan.id_zak,
          zadan.SMEN AS shift,
          CONCAT(RIGHT( zadan.DATE, 2), '.', SUBSTRING( zadan.DATE, -4, 2), '.', LEFT( zadan.DATE, 4)) AS zadan_date,
          zadan.DATE AS bin_date,

          CONCAT(zak_type.description, ' ', zak.NAME) AS zak_name,
          zak.DSE_NAME AS zak_dse_name,

          oper.NAME AS operation,
          park.NAME AS unit_name,
          park.MARK AS unit_type,
          resurs1.NAME  AS res_name,
          users.FIO  AS shutter_name,
          causes.description AS cause

          FROM `noncomplete_execution_precedents` AS precedents
          INNER JOIN okb_db_zadan AS zadan ON zadan.ID = precedents.zadan_id
          LEFT JOIN okb_db_zak AS zak ON zak.ID = zadan.id_zak
          LEFT JOIN okb_db_zak_type AS zak_type ON zak_type.id = zak.TID
          LEFT JOIN okb_db_operitems AS operitems ON operitems.id = zadan.id_operitems

          LEFT JOIN okb_db_oper AS oper ON oper.id = operitems.ID_oper
          LEFT JOIN okb_db_park AS park ON park.id = operitems.ID_park

          LEFT JOIN okb_db_resurs AS resurs1 ON resurs1.id = zadan.ID_resurs
          LEFT JOIN okb_users AS users ON users.id = precedents.shutter_user_id

          LEFT JOIN noncomplete_execution_causes AS causes ON causes.id = precedents.cause
          LEFT JOIN okb_db_zakdet AS zakdet ON zakdet.id = zadan.ID_zakdet

          WHERE 
          cause IN 
          (
            SELECT nec.id
            FROM noncomplete_execution_causes nec
            LEFT JOIN okb_db_resurs res ON JSON_CONTAINS(nec.responsible_res_id, CAST( res.id AS JSON ), '$')
            WHERE res.ID = $res_id
          )
          ORDER BY zadan.DATE, zadan.SMEN


 # Запрос на получение общего количества переносов
 SELECT prec.cause, COUNT( prec.id ) AS count, causes.description AS description
 FROM `noncomplete_execution_precedents` AS prec
 LEFT JOIN `noncomplete_execution_causes` AS causes ON causes.id = prec.cause
 WHERE 1
 GROUP BY prec.cause

 # Запрос на получение количества переносов по id ресурса
 SELECT prec.cause, COUNT( prec.id ) AS count, causes.description AS description
 FROM `noncomplete_execution_precedents` AS prec
 LEFT JOIN `noncomplete_execution_causes` AS causes ON causes.id = prec.cause
 WHERE cause IN 
          (
            SELECT nec.id
            FROM noncomplete_execution_causes nec
            LEFT JOIN okb_db_resurs res ON JSON_CONTAINS(nec.responsible_res_id, CAST( res.id AS JSON ), '$')
            WHERE res.ID = 13
          )
 GROUP BY prec.cause