create table users
(
    id                bigint unsigned auto_increment
        primary key,
    name              varchar(255) not null,
    email             varchar(255) not null,
    email_verified_at timestamp    null,
    password          varchar(255) not null,
    remember_token    varchar(100) null,
    created_at        timestamp    null,
    updated_at        timestamp    null,
    constraint users_email_unique
        unique (email)
)
    collate = utf8mb4_unicode_ci;

INSERT INTO game_serve.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES (1, 'nik', 'admin@gmail.com', null, '$2y$10$065kPqp5j5XyAe0dzdBhTeavOHjpnIGPC5eQaj3LCfFJ5AtdN1JjO', null, '2022-02-14 10:41:31', '2022-02-14 10:41:31');
INSERT INTO game_serve.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES (12, 'nik2', 'admin2@gmail.com', null, '$2y$10$m/YVrEu37CELWKlvZO9t8e45d5ipjYDsihqwAASlYOEpzqhpn3vyS', null, '2022-02-17 09:05:52', '2022-02-17 09:05:52');
INSERT INTO game_serve.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES (13, 'nik', 'admin3@gmail.com', null, '$2y$10$ORiaUSnIRgrpiso0nVgt.OXsaDgH31iKQqdKw4fAn9ld0zDxZz8oy', null, '2022-02-17 09:10:13', '2022-02-17 09:10:13');
INSERT INTO game_serve.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES (14, 'dima', 'admin4@gmail.com', null, '$2y$10$dHHqQQhFXhm1iHttxa5tW.2P3ZvwlYedaMgFuwNSp21xoD3QXsmMO', null, '2022-02-17 09:14:29', '2022-02-17 09:14:29');
INSERT INTO game_serve.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES (20, 'niki', 'admin23@gmail.com', null, '$2y$10$MEHTR.JbQ/ou1DTaKvfo4OndqUjmgPJEkSjSpYggfv.Yal3nmgyoy', null, '2022-06-07 07:44:59', '2022-06-07 07:44:59');
INSERT INTO game_serve.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES (21, '111', 'admin333@gmail.com', null, '$2y$10$ba1fkyzD1UlJ2Afsubevie1EgAl4v.kcE6zZaPqeEr6ZXkKwN404W', null, '2022-06-07 07:53:03', '2022-06-07 07:53:03');
INSERT INTO game_serve.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES (22, 'nik', 'admin1233@gmail.com', null, '$2y$10$fQMwpUe2HSsIMDOUyqrpquBDrNZxE0z/qz6bI9G2Dg4bcYJt5DiWC', null, '2022-06-07 07:53:59', '2022-06-07 07:53:59');
INSERT INTO game_serve.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES (23, 'nik', 'admin666@gmail.com', null, '$2y$10$.KadXHw/6ZUS/X8UG/kmVOcSY5t2NmLYBiHUxlaHmKc1rE4UTxUIm', null, '2022-06-07 07:56:08', '2022-06-07 07:56:08');
INSERT INTO game_serve.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES (24, 'nik', '24@rf', null, '$2y$10$b5HLU7ENNCCjgKThcsHleuNQYXsqyj.Hpkv8wI9xT4oCOitIGmZMW', null, '2023-06-12 18:23:44', '2023-06-12 18:23:44');
INSERT INTO game_serve.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES (25, 'nik', 'wer@sdfg', null, '$2y$10$TKYa/Anp7Br238/GlKs.u.xwl12NwJOOvqg48qxLFT2YXRxrmSgvu', null, '2023-06-12 18:24:30', '2023-06-12 18:24:30');
INSERT INTO game_serve.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES (26, 'nik22', '35@se', null, '$2y$10$a4F1eSU91VR/5uLBN7ztC.D5DWexkdPA1k.f.o0b9NpPhuk5JzwAy', null, '2023-06-12 18:25:32', '2023-06-12 18:25:32');
