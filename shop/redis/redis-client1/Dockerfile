FROM centos:centos7
MAINTAINER LIZEJIA
RUN cd /etc/yum.repos.d/ && rm -rf /etc/yum.repos.d/* 
COPY Centos-7.repo /etc/yum.repos.d
RUN yum clean all && yum repolist
RUN yum install -y epel-release && yum install -y redis
