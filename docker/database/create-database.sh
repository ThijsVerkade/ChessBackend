#!/usr/bin/env bash

mysql --user=root --password="$MYSQL_ROOT_PASSWORD" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS $MYSQL_DB;
    GRANT ALL PRIVILEGES ON \`$MYSQL_DB%\`.* TO '$MYSQL_USER'@'%';
EOSQL
