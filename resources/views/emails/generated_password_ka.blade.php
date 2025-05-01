<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>სისტემაში შესასვლელად გამოიყენეთ დაგენერირებული პაროლი</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .password {
            font-size: 18px;
            font-weight: bold;
            background-color: #eee;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            margin: 15px 0;
            letter-spacing: 2px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>თქვენი გენერირებული პაროლი</h2>
        <p>მოცემული პაროლი არის დროებითი და გთხოვთ შეცვალოთ.</p>

        <div class="password">{{ $password }}</div>

        <p>გთხოვთ შეინახოთ ეს პაროლი უსაფრთხოდ და არ გაუზიაროთ არავის.</p>
    </div>

    <div class="footer">
        <p>ეს არის ავტომატური ელფოსტა. გთხოვთ არ უპასუხოთ.</p>
        <p>&copy; {{ date('Y') }} GeoGapp. ყველა უფლება დაცულია.</p>
    </div>
</body>
</html>
