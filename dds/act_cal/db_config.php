<?php
error_reporting( E_ALL );
error_reporting( 0 );

switch( gethostname() )
{  
case 'Iktorn' : // ��� ������
    // ����� ������� MySQL 
  $dblocation = "localhost"; 
  // ��� ���� ������ �� �������� ��� ��������� ������ 
  $dbname = "okbdb"; 
  // ��� ������������ ���� ������ 
  $dbuser = "root"; 
  // � ��� ������ 
  $dbpasswd = ""; 
 
  // ������������� ���������� � �������� MySQL 
  $mysqli = new mysqli($dblocation, $dbuser, $dbpasswd, $dbname); 
  
  if ( mysqli_connect_errno() ) 
        exit("������ ��������� ����������.$mysqli->error"); 
 
  // ������������� ��������� ����������. ������� ������� �� ���������, 
  // � ������� ������ ����� ������������ MySQL-������� 
  $mysqli->query("SET NAMES 'cp1251'"); 
  break ;

case 'Programm-001' : 
  // ����� ������� MySQL 
  $dblocation = "localhost"; 
  // ��� ���� ������ �� �������� ��� ��������� ������ 
  $dbname = "okbnew"; 
  // ��� ������������ ���� ������ 
  $dbuser = "root"; 
  // � ��� ������ 
  $dbpasswd = "150182"; 
 
  // ������������� ���������� � �������� MySQL 
  $mysqli = new mysqli($dblocation, $dbuser, $dbpasswd, $dbname); 
  
  if ( mysqli_connect_errno() ) 
        exit("������ ��������� ����������.$mysqli->error"); 
 
  // ������������� ��������� ����������. ������� ������� �� ���������, 
  // � ������� ������ ����� ������������ MySQL-������� 
  $mysqli->query("SET NAMES 'cp1251'"); 
  
  break ;
}
?>
