CREATE TABLE gcr_commission
(
  id bigserial NOT NULL,
  institution_id character varying(50) NOT NULL DEFAULT ''::character varying,
  eschool_id character varying(50) NOT NULL DEFAULT ''::character varying,
  commission_rate numeric NOT NULL DEFAULT 0.00,
  CONSTRAINT gcr_commission_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
alter table gcr_purchase add column commission_fee numeric NOT NULL DEFAULT 0;
alter table gcr_paypal add column commission_fee numeric NOT NULL DEFAULT 0;