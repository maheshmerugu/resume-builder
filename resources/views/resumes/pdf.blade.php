<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $resume->full_name ?: $resume->title }}</title>
    <style>
        @page { margin: 32px 40px; }
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; }
        .rf-watermark {
            position: fixed;
            top: 42%;
            left: 0;
            width: 100%;
            text-align: center;
            transform: rotate(-30deg);
            font-size: 68px;
            font-weight: bold;
            color: #000;
            opacity: 0.06;
            z-index: 0;
        }
    </style>
</head>
<body>
    @if (! empty($watermark))
        <div class="rf-watermark">ResumeForge</div>
    @endif
    @include($template, ['resume' => $resume])
</body>
</html>
