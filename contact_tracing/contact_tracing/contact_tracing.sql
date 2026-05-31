-- ============================================================
-- Contact Tracing System — USC Department of Computer Engineering
-- Full database export: DROP, CREATE, sample data
-- MySQL 5.7+ / MariaDB 10.3+
-- ============================================================

DROP DATABASE IF EXISTS contact_tracing_db;
CREATE DATABASE contact_tracing_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE contact_tracing_db;

-- ── Table: visitors ─────────────────────────────────────────
CREATE TABLE visitors (
    visitor_id     INT           NOT NULL AUTO_INCREMENT,
    id_number      VARCHAR(50)   NULL          COMMENT 'USC student/staff ID; NULL for guests',
    first_name     VARCHAR(100)  NOT NULL,
    middle_name    VARCHAR(100)  NULL,
    last_name      VARCHAR(100)  NOT NULL,
    barangay       VARCHAR(100)  NOT NULL,
    city           VARCHAR(100)  NOT NULL,
    province       VARCHAR(100)  NOT NULL,
    contact_number VARCHAR(20)   NOT NULL,
    email          VARCHAR(150)  NOT NULL,
    created_at     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (visitor_id),
    UNIQUE KEY uq_id_number (id_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Table: visit_logs ────────────────────────────────────────
CREATE TABLE visit_logs (
    log_id      INT      NOT NULL AUTO_INCREMENT,
    visitor_id  INT      NOT NULL,
    time_in     DATETIME NOT NULL,
    time_out    DATETIME NULL     COMMENT 'NULL while visitor is still on-premises',
    PRIMARY KEY (log_id),
    CONSTRAINT fk_visitor
        FOREIGN KEY (visitor_id)
        REFERENCES visitors (visitor_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Sample visitors ──────────────────────────────────────────
INSERT INTO visitors
    (id_number, first_name, middle_name, last_name,
     barangay, city, province, contact_number, email, created_at)
VALUES
    ('2021-00001', 'Maria',   'Santos',   'Reyes',
     'Lahug',    'Cebu City', 'Cebu',   '09171234567', 'maria.reyes@usc.edu.ph',
     '2024-01-15 08:00:00'),

    ('2022-00145', 'Jose',    'Cruz',     'Dela Cruz',
     'Talamban', 'Cebu City', 'Cebu',   '09281234568', 'jose.delacruz@gmail.com',
     '2024-01-16 07:45:00'),

    ('2020-00789', 'Ana',     'Gomez',    'Villanueva',
     'Mabolo',   'Cebu City', 'Cebu',   '09351234569', 'ana.villanueva@yahoo.com',
     '2024-02-05 09:30:00'),

    (NULL,         'Roberto', 'Lim',      'Tan',
     'Punta Princesa', 'Cebu City', 'Cebu', '09451234570', 'roberto.tan@gmail.com',
     '2024-03-10 10:15:00'),

    ('2023-00412', 'Sophia',  'Aquino',   'Mendoza',
     'Banilad',  'Cebu City', 'Cebu',   '09561234571', 'sophia.mendoza@usc.edu.ph',
     '2024-03-11 08:20:00'),

    ('2019-00256', 'Carlos',  'Fernandez','Garcia',
     'Mactan',   'Lapu-Lapu City', 'Cebu', '09671234572', 'carlos.garcia@gmail.com',
     '2024-04-02 11:00:00'),

    (NULL,         'Elena',   'Bautista', 'Torres',
     'Mandaue',  'Mandaue City',   'Cebu', '09781234573', 'elena.torres@email.com',
     '2024-04-15 13:30:00');

-- ── Sample visit logs ────────────────────────────────────────
-- Maria Reyes: two completed visits + one still open
INSERT INTO visit_logs (visitor_id, time_in, time_out) VALUES
    (1, '2024-05-10 08:05:00', '2024-05-10 12:30:00'),
    (1, '2024-05-15 07:50:00', '2024-05-15 17:00:00'),
    (1, '2024-05-28 08:00:00', NULL);

-- Jose Dela Cruz: one completed visit
INSERT INTO visit_logs (visitor_id, time_in, time_out) VALUES
    (2, '2024-05-12 09:10:00', '2024-05-12 11:45:00'),
    (2, '2024-05-20 13:00:00', '2024-05-20 15:30:00');

-- Ana Villanueva: one completed visit, one open
INSERT INTO visit_logs (visitor_id, time_in, time_out) VALUES
    (3, '2024-05-18 10:00:00', '2024-05-18 16:00:00'),
    (3, '2024-05-28 09:30:00', NULL);

-- Roberto Tan (guest): one completed visit
INSERT INTO visit_logs (visitor_id, time_in, time_out) VALUES
    (4, '2024-05-22 14:00:00', '2024-05-22 14:45:00');

-- Sophia Mendoza: multiple visits
INSERT INTO visit_logs (visitor_id, time_in, time_out) VALUES
    (5, '2024-05-05 07:30:00', '2024-05-05 12:00:00'),
    (5, '2024-05-12 08:00:00', '2024-05-12 17:00:00'),
    (5, '2024-05-19 08:15:00', '2024-05-19 16:45:00'),
    (5, '2024-05-28 07:45:00', NULL);

-- Carlos Garcia: completed visit
INSERT INTO visit_logs (visitor_id, time_in, time_out) VALUES
    (6, '2024-05-27 10:00:00', '2024-05-27 12:00:00');

-- Elena Torres (guest): open visit
INSERT INTO visit_logs (visitor_id, time_in, time_out) VALUES
    (7, '2024-05-28 11:00:00', NULL);

-- ── End of file ──────────────────────────────────────────────
