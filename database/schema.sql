CREATE TABLE IF NOT EXISTS packages (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    name        TEXT    NOT NULL,
    price       INTEGER NOT NULL,
    period      TEXT    NOT NULL,
    note        TEXT,
    description TEXT    NOT NULL,
    months      INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS members (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    name          TEXT NOT NULL,
    email         TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    status        TEXT NOT NULL DEFAULT 'Aktif',
    photo         TEXT NOT NULL,
    package_id    INTEGER REFERENCES packages(id),
    expires_at    TEXT NOT NULL,
    created_at    TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS facilities (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    title       TEXT NOT NULL,
    description TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS classes (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    name        TEXT NOT NULL,
    description TEXT NOT NULL,
    trainer     TEXT NOT NULL,
    days        TEXT NOT NULL,
    start_time  TEXT NOT NULL,
    end_time    TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS enrollments (
    member_id  INTEGER NOT NULL REFERENCES members(id) ON DELETE CASCADE,
    class_id   INTEGER NOT NULL REFERENCES classes(id) ON DELETE CASCADE,
    created_at TEXT    NOT NULL,
    PRIMARY KEY (member_id, class_id)
);

CREATE TABLE IF NOT EXISTS checkins (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    member_id  INTEGER NOT NULL REFERENCES members(id) ON DELETE CASCADE,
    code       TEXT    NOT NULL,
    created_at TEXT    NOT NULL
);
