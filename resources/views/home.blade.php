<!DOCTYPE html>
<html>
<head>
    <title>SMS API Service</title>
</head>
<body>
    <h1>SMS API Service</h1>
    <p>School Management System API</p>
    
    <h2>Status</h2>
    <p>API Status: <strong>Running</strong></p>
    <p>Database Status: <strong>Connected</strong></p>
    
    <h2>Available Services</h2>
    <ul>
        <li>User Management</li>
        <li>Student Management</li>
        <li>Teacher Management</li>
        <li>Academic Management</li>
    </ul>
    
    <h2>API Endpoints</h2>
    <ul>
        <li>GET /api/health - System health check</li>
        <li>POST /api/user/students - Student operations</li>
        <li>POST /api/user/teachers - Teacher operations</li>
        <li>GET /api/academic/classes - Class information</li>
    </ul>
    
    <h2>System Information</h2>
    <ul>
        <li>Environment: {{ app()->environment() }}</li>
        <li>PHP Version: {{ PHP_VERSION }}</li>
        <li>Laravel Version: {{ app()->version() }}</li>
        <li>Server Time: {{ date('Y-m-d H:i:s T') }}</li>
        <li>Uptime: {{ shell_exec('uptime') ?: 'N/A' }}</li>
    </ul>
    
    <h2>Kubernetes Information</h2>
    <ul>
        <li>Namespace: {{ env('KUBERNETES_NAMESPACE', 'sms-app') }}</li>
        <li>Pod Name: {{ gethostname() }}</li>
        <li>Container Image: sms-api:v21-ultra-clean</li>
        <li>Deployment: sms-api</li>
        <li>Service: sms-api-service</li>
        <li>Ingress Host: sms-api.local</li>
    </ul>
    
    <h2>Microservices Health</h2>
    <ul>
        <li><a href="/gateway/test-academic-health">Academic Service Health</a></li>
        <li><a href="/gateway/test-teacher-health">Teacher Service Health</a></li>
        <li><a href="/gateway/health">Main API Health</a></li>
    </ul>
    
    <h2>Links</h2>
    <ul>
        <li><a href="/gateway/health">Health Check</a></li>
        <li><a href="/docs">Documentation</a></li>
    </ul>
    
</body>
</html>
