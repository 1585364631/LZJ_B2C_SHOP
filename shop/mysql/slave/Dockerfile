FROM centos:centos7
COPY config /config
RUN rm -rf /etc/yum.repos.d/* && cp -r /config/Centos-7.repo /etc/yum.repos.d/Centos-7.repo && yum clean all && yum repolist && yum install -y mariadb mariadb-server
RUN sed -i 's/\[client-server\]/\[client-server\]\n\[mysqld\]\nlog-bin=mysql-bin\nserver-id=6/g' /etc/my.cnf && mysqld_safe & sleep 5 && \
mysqladmin -u root password "000000" && \
mysql -uroot -p000000 -e "grant all on *.* to 'root'@'%' identified by '000000';create database shop;"
RUN chmod 777 /config/start.sh
CMD ["sh","-c","/config/start.sh"]
