<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }} - SMS API Documentation</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 20px 40px; 
            background-color: #fafafa;
            line-height: 1.6;
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            background: white; 
            padding: 40px; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .back-link { 
            margin-bottom: 20px; 
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .back-link a { 
            color: #666; 
            text-decoration: none; 
            font-size: 14px;
        }
        .back-link a:hover { 
            color: #0066cc; 
            text-decoration: underline; 
        }
        h1 { 
            color: #2c3e50; 
            border-bottom: 3px solid #3498db; 
            padding-bottom: 10px; 
        }
        h2 { 
            color: #34495e; 
            margin-top: 30px; 
            border-left: 4px solid #3498db; 
            padding-left: 15px;
        }
        h3 { 
            color: #34495e; 
            margin-top: 25px; 
        }
        h4 { 
            color: #34495e; 
            margin-top: 20px; 
        }
        p { 
            color: #444; 
            margin: 15px 0; 
        }
        code { 
            background-color: #f8f9fa; 
            padding: 2px 6px; 
            border-radius: 4px; 
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace; 
            color: #e83e8c;
        }
        pre { 
            background-color: #f8f9fa; 
            padding: 20px; 
            border-radius: 6px; 
            overflow-x: auto; 
            border-left: 4px solid #007bff;
        }
        pre code { 
            background: none; 
            padding: 0; 
            color: #333;
        }
        ul, ol { 
            margin: 15px 0; 
            padding-left: 30px; 
        }
        li { 
            margin: 8px 0; 
        }
        a { 
            color: #007bff; 
            text-decoration: none; 
        }
        a:hover { 
            text-decoration: underline; 
        }
        strong { 
            color: #2c3e50; 
        }
        em { 
            color: #6c757d; 
        }
        .file-info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #6c757d;
        }
        .raw-toggle {
            margin: 20px 0;
            padding: 10px 0;
            border-top: 1px solid #eee;
        }
        .raw-content {
            display: none;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            white-space: pre-wrap;
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            font-size: 12px;
            color: #495057;
            border: 1px solid #dee2e6;
        }
        .toggle-btn {
            background: #007bff;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .toggle-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-link">
            <a href="/docs">&larr; Back to Documentation Index</a>
        </div>
        
        <div class="file-info">
            <strong>File:</strong> {{ $filename }} | 
            <strong>Title:</strong> {{ $title }}
        </div>
        
        <div class="content">
            {!! $content !!}
        </div>
        
        <div class="raw-toggle">
            <button class="toggle-btn" onclick="toggleRaw()">Show/Hide Raw Markdown</button>
            <div class="raw-content" id="rawContent">{{ $rawContent }}</div>
        </div>
    </div>
    
    <script>
        function toggleRaw() {
            const rawContent = document.getElementById('rawContent');
            if (rawContent.style.display === 'none' || rawContent.style.display === '') {
                rawContent.style.display = 'block';
            } else {
                rawContent.style.display = 'none';
            }
        }
    </script>
</body>
</html>