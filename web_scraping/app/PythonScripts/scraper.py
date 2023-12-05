# scraper.py
import requests
import sys
from bs4 import BeautifulSoup
import argparse

def scrape_products():

    parser = argparse.ArgumentParser(description='Your Python Scraper Script')
    parser.add_argument('--gender', type=str, help='Gender of overarching clothing collection.',nargs='?', default=0, const=0)
    parser.add_argument('--category', type=str, help='Category of scraped listings page.',nargs='?', default=0, const=0)
    parser.add_argument('--sort', type=str, help='Sort method for the scraped product listings.',nargs='?', default=0, const=0)
    parser.set_defaults(gender="heren",category="schoenen", sort="prijs-laag-hoog",nargs='?', default=0, const=0)

    args = parser.parse_args()

    url = f"https://www.torfs.be/nl/{args.gender}/{args.category}/?cgid={args.gender}-{args.category}&page=1.0&srule={args.sort}&sz=24"
    response = requests.get(url)

    if response.status_code == 200:
        soup = BeautifulSoup(response.text, 'html.parser')

        # Extract products
        product_tiles = soup.find_all("div", class_="product-tile-wrapper")
        for product_tile in product_tiles:

            # find the target elements
            product_manufacturer_element = product_tile.find("div", class_="pdp-link")
            product_name_element = product_tile.find("div", class_="pdp-link brand")
            product_image_element = product_tile.find("img", class_="tile-image")
            product_price_element = product_tile.find("div", class_="product-tile__price")
            product_color_amount_element = product_tile.find("div", class_="product-tile__color-amount")

            # find the required content inside these elements
            product_manufacturer = product_manufacturer_element.get_text() if product_manufacturer_element else "N/A"

            if product_name_element != None:
                product_name = product_name_element.get_text() if product_name_element else "N/A"
            else:
                continue

            if product_image_element != None:
                product_image_link = product_image_element.get('data-src') if product_image_element.get('data-src') != None else product_image_element.get('src')
            else:
                continue

            product_price = product_price_element.find("span", class_="value").get("content") if product_price_element else "N/A"

            product_color_amount = product_color_amount_element.get_text() if product_color_amount_element else "N/A"

            product_dict = {
                    'product_manufacturer': product_manufacturer,
                    'product_name': product_name,
                    'product_image_link': product_image_link,
                    'product_price': product_price,
                    'product_color_amount': product_color_amount,
                    }

            print(product_dict)

if __name__ == "__main__":
   scrape_products()
