{extends file='component/framework'}

{block name='mainContent'}

{inject name='qrCode' type='Blog\DTO\QrCodeUrl'}

<div class="row">
  <div class="col-md-12 panel panel-default">

    <h1>Set up 2FA</h1>

     <p>Scan this code into Google Authenticator:</p>
     <img src="{$qrCode->src | nofilter}" />


     <p>or enter this code: <tt>{$qrCode->secret}</tt></p>

     <p>Enter code from Google Authenticator to confirm:</p>
     <form method="POST" action="/setup2fa">
     <div class="form-group">
        <input name="code" maxlength="6">
        <button type="submit">Confirm</button>
         </div>
     </form>

  </div>
</div>
{/block}

