from classes.cryptoprice import CryptoPrice
import requests
import json

# API Request of USD value
usd_api_request = requests.get("http://api.fixer.io/latest?base=USD")
usd = json.loads(usd_api_request.content)
usd_eur_price = float(usd['rates']['EUR'])

# For each coin, specify the short name, full name, the amount of coins that you hold and the purchase price for those coins.
COIN_SHORT_NAME = CryptoPrice("COIN_FULL_NAME".lower(), AMOUNT_OF_COINS, PURCHASE_PRICE)
COIN_SHORT_NAMEp, COIN_SHORT_NAMEn, COIN_SHORT_NAMEt = COIN_SHORT_NAME.getCryptoPrice()
# Example for Ethereum
ETH = CryptoPrice("ethereum".lower(), 10, 6789.123456)
ETHp, ETHn, ETHt = ETH.getCryptoPrice()
# Another example for XRP
XRP = CryptoPrice("ripple".lower(), 12345.67890, 12345.67890)
XRPp, XRPn, XRPt = XRP.getCryptoPrice()

# Add all COIN_SHORT_NAMEp values to get the total profit
totalProfit = (ETHp + XRPp)
# Add all COIN_SHORT_NAMEt values to get the total investment - remove "* usd_eur_price" to get price in Dollars in stead of Euro's
totalInvestment = ((ETHt + XRPt) * usd_eur_price)
# Add all COIN_SHORT_NAMEn values to get the total value
totalValue = (ETHn + XRPn)


print("---")
print("Total Profit: €{0:.2f}".format(totalProfit))
print("Total Investment: €{0:.2f}".format(totalInvestment))
print("Total Value: €{0:.2f}".format(totalValue))
print("USD Value: €{0:.5f}".format(usd_eur_price))
