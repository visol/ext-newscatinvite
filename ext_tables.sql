#
# Table structure for table 'tx_newscatinvite_domain_model_invitation'
#
CREATE TABLE tx_newscatinvite_domain_model_invitation (
	status int(11) DEFAULT '0' NOT NULL,
	sent tinyint(1) unsigned DEFAULT '0' NOT NULL,
	category int(11) unsigned DEFAULT '0' NOT NULL,
	news int(11) unsigned DEFAULT '0' NOT NULL,
	approving_beuser int(11) unsigned DEFAULT '0' NOT NULL,
);

#
# Table structure for table 'tx_news_domain_model_news'
#
CREATE TABLE tx_news_domain_model_news (
	invitations int(11) DEFAULT '0' NOT NULL,
);
