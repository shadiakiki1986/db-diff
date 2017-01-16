# exporter
CLI of `db-diff` workflow

## Usage
Against mysql odbc entry
```bash
./bin/pdo-git export MarketflowAcc mf.t1
./bin/pdo-git post-commit MarketflowAcc mf.t1
```

Against SQL server odbc entry
```bash
./bin/pdo-git export MarketflowAcc Marketflow..TITRE TIT_COD
./bin/pdo-git export MarketflowAccDb MkfFFADubai..TITRE TIT_COD
./bin/pdo-git post-commit MarketflowAcc Marketflow..TITRE --format console --columns src/columns/ffa-titre.yml
```

Reset:
```bash
./bin/pdo-git admin git:deleteAll
```
