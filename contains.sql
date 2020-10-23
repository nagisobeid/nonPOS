CREATE TABLE contains(
	FOREIGN KEY (id_orders) REFERENCES orders(id_orders),
	FOREIGN KEY (id_item) REFERENCES items(id_item),
    qu_order INT,
	FOREIGN KEY (price_item) REFERENCES items(price_item),
    PRIMARY KEY(id_orders)
);