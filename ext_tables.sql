#
# Table structure for table 'tx_sfeventmgt_domain_model_event'
#
CREATE TABLE tx_sfeventmgt_domain_model_event (
	title varchar(255) DEFAULT '' NOT NULL,
	teaser text,
	description text,
	program text,
	custom_text text,
	rowDescription text,
	startdate int(11) DEFAULT '0' NOT NULL,
	enddate int(11) DEFAULT '0' NOT NULL,
	max_participants int(11) DEFAULT '0' NOT NULL,
	max_registrations_per_user int(11) DEFAULT '1' NOT NULL,
	price double(11,2) DEFAULT '0.00' NOT NULL,
	currency varchar(255) DEFAULT '' NOT NULL,
	enable_payment tinyint(4) unsigned DEFAULT '0' NOT NULL,
	restrict_payment_methods tinyint(4) unsigned DEFAULT '0' NOT NULL,
	selected_payment_methods text,
	category int(11) unsigned DEFAULT '0' NOT NULL,
	registration int(11) unsigned DEFAULT '0' NOT NULL,
	registration_waitlist int(11) unsigned DEFAULT '0' NOT NULL,
	registration_fields int(11) unsigned DEFAULT '0' NOT NULL,
	price_options int(11) unsigned DEFAULT '0' NOT NULL,
	image varchar(255) DEFAULT '' NOT NULL,
	files int(11) DEFAULT '0' NOT NULL,
	related int(11) DEFAULT '0' NOT NULL,
	additional_image varchar(255) DEFAULT '' NOT NULL,
	location int(11) unsigned DEFAULT '0' NOT NULL,
	room varchar(255) DEFAULT '' NOT NULL,
	enable_registration tinyint(4) unsigned DEFAULT '0' NOT NULL,
	enable_waitlist tinyint(4) unsigned DEFAULT '0' NOT NULL,
	enable_waitlist_moveup tinyint(4) unsigned DEFAULT '0' NOT NULL,
	registration_startdate int(11) DEFAULT '0' NOT NULL,
	registration_deadline int(11) DEFAULT '0' NOT NULL,
	link varchar(2048) DEFAULT '' NULL,
	top_event tinyint(4) unsigned DEFAULT '0' NOT NULL,
	organisator int(11) unsigned DEFAULT '0' NOT NULL,
	speaker int(11) unsigned DEFAULT '0' NOT NULL,
	notify_admin tinyint(4) unsigned DEFAULT '1' NOT NULL,
	notify_organisator tinyint(4) unsigned DEFAULT '0' NOT NULL,
	enable_cancel tinyint(4) unsigned DEFAULT '0' NOT NULL,
	cancel_deadline int(11) DEFAULT '0' NOT NULL,
	enable_autoconfirm tinyint(4) unsigned DEFAULT '0' NOT NULL,
	unique_email_check tinyint(4) unsigned DEFAULT '0' NOT NULL,
	meta_keywords text,
	meta_description text,
	alternative_title tinytext,
	slug varchar(2048)
);


#
# Table structure for table 'tx_sfeventmgt_domain_model_organisator'
#
CREATE TABLE tx_sfeventmgt_domain_model_organisator (
	name varchar(255) DEFAULT '' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	email_signature text,
	phone varchar(255) DEFAULT '' NOT NULL,
	link varchar(255) DEFAULT '' NOT NULL,
	image varchar(255) DEFAULT '' NOT NULL,
	slug varchar(2048)
);

#
# Table structure for table 'tx_sfeventmgt_domain_model_customnotificationlog'
#
CREATE TABLE tx_sfeventmgt_domain_model_customnotificationlog (
	event int(11) unsigned DEFAULT '0' NOT NULL,
	details text,
	emails_sent int(11) DEFAULT '0' NOT NULL,
	message text,

	KEY event (event)
);

#
# Table structure for table 'tx_sfeventmgt_domain_model_location'
#
CREATE TABLE tx_sfeventmgt_domain_model_location (
	title varchar(255) DEFAULT '' NOT NULL,
	address varchar(255) DEFAULT '' NOT NULL,
	zip varchar(32) DEFAULT '' NOT NULL,
	city varchar(255) DEFAULT '' NOT NULL,
	country varchar(255) DEFAULT '' NOT NULL,
	description text,
	link tinytext,
	longitude decimal(9,6) DEFAULT '0.000000' NOT NULL,
	latitude decimal(9,6) DEFAULT '0.000000' NOT NULL,
	slug varchar(2048)
);

