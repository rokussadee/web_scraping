# scraper.py
import requests
from bs4 import BeautifulSoup

def scrape_quotes():
    url = 'https://www.torfs.be/nl/herencollectie/?page=1.0&srule=nieuwste&sz=24'

    response = requests.get(url)

    if response.status_code == 200:
        soup = BeautifulSoup(response.text, 'html.parser')

        # Extract quotes
        product_tiles = soup.find_all("div", class_="product-tile-wrapper")
        for product_tile in product_tiles:
            product_manufacturer = product_tile.find("div", class_="pdp-link").get_text()
            product_name = product_tile.find("div", class_="pdp-link brand").get_text()
            product_image_link = product_tile.find("img", class_="tile-image").get('href')
            product_price = product_tile.find("div", class_="product-tile__price").get_text()

            product_dict = {
                    'product_manufacturer': product_manufacturer,
                    'product_name': product_name,
                    'product_image_link': product_image_link,
                    'product_price': product_price
                    }


            print(product_dict)

if __name__ == "__main__":
    scrape_quotes()
