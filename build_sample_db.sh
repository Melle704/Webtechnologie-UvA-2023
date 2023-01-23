#!/bin/bash

set -m
/run.sh &

echo "=> Waiting for MySQL ..."
until mysql -uroot &> /dev/null; do
  sleep 1
done

passwd='$2y$10$zlU3A1foU9tBmOIL9K7fJ.OJwo7unIBJve/KI2WkrpRedE3ote15K'

cat <<EOF | mysql -uroot
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE test;
USE test;

CREATE TABLE users (
  id INT NOT NULL,
  uname VARCHAR(25) NOT NULL,
  email VARCHAR(30) NOT NULL,
  dob DATE NOT NULL,
  passwd CHAR(60) NOT NULL,
  profile_pic MEDIUMBLOB,
  last_activity TIMESTAMP NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO users (id, uname, email, dob, passwd, last_activity) VALUES
(1, "admin", "email@address.com", "1996-08-01", "$passwd", now());

ALTER TABLE users ADD PRIMARY KEY (id);
ALTER TABLE users MODIFY id int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

CREATE TABLE products (
  id INT NOT NULL,
  card_id INT NOT NULL,
  name VARCHAR(30) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  amount INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE products ADD PRIMARY KEY (id);

CREATE TABLE purchases (
  id INT NOT NULL,
  uid INT NOT NULL,
  name VARCHAR(80) NOT NULL,
  address VARCHAR(80) NOT NULL,
  postcode CHAR(7) NOT NULL,
  city VARCHAR(30) NOT NULL,
  price DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE purchases ADD PRIMARY KEY (id);
ALTER TABLE purchases MODIFY id int NOT NULL AUTO_INCREMENT;
COMMIT;
EOF

# Generate many test products
str="USE test;"
str+="INSERT INTO products (id, card_id, name, price, amount) VALUES "
for i in {1..199}; do
    str+="($i, $i, \"Test product $i\", 2.56, 64), "
done
i=200
str+="($i, $i, \"Test product $i\", 2.56, 64); "
echo $str | mysql -uroot

echo "=> Database generated!"
fg
