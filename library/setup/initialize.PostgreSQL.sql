CREATE TABLE [##_dbPrefix_##]Attachments (
  blogid integer NOT NULL default '0',
  parent integer NOT NULL default '0',
  name varchar(32) NOT NULL default '',
  label varchar(64) NOT NULL default '',
  mime varchar(32) NOT NULL default '',
  size integer NOT NULL default '0',
  width integer NOT NULL default '0',
  height integer NOT NULL default '0',
  attached integer NOT NULL default '0',
  downloads integer NOT NULL default '0',
  enclosure integer NOT NULL default '0',
  PRIMARY KEY  (blogid,name)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]BlogSettings (
  blogid integer NOT NULL default '0',
  name varchar(32) NOT NULL default '',
  value text NOT NULL,
  PRIMARY KEY (blogid, name)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]BlogStatistics (
  blogid integer NOT NULL default '0',
  visits integer NOT NULL default '0',
  PRIMARY KEY  (blogid)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]Categories (
  blogid integer NOT NULL default '0',
  id integer NOT NULL,
  parent integer default NULL,
  name varchar(127) NOT NULL default '',
  priority integer NOT NULL default '0',
  entries integer NOT NULL default '0',
  entriesinlogin integer NOT NULL default '0',
  label varchar(255) NOT NULL default '',
  visibility integer NOT NULL default '2',
  bodyid varchar(20) default NULL,
  PRIMARY KEY (blogid,id)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]Comments (
  blogid integer NOT NULL default '0',
  replier integer default NULL,
  id integer NOT NULL,
  openid varchar(128) NOT NULL default '',
  entry integer NOT NULL default '0',
  parent integer default NULL,
  name varchar(80) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  homepage varchar(80) NOT NULL default '',
  secret integer NOT NULL default '0',
  comment text NOT NULL,
  ip varchar(15) NOT NULL default '',
  written integer NOT NULL default '0',
  isfiltered integer NOT NULL default '0',
  PRIMARY KEY  (blogid, id)
) [##_charset_##];
CREATE INDEX Comments_blogid_idx ON [##_dbPrefix_##]Comments (blogid);
CREATE INDEX Comments_entry_idx ON [##_dbPrefix_##]Comments (entry);
CREATE INDEX Comments_parent_idx ON [##_dbPrefix_##]Comments (parent);
CREATE INDEX Comments_isfiltered_idx ON [##_dbPrefix_##]Comments (isfiltered);
CREATE TABLE [##_dbPrefix_##]CommentsNotified (
  blogid integer NOT NULL default '0',
  replier integer default NULL,
  id integer NOT NULL,
  entry integer NOT NULL default '0',
  parent integer default NULL,
  name varchar(80) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  homepage varchar(80) NOT NULL default '',
  secret integer NOT NULL default '0',
  comment text NOT NULL,
  ip varchar(15) NOT NULL default '',
  written integer NOT NULL default '0',
  modified integer NOT NULL default '0',
  siteid integer NOT NULL default '0',
  isnew integer NOT NULL default '1',
  url varchar(255) NOT NULL default '',
  remoteid integer NOT NULL default '0',
  entrytitle varchar(255) NOT NULL default '',
  entryurl varchar(255) NOT NULL default '',
  PRIMARY KEY  (blogid, id)
) [##_charset_##];
CREATE INDEX CommentsNotified_blogid_idx ON [##_dbPrefix_##]CommentsNotified (blogid);
CREATE INDEX CommentsNotified_entry_idx ON [##_dbPrefix_##]CommentsNotified (entry);
CREATE TABLE [##_dbPrefix_##]CommentsNotifiedQueue (
  blogid integer NOT NULL default '0',
  id integer NOT NULL,
  commentid integer NOT NULL default '0',
  sendstatus integer NOT NULL default '0',
  checkdate integer NOT NULL default '0',
  written integer NOT NULL default '0',
  PRIMARY KEY  (blogid, id)
) [##_charset_##];
CREATE UNIQUE INDEX CommentsNotifiedQueue_commentid_idx ON [##_dbPrefix_##]CommentsNotifiedQueue (commentid);
CREATE TABLE [##_dbPrefix_##]CommentsNotifiedSiteInfo (
  id integer NOT NULL,
  title varchar(255) NOT NULL default '',
  name varchar(255) NOT NULL default '',
  url varchar(255) NOT NULL default '',
  modified integer NOT NULL default '0',
  PRIMARY KEY  (id)
) [##_charset_##];
CREATE UNIQUE INDEX CommentsNotifiedSiteInfo_url_idx ON [##_dbPrefix_##]CommentsNotifiedSiteInfo (url);
CREATE TABLE [##_dbPrefix_##]DailyStatistics (
  blogid integer NOT NULL default '0',
  date integer NOT NULL default '0',
  visits integer NOT NULL default '0',
  PRIMARY KEY  (blogid,date)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]Entries (
  blogid integer NOT NULL default '0',
  userid integer NOT NULL default '0',
  id integer NOT NULL,
  draft integer NOT NULL default '0',
  visibility integer NOT NULL default '0',
  starred integer NOT NULL default '1',
  category integer NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  slogan varchar(255) NOT NULL default '',
  content text NOT NULL,
  contentformatter varchar(32) DEFAULT '' NOT NULL,
  contenteditor varchar(32) DEFAULT '' NOT NULL,
  location varchar(255) NOT NULL default '/',
  password varchar(32) default NULL,
  acceptcomment integer NOT NULL default '1',
  accepttrackback integer NOT NULL default '1',
  published integer NOT NULL default '0',
  created integer NOT NULL default '0',
  modified integer NOT NULL default '0',
  comments integer NOT NULL default '0',
  trackbacks integer NOT NULL default '0',
  PRIMARY KEY (blogid, id, draft, category, published)
) [##_charset_##];
CREATE INDEX Entries_visibility_idx ON [##_dbPrefix_##]Entries (visibility);
CREATE INDEX Entries_userid_idx ON [##_dbPrefix_##]Entries (userid);
CREATE INDEX Entries_published_idx ON [##_dbPrefix_##]Entries (published);
CREATE INDEX Entries_id_category_visibility_idx ON [##_dbPrefix_##]Entries (id, category, visibility);
CREATE INDEX Entries_blogid_published_idx ON [##_dbPrefix_##]Entries (blogid, published);
CREATE TABLE [##_dbPrefix_##]EntriesArchive (
  blogid integer NOT NULL default '0',
  userid integer NOT NULL default '0',
  id integer NOT NULL,
  visibility integer NOT NULL default '0',
  category integer NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  slogan varchar(255) NOT NULL default '',
  content text NOT NULL,
  contentformatter varchar(32) DEFAULT '' NOT NULL,
  contenteditor varchar(32) DEFAULT '' NOT NULL,
  location varchar(255) NOT NULL default '/',
  password varchar(32) default NULL,
  created integer NOT NULL default '0',
  PRIMARY KEY (blogid, id, created)
) [##_charset_##];
CREATE INDEX EntriesArchive_visibility_idx ON [##_dbPrefix_##]EntriesArchive (visibility);
CREATE INDEX EntriesArchive_blogid__id_idx ON [##_dbPrefix_##]EntriesArchive (blogid, id);
CREATE INDEX EntriesArchive_userid_blogid_idx ON [##_dbPrefix_##]EntriesArchive (userid, blogid);
CREATE TABLE [##_dbPrefix_##]FeedGroupRelations (
  blogid integer NOT NULL default '0',
  feed integer NOT NULL default '0',
  groupid integer NOT NULL default '0',
  PRIMARY KEY  (blogid,feed,groupid)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]FeedGroups (
  blogid integer NOT NULL default '0',
  id integer NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  PRIMARY KEY  (blogid,id)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]FeedItems (
  id integer NOT NULL default 1,
  feed integer NOT NULL default '0',
  author varchar(255) NOT NULL default '',
  permalink varchar(255) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  description text NOT NULL,
  tags varchar(255) NOT NULL default '',
  enclosure varchar(255) NOT NULL default '',
  written integer NOT NULL default '0',
  PRIMARY KEY  (id)
) [##_charset_##];
CREATE INDEX FeedItems_feed_idx ON [##_dbPrefix_##]FeedItems (feed);
CREATE INDEX FeedItems_written_idx ON [##_dbPrefix_##]FeedItems (written);
CREATE INDEX FeedItems_permalink_idx ON [##_dbPrefix_##]FeedItems (permalink);
CREATE TABLE [##_dbPrefix_##]FeedReads (
  blogid integer NOT NULL default '0',
  item integer NOT NULL default '0',
  PRIMARY KEY  (blogid,item)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]FeedSettings (
  blogid integer NOT NULL default '0',
  updatecycle integer NOT NULL default '120',
  feedlife integer NOT NULL default '30',
  loadimage integer NOT NULL default '1',
  allowscript integer NOT NULL default '2',
  newwindow integer NOT NULL default '1',
  PRIMARY KEY  (blogid)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]FeedStarred (
  blogid integer NOT NULL default '0',
  item integer NOT NULL default '0',
  PRIMARY KEY  (blogid,item)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]Feeds (
  id integer NOT NULL default 1,
  xmlurl varchar(255) NOT NULL default '',
  blogurl varchar(255) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  language varchar(5) NOT NULL default 'en-US',
  modified integer NOT NULL default '0',
  PRIMARY KEY  (id)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]Filters (
  id integer NOT NULL default 1,
  blogid integer NOT NULL default '0',
  type varchar(11) NOT NULL default 'content',
  pattern varchar(255) NOT NULL default '',
  PRIMARY KEY (id)
) [##_charset_##];
CREATE UNIQUE INDEX Filters_blogid_type_pattern_idx ON [##_dbPrefix_##]Filters (blogid, type, pattern);
CREATE TABLE [##_dbPrefix_##]Links (
  pid integer NOT NULL default '0',
  blogid integer NOT NULL default '0',
  id integer NOT NULL default '0',
  category integer NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  url varchar(255) NOT NULL default '',
  rss varchar(255) NOT NULL default '',
  written integer NOT NULL default '0',
  visibility integer NOT NULL default '2',
  xfn varchar(128) NOT NULL default '',
  PRIMARY KEY (pid)
) [##_charset_##];
CREATE UNIQUE INDEX Links_blogid_url_idx ON [##_dbPrefix_##]Links (blogid, url);
CREATE TABLE [##_dbPrefix_##]LinkCategories (
  pid integer NOT NULL default '0',
  blogid integer NOT NULL default '0',
  id integer NOT NULL default '0',
  name varchar(128) NOT NULL,
  priority integer NOT NULL default '0',
  visibility integer NOT NULL default '2',
  PRIMARY KEY (pid)
) [##_charset_##];
CREATE UNIQUE INDEX LinkCategories_blogid_id_idx ON [##_dbPrefix_##]LinkCategories (blogid, id);
CREATE TABLE [##_dbPrefix_##]OpenIDUsers (
  blogid integer NOT NULL default '0',
  openid varchar(128) NOT NULL,
  delegatedid varchar(128) default NULL,
  firstlogin integer default NULL,
  lastlogin integer default NULL,
  logincount integer default NULL,
  data text,
  PRIMARY KEY  (blogid,openid)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]PageCacheLog (
  blogid integer NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  value text NOT NULL,
  PRIMARY KEY (blogid,name)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]Plugins (
  blogid integer NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  settings text,
  PRIMARY KEY  (blogid,name)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]RefererLogs (
  blogid integer NOT NULL default '0',
  host varchar(64) NOT NULL default '',
  url varchar(255) NOT NULL default '',
  referred integer NOT NULL default '0'
) [##_charset_##];
CREATE INDEX RefererLogs_blogid_referred_idx ON [##_dbPrefix_##]RefererLogs (blogid, referred);
CREATE TABLE [##_dbPrefix_##]RefererStatistics (
  blogid integer NOT NULL default '0',
  host varchar(64) NOT NULL default '',
  count integer NOT NULL default '0',
  PRIMARY KEY  (blogid,host)
) [##_charset_##];
CREATE INDEX RefererStatistics_blogid_count_idx ON [##_dbPrefix_##]RefererStatistics (blogid, count);
CREATE TABLE [##_dbPrefix_##]ReservedWords (
  word varchar(16) NOT NULL default '',
  PRIMARY KEY  (word)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]ServiceSettings (
  name varchar(32) NOT NULL default '',
  value text NOT NULL,
  PRIMARY KEY  (name)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]SessionVisits (
  id varchar(32) NOT NULL default '',
  address varchar(15) NOT NULL default '',
  blogid integer NOT NULL default '0',
  PRIMARY KEY  (id,address,blogid)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]Sessions (
  id varchar(32) NOT NULL default '',
  address varchar(15) NOT NULL default '',
  userid integer default NULL,
  preexistence integer default NULL,
  data text default NULL,
  server varchar(64) NOT NULL default '',
  request varchar(255) NOT NULL default '',
  referer varchar(255) NOT NULL default '',
  timer float NOT NULL default '0',
  created integer NOT NULL default '0',
  updated integer NOT NULL default '0',
  PRIMARY KEY  (id,address)
) [##_charset_##];
CREATE INDEX Sessions_updated_idx ON [##_dbPrefix_##]Sessions (updated);
CREATE TABLE [##_dbPrefix_##]SkinSettings (
  blogid integer NOT NULL default '0',
  skin varchar(32) NOT NULL default 'coolant',
  entriesonrecent integer NOT NULL default '10',
  commentsonrecent integer NOT NULL default '10',
  commentsonguestbook integer NOT NULL default '5',
  archivesonpage integer NOT NULL default '5',
  tagsontagbox integer NOT NULL default '10',
  tagboxalign integer NOT NULL default '1',
  trackbacksonrecent integer NOT NULL default '5',
  expandcomment integer NOT NULL default '1',
  expandtrackback integer NOT NULL default '1',
  recentnoticelength integer NOT NULL default '30',
  recententrylength integer NOT NULL default '30',
  recentcommentlength integer NOT NULL default '30',
  recenttrackbacklength integer NOT NULL default '30',
  linklength integer NOT NULL default '30',
  showlistoncategory integer NOT NULL default '1',
  showlistonarchive integer NOT NULL default '1',
  showlistontag integer NOT NULL default '1',
  showlistonauthor integer NOT NULL default '1',
  showlistonsearch integer NOT NULL default '1',
  tree varchar(32) NOT NULL default 'base',
  colorontree varchar(6) NOT NULL default '000000',
  bgcolorontree varchar(6) NOT NULL default '',
  activecolorontree varchar(6) NOT NULL default 'FFFFFF',
  activebgcolorontree varchar(6) NOT NULL default '00ADEF',
  labellengthontree integer NOT NULL default '30',
  showvalueontree integer NOT NULL default '1',
  PRIMARY KEY  (blogid)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]TagRelations (
  blogid integer NOT NULL default '0',
  tag integer NOT NULL default '0',
  entry integer NOT NULL default '0',
  PRIMARY KEY  (blogid, tag, entry)
) [##_charset_##];
CREATE INDEX TagRelations_blogid_idx ON [##_dbPrefix_##]TagRelations (blogid);
CREATE TABLE [##_dbPrefix_##]Tags (
  id integer NOT NULL default 1,
  name varchar(255) NOT NULL default '',
  PRIMARY KEY (id)
) [##_charset_##];
CREATE UNIQUE INDEX Tags_name_idx ON [##_dbPrefix_##]Tags (name);
CREATE TABLE [##_dbPrefix_##]TrackbackLogs (
  blogid integer NOT NULL default '0',
  id integer NOT NULL,
  entry integer NOT NULL default '0',
  url varchar(255) NOT NULL default '',
  written integer NOT NULL default '0',
  PRIMARY KEY  (blogid, entry, id)
) [##_charset_##];
CREATE UNIQUE INDEX TrackbackLogs_blogid_id_idx ON [##_dbPrefix_##]TrackbackLogs (blogid, id);
CREATE TABLE [##_dbPrefix_##]Trackbacks (
  id integer NOT NULL,
  blogid integer NOT NULL default '0',
  entry integer NOT NULL default '0',
  url varchar(255) NOT NULL default '',
  writer integer default NULL,
  site varchar(255) NOT NULL default '',
  subject varchar(255) NOT NULL default '',
  excerpt varchar(255) NOT NULL default '',
  ip varchar(15) NOT NULL default '',
  written integer NOT NULL default '0',
  isfiltered integer NOT NULL default '0',
  PRIMARY KEY (blogid, id)
) [##_charset_##];
CREATE INDEX Trackbacks_isfiltered_idx ON [##_dbPrefix_##]Trackbacks (isfiltered);
CREATE INDEX Trackbacks_blogid_isfiltered_written_idx ON [##_dbPrefix_##]Trackbacks (blogid, isfiltered, written);
CREATE TABLE [##_dbPrefix_##]Users (
  userid integer NOT NULL default 1,
  loginid varchar(64) NOT NULL default '',
  password varchar(32) default NULL,
  name varchar(32) NOT NULL default '',
  created integer NOT NULL default '0',
  lastlogin integer NOT NULL default '0',
  host integer NOT NULL default '0',
  PRIMARY KEY  (userid)
) [##_charset_##];
CREATE UNIQUE INDEX Users_loginid_idx ON [##_dbPrefix_##]Users (loginid);
CREATE UNIQUE INDEX Users_name_idx ON [##_dbPrefix_##]Users (name);
CREATE TABLE [##_dbPrefix_##]UserSettings (
  userid integer NOT NULL default '0',
  name varchar(32) NOT NULL default '',
  value text NOT NULL,
  PRIMARY KEY (userid,name)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]XMLRPCPingSettings (
  blogid integer NOT NULL default 0,
  url varchar(255) NOT NULL default '',
  type varchar(32) NOT NULL default 'xmlrpc',
  PRIMARY KEY (blogid)
) [##_charset_##];
CREATE TABLE [##_dbPrefix_##]Teamblog (
  blogid integer NOT NULL default 1,
  userid integer NOT NULL default 1,
  acl integer NOT NULL default 0,
  created integer NOT NULL default 0,
  lastlogin integer NOT NULL default 0,
  PRIMARY KEY (blogid,userid)
) [##_charset_##];
