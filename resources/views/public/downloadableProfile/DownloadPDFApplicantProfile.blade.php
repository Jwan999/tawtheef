<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $applicant->details['fullName'] ?? 'Applicant' }} - Resume</title>
    @vite('resources/css/app.css')
    <style>
        body {
            font-family: 'Dosis', sans-serif;
        }

        .rating-block {
            width: 30px;
            height: 10px;
            display: inline-block;
            margin-right: 2px;
        }

        .rating-block-filled {
            background-color: #E26600;
        }

        .rating-block-empty {
            background-color: #E2E8F0;
        }

        .list-circle {
            list-style-type: circle;
        }

        .section-header {
            font-size: 1.2rem;
            font-weight: 500;
            color: #000;
            border-bottom: 2px solid #E26600;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<div>
    Test
</div>
</body></html>
