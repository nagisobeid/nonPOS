CREATE TABLE IF NOT EXISTS owners(
    bID integer NOT NULL AUTO_INCREMENT,
    fName varchar(50),
    lName varchar(50),
    email varchar(75) NOT NULL,
    username varchar(25) NOT NULL,
    password varchar(75) NOT NULL,
    bName varchar(25) NOT NULL,
    tax decimal(5,2) DEFAULT 0.00,
    PRIMARY KEY (bID)
);
