<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
    </head>
    <body>
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td align="left">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">
                        <tr>
                            <td><p><font><em><strong>Здравствуйте!</strong></em></font></p></td>
                        </tr>
                        <tr>
                            <td colspan="3" align="left">
                                <table cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">
                                    <tr>
                                        <td>
                                            <p>
                                                <?= date("d.m.Y H:i:s"); ?> Вами была запрошена процедура смены пароля. Логин <?=$login;?>. Новый пароль <?= $password; ?>. Данный пароль является одноразовым и служит для смены пароля на Ваш. После введения данного пароля в форме авторизации на сайте у вас отобразиться форма смены пароля.<br />
                                                Если вы не запрашивали процедуру смены пароля, обратитесь в отдел IT ОАО 'Нэфис Косметикс'<br />
                                                Не отвечайте на это письмо, данный пароль сгенерирован и выслан Вам автоматически, в открытом виде на серверах ОАО 'Нэфис Косметикс' пароль не хранится.
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">
                        <tr>
                            <td>
                                <font><strong>С уважением</strong><br />
                                    <strong>отдел IT</strong><br />
                                    <strong>ОАО "Нэфис Косметикс"</strong><br />
                                    <em><a href="http://sd.ncsd.ru">http://sd.ncsd.ru</a></em><br />
                                    <em><a href="mailto:it@ncsd.ru">it@ncsd.ru</a></em>
                                </font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table> 
    </body>
</html>