<?php

class m260406_000001_create_user_and_profile_tables extends CDbMigration
{
    public function safeUp()
    {
        // Create user table
        $this->createTable('user', array(
            'id' => 'pk',
            'email' => 'VARCHAR(255) NOT NULL',
            'created_at' => 'DATETIME NOT NULL',
            'updated_at' => 'DATETIME NOT NULL',
            'referer' => 'VARCHAR(255) DEFAULT NULL',
            'type' => "ENUM('standard','vip') NOT NULL DEFAULT 'standard'",
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->createIndex('idx_user_email', 'user', 'email', true);

        // Create profile table
        $this->createTable('profile', array(
            'id' => 'pk',
            'user_id' => 'INT NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'surname' => 'VARCHAR(255) NOT NULL',
            'lang' => "ENUM('en','ua') NOT NULL DEFAULT 'en'",
            'login_at' => 'DATETIME DEFAULT NULL',
            'status' => "TINYINT(1) NOT NULL DEFAULT 1",
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->addForeignKey(
            'fk_profile_user',
            'profile', 'user_id',
            'user', 'id',
            'CASCADE', 'CASCADE'
        );

        $this->createIndex('idx_profile_user_id', 'profile', 'user_id', true);

        // Seed test data
        $now = date('Y-m-d H:i:s');

        $users = array(
            array('john.doe@example.com', $now, $now, 'https://google.com', 'standard'),
            array('jane.smith@example.com', '2026-03-15 10:30:00', $now, 'https://facebook.com', 'vip'),
            array('bob.wilson@example.com', '2026-02-20 08:00:00', $now, null, 'standard'),
            array('alice.wonder@example.com', '2026-01-10 14:22:00', $now, 'https://twitter.com', 'vip'),
            array('charlie.brown@example.com', '2025-12-05 09:15:00', $now, 'https://linkedin.com', 'standard'),
            array('diana.prince@example.com', '2026-03-28 16:45:00', $now, null, 'vip'),
            array('edward.norton@example.com', '2026-04-01 11:00:00', $now, 'https://reddit.com', 'standard'),
            array('fiona.apple@example.com', '2026-03-01 07:30:00', $now, 'https://youtube.com', 'standard'),
            array('george.lucas@example.com', '2026-02-14 13:00:00', $now, null, 'vip'),
            array('helen.troy@example.com', '2026-01-25 20:10:00', $now, 'https://github.com', 'standard'),
            array('ivan.petrov@example.com', '2026-03-20 06:00:00', $now, 'https://stackoverflow.com', 'vip'),
            array('julia.roberts@example.com', '2026-04-02 15:30:00', $now, null, 'standard'),
        );

        foreach ($users as $i => $u) {
            $this->insert('user', array(
                'email' => $u[0],
                'created_at' => $u[1],
                'updated_at' => $u[2],
                'referer' => $u[3],
                'type' => $u[4],
            ));
        }

        $profiles = array(
            array(1, 'John', 'Doe', 'en', '2026-04-05 23:00:00', 1),
            array(2, 'Jane', 'Smith', 'ua', '2026-04-05 22:30:00', 1),
            array(3, 'Bob', 'Wilson', 'en', '2026-04-04 10:00:00', 0),
            array(4, 'Alice', 'Wonder', 'ua', '2026-04-05 18:00:00', 1),
            array(5, 'Charlie', 'Brown', 'en', '2026-04-03 09:00:00', 1),
            array(6, 'Diana', 'Prince', 'en', '2026-04-05 20:00:00', 1),
            array(7, 'Edward', 'Norton', 'ua', null, 0),
            array(8, 'Fiona', 'Apple', 'en', '2026-04-01 12:00:00', 1),
            array(9, 'George', 'Lucas', 'ua', '2026-04-05 08:00:00', 1),
            array(10, 'Helen', 'Troy', 'en', '2026-03-30 14:00:00', 0),
            array(11, 'Ivan', 'Petrov', 'ua', '2026-04-05 21:00:00', 1),
            array(12, 'Julia', 'Roberts', 'en', '2026-04-04 16:00:00', 1),
        );

        foreach ($profiles as $p) {
            $this->insert('profile', array(
                'user_id' => $p[0],
                'name' => $p[1],
                'surname' => $p[2],
                'lang' => $p[3],
                'login_at' => $p[4],
                'status' => $p[5],
            ));
        }
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_profile_user', 'profile');
        $this->dropTable('profile');
        $this->dropTable('user');
    }
}
