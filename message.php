<?php
//sprawdzimy czy zalogowany

//pobierz wiadomość z bazy na podstawie $_GET['id']
//JOIN users aby pobrać nazwę nadawcy i jeszcze raz JOIN users żeby pobrać nazwę odbiorcy

//SELECT * FROM Message m
//JOIN users ua ON m.author_id = ua.id
//JOIN users ur ON ur.id = m.recipient
//WHERE m.id = $_GET['id']
//        - mysql_real_escape_string();
