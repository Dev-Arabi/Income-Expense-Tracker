CREATE DATABASE finance_tracker;
USE finance_tracker;

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    type ENUM('income', 'expense') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    time TIME,
    comment VARCHAR(10) NULL  -- Add the comment column here
);



CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);







#### Take Backup Database from ubuntu server ####

1.Setup a Cron Job

2.Open the terminal and type:

crontab -e

3.Add the following line to run the script every 24 hours (at midnight):

0 0 * * * /usr/bin/php /var/www6/html/php/backup.php

Replace /path/to/backup.php with the actual location of your script.


########################################################