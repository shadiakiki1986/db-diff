# ffa-database-diff
Workflow to save database data to git repository and display differences in email/ui

## Architecture
Similar to [ffa-zkteco-mfbf](https://github.com/shadiakiki1986/ffa-zkteco-mfbf/)

## Testing `exporter`
1. Set up a mysql database to test against locally:

`docker-compose -f docker-compose.yml -f docker-compose.dev.yml up db`

Test with `mysql --host 127.0.0.1 --user user --password` and run `use mf; select * from t1`

2. Run the `git-rest-api` server locally: `docker-compose up git`
3. Set up dev env using option 1 or 2 below
4. Install dependencies: `composer install`
4. Run tests in `exporter`: `composer test`

### Option 1: Dev env with vagrant
```bash
cd exporter
vagrant up
VAGRANT> composer test
```

### Option 2: Manually
Install `odbc` drivers like [docker-php7...](https://github.com/shadiakiki1986/docker-php7-apache-odbc-and-other/blob/master/Dockerfile):
`[sudo] apt-get unixodbc unixodbc-dev tdsodbc php7.0-odbc`

Test installation with : `php -i|grep odbc`

Install mysql-odbc drivers
1. [ubuntu 13.04](http://askubuntu.com/a/258295/543234): `apt-get install libmyodbc`
2. [ubuntu 16.04](http://askubuntu.com/a/822399/543234):
```bash
wget https://cdn.mysql.com//Downloads/Connector-ODBC/5.3/mysql-connector-odbc-5.3.7-linux-ubuntu16.04-x86-64bit.tar.gz
tar -xzf mysql-connector-odbc-5.3.7-linux-ubuntu16.04-x86-64bit.tar.gz
cd mysql.../lib
cp * /usr/lib/x86_64.../odbc/
```

Set up odbc configuration
```bash
cp etc/odbc.dev.ini /etc/
cp etc/odbcinst.dev.ini /etc/
```

Test with
```bash
isql MarketflowAcc user password
```
