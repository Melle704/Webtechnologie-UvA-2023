#!/bin/bash

/run.sh &

echo "=> Waiting for mysql"
until mysql -uroot &> /dev/null; do
  sleep 1
done

cat <<EOF | mysql -uroot
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `test`
--

CREATE DATABASE test;
USE test;

--
-- Table structure for table `users`
--

CREATE TABLE users (
  id int NOT NULL,
  uname varchar(25) NOT NULL,
  email varchar(30) NOT NULL,
  dob date NOT NULL,
  passwd char(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users` (user: admin, password: admin)
--

INSERT INTO users (id, uname, email, dob, passwd) VALUES
(1, "admin", "email@address.com", "1996-08-01", "$2y$10$zlU3A1foU9tBmOIL9K7fJ.OJwo7unIBJve/KI2WkrpRedE3ote15K");

--
-- Indexes for table `users`
--
ALTER TABLE users ADD PRIMARY KEY (id);

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE users MODIFY id int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;
EOF

echo "=> Database generated"
wait
