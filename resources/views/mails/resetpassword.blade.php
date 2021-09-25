<!DOCTYPE html>
<html>
<body>
<p>Hello {{ $user->first_name }},</p>
<p>You have made a request to reset your password. Please click on this link to reset it and use this code:</p>
<p style="font-size: 36px">{{ $verification->payload }}</p>
<p>Thanks!</p>
</body>
</html>
