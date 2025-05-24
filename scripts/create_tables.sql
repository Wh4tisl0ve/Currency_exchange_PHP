CREATE TABLE IF NOT EXISTS currencies (
    id SERIAL PRIMARY KEY,
    code VARCHAR(3) UNIQUE NOT NULL CHECK (
         length(code) = 3 AND code ~ '^[A-Z]{3}$'
    ),
    fullname VARCHAR(50),
    sign VARCHAR(5)
);

CREATE TABLE IF NOT EXISTS exchange_rates (
    id SERIAL PRIMARY KEY,
    base_currency_id INTEGER NOT NULL,
    target_currency_id INTEGER NOT NULL,
    rate DECIMAL(11, 6) NOT NULL,
    UNIQUE(base_currency_id, target_currency_id),
    CHECK (base_currency_id <> target_currency_id),
    FOREIGN KEY (base_currency_id) REFERENCES currencies (id),
    FOREIGN KEY (target_currency_id) REFERENCES currencies (id)
);
