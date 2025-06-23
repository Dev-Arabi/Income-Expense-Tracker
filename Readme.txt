#### Take Backup Database from ubuntu server ####

1.Setup a Cron Job

2.Open the terminal and type:

crontab -e

3.Add the following line to run the script every 24 hours (at midnight):

0 0 * * * /usr/bin/php /var/www6/html/php/backup.php

Replace /path/to/backup.php with the actual location of your script.


########################################################