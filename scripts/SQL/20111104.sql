CREATE TABLE gcr_user_storage_s3
(
  id bigserial NOT NULL,
  app_id character varying(50) NOT NULL DEFAULT ''::character varying,
  bucket_name character varying(150) NOT NULL DEFAULT ''::character varying,
  CONSTRAINT user_storage_s3_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE gcr_user_storage_s3 OWNER TO gc4_admin;