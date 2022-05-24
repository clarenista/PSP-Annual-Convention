<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
</head>
<body style="height: 100% !important; width: 100% !important; background-color: #f4f5f9; margin: 0; padding: 0;">
    <table border="0" cellpadding="0" cellspacing="0" align="center" style="font-size: 13px;color:#39394d;font-family: Arial;background-color: #f4f5f9;
        width: 700px; height:420px;padding-bottom: 10px; background-size: contain; margin-top: 150px;
        background-image: url('https://psp71-s3bucket.s3.ap-southeast-1.amazonaws.com/webinarBGrev1.jpg');" border="0"
        cellpadding="0" cellspacing="0" align="center">
        <#if joinUrl??>
            <tr>
                <td colspan="2" style="padding-top: 5px;line-height: 34px; padding-bottom: 8px; text-align: center;">
                    <a type="button" href="https://convention.psp.com.ph/" target="_blank"
                    style="text-decoration:none;display: inline-block;font-size: 20px;font-weight: 400; margin-top:320px;
                    color: #fff;background: #279a1b;border-radius: 14px;line-height: 40px;display: inline-block;padding: 0 15px;">
                    Enter Convention
                </a><br>
                    <a type="button" href="${joinUrl}" target="_blank"
                        style="text-decoration:none;display: inline-block;font-size: 9px;font-weight: 400;
                        margin-top: 10px;
                        color: #fff;background: #2d8cff;border-radius: 14px;line-height: 24px;display: inline-block;padding: 0 8px;">
                        Zoom Webinar
                    </a>
                </td>
            </tr>
        </#if>
    </table>
    <#if (customTextHeader!"") !="">
        ${(customTextHeader!"")?html?replace("\n","<br>")}
    </#if>
    <#if (customTextFooter!"") !="">
        ${(customTextFooter!"")?html?replace("\n","<br>")}
    </#if>
</body>

</html>
