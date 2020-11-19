CREATE TABLE items(
	id_item DOUBLE,
    name_item VARCHAR(100),
	desc_item VARCHAR(255),
	price_item FLOAT,
	img_item BLOB,
	option_item VARCHAR(100),
	FOREIGN KEY (id_menu) REFERENCES menu(id_menu)
);