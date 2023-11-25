# scraper.py
import requests
from bs4 import BeautifulSoup

def scrape_quotes():
    url = 'http://quotes.toscrape.com'
    response = requests.get(url)

    if response.status_code == 200:
        soup = BeautifulSoup(response.text, 'html.parser')

        # Extract quotes
        quotes = soup.select('span.text')
        for quote in quotes:
            print(quote.get_text())

if __name__ == "__main__":
    scrape_quotes()
