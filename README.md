# db-diff [![Build Status](https://travis-ci.org/shadiakiki1986/db-diff.svg?branch=master)](https://travis-ci.org/shadiakiki1986/db-diff)
Console application to version control database table contents and show their daily diff

Does it in 3 steps

1. Set up PHP-ODBC connections and store your database connection in `/etc/odbc.ini`
2. export table from database via ODBC connection to git via [git-rest-api](https://github.com/korya/node-git-rest-api/) server
3. export table again after edits
4. get `git diff` results

Running periodic exports gives more points in time to `diff` against

Similar tools (didn't try them)
* [Red gate](http://www.red-gate.com/products/sql-development/sql-source-control/): similar to this repo, can version control "static data"
* [git-sql](http://www.gitsql.net/): diff of sql data dumps
* [Nayjest/db-diff](https://github.com/Nayjest/db-diff): diff of two tables with the same structure
* [PM-Connect/db-diff](https://github.com/PM-Connect/db-diff): diff of two database structures

## Usage
(Example usage: [docker-db-diff](https://github.com/shadiakiki1986/docker-db-diff/) )

1. Requirements:
  1. [PDO-ODBC](http://php.net/manual/en/ref.pdo-odbc.php) driver and a ready connection
  2. a [node-git-rest-api](https://github.com/korya/node-git-rest-api/) server with `deep-diff-yml` configured for yml files

2. Set env var for `git-rest-api` server

```bash
export DBDIFF_GRAPI_HOST=http://localhost:8082
```

3. Synopsis

```bash
./bin/pdo-git export [--init] <DSN> <DB>..<TABLE>
./bin/pdo-git post-commit --format [html,console,json] -- <DSN> <DB>..<TABLE>
```

Note: the `<DB>..<TABLE>` notation is SQLServer-specific. For MySql, use `<DB>.<TABLE>` (single dot)

Reset:
```bash
./bin/pdo-git admin git:deleteAll
```

## Testing
1. (optional) Set up a mysql database to test against locally:

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

2. (optional) Run the `git-rest-api` server locally:

```bash
docker-compose up git
```
3. (optional) Set up dev env using option 1 or 2 below
4. Install dependencies: `composer install`
5. Run tests: `composer test`

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
