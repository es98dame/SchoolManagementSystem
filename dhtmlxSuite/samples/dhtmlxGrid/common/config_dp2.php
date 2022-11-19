<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 12/8/2015
 * Time: 9:56 PM
 */

$HostName = "sql549.main-hosting.eu";							    // mysql ���� ȣ��Ʈ��
$UserName = "u866174927_es98dame";								// mysql ���� ������
$PasswdName = "chzhclq312A";								// mysql ���� �н�����
$DatabaseName = "u866174927_alidb";

$link = mysqli_connect($HostName, $UserName, $PasswdName,$DatabaseName);
if (!$link) {
    die('Connect Error: ' . mysqli_connect_error());
}

?>