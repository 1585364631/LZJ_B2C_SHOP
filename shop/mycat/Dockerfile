FROM centos:centos7
COPY config /config
RUN rm -rf /etc/yum.repos.d/* && cp -r /config/Centos-7.repo /etc/yum.repos.d/Centos-7.repo && yum clean all && yum repolist && chmod -R 777 /config && yum install -y yum install java-1.8.0-openjdk* -y
RUN echo 'export PATH=$PATH:/config/mycat/bin' >> /etc/bashrc
CMD ["sh","-c","/config/mycat/bin/mycat start && tail -f /dev/null"]
