CREATE TABLE IF NOT EXISTS owners(
    bID integer NOT NULL AUTO_INCREMENT,
    fName varchar(50),
    lName varchar(50),
    email varchar(75) NOT NULL,
	username varchar(75) NOT NULL,
    password varchar(25) NOT NULL,
    bName varchar(25) NOT NULL,
    PRIMARY KEY (bID)
);
