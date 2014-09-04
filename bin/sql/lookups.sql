insert into deals (name) values ('Weekly Ad'), ('Sale'), ('Manager\'s Special');
insert into quantities (name) values ('Lots'), ('Enough'), ('Little'), ('Almost Gone');
insert into conditions (name) values ('Ripe'), ('Good'), ('Unripe'), ('Over Ripe'), ('Bruised/Damaged'), ('Old');
insert into types (name, plu) values ('Organic',90000), ('Grassfed', 0), ('Conventional', 0), ('Local', 0);
insert into amounts (unit, amount, amount_u_id, abbreviation, symbol) values
('pound', '', '', 'lb.', '#'),
('each', '', '', 'ea.', ''),
('for', 3, 2, ,'', '/'),
('bag', 3, 1, '', ''), 
('bag', 5, 1, '', ''), 
('for', 2, 2, ,'', '/'),
('for', 4, 2, ,'', '/'),
('for', 5, 2, ,'', '/'),
('for', 10, 2, ,'', '/'),
('bag', 10, 1, '', ''), 
('bag', 1, 1, '', '');
