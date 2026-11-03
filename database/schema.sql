CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    role TEXT DEFAULT NULL,
    password TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS actions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    userId INTEGER NOT NULL,
    date TEXT NOT NULL,
    action TEXT NOT NULL CHECK (action IN ('login', 'logout', 'registration', 'view-page', 'button-click')),
    data TEXT DEFAULT NULL,
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (id, username, role, password) VALUES (null, 'admin', 'admin', '$2y$12$txGaaVnuoPh77ioULnf28eZmqW/ExtoUChmoldyDSf4Bw4U/zlW.i'); -- password: admin
INSERT INTO users (id, username, role, password) VALUES (null, 'test', 'user', '$2y$12$z42I0QoH0Bv3nP6PHqqWluFoG1SrNsNZO1dnOGawBP0ANd/rQaISW'); -- password: test