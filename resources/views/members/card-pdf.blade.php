<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{ $member->name }} - ID Card</title>
@include('members.partials.card-styles')
</head>
<body>
@include('members.card-front-pdf')
<pagebreak />
@include('members.card-back-pdf')
</body>
</html>
