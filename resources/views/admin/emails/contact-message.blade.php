<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>رسالة تواصل جديدة</title>
</head>
<body style="margin:0; padding:0; background:#f4f1ea; font-family: Tahoma, Arial, sans-serif;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f1ea; padding:32px 16px;">
    <tr>
      <td align="center">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:480px; background:#ffffff; border-radius:14px; overflow:hidden; border:1px solid #eae4d6;">
          <tr>
            <td style="background:#123a3f; padding:24px 28px;">
              <span style="color:#ffffff; font-size:18px; font-weight:700;">نوتابوكس — رسالة تواصل جديدة</span>
            </td>
          </tr>
          <tr>
            <td style="padding:28px;">
              <p style="margin:0 0 4px; font-size:13px; color:#8a9490;">الاسم</p>
              <p style="margin:0 0 16px; font-size:15px; color:#1f2d2b;">{{ $contactMessage->name }}</p>

              <p style="margin:0 0 4px; font-size:13px; color:#8a9490;">البريد الإلكتروني</p>
              <p style="margin:0 0 16px; font-size:15px; color:#1f2d2b;">
                <a href="mailto:{{ $contactMessage->email }}" style="color:#123a3f;">{{ $contactMessage->email }}</a>
              </p>

              <p style="margin:0 0 4px; font-size:13px; color:#8a9490;">الموضوع</p>
              <p style="margin:0 0 16px; font-size:15px; color:#1f2d2b;">{{ $contactMessage->subject }}</p>

              <p style="margin:0 0 4px; font-size:13px; color:#8a9490;">الرسالة</p>
              <p style="margin:0; font-size:14px; color:#1f2d2b; line-height:1.8; white-space:pre-wrap;">{{ $contactMessage->message }}</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
