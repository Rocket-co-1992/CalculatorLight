CREATE TABLE cache (
    `key` VARCHAR(255) NOT NULL PRIMARY KEY,
    `value` LONGTEXT NOT NULL,
    `expiration` INT NOT NULL
);

CREATE TABLE cache_locks (
    `key` VARCHAR(255) NOT NULL PRIMARY KEY,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL
);

CREATE INDEX cache_expiration_idx ON cache(expiration);
