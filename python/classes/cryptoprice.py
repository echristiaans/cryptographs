import requests
import json
import pymysql.cursors
import datetime

# Set up database connection
dbconn = pymysql.connect(host="mysql_host",
                     user="mysql_user",
                     password="mysql_pass",
                     db="mysql_db")

ts = '{:%Y-%m-%d %H:%M}'.format(datetime.datetime.now())

# API Request of USD value
usd_api_request = requests.get("http://api.fixer.io/latest?base=USD")
usd = json.loads(usd_api_request.content)
usd_eur_price = float(usd['rates']['EUR'])

class CryptoPrice:
    def __init__(self, cryptoName, numberOf, totalCost):
        self.cryptoName = cryptoName
        self.numberOf = numberOf
        self.totalCost = totalCost
    # This function simply prints the price to stdout
    def getCryptoPrice(self):
        api_request = requests.get("https://api.coinmarketcap.com/v1/ticker/"+self.cryptoName+"/")
        name = json.loads(api_request.content)
        for n in name:
            crypto_price = (float(n['price_usd']) * usd_eur_price) #
            crypto_total_value = (crypto_price * self.numberOf)
            crypto_percentage = ((crypto_total_value - (self.totalCost * usd_eur_price))/(self.totalCost * usd_eur_price)*100)
            crypto_profit = (crypto_total_value - (self.totalCost * usd_eur_price))
            print("---")
            print("{sym} = €{price:.5f}".format(sym=n['symbol'],price=crypto_price))
            print("Total value: €{0:.2f}".format(crypto_total_value))
            print("{0:.2f}%".format(crypto_percentage))
            print("{sym} Profit: €{profit:.2f}".format(sym=n['symbol'],profit=crypto_profit))
            return crypto_profit, crypto_total_value, self.totalCost
    # This function dumps the prices into a MySQL database
    def getCryptoPriceDB(self):
        api_request = requests.get("https://api.coinmarketcap.com/v1/ticker/"+self.cryptoName+"/")
        name = json.loads(api_request.content)
        for n in name:
            crypto_price = (float(n['price_usd']) * usd_eur_price)
            crypto_total_value = (crypto_price * self.numberOf)
            crypto_percentage = ((crypto_total_value - (self.totalCost * usd_eur_price))/(self.totalCost * usd_eur_price)*100)
            crypto_profit = (crypto_total_value - (self.totalCost * usd_eur_price))
            with dbconn.cursor() as cursor:
                sql1 = "INSERT INTO `coins` (`coinShortName`, `coinName`, `coinPurchaseValue`, `coinPurchaseAmount`, `coinCurrentPrice`, `coinCurrentValue`, `coinPercentage`) VALUES (%s, %s, %s, %s, %s, %s, %s)"
                cursor.execute(sql1, (n['symbol'], self.cryptoName, self.totalCost, self.numberOf, crypto_price, crypto_total_value, crypto_percentage))
            dbconn.commit()
            return crypto_profit, crypto_total_value, self.totalCost
    # This function dumps the prices to JSON
    def getCryptoPriceJson(self):
        api_request = requests.get("https://api.coinmarketcap.com/v1/ticker/"+self.cryptoName+"/")
        name = json.loads(api_request.content)
        for n in name:
            crypto_price = (float(n['price_usd']) * usd_eur_price) #
            crypto_total_value = (crypto_price * self.numberOf)
            crypto_percentage = ((crypto_total_value - (self.totalCost * usd_eur_price))/(self.totalCost * usd_eur_price)*100)
            crypto_profit = (crypto_total_value - (self.totalCost * usd_eur_price))
            json_string = '{"timestamp": "' + str(ts) + '", "name": "' + str(self.cryptoName) + '", "symbol": "' + n['symbol'] + '", "purchase_value": "' + str(self.totalCost) + '", "purchase_amount": "' + str(self.numberOf) + '", "current_price": "' + str(crypto_price) + '", "current_value": "' + str(crypto_total_value) + '", "percentage": "' + str(crypto_percentage) + '", "coin_profit": "' + str(crypto_profit) + '"}'
            return json_string
