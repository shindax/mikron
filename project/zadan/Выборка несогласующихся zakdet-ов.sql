SET @list = '56194 583050';
SELECT *
FROM okb_db_zakdet 
WHERE 
ID IN 
( SUBSTRING_INDEX( @list, ' ', 1), SUBSTRING_INDEX( @list, ' ', -1) )
OR 
ID IN 
( SELECT PID FROM okb_db_zakdet WHERE ID IN ( SUBSTRING_INDEX( @list, ' ', 1), SUBSTRING_INDEX( @list, ' ', -1) ) )