## Admin tool script

CREATE TABLE 

<all moodles>

mdl_gcr_profile_picture
(
  id bigserial NOT NULL,
  user_id bigint NOT NULL UNIQUE DEFAULT 0,
  picture_id bigint NOT NULL DEFAULT 0,
  CONSTRAINT mdl_gcr_profile_picture_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
)
