# exporter
CLI of `db-diff` workflow

## Usage
Against mysql odbc entry
```bash
./bin/pdo-git export MarketflowAcc mf.t1
./bin/pdo-git post-commit MarketflowAcc mf.t1
```

```yml
New:
- TIT_COD
- TIT_NOM
- TIT_DEV_COD
- TIT_REU_COD
- TIT_ISIN_COD
- TIT_DESC
- TIT_DAT_MAT
- TIT_UNDERLYING
Deleted:
- TIT_COD
- TIT_NOM
```

Against SQL server odbc entry
```bash
./bin/pdo-git export MarketflowAcc Marketflow..t1 "select top 4 TIT_COD,TIT_NOM from Marketflow..t1" TIT_COD
./bin/pdo-git post-commit MarketflowAcc Marketflow..t1
```

Reset:
```bash
./bin/pdo-git admin git:deleteAll
```
