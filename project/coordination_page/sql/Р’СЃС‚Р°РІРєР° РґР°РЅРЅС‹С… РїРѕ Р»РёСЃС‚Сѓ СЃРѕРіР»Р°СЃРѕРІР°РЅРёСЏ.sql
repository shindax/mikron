SET @num = 1 ;

INSERT INTO  `coordination_page_items` 
( id, page_id, row_id, task_id ) 

VALUES	
# Инициатор заявки
		( NULL, @num, 1, 1 ),
		( NULL, @num, 1, 2 ),
		( NULL, @num, 1, 4 ),
		( NULL, @num, 1, 5 ),
		( NULL, @num, 1, 6 ),

# Коммерческий директор
		( NULL, @num, 2, 7 ),

# Технический директор
		( NULL, @num, 3, 8 ),
		( NULL, @num, 3, 9 ),
		( NULL, @num, 3, 10 ),

# Начальник ОМТС
		( NULL, @num, 4, 3 ),
		( NULL, @num, 4, 4 ),
		( NULL, @num, 4, 5 ),
		( NULL, @num, 4, 6 ),

# Начальник ОВК
		( NULL, @num, 5, 3 ),
		( NULL, @num, 5, 4 ),
		( NULL, @num, 5, 5 ),
		( NULL, @num, 5, 6 ),

# Начальник ПДО
		( NULL, @num, 6, 11 ),
		( NULL, @num, 6, 12 ),
		( NULL, @num, 6, 13 ),
		( NULL, @num, 6, 14 ),

# Начальник производства
		( NULL, @num, 7, 7 );