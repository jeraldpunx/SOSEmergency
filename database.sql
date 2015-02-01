ALTER TABLE users
ADD CONSTRAINT fk_pu_users
FOREIGN KEY (`puid_ruid`)
REFERENCES person_units(`id`)

ALTER TABLE users
ADD CONSTRAINT fk_ru_users
FOREIGN KEY (`puid_ruid`)
REFERENCES rescue_units(`id`)

ALTER TABLE reports
ADD CONSTRAINT fk_pu_reports
FOREIGN KEY (`pu_id`)
REFERENCES person_units(`id`)

ALTER TABLE reports
ADD CONSTRAINT fk_ru_reports
FOREIGN KEY (`ru_id`)
REFERENCES rescue_units(`id`)

ALTER TABLE reports
ADD CONSTRAINT fk_ec_reports
FOREIGN KEY (`ec_id`)
REFERENCES emergency_codes(`id`)

ALTER TABLE ru_contacts
ADD CONSTRAINT fk_ec_rucontacts
FOREIGN KEY (`ru_id`)
REFERENCES rescue_units(`id`)

ALTER TABLE ru_ec
ADD CONSTRAINT fk_ru_ruec
FOREIGN KEY (`ru_id`)
REFERENCES rescue_units(`id`)


ALTER TABLE ru_ec
ADD CONSTRAINT fk_ec_ruec
FOREIGN KEY (`ec_id`)
REFERENCES emergency_codes(`id`)

