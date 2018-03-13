# cryptographs
I have written this collection of scripts to practise my programming skills. I am not a developer by trade but took a few months off work to recharge the battery and decided it was fun to learn some programming. It has been on my list for quite some time to get a bit more in-depth programming skills (I know the basics) and this is the result.

I have a few crypto currencies in my portfolio and got tired of updating my Google Sheet on a daily basis and thought "I can do this better and automated". Hence the creation of this set of scripts.

The scripts work 2-fold. Using Python, values of your crypto coins can be:
- printed to stdout (pullCryptro.py)
- imported into a MySQL database (pullCryptoIntoDB.py)
- sent to Elasticsearch (pullCryptoToJson.py)

These Python scripts use the "cryptoprice.py" script as a basis so it must be imported.

Using PHP, the values of your crypto coins can be displayed as a chart on a web page using FusionCharts.

I have this whole thing running on my Synology NAS which is capable of running Python, a web server with PHP and MySQL.

Surely there is lots of room for improvement but feel free to use these scripts for your pleasure.

## Requirements
Python is required to run the Python scripts
At a minimum you will require the following modules:
- [requests][http://docs.python-requests.org/en/master/user/install/]
- json, which is part of the standard library
- [pymysql][http://pymysql.readthedocs.io/en/latest/user/installation.html]

These modules can be installed using pip. If you do not have [pip][https://pip.pypa.io/en/stable/installing/] installed:
```
wget https://bootstrap.pypa.io/get-pip.py
python get-pip.py
```

If you want to import into Elasticsearch, you will require the [Elasticsearch][https://github.com/elastic/elasticsearch-py] module.

You need access to a MySQL server on which you can create the "crypto" database. Use the "createDB.sql" script to create the database and tables.

You need access to an Elasticsearch environment to use the pullCryptoToJson.py script. Use the "elastic_fields.txt" file to create the required fields for the "crypto" index.

## Variables
cryptoprice.py / pullCryptoIntoDB.py:
```
"mysql_host" - the hostname or IP address of your MySQL server
"mysql_user" - the username to connect to your MySQL server database
"mysql_pass" - the password for the username to connect to your MySQL server database
"mysql_db" - the name of the database you created on your MySQL server
```

All Python scripts:
```
COIN_SHORT_NAME - the short name of the coin, e.g. ETH
COIN_FULL_NAME - the full name of the coin, e.g. Ethereum
AMOUNT_OF_COINS - the amount of coins that you own for the given currency
PURCHASE_PRICE - the total purchase price you paid for the coins of the given currency. For example, you have 10 coins purchased fo €10 each, then PURCHASE_PRICE = 100
```

pullCryptoToJson.py
```
elastic_hostname.domain.local - the host name or IP address for you Elasticsearch environment
```
## Scheduling cron
I have the "pullCryptoIntoDB" script scheduled in cron to run every 5 minutes. Here is my cron entry:
```
# Example of job definition:
# .---------------- minute (0 - 59)
# |  .------------- hour (0 - 23)
# |  |  .---------- day of month (1 - 31)
# |  |  |  .------- month (1 - 12) OR jan,feb,mar,apr ...
# |  |  |  |  .---- day of week (0 - 6) (Sunday=0 or 7) OR sun,mon,tue,wed,thu,fri,sat
# |  |  |  |  |
# *  *  *  *  * user-name  command to be executed
*/5 * * * * echristi /opt/python/pullCryptoIntoDb.py
```

I have made the script executable using `chmod +x /opt/python/pullCryptoIntoDb.py` and have the executable for Python in my PATH.

## Dependencies
Python on the host you run the scripts
A web server with PHP for displaying the graphs

## License

BSD

## Author Information

These scripts were created by Erik Christiaans, erik@ascode.nl
