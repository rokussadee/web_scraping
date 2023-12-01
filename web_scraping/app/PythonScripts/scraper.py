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

    print(args)

    url = f"https://www.torfs.be/nl/{args.gender}/{args.category}/?cgid={args.gender}-{args.category}&page=1.0&srule={args.sort}&sz=24"
    print(url)

    response = requests.get(url)

    print("scraper log")
    print(response)

    if response.status_code == 200:
        soup = BeautifulSoup(response.text, 'html.parser')

        # Extract quotes
        product_tiles = soup.find_all("div", class_="product-tile-wrapper")
        for product_tile in product_tiles:
            product_manufacturer = product_tile.find("div", class_="pdp-link")
            product_name = product_tile.find("div", class_="pdp-link brand")
            product_image_link = product_tile.find("img", class_="tile-image")
            product_price = product_tile.find("div", class_="product-tile__price")

            product_dict = {
                    'product_manufacturer': product_manufacturer.get_text() if product_manufacturer else "N/A",
                    'product_name': product_name.get_text() if product_name else "N/A",
                    'product_image_link': product_image_link.get('href') if product_image_link else "N/A",
                    'product_price': product_price.get_text() if product_price else "N/A",
                    }

            print(product_dict)

if __name__ == "__main__":
    scrape_products()
