FROM percona/percona-xtradb-cluster:8.0
#FROM percona/percona-xtradb-cluster
#FROM percona/percona-server:8.0

USER root
#
#RUN touch /var/log/mysql/mysql.err; \
#    chmod 640 /var/log/mysql/mysql.err; \
#    touch /var/log/mysql/error.log; \
#    chmod 640 /var/log/mysql/error.log; \
#    touch /var/log/mysql/mysql-slow.log; \
#    chmod 640 /var/log/mysql/mysql-slow.log; \
#    chown -R mysql:mysql /var/log/mysql

# xtrabackup - перкона переехала на центос 7
#RUN apt-get update && apt-get install -y wget lsb-release
#RUN wget https://repo.percona.com/apt/percona-release_0.1-4.$(lsb_release -sc)_all.deb \
#    && dpkg -i percona-release_0.1-4.$(lsb_release -sc)_all.deb \
#    && apt-get update \
#    && apt-get install -y percona-xtrabackup-24 pv

# PMM Client
#RUN yum install -y https://www.percona.com/downloads/pmm/1.17.2/binary/redhat/7/x86_64/pmm-client-1.17.2-1.el7.x86_64.rpm
#RUN yum install -y initscripts

WORKDIR '/opt/project/sqldump'

USER mysql

#CMD '--user=mysql'