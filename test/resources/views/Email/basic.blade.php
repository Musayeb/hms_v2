<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>HMS Email Approval</title>
</head>
<body style="margin:0px; background: #f8f8f8; ">
<div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
  <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
      <tbody>
        <tr>
          <td style="vertical-align: top; padding-bottom:30px;" align="center">
            <a href="{{url('/')}}" target="_blank">
            <img src="{{url('public/assets/images/brand/logo-white.png')}}" class="header-brand-img" alt="">
          </td>
        </tr>
      </tbody>
    </table>
    <div style="padding: 40px; background: #fff;">
      <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
        <tbody>
          <tr>
            <td><b>Dear {{$details['Receiver']}} ,</b>
              <p> This email is to inform you that you have pending approval for {{$details['relate_to']}} . Login into your account for more details</p>
              <a href="{{$details['link']}} " style="display: inline-block; padding: 11px 30px; margin: 20px 0px 30px; font-size: 15px; 
              color: #fff; background: #00c0c8; border-radius: 60px; text-decoration:none;">Action</a>
              <p>Note: This email is generated from the system automatically. Please do not reply to this.</p>
              <b>- Thank you <br>{{$details['sender']}} </b> </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div style="text-align: center; font-size: 12px; color: #b2b2b5; margin-top: 20px">
      <p> Copyright © 2021 HMS. Powered by Sarey Solution <br>
    </div>
  </div>
</div>
</body>
</html>
