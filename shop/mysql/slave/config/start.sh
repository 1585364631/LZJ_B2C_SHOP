#!bin/bash
mysqld_safe &
sleep 10
lsls=$(mysql -uroot -p000000 -e "show master status;")
mysql -uroot -p000000 -e "change master to master_host='mariadb_master',master_user='root',master_password='000000',master_log_file='$(echo ${lsls:43:17})',master_log_pos=$(echo ${lsls:60});start slave;"
tail -f /dev/null;
