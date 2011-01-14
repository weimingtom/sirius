CREATE TABLE invite (id BIGINT AUTO_INCREMENT, code VARCHAR(100) NOT NULL, generater_id BIGINT, purpose VARCHAR(100) DEFAULT 'register' NOT NULL, is_used TINYINT(1) DEFAULT '0' NOT NULL, used_by BIGINT, expire_date DATE, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE profile (id BIGINT AUTO_INCREMENT, owner_id BIGINT NOT NULL, profile_name VARCHAR(255) NOT NULL, screen_name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, avatar_url TEXT, connect_data TEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX owner_id_index_idx (owner_id), UNIQUE INDEX fingerprint_index_idx (profile_name, screen_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE tab (id BIGINT AUTO_INCREMENT, owner_id BIGINT NOT NULL, title VARCHAR(255) NOT NULL, thread_ids TEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX owner_id_idx (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE thread (id BIGINT AUTO_INCREMENT, title VARCHAR(255) NOT NULL, owner_id BIGINT NOT NULL, tab_id BIGINT NOT NULL, profile_id BIGINT, profile_name VARCHAR(255), profile_type VARCHAR(255), type VARCHAR(255) NOT NULL, parameters VARCHAR(255) DEFAULT '' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX tab_thread_index_idx (tab_id, profile_id, type, parameters), INDEX owner_id_idx (owner_id), INDEX tab_id_idx (tab_id), INDEX profile_id_idx (profile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
CREATE TABLE user (id BIGINT AUTO_INCREMENT, email VARCHAR(255) NOT NULL UNIQUE, full_name VARCHAR(255), password VARCHAR(255) NOT NULL, status VARCHAR(255) DEFAULT 'verified', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB;
ALTER TABLE profile ADD CONSTRAINT profile_owner_id_user_id FOREIGN KEY (owner_id) REFERENCES user(id) ON DELETE CASCADE;
ALTER TABLE tab ADD CONSTRAINT tab_owner_id_user_id FOREIGN KEY (owner_id) REFERENCES user(id) ON DELETE CASCADE;
ALTER TABLE thread ADD CONSTRAINT thread_tab_id_tab_id FOREIGN KEY (tab_id) REFERENCES tab(id) ON DELETE CASCADE;
ALTER TABLE thread ADD CONSTRAINT thread_profile_id_profile_id FOREIGN KEY (profile_id) REFERENCES profile(id) ON DELETE CASCADE;
ALTER TABLE thread ADD CONSTRAINT thread_owner_id_user_id FOREIGN KEY (owner_id) REFERENCES user(id) ON DELETE CASCADE;
