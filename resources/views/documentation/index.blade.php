<!DOCTYPE html>
<html>
<head>
    <title>SMS API Documentation</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h1 { color: #333; }
        .doc-section { margin: 30px 0; }
        .doc-list { list-style: none; padding: 0; }
        .doc-list li { margin: 10px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .doc-list li:hover { background-color: #f5f5f5; }
        .doc-list a { text-decoration: none; color: #0066cc; font-weight: bold; }
        .doc-list a:hover { text-decoration: underline; }
        .back-link { margin: 20px 0; }
        .back-link a { color: #666; text-decoration: none; }
    </style>
</head>
<body>
    <div class="back-link">
        <a href="/">&larr; Back to Home</a>
    </div>
    
    <h1>📚 SMS API Documentation</h1>
    <p>Welcome to the SMS Development System documentation. Choose from the available documentation files below:</p>
    
    <div class="doc-section">
        <h2>📋 Main Documentation</h2>
        <ul class="doc-list">
            @foreach($docFiles as $filename => $title)
                <li>
                    <a href="/docs/{{ $filename }}">{{ $title }}</a>
                    <br><small style="color: #666;">{{ $filename }}</small>
                </li>
            @endforeach
        </ul>
    </div>
    
    <div class="doc-section">
        <h2>🔧 Developer Guides</h2>
        <ul class="doc-list">
            @foreach($docsFromFolder as $filename => $title)
                <li>
                    <a href="/docs/{{ $filename }}">{{ $title }}</a>
                    <br><small style="color: #666;">{{ $filename }}</small>
                </li>
            @endforeach
        </ul>
    </div>
    
    <div class="doc-section">
        <h2>🚀 Quick Links</h2>
        <ul class="doc-list">
            <li><a href="/gateway/health">API Health Check</a></li>
            <li><a href="/gateway/test-academic-health">Academic Service Health</a></li>
            <li><a href="/gateway/test-teacher-health">Teacher Service Health</a></li>
        </ul>
    </div>
</body>
</html>