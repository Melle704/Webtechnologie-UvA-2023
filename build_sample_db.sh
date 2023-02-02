#!/bin/bash

err() {
  echo "=> Database failed to generate."
  exit 1
}

echo -e "<Directory /var/www/>" >> /etc/apache2/apache2.conf
echo -e "\tOptions Indexes FollowSymLinks" >> /etc/apache2/apache2.conf
echo -e "\tAllowOverride All" >> /etc/apache2/apache2.conf
echo -e "\tRequire all granted" >> /etc/apache2/apache2.conf
echo -e "</Directory>" >> /etc/apache2/apache2.conf

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
    last_activity TIMESTAMP NOT NULL,
    email_verified BIT NOT NULL DEFAULT 0,
    verification_code CHAR(22) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci AUTO_INCREMENT = 1;

INSERT INTO users (uname, email, dob, passwd, role, last_activity, email_verified, verification_code) VALUES
("admin", "email@address.com", "1996-08-01", "$passwd", "admin", now(), 1, LEFT(MD5(RAND()), 22));

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uid INT NOT NULL,
    text TINYTEXT NOT NULL,
    date TIMESTAMP NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT = 1;

CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uid INT NOT NULL,
    mollie_id VARCHAR(32) NOT NULL,
    status ENUM("open", "canceled", "expired", "failed", "paid") NOT NULL,
    name VARCHAR(80) NOT NULL,
    address VARCHAR(80) NOT NULL,
    postcode CHAR(7) NOT NULL,
    city VARCHAR(30) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    time DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT = 1;

CREATE TABLE forum_threads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date TIMESTAMP NOT NULL DEFAULT now(),
    comments INT NOT NULL DEFAULT 0,
    title TINYTEXT NOT NULL,
    thread_content TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT = 1;

INSERT INTO forum_threads (user_id, comments, title, thread_content) VALUES (1, 2, "test thread 1", "test content 1");
INSERT INTO forum_threads (user_id, comments, title, thread_content) VALUES (1, 1, "test thread 2", "test content 2");
INSERT INTO forum_threads (user_id, comments, title, thread_content) VALUES (1, 1, "test thread 3", "test content 3");
INSERT INTO forum_threads (user_id, comments, title, thread_content) VALUES (1, 1, "test thread 4", "test content 4");

CREATE TABLE forum_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    thread_id INT NOT NULL,
    user_id INT NOT NULL,
    date TIMESTAMP NOT NULL DEFAULT now(),
    text TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT = 1;

INSERT INTO forum_posts (thread_id, user_id, text) VALUES (1, 1, "test post 1");
INSERT INTO forum_posts (thread_id, user_id, text) VALUES (1, 1, "test post 2");
INSERT INTO forum_posts (thread_id, user_id, text) VALUES (2, 1, "test post 3");
INSERT INTO forum_posts (thread_id, user_id, text) VALUES (3, 1, "test post 4");
INSERT INTO forum_posts (thread_id, user_id, text) VALUES (4, 1, "test post 5");

COMMIT;
EOF

echo "=> Test database generated!"

# Generate card database.
# First generate query and store in `sql_query` otherwise mysql times out.
sql_query=$(/mnt/build_cards_db.py || err)
echo $sql_query | mysql -uroot || err

echo "=> Cards database generated!"

fg