#
# Table structure for table 'tx_sfeventmgt_domain_model_registration'
#
CREATE TABLE tx_sfeventmgt_domain_model_registration (
	event int(11) unsigned DEFAULT '0' NOT NULL,
	main_registration int(11) unsigned DEFAULT '0' NOT NULL,
	language varchar(32) DEFAULT '' NOT NULL,
	firstname varchar(255) DEFAULT '' NOT NULL,
	lastname varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	company varchar(255) DEFAULT '' NOT NULL,
	address varchar(255) DEFAULT '' NOT NULL,
	zip varchar(32) DEFAULT '' NOT NULL,
	city varchar(255) DEFAULT '' NOT NULL,
	country varchar(255) DEFAULT '' NOT NULL,
	phone varchar(255) DEFAULT '' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	ignore_notifications tinyint(4) unsigned DEFAULT '0' NOT NULL,
	gender varchar(1) DEFAULT '' NOT NULL,
	accepttc tinyint(1) unsigned DEFAULT '0' NOT NULL,
	confirmed tinyint(1) unsigned DEFAULT '0' NOT NULL,
	notes mediumtext,
	date_of_birth int(11),
	confirmation_until int(11) unsigned DEFAULT '0' NOT NULL,
	registration_date int(11),
	amount_of_registrations int(11) DEFAULT '1' NOT NULL,
	recaptcha varchar(255) DEFAULT '' NOT NULL,
	fe_user int(11) DEFAULT '0' NOT NULL,
	paid tinyint(1) unsigned DEFAULT '0' NOT NULL,
	paymentmethod varchar(255) DEFAULT '' NOT NULL,
	payment_reference varchar(255) DEFAULT '' NOT NULL,
	waitlist tinyint(1) unsigned DEFAULT '0' NOT NULL,
	field_values int(11) unsigned DEFAULT '0' NOT NULL,

	KEY event (event, waitlist)
);

#
# Table structure for table 'tx_sfeventmgt_domain_model_priceoption'
#
CREATE TABLE tx_sfeventmgt_domain_model_priceoption (
	price double(11,2) DEFAULT '0.00' NOT NULL,
	valid_until int(11) DEFAULT '0' NOT NULL,
	event int(11) unsigned DEFAULT '0' NOT NULL,

	KEY event (event)
);

#
# Table structure for table 'tx_sfeventmgt_domain_model_speaker'
#
CREATE TABLE tx_sfeventmgt_domain_model_speaker (
	name varchar(255) DEFAULT '' NOT NULL,
	job_title varchar(255) DEFAULT '' NOT NULL,
	description text,
	image int(11) unsigned DEFAULT '0' NOT NULL,
	slug varchar(2048),
);

#
# Table structure for table 'tx_sfeventmgt_domain_model_registration_field'
#
CREATE TABLE tx_sfeventmgt_domain_model_registration_field (
	title varchar(255) DEFAULT '' NOT NULL,
	type varchar(255) DEFAULT '' NOT NULL,
	required tinyint(4) unsigned DEFAULT '0' NOT NULL,
	placeholder text,
	default_value text,
	settings text,
	text text,
	datepickermode tinyint(3) DEFAULT '0' NOT NULL,
	event int(11) unsigned DEFAULT '0' NOT NULL,

	KEY event (event)
);

#
# Table structure for table 'tx_sfeventmgt_domain_model_registration_fieldvalue'
#
CREATE TABLE tx_sfeventmgt_domain_model_registration_fieldvalue (
	value text,
	value_type int(11) unsigned DEFAULT '0' NOT NULL,
	field int(11) unsigned DEFAULT '0' NOT NULL,
	registration int(11) unsigned DEFAULT '0' NOT NULL,

	KEY registration (registration)
);

#
# Extend table structure of table 'sys_category'
#
CREATE TABLE sys_category (
    slug varchar(2048)
);

#
# Table structure for table 'sys_file_reference'
#
CREATE TABLE sys_file_reference (
    show_in_views tinyint(4) DEFAULT '0' NOT NULL
);
