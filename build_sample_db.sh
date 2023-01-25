#!/bin/bash

err() {
  echo "=> Database failed to generate."
  exit 1
}

set -m
/run.sh &

echo "=> Waiting for MySQL ..."
until mysql -uroot &> /dev/null; do
  sleep 1
done

passwd='$2y$10$zlU3A1foU9tBmOIL9K7fJ.OJwo7unIBJve/KI2WkrpRedE3ote15K'

cat <<EOF | mysql -uroot || err
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE test;
USE test;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  uname VARCHAR(25) NOT NULL,
  email VARCHAR(30) NOT NULL,
  dob DATE NOT NULL,
  passwd VARCHAR(500) NOT NULL,
  profile_desc VARCHAR(300),
  role VARCHAR(30),
  last_activity TIMESTAMP NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci AUTO_INCREMENT = 1;

INSERT INTO users (uname, email, dob, passwd, role, last_activity) VALUES
("admin", "email@address.com", "1996-08-01", "$passwd", "admin", now());

CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  uid INT NOT NULL,
  text TINYTEXT NOT NULL,
  date TIMESTAMP NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT = 1;

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  card_id INT NOT NULL,
  name VARCHAR(30) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  amount INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT = 1;

CREATE TABLE purchases (
  id INT AUTO_INCREMENT PRIMARY KEY,
  uid INT NOT NULL,
  name VARCHAR(80) NOT NULL,
  address VARCHAR(80) NOT NULL,
  postcode CHAR(7) NOT NULL,
  city VARCHAR(30) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  time DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
echo $str | mysql -uroot || err

echo "=> Test database generated!"

# Generate card database.
# First generate query and store in `sql_query` otherwise mysql times out.
sql_query=$(/mnt/build_cards_db.py || err)
echo $sql_query | mysql -uroot || err

echo "=> Cards database generated!"

fg
