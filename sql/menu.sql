CREATE TABLE IF NOT EXISTS menus(
	menuID integer NOT NULL AUTO_INCREMENT,
	mName varchar(25) NOT NULL,
	bID integer NOT NULL,
	mDescrip varchar(125),
	PRIMARY KEY(menuID),
	FOREIGN KEY (bID) REFERENCES owners(bID)
);