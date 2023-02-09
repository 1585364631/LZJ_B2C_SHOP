FROM centos:centos7
MAINTAINER LIZEJIA
RUN cd /etc/yum.repos.d/ && rm -rf /etc/yum.repos.d/* 
COPY Centos-7.repo /etc/yum.repos.d
RUN yum clean all && yum repolist
RUN yum install -y wget
RUN yum -y install make zlib zlib-devel gcc-c++ libtool openssl openssl-devel pcre pcre-devel
COPY nginx-1.22.0 /nginx-1.22.0
COPY nginx-upsync-module-master /nginx-upsync-module-master
RUN cd /nginx-1.22.0  && ./configure \
 --prefix=/usr/local/nginx \
 --with-pcre --add-module=../nginx-upsync-module-master && make && make install
COPY nginx.conf /usr/local/nginx/conf/nginx.conf
COPY start.sh /start.sh
RUN touch /usr/local/nginx/conf/servers_upsync.conf && chmod 777 /usr/local/nginx/conf/servers_upsync.conf && chmod +x /start.sh
CMD ["sh","-c","/start.sh"]
