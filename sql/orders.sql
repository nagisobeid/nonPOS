CREATE TABLE orders(
	id_orders INT AUTO_INCREMENT,
	date_order DATE,
	start_time TIME,
	end_time TIME,
	FOREIGN KEY (first_name) REFERENCES users(first_name),
	FOREIGN KEY (name) REFERENCES employees(name)
    );