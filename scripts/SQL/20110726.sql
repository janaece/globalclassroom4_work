CREATE TABLE gcr_purchase_item
(
  short_name character varying(100) NOT NULL DEFAULT ''::character varying,
  description character varying(255) NOT NULL DEFAULT ''::character varying,
  amount numeric NOT NULL DEFAULT 0,
  CONSTRAINT purchase_item_pkey PRIMARY KEY (short_name)
)
WITH (
  OIDS=FALSE
);

insert into gcr_purchase_item values('act48_credits_1', 'Graduate Level Credit: 1 Credit', 150);
insert into gcr_purchase_item values('act48_credits_2', 'Graduate Level Credit: 2 Credits', 300);
insert into gcr_purchase_item values('act48_credits_3', 'Graduate Level Credit: 3 Credits', 450);