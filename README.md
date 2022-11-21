# School Management System
It is a web-based system that allows students to manage their information and check their academic records. Also, allows staff, and instructors to put their students' records and export records as pdf, excel and send emails, etc...

## Table of contents
- [Tech stacks](#tech-stacks)
- [System Flow](#system-flow)
- [Database](#database)
- [Get Started](#get-started)

## Tech stacks
  1. CodeIgniter(https://codeigniter.com/)<br/>
    CodeIgniter is an Application Development Framework - a toolkit - for people who build web sites using PHP. Its goal is to enable you to develop projects much faster than you could if you were writing code from scratch, by providing a rich set of libraries for commonly needed tasks, as well as a simple interface and logical structure to access these libraries.
    <img src="https://user-images.githubusercontent.com/25275753/202945728-da101163-0113-4ac4-b348-b5e2ff7f6b25.png"  width="400" height="300"/>
  2. Dhtmlx(https://dhtmlx.com/)<br/>
  3. Ckeditor(https://ckeditor.com/ckeditor-4/)<br/>
  4. Bootstrap(`./assets`)<br/>
  5. Jquery

## System Flow
The flow differs depending on the role of the account, but for the highest permissioned admin account, the flow is as follows:
<img src="https://user-images.githubusercontent.com/25275753/202990886-32765dc7-26e2-45b8-bf14-27485e1a5f96.png"  height="500px"/>
## Database 
![MySQL](https://img.shields.io/badge/mysql-%2300000f.svg?style=for-the-badge&logo=mysql&logoColor=white)<br/>
Use sql file `./Database.sql` to create database tables. The database consists of a total of <b>42 tables</b>. You can check the detailed structure in the sql file.
here is an example of tables connected by the most relations.<br/>
<img src="https://user-images.githubusercontent.com/25275753/202981269-54de4e9e-ec1c-4551-8df3-322a55caf1eb.png"  width="80%" height="100%"/>
#### Query example
```sql
## To get weekly attendance record per student.
SELECT AT
    .students_no,
    AT.fullname,
    MAX(
        IF(
            WEEKDAY(AT.attendance_day) = 0,
            CONCAT(AT.marks, '|', AT.no),
            ''
        )
    ) AS mon,
    MAX(
        IF(
            WEEKDAY(AT.attendance_day) = 1,
            CONCAT(AT.marks, '|', AT.no),
            ''
        )
    ) AS tue,
    MAX(
        IF(
            WEEKDAY(AT.attendance_day) = 2,
            CONCAT(AT.marks, '|', AT.no),
            ''
        )
    ) AS wes,
    MAX(
        IF(
            WEEKDAY(AT.attendance_day) = 3,
            CONCAT(AT.marks, '|', AT.no),
            ''
        )
    ) AS thu,
    MAX(
        IF(
            WEEKDAY(AT.attendance_day) = 4,
            CONCAT(AT.marks, '|', AT.no),
            ''
        )
    ) AS fri,
    MAX(
        IF(
            WEEKDAY(AT.attendance_day) = 5,
            CONCAT(AT.marks, '|', AT.no),
            ''
        )
    ) AS sat,
    MAX(
        IF(
            WEEKDAY(AT.attendance_day) = 6,
            CONCAT(AT.marks, '|', AT.no),
            ''
        )
    ) AS sun
FROM
    (
    SELECT
        ST.no,
        SS.students_no,
        CONCAT(SS.lastname, ', ', SS.firstname) AS fullname,
        ST.attendance_day,
        ST.marks
    FROM
        `ali_roster` AS SS
    LEFT JOIN(
        SELECT NO,
            student_no,
            attendance_day,
            marks,
            class_no
        FROM
            `ali_attendance_new`
        WHERE
            attendance_day BETWEEN '" . $monv . "' and '" . $sunv . "'
    ) AS ST
ON
    SS.students_no = ST.student_no AND SS.class_no = ST.class_no
INNER JOIN ali_students AS bb
ON
    SS.students_no = bb.students_no
WHERE
    bb.progress = 'r' AND SS.class_no = " . $params["classno"] . "
) AS AT
GROUP BY AT
    .fullname,
    AT.students_no;
```
```sql
## To get assignment information(Class name, Assignment Name, Due Date, Category, Attach File1, Attach File2, etc..)
SELECT
    sc.name AS classname,
    sa.class_no,
    sa.no,
    sa.assigncat_no,
    sa.points,
    sa.name,
    sa.duedate,
    sa.description,
    sa.isview,
    sa.writer,
    sa.regdate
FROM
    ali_assignments AS sa
INNER JOIN ali_class AS sc
ON
    sa.class_no = sc.no
INNER JOIN ali_assign_cate AS st
ON
    sa.assigncat_no = st.no
WHERE
    sa.no = " . $params['id'] . "
ORDER BY
    sa.no
DESC;
```


## Get Started
- Set up MySQL database
- (Optional?) Run `composer install`
- set database connection values in `application/config/database.php` `application/libraries/Alidb.php`
- Run `./server.sh` to start the server
- Go to `http://localhost:8001/` and you should see an login page
