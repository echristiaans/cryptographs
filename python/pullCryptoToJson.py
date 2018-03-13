from classes.cryptoprice import CryptoPrice
import requests
import json
from elasticsearch import Elasticsearch
import datetime

es = Elasticsearch([{'host': 'elastic_hostname.domain.local', 'port': 9200}])
ts = '{:%Y-%m-%d %H:%M}'.format(datetime.datetime.now())

# API Request of USD value
usd_api_request = requests.get("http://api.fixer.io/latest?base=USD")
usd = json.loads(usd_api_request.content)
usd_eur_price = float(usd['rates']['EUR'])

# For each coin, specify the short name, full name, the amount of coins that you hold and the purchase price for those coins.
COIN_SHORT_NAME = CryptoPrice("COIN_FULL_NAME".lower(), AMOUNT_OF_COINS, PURCHASE_PRICE)
COIN_SHORT_NAMEp, COIN_SHORT_NAMEn, COIN_SHORT_NAMEt = COIN_SHORT_NAME.getCryptoPriceJson()
# Example for Ethereum
ETH = CryptoPrice("ethereum".lower(), 10, 6789.123456)
ETHj = ETH.getCryptoPriceJson()
# Another example for XRP
XRP = CryptoPrice("ripple".lower(), 12345.67890, 12345.67890)
XRPj = XRP.getCryptoPriceJson()

# Load the values into Elasticsearch
es.index(index='crypto', doc_type='coins', body=json.loads(ETHj))
es.index(index='crypto', doc_type='coins', body=json.loads(XRPj))
