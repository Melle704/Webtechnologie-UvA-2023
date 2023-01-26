#!/bin/python3

import requests

print("""
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET @@local.net_read_timeout=360;

USE test;

CREATE TABLE IF NOT EXISTS cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_card TEXT,
    layout TEXT NOT NULL,
    name TEXT,
    oracle_text TEXT,
    flavor_text TEXT,
    image TEXT,
    back_image TEXT,
    artist TEXT,
    cmc FLOAT,
    color_identity TEXT,
    colors TEXT,
    mana_cost TEXT,
    keywords TEXT,
    type_line TEXT,
    real_card INT,
    standard_legal TEXT NOT NULL,
    pioneer_legal TEXT NOT NULL,
    modern_legal TEXT NOT NULL,
    legacy_legal TEXT NOT NULL,
    vintage_legal TEXT NOT NULL,
    pauper_legal TEXT NOT NULL,
    commander_legal TEXT NOT NULL,
    penny_legal TEXT NOT NULL,
    power TEXT,
    toughness TEXT,
    loyalty TEXT,
    rarity TEXT,
    normal_price FLOAT,
    foil_price FLOAT,
    populairity INT,
    collector_number TEXT,
    released_at DATE,
    set_code TEXT,
    set_name TEXT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci AUTO_INCREMENT = 1;
""")

bulk_files = requests.get("https://api.scryfall.com/bulk-data")
bulk_files_data = bulk_files.json()

bulk_file = requests.get(bulk_files_data["data"][2]["download_uri"])
bulk_file_data = bulk_file.json()

for card in bulk_file_data:
  card_fields = {}

  card_fields["id_card"] = card["id"]
  card_fields["name"] = card["name"].replace('\"', '\\\"')
  card_fields["layout"] = card["layout"]
  card_fields["artist"] = card["artist"].replace('\"', '\\\"')
  card_fields["color_identity"] = " ".join(card["color_identity"])
  card_fields["keywords"] = " ".join(card["keywords"])
  card_fields["standard_legal"] = card["legalities"]["standard"]
  card_fields["pioneer_legal"] = card["legalities"]["pioneer"]
  card_fields["modern_legal"] = card["legalities"]["modern"]
  card_fields["legacy_legal"] = card["legalities"]["legacy"]
  card_fields["vintage_legal"] = card["legalities"]["vintage"]
  card_fields["pauper_legal"] = card["legalities"]["pauper"]
  card_fields["commander_legal"] = card["legalities"]["commander"]
  card_fields["penny_legal"] = card["legalities"]["penny"]
  card_fields["rarity"] = card["rarity"]
  card_fields["populairity"] = "0"
  card_fields["collector_number"] = card["collector_number"]
  card_fields["released_at"] = card["released_at"]
  card_fields["set_code"] = card["set"]
  card_fields["set_name"] = card["set_name"]

  if "paper" in card["games"]:
    card_fields["real_card"] = "1"
  else:
    card_fields["real_card"] = "0"

  # These values are not always available.
  if "usd" in card["prices"]:
    card_fields["normal_price"] = str(card["prices"]["usd"])
  else:
    card_fields["normal_price"] = "0"

  if "usd_foil" in card["prices"]:
    card_fields["foil_price"] = str(card["prices"]["usd_foil"])
  else:
    card_fields["foil_price"] = "0"

  if "oracle_text" in card.keys():
    card_fields["oracle_text"] = card["oracle_text"].replace('\"', '\\\"')

  if "flavor_text" in card.keys():
    card_fields["flavor_text"] = card["flavor_text"].replace('\"', '\\\"')

  if "cmc" in card.keys():
    card_fields["cmc"] = str(card["cmc"])

  if "colors" in card.keys():
    card_fields["colors"] = " ".join(card["colors"])

  if "mana_cost" in card.keys():
    card_fields["mana_cost"] = card["mana_cost"]

  if "type_line" in card.keys():
    card_fields["type_line"] = card["type_line"].replace('\"', '\\\"')

  if "toughness" in card.keys() and "power" in card.keys():
    card_fields["power"] = card["power"]
    card_fields["toughness"] = card["toughness"]

  if "loyality" in card.keys():
    card_fields["loyalty"] = card["loyalty"]

  # Checks to see if a backside image is present.
  if (card["layout"] == "transform" or
      card["layout"] == "modal_dfc" or
      card["layout"] == "double_faced_token" or
      card["layout"] == "reversible_card" or
      card["layout"] == "art_series") :
    if "image_uris" in card["card_faces"][0].keys():
        card_fields["image"] = card["card_faces"][0]["image_uris"]["normal"]
    if "image_uris" in card["card_faces"][1].keys():
        card_fields["back_image"] = card["card_faces"][1]["image_uris"]["normal"]
  # Meld cards are checked seperately because they are a bit weird.
  elif (card["layout"] == "meld") :
    card_fields["image"] = card["image_uris"]["normal"]
    for part in card["all_parts"]:
      if (part["component"] == "meld_result"):
        meld_result = requests.get(part["uri"])
        meld_result_data = meld_result.json()
        card_fields["back_image"] = meld_result_data["image_uris"]["normal"]
  else :
    if "image_uris" in card.keys():
      if "normal" in card["image_uris"].keys():
        card_fields["image"] = card["image_uris"]["normal"]

  fields = ", ".join(card_fields.keys())

  values = ['"' + field + '"' for field in card_fields.values()]
  values = ", ".join(values)

  print(f"INSERT INTO cards ({fields}) VALUES ({values});")

print("COMMIT;")
