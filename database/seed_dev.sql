-- Seed roles
INSERT INTO
    roles (role_id, role_name)
VALUES
    (1, 'superadmin'),
    (2, 'dosen'),
    (3, 'mahasiswa');

-- seed superadmin (plain password: superadmin123)
INSERT INTO
    users (username, password, role_id)
VALUES
    (
        'superadmin',
        '$2y$10$qaxXzC496xa93Pr2mB6s5ee7toNS4CUtv2tXDRnxlNeXlxN7e4I/y',
        1
    );