
#set -x #echo on
set -eux -o pipefail

#MYSQL_USERNAME=`php src/info.php "mysql.username"`
# MYSQL_ROOT_PASSWORD=`php src/info.php "mysql.root_password"`
MYSQL_ROOT_PASSWORD="pass123"

#echo "MYSQL_ROOT_PASSWORD is ${MYSQL_ROOT_PASSWORD}"

# php bin/cli.php getLatestBackup ./var/backup.sql.gz
gunzip < ./var/backup.sql.gz | mysql -uroot -p$MYSQL_ROOT_PASSWORD -S /var/lib/mysql/mysql.sock

#php bin/cli.php upgrade

