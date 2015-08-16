CREATE TABLE gcr_chat_session
(
  id bigserial NOT NULL,
  time_created integer NOT NULL DEFAULT 0,
  eschool_id character varying(50) NOT NULL DEFAULT ''::character varying,
  room_id character varying(100) NOT NULL DEFAULT ''::character varying,
  CONSTRAINT gcr_chat_session_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE gcr_chat_session OWNER TO gc4_admin;
CREATE TABLE gcr_chat_session_invite
(
  user_eschool_id character varying(50) NOT NULL DEFAULT ''::character varying,
  time_created integer NOT NULL DEFAULT 0,
  id bigserial NOT NULL,
  user_id integer NOT NULL DEFAULT 0,
  session_id integer NOT NULL DEFAULT 0,
  from_user_id integer NOT NULL DEFAULT 0,
  from_user_eschool_id character varying(50) NOT NULL DEFAULT ''::character varying,
  CONSTRAINT gcr_chat_session_invite_pk PRIMARY KEY (id),
  CONSTRAINT gcr_chat_session_invite_roo_fk FOREIGN KEY (session_id)
      REFERENCES gcr_chat_session (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE gcr_chat_session_invite OWNER TO gc4_admin;
CREATE TABLE gcr_chat_session_users
(
  user_id integer NOT NULL DEFAULT 0,
  session_id integer NOT NULL DEFAULT 0,
  user_eschool_id character varying(50) NOT NULL DEFAULT ''::character varying,
  time_created integer NOT NULL DEFAULT 0,
  CONSTRAINT gcr_chat_session_users_pk PRIMARY KEY (user_id, session_id),
  CONSTRAINT gcr_chat_session_users_roo_fk FOREIGN KEY (session_id)
      REFERENCES gcr_chat_session (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE gcr_chat_session_users OWNER TO gc4_admin;