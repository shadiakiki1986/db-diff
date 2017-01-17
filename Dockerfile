FROM shadiakiki1986/php7-apache-odbc-and-other
RUN  apt-get update \
  && apt-get install cron
COPY etc/cron.d/* /etc/cron.d/
COPY etc/odbc.ini /etc/
COPY etc/odbcinst.ini /etc/
ENTRYPOINT entry.sh \
        && cron -f
