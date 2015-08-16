CREATE TABLE gcr_wants_url
(
  id bigserial NOT NULL,
  app_id character varying(50) NOT NULL DEFAULT ''::character varying,
  wants_url text NOT NULL DEFAULT ''::text,
  time_created integer NOT NULL DEFAULT 0,
  redirect_type character varying(15) NOT NULL DEFAULT ''::character varying,
  CONSTRAINT gcr_wants_url_pk PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE gcr_wants_url OWNER TO gc4_admin;