<?php
echo "
	<tr class='top'>
		<td colspan='3'>������������ ������ ������  �</td>
		<td colspan='3'>".$zaknum."</td>
		<td>����  �������</td>
		<td>".FVal($krz,"db_krz","DATE_START")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>���������</td>
		<td colspan='5'>".FVal($krz,"db_krz","ID_users")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>����������</td>
		<td colspan='5'>".FVal($krz,"db_krz","ID_clients")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first' style='background : #ddd;'>������������ �������</td>
		<td colspan='4' style='background : #ddd;'>� ������� �������</td>
		<td style='background : #ddd;'>����������</td>
	</tr>
	<tr>
		<td colspan='3'>".FVal($det,"db_krzdet","NAME")."</td>
		<td colspan='4'>".FVal($det,"db_krzdet","OBOZ")."</td>
		<td><i>";
IVal("count", $count);
	echo "</i></td>
	</tr>
	<tr>
		<td colspan='3' class='first'>��������� ���������</td>
		<td colspan='5'>".FVal($krz,"db_krz","ID_postavshik")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>����������� ����������</td>
		<td colspan='5'>".FVal($krz,"db_krz","SERIYA")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>����������� ����� ��������</td>
		<td colspan='5'>".FVal($krz,"db_krz","DATE_PLAN")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>����������� ���. ���������</td>
		<td colspan='5'>".FVal($krz,"db_krz","DOCS")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>���� �/� �� ������, ���</td>
		<td colspan='5'>";
RVal("price", $price);
	echo "</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>�������</td>
		<td colspan='5'>".FVal($krz,"db_krz","EXPERT")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>���������� ��������</td>
		<td colspan='5'>".FVal($krz,"db_krz","MORE_EXPERT")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>����������</td>
		<td colspan='5'>".FVal($krz,"db_krz","MORE")."</td>
	</tr>
	<tr class='center'>
		<td>�</td>
		<td>����������</td>
		<td>���� ��.<br>��� ��� ���, ���</td>
		<td>��. ���.</td>
		<td>�� ��.</td>
		<td>�����</td>
		<td>��� ��� ��� �� ��.</td>
		<td>��� ��� ��� �����</td>
	</tr>
";
?>