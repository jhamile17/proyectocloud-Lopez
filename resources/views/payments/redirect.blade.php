<!doctype html>
<html>
  <body onload="document.forms[0].submit()">
    <p>Redirigiendo a PayU…</p>
    <form method="post" action="{{ $action }}">
      <input type="hidden" name="merchantId" value="{{ $merchantId }}">
      <input type="hidden" name="accountId" value="{{ $accountId }}">
      <input type="hidden" name="description" value="{{ $description }}">
      <input type="hidden" name="referenceCode" value="{{ $referenceCode }}">
      <input type="hidden" name="amount" value="{{ $amount }}">
      <input type="hidden" name="currency" value="{{ $currency }}">
      <input type="hidden" name="signature" value="{{ $signature }}">
      <input type="hidden" name="test" value="1">
      <input type="hidden" name="buyerEmail" value="test@test.com">
      <input type="hidden" name="responseUrl" value="{{ $responseUrl }}">
      <input type="hidden" name="confirmationUrl" value="{{ $confirmationUrl }}">
      <noscript><button>Continuar</button></noscript>
    </form>
  </body>
</html>
