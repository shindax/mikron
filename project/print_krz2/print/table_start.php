<?php
echo "
	<tr class='top'>
		<td colspan='3'>������������ ������ ������  �</td>
		<td colspan='3'>".$zaknum."</td>
		<td>����  �������</td>
		<td>".FVal($krz,"db_krz2","DATE_START")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>���������</td>
		<td colspan='5'>".FVal($krz,"db_krz2","ID_users")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>����������</td>
		<td colspan='5'>".FVal($krz,"db_krz2","ID_clients")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first' style='background : #ddd;'>������������ �������</td>
		<td colspan='4' style='background : #ddd;'>� ������� �������</td>
		<td style='background : #ddd;'>����������</td>
	</tr>
	<tr>
		<td colspan='3'>".FVal($det,"db_krz2det","NAME")."</td>
		<td colspan='4'>".FVal($det,"db_krz2det","OBOZ")."</td>
		<td><i>";
IVal("count", $count);
	echo "</i></td>
	</tr>
	<tr>
		<td colspan='3' class='first'>��������� ���������</td>
		<td colspan='5'>".FVal($krz,"db_krz2","ID_postavshik")."</td>
	</tr>
	
	<tr>
		<td colspan='3' class='first'>����������� ����� ��������</td>
		<td colspan='5'>".FVal($krz,"db_krz2","DATE_PLAN")."</td>
	</tr>
		<tr>
		<td colspan='3' class='first'>���� ����� ������ c ���, ���</td>
		<td colspan='5'>";
RVal("price_all", $price_all_nds);
	echo "</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>���� �/� �� ������, ���</td>
		<td colspan='5'>";
RVal("price", $price);
	echo "</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>�������</td>
		<td colspan='5'>".FVal($krz,"db_krz2","EXPERT")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>���������� ��������</td>
		<td colspan='5'>".FVal($krz,"db_krz2","MORE_EXPERT")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>����������</td>
		<td colspan='5'>".FVal($krz,"db_krz2","MORE")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>����������</td>
		<td colspan='5'>".FVal($krz,"db_krz2","MORE2")."</td>
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