# LaravelPlace

## Command

[Composer](https://getcomposer.org/Composer-Setup.exe)のインストーラをダウンロード

以下のコマンドを実行

> $ .\Composer-Setup.exe
>
> $ composer global require "laravel/installer"
>
> $ cd .\Documents\works\PHP
>
> $ laravel new laravel-place

[PostgreSQL](https://www.enterprisedb.com/downloads/postgres-postgresql-downloads)のインストーラをダウンロード

以下のコマンドを実行

> $ .\postgresql-10.4-1-windows-x64.exe

以下をPATHに追加

```text
C:\Program Files\PostgreSQL\10\bin
```

以下のコマンドを実行

> $ postgres -V
>
> $ createdb -O postgres -U postgres laravel-place

接続情報を設定

> $ nano .\Documents\works\PHP\laravel-place\.env

```text
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secrets
```

のブロックを、以下のように修正(DB_USERNAMEとDB_PASSWORDはDB作成時に指定したもの)

```text
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=laravel-place
DB_USERNAME=postgres
DB_PASSWORD=password
```

php.ini の以下の行のコメントを解除

```text
;extension=pdo_pgsql
```

---

Copyright (c) 2018 YA-androidapp(https://github.com/YA-androidapp) All rights reserved.