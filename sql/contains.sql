CREATE TABLE IF NOT EXISTS contains(
	oID integer NOT NULL AUTO_INCREMENT,
	itemID integer NOT NULL,
	quantity integer NOT NULL,
	price float NOT NULL,
	PRIMARY KEY (oID, itemID),
	FOREIGN KEY (oID) REFERENCES orders(oID), 
	FOREIGN KEY (itemID) REFERENCES items(itemID)
);