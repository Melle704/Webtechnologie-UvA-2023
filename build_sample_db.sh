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
  last_activity TIMESTAMP NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO users (id, uname, email, dob, passwd, last_activity) VALUES
(1, "admin", "email@address.com", "1996-08-01", "$passwd", now());

ALTER TABLE users ADD PRIMARY KEY (id);
ALTER TABLE users MODIFY id int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;
EOF

echo "=> Database generated!"
fg
