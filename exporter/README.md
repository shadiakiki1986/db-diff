PHP script that gets TITRE value, saves to git-rest-api as yml

## Usage
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
