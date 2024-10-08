CREATE TABLE IF NOT EXISTS users (
    `ID` INT(100) NOT NULL AUTOINCREMENT,
    `LOGIN` VARCHAR(255) NOT NULL,
    `PASSWORD` VARCHAR(255) NOT NULL,
    `COMMENT` TEXT,
    PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS users;

/* индексы */
CREATE INDEX user_id ON users (ID);
CREATE UNIQUE INDEX title ON posts (TITLE), blogs (TITLE);
DROP INDEX user_id ON users;

/* CRUD
c - create
r - read
u - update
d - delete
 */

/* C. Добавление элементов в таблицу БД */
INSERT INTO users (LOGIN, PASSWORD, NAME, DATE_REGISTRATION) VALUES ('admin', '123456', 'Petia', CURRENT_DATE);

/* R. Чтение из таблицы БД */
SELECT * FROM users;
SELECT LOGIN, NAME, AGE FROM users;
SELECT LOGIN, NAME, AGE FROM users WHERE DATE_REGISTRATION < CURRENT_DATE - '100 day';
SELECT AGE * 365 AS DAYS, AGE FROM users;
SELECT * FROM users WHERE (DATE_REGISTRATION < DATE('02.03.24') AND NAME != 'Petia')
                            OR AGE > 12 XOR AGE < 40;
SELECT * FROM users WHERE AGE BETWEEN(12, 40);

SELECT * FROM users, groups WHERE users.GROUP_ID = groups.ID RIGHT JOIN rules WHERE rules.ID = groups.RULE_ID;

/* U. Обновление данных в таблицах БД */
UPDATE users SET LOGIN = 'admin2', PASSWORD = '2344567' WHERE ID=1;

/* D. Удаление записи из таблицы БД */
DELETE FROM users WHERE ID = 10;