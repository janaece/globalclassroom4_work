CREATE TABLE gcr_trial_application
(
  id serial NOT NULL,
  contact integer NOT NULL DEFAULT 0,
  address integer NOT NULL DEFAULT 0,
  verify_hash character varying(64) NOT NULL DEFAULT ''::character varying,
  CONSTRAINT gcr_trial_application_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE gcr_trial_application OWNER TO gc4_admin;
alter table gcr_institution drop column verify_hash;
