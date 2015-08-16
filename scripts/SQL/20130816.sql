CREATE TABLE gcr_user_storage_s3_account
(
  id bigserial NOT NULL,
  access_key_id character varying(50) NOT NULL DEFAULT ''::character varying,
  secret_access_key character varying(50) NOT NULL DEFAULT ''::character varying,
  CONSTRAINT gcr_user_storage_s3_account_pkey PRIMARY KEY (id )
)
WITH (
  OIDS=FALSE
);

alter table gcr_user_storage_s3 alter column account_id integer NOT NULL DEFAULT 1;

insert into gcr_user_storage_s3_account values(DEFAULT, 'AKIAJNZWIZD7IX45FIVQ','43f5ShJsAEJOIK0KyirSuXzSA99D9aeLh56I5OCT');
insert into gcr_user_storage_s3_account values(DEFAULT, 'AKIAJRMAR2A2JCKJVQGQ','F4VFpRALw2Fk5sEJTKmpzXIuK5QjlAfn4KvEqYym');
