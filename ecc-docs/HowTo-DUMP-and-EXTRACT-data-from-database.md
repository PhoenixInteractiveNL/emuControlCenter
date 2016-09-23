## HowTo - DUMP and EXTRACT data from the database
***
### Dump whole database to SQL file

* From ECC root folder execute:

`ecc-core\thirdparty\sqlite\sqlite.exe ecc-system\database\eccdb .dump >eccdb.sql`

***
### Create database from SQL file

* From ECC root folder execute:

`ecc-core\thirdparty\sqlite\sqlite.exe ecc-system\database\eccdb <eccdb.sql`

***
### Dump data from database into CSV file
Here is an example to dump all CRC32 of the parsed Atari 5200 games (a5200)

* In the ECC root folder, create a file called "**sql.inst**" in here you put all the SQLite .commands

    `.separator ;`

    `.output output.txt`

    `SELECT eccident, crc32 FROM fdata WHERE eccident='a5200';`


* From ECC root folder execute:

`ecc-core\thirdparty\sqlite\sqlite.exe ecc-system\database\eccdb <sql.inst`

* Output in file **output.txt**

`    a5200;02931055`

    `a5200;02C9ABC6`

    `a5200;02CDFC70`

    `a5200;02D9B87A`

    `a5200;04807705`

    `a5200;04B299A4`

    `a5200;0624E6E7`

    `a5200;063EC2C4`

    `...`