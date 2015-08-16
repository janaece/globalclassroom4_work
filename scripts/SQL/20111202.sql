CREATE TABLE gcr_background_process
(
  id bigserial NOT NULL,
  job_data text NOT NULL DEFAULT ''::text,
  process_type character varying(50) NOT NULL DEFAULT ''::character varying,
  CONSTRAINT gcr_background_process_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE gcr_background_process OWNER TO gc4_admin;