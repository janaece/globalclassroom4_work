alter table gcr_institution add column password_salt character varying(64) NOT NULL DEFAULT ''::character varying;
CREATE TABLE gcr_institution_salt_history
(
  id bigserial NOT NULL,
  institutionid integer NOT NULL DEFAULT 0,
  salt character varying(150) NOT NULL DEFAULT ''::character varying,
  CONSTRAINT gcr_institution_salt_history_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);