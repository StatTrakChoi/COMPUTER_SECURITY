<?php
  session_start(); 
  $token = base64_encode(openssl_random_pseudo_bytes(32)); 
  $_SESSION['token'] = $token; 
?>

<?php
  define('SITE_KEY', '6LfAOAkaAAAAAEbFqR5eYDZRlzXPcqj-QMlWzHrc');
  define('SECRET_KEY', '6LfAOAkaAAAAAI22WnKGzF_Vs_nQLJf1v-nc2m1f');
  ?>
<script src='https://www.google.com/recaptcha/api.js?render=<?php echo SITE_KEY; ?>'></script>
<?php
    echo "<form action='complexLoginCheck.php' method='POST'>";
    echo "Username ";
    echo "<input name='txtUsername' type='text'>";
    echo "<br />Password ";
    echo "<input name='txtPassword' type='password' />";
?>
<input type="hidden" name="token" value="<?= $token ?>">

<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" /><br >
<script>
      grecaptcha.ready(function() {
      grecaptcha.execute('<?php echo SITE_KEY; ?>', {action: 'login'}).then(function(tok) {
          //console.log(tok);
          document.getElementById('g-recaptcha-response').value=tok;
      });
      });
</script>
<?php
    echo "<br /><input type='submit' value='Login'>";
    echo "  <input type=button value=\"Reset Password\" onclick=\"location.href='setPasswordForm.php'\">";
    echo "</form>";
?>

