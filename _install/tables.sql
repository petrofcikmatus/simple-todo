-- tabuľka pre databázu
CREATE DATABASE todo
  CHARACTER SET 'utf8'
  COLLATE 'utf8_general_ci';

-- tabuľka pre užívateľov
CREATE TABLE users (
  `user_id`         SERIAL PRIMARY KEY,
  `user_name`       VARCHAR(128) NOT NULL,
  `user_email`      VARCHAR(128) NOT NULL UNIQUE,
  `user_password`   CHAR(128)    NOT NULL, -- sha512
  `user_created_at` TIMESTAMP    NOT NULL         DEFAULT NOW()
)
  CHARACTER SET 'utf8'
  COLLATE 'utf8_general_ci';

-- tabuľka pre úlohy
CREATE TABLE tasks (
  `task_id`         SERIAL PRIMARY KEY,
  `task_uid`        BIGINT UNSIGNED NOT NULL REFERENCES users (user_id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  `task_text`       TEXT            NOT NULL,
  `task_encrypted`  BOOLEAN         NOT NULL                  DEFAULT TRUE,
  `task_created_at` TIMESTAMP       NOT NULL                  DEFAULT NOW() ON UPDATE NOW()
)
  CHARACTER SET 'utf8'
  COLLATE 'utf8_general_ci';
