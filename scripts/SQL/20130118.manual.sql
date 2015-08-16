## Admin tool script

CREATE TABLE 

<all maharas>

mhr_gcr_institution_catalog
(
  mhr_institution_name character varying(100) NOT NULL,
  eschool_id character varying(100) NOT NULL DEFAULT ''::character varying,
  CONSTRAINT mhr_gcr_institution_catalog_pkey PRIMARY KEY (mhr_institution_name , eschool_id )
)
WITH (
  OIDS=FALSE
)

insert into 

<all maharas>

mhr_event_subscription values (DEFAULT, 'updateuser', 'gcr_update_user_event_listener')