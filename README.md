# db-diff
CLI to diff database tables

Does it in 3 steps

1. Set up PHP-ODBC connections and store your database connection in `/etc/odbc.ini`
2. export table from database via ODBC connection to git via `git-rest-api` server
3. export table again after edits
4. get `git diff` results

Running periodic exports gives more points in time to `diff` against

## Usage

Synopsis
```bash
./bin/pdo-git export [--init] <DSN> <DB>..<TABLE>
./bin/pdo-git post-commit --format [html,console,json] -- <DSN> <DB>..<TABLE>
```

Set env var for `git-rest-api` server

```bash
export DBDIFF_GRAPI_HOST=http://localhost:8082
```

Against mysql odbc entry
```bash
./bin/pdo-git export MarketflowAcc mf.t1
./bin/pdo-git post-commit MarketflowAcc mf.t1
```

Against SQL server odbc entry
```bash
./bin/pdo-git export MarketflowAcc Marketflow..t1
./bin/pdo-git post-commit MarketflowAcc Marketflow..t1
```

Reset:
```bash
./bin/pdo-git admin git:deleteAll
```
## Testing
1. Set up a mysql database to test against locally:

```bash
docker run \
  -e MYSQL_RANDOM_ROOT_PASSWORD=yes \
  -e MYSQL_DATABASE=mf \
  -e MYSQL_USER=user \
  -e MYSQL_PASSWORD=password \
  -v $PWD/tests/initdb.d:/docker-entrypoint-initdb.d \
  -p 3306:3306 \
  mysql:8
```

Test with

```bash
mysql --host 127.0.0.1 --user user --password
> use mf;
> select * from t1
```

2. Run the `git-rest-api` server locally:

```bash
docker-compose up git
```
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
