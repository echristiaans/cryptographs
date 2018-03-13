from classes.cryptoprice import CryptoPrice
import requests
import json
import pymysql.cursors

# Set up database connection
dbconn2 = pymysql.connect(host="mysql_host",
                     user="mysql_user",
                     password="mysql_pass",
                     db="mysql_db")

# API Request of USD value
usd_api_request = requests.get("http://api.fixer.io/latest?base=USD")
usd = json.loads(usd_api_request.content)
usd_eur_price = float(usd['rates']['EUR'])


# For each coin, specify the short name, full name, the amount of coins that you hold and the purchase price for those coins.
COIN_SHORT_NAME = CryptoPrice("COIN_FULL_NAME".lower(), AMOUNT_OF_COINS, PURCHASE_PRICE)
COIN_SHORT_NAMEp, COIN_SHORT_NAMEn, COIN_SHORT_NAMEt = COIN_SHORT_NAME.getCryptoPriceDB()
# Example for Ethereum
ETH = CryptoPrice("ethereum".lower(), 10, 6789.123456)
ETHp, ETHn, ETHt = ETH.getCryptoPriceDB()

# Another example for XRP
XRP = CryptoPrice("ripple".lower(), 12345.67890, 12345.67890)
XRPp, XRPn, XRPt = XRP.getCryptoPriceDB()


# Add all COIN_SHORT_NAMEp values to get the total profit
totalProfit = (ETHp + XRPp)
# Add all COIN_SHORT_NAMEt values to get the total investment - remove "* usd_eur_price" to get price in Dollars in stead of Euro's
totalInvestment = ((ETHt + XRPt) * usd_eur_price)
# Add all COIN_SHORT_NAMEn values to get the total value
totalValue = totalProfit + totalInvestment

# inject the values into the database
with dbconn2.cursor() as cursor:
    sql2 = "INSERT INTO `totals` (`totalProfit`, `totalInvestment`, `totalCurrentValue`) VALUES (%s, %s, %s)"
    cursor.execute(sql2, (totalProfit, totalInvestment, totalValue))
dbconn2.commit()
dbconn2.close()
