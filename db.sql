CREATE TABLE IF NOT EXISTS url_catalog_item (
    id  TEXT PRIMARY KEY,
    url TEXT NOT NULL UNIQUE -- case sensitivity matters here
);

CREATE TABLE IF NOT EXISTS number_catalog_item (
    id     INTEGER PRIMARY KEY, -- Autoincrement by default in sqlite
    number INTEGER NOT NULL
);