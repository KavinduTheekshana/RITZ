<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Engagement Letter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 40px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .content {
            font-size: 11pt;
        }
        
        p {
            margin-bottom: 12px;
        }
        
        .section-title {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .sub-section {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 8px;
        }
        
        .signature-section {
            margin-top: 50px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
        
        .company-header {
            text-align: center;
            font-weight: bold;
            font-size: 16pt;
            margin-bottom: 30px;
        }
        
        /* Custom spacing */
        .extra-space {
            margin-bottom: 20px;
        }
        
        .indent {
            margin-left: 20px;
        }
        
        /* Bold */
        .bold {
            font-weight: bold;
        }
        
        /* Italic */
        .italic {
            font-style: italic;
        }
        
        /* Numbered list */
        ol {
            margin-left: 20px;
            margin-bottom: 15px;
        }
        
        ul {
            margin-left: 20px;
            margin-bottom: 15px;
        }
        
        li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="content">
        {!! $letterContent !!}
    </div>
</body>
</html>