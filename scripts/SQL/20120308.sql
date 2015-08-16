CREATE TABLE gcr_chained_payment
(
  id bigserial NOT NULL,
  user_institution_id character varying(50) NOT NULL DEFAULT ''::character varying,
  user_id character varying(50) NOT NULL DEFAULT ''::character varying,
  CONSTRAINT gcr_chained_payment_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);