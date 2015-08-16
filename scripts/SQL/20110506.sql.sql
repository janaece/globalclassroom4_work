drop table gcr_eclassroom_monthly_data;
CREATE TABLE gcr_user_monthly_data
(
  id bigserial NOT NULL,
  year_value integer NOT NULL DEFAULT 0,
  month_value integer NOT NULL DEFAULT 0,
  user_id integer NOT NULL DEFAULT 0,
  user_balance numeric NOT NULL DEFAULT 0,
  gross numeric NOT NULL DEFAULT 0,
  gc_fee numeric NOT NULL DEFAULT 0,
  owner_fee numeric NOT NULL DEFAULT 0,
  user_institution_id character varying(100) NOT NULL DEFAULT ''::character varying,
  CONSTRAINT gcr_user_monthly_data_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE gcr_user_monthly_data OWNER TO gc4_admin;