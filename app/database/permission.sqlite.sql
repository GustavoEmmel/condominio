CREATE TABLE system_user (
    id INTEGER PRIMARY KEY NOT NULL,
    name varchar(100),
    login varchar(100),
    password varchar(100),
    email varchar(100),
    frontpage_id int,
    FOREIGN KEY(frontpage_id) REFERENCES system_program(id));

CREATE TABLE system_group (
    id INTEGER PRIMARY KEY NOT NULL,
    name varchar(100));

CREATE TABLE system_program (
    id INTEGER PRIMARY KEY NOT NULL,
    name varchar(100),
    controller varchar(100));

CREATE TABLE system_user_group (
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id int,
    system_group_id int,
    FOREIGN KEY(system_user_id) REFERENCES system_user(id),
    FOREIGN KEY(system_group_id) REFERENCES system_group(id));

CREATE TABLE system_group_program (
    id INTEGER PRIMARY KEY NOT NULL,
    system_group_id int,
    system_program_id int,
    FOREIGN KEY(system_group_id) REFERENCES system_group(id),
    FOREIGN KEY(system_program_id) REFERENCES system_program(id));

CREATE TABLE system_user_program (
    id INTEGER PRIMARY KEY NOT NULL,
    system_user_id int,
    system_program_id int,
    FOREIGN KEY(system_user_id) REFERENCES system_user(id),
    FOREIGN KEY(system_program_id) REFERENCES system_program(id));

--- data
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program),'System Group Form','SystemGroupForm');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program),'System Group List','SystemGroupList');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program),'System Program Form','SystemProgramForm');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program),'System Program List','SystemProgramList');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program),'System User Form','SystemUserForm');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program),'System User List','SystemUserList');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program),'Common Page','CommonPage');

INSERT INTO system_group VALUES(1,'Admin');
INSERT INTO system_group VALUES(2,'Public');

INSERT INTO system_user VALUES(1,'Administrator','admin','21232f297a57a5a743894a0e4a801fc3','admin@admin.net', 7);
INSERT INTO system_user VALUES(2,'User','user','ee11cbb19052e40b07aac0ca060c23ee','user@user.net', 7);

INSERT INTO system_user_group VALUES(1,1,1);
INSERT INTO system_user_group VALUES(2,2,2);
INSERT INTO system_user_group VALUES(3,1,2);

INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program), 1,
                                        (select id from system_program where controller='SystemGroupForm'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program), 1,
                                        (select id from system_program where controller='SystemGroupList'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program), 1,
                                        (select id from system_program where controller='SystemProgramForm'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program), 1,
                                        (select id from system_program where controller='SystemProgramList'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program), 1,
                                        (select id from system_program where controller='SystemUserForm'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program), 1,
                                        (select id from system_program where controller='SystemUserList'));
INSERT INTO system_user_program VALUES((select coalesce(max(id),0)+1 from system_user_program), 2,
                                       (select id from system_program where controller='CommonPage'));
                                       
--- new programs of 3.0.0
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program),'System PHP Info','SystemPHPInfoView');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program),'System ChangeLog View','SystemChangeLogView');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program),'Welcome View','WelcomeView');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program),'System Sql Log','SystemSqlLogList');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program),'System Profile View','SystemProfileView');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program),'System Profile Form','SystemProfileForm');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program),'System SQL Panel','SystemSQLPanel');
INSERT INTO system_program VALUES((select coalesce(max(id),0)+1 from system_program),'System Access Log','SystemAccessLogList');

INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program), 1,
                                        (select id from system_program where controller='SystemPHPInfoView'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program), 1,
                                        (select id from system_program where controller='SystemChangeLogView'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program), 1,
                                        (select id from system_program where controller='SystemSqlLogList'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program), 1,
                                        (select id from system_program where controller='SystemSQLPanel'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program), 1,
                                        (select id from system_program where controller='SystemAccessLogList'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program), 2,
                                        (select id from system_program where controller='WelcomeView'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program), 2,
                                        (select id from system_program where controller='SystemProfileView'));
INSERT INTO system_group_program VALUES((select coalesce(max(id),0)+1 from system_group_program), 2,
                                        (select id from system_program where controller='SystemProfileForm'));
                                        
UPDATE system_user set frontpage_id = (select id from system_program where controller='WelcomeView') where id=1;
