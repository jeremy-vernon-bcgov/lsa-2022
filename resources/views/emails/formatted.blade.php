<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Long Service Awards: @yield('title')</title>
  <style type="text/css">

  /* Take care of image borders and formatting */

  img {
    max-width: 600px;
    outline: none;
    text-decoration: none;
    -ms-interpolation-mode: bicubic;
  }

  a {
    border: 0;
    outline: none;
  }

  a img {
    border: none;
  }

  p {
    text-align: center;
  }

  /* General styling */

  table, td, h1, h2, h3  {
    font-family: Helvetica, Arial, sans-serif;
    font-weight: 400;
    border-radius: 8px;
    text-align: center;
  }

  td, th {
    font-size: 13px;
    line-height: 19px;
    text-align: left;
    vertical-align: top;
  }

  body {
    -webkit-font-smoothing:antialiased;
    -webkit-text-size-adjust:none;
    width: 100%;
    height: 100%;
    color: #37302d;
    background: #ffffff;
    text-align: center;
  }

  table {
    border-collapse: collapse !important;
  }


  h1, h2, h3, h4 {
    padding: 10px 0;
    margin: 0;
    color: #444444;
    font-weight: 400;
    line-height: 110%;
    text-align: center;
  }

  h1 {
    font-size: 35px;
  }

  h2 {
    font-size: 30px;
  }

  h3 {
    font-size: 24px;
  }

  h4 {
    font-size: 18px;
    font-weight: normal;
  }

  .important-font {
    color: #21BEB4;
    font-weight: bold;
  }

  .hide {
    display: none !important;
  }

  .force-full-width {
    width: 100% !important;
  }

  div.logo, img.logo {
    width: 150px;
    height: 150px;
    margin: auto;
  }

  </style>

  <style type="text/css" media="screen">
      @media screen {
        @import url(http://fonts.googleapis.com/css?family=Open+Sans:400);

        td, h1, h2, h3 {
          font-family: 'Open Sans', 'Helvetica Neue', Arial, sans-serif !important;
        }
      }
  </style>

  <style type="text/css" media="only screen and (max-width: 600px)">
    /* Mobile styles */
    @media only screen and (max-width: 600px) {

      table[class="w320"] {
        width: 320px !important;
      }

      table[class="w300"] {
        width: 300px !important;
      }

      table[class="w290"] {
        width: 290px !important;
      }

      td[class="w320"] {
        width: 320px !important;
      }

      td[class~="mobile-padding"] {
        padding-left: 14px !important;
        padding-right: 14px !important;
      }

      td[class*="mobile-padding-left"] {
        padding-left: 14px !important;
      }

      td[class*="mobile-padding-right"] {
        padding-right: 14px !important;
      }

      td[class*="mobile-padding-left-only"] {
        padding-left: 14px !important;
        padding-right: 0 !important;
      }

      td[class*="mobile-padding-right-only"] {
        padding-right: 14px !important;
        padding-left: 0 !important;
      }

      td[class*="mobile-block"] {
        display: block !important;
        width: 100% !important;
        text-align: left !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        padding-bottom: 15px !important;
      }

      td[class*="mobile-no-padding-bottom"] {
        padding-bottom: 0 !important;
      }

      td[class~="mobile-center"] {
        text-align: center !important;
      }

      table[class*="mobile-center-block"] {
        float: none !important;
        margin: 0 auto !important;
      }

      *[class*="mobile-hide"] {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
        line-height: 0 !important;
        font-size: 0 !important;
      }

      td[class*="mobile-border"] {
        border: 0 !important;
      }
    }
  </style>
</head>
<body class="body" style="padding:0; margin:0; display:block; background:#ffffff; -webkit-text-size-adjust:none" bgcolor="#ffffff">
<table align="center" cellpadding="0" cellspacing="0" width="100%" height="100%">
  <tr>
    <td align="center" valign="middle" bgcolor="#ffffff"  width="100%">

    <table cellspacing="0" cellpadding="0" width="100%">
      <tr>
        <td width="100%">
          <div class="logo">
            <img class="logo" src="https://longserviceawards.gww.gov.bc.ca/wp-content/uploads/2022/05/lsa-logo.png">
          </div>
        </td>
      </tr>
      <tr>
        <td style="border-bottom:1px solid #e7e7e7;">

          <center>
            <table cellpadding="0" cellspacing="0" width="600" class="w320">
              <tr>
                <td align="left" class="mobile-padding" style="padding:20px 20px 0">

                  <br class="mobile-hide" />
                    @yield('content')
                  <br><br>
                </td>
              </tr>
            </table>
          </center>
        </td>
      </tr>
    </table>

    </td>
  </tr>
</table>
</body>
</html>
