CREATE TABLE IF NOT EXISTS items(
	itemID integer NOT NULL AUTO_INCREMENT,
	name varchar(25) NOT NULL,
	iDescrip varchar(125),
	price float NOT NULL,
	menuID integer,
	extraKey varchar(25),
	PRIMARY KEY(itemID),
	FOREIGN KEY (menuID) REFERENCES menus(menuID)
);