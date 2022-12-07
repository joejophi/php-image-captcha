# Project Title

Simple & Secure PHP Image Captcha

## Usage/Examples

### sample-form.php

```
<html>
  <head>
    <title>Simple Captcha</title>
  </head>
  <body>
    <center>
    <form method="post" action="formSubmit.php">
        <button type="button" onclick="document.getElementById('captchaImage').src = 'generateCaptcha.php?' + new Date().getTime();">Refresh Captcha</button>
        <br/><br/>
        <img src="generateCaptcha.php?<?php time();?>" id="captchaImage" />
        <br/><br/>
        <input type="text" id="captcha" name="captcha" />
        <br/><br/>
        <button type="submit">Submit</button>
    </form>
    </center>
  </body>
</html>
```

### generateCaptcha.php

```
<?php

require_once 'vendor/autoload.php';

$captcha = new \Joejophi\PhpImageCaptcha\Captcha();
return $captcha->generate();
```

### formSubmit.php

```
<?php

require_once 'vendor/autoload.php';

$captcha = new \Joejophi\PhpImageCaptcha\Captcha();

if (isset($_POST["captcha"])) {
    if ($captcha->verify($_POST["captcha"])) {
        echo "Valid Captcha";
    } else {
        echo "Invalid Valid Captcha";
    }
}
```

## Authors

-   N M Jophi

## Features

-   Easy to integrate
-   Add Simple & Secure PHP Captcha on any form within seconds
-   Image Captcha created is a combination of alphabets and numbers
-   Used to avoid spam
