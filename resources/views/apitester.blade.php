<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS API Tester</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; background: white; border-radius: 15px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); overflow: hidden; }
        .header { background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { font-size: 2.5em; margin-bottom: 10px; }
        .header p { font-size: 1.1em; opacity: 0.9; }
        .content { padding: 30px; }
        .auth-section { background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 30px; border-left: 4px solid #007bff; }
        .auth-section h3 { color: #007bff; margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        .form-group textarea { height: 120px; resize: vertical; font-family: 'Courier New', monospace; }
        .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; margin-right: 10px; margin-bottom: 10px; }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-warning:hover { background: #e0a800; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 20px; }
        .section { background: #f8f9fa; padding: 20px; border-radius: 10px; }
        .section h3 { margin-bottom: 15px; color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .endpoint-btn { display: block; width: 100%; text-align: left; background: white; border: 1px solid #ddd; padding: 12px; margin-bottom: 8px; border-radius: 5px; cursor: pointer; transition: all 0.3s; }
        .endpoint-btn:hover { background: #e9ecef; border-color: #007bff; }
        .endpoint-btn.get { border-left: 4px solid #28a745; }
        .endpoint-btn.post { border-left: 4px solid #007bff; }
        .endpoint-btn.delete { border-left: 4px solid #dc3545; }
        .endpoint-btn.put { border-left: 4px solid #ffc107; }
        .method-badge { display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 11px; font-weight: bold; color: white; margin-right: 8px; }
        .method-badge.get { background: #28a745; }
        .method-badge.post { background: #007bff; }
        .method-badge.delete { background: #dc3545; }
        .method-badge.put { background: #ffc107; color: #212529; }
        .response-area { background: #f8f9fa; border: 1px solid #ddd; border-radius: 5px; padding: 15px; margin-top: 20px; min-height: 200px; font-family: 'Courier New', monospace; font-size: 13px; white-space: pre-wrap; max-height: 400px; overflow-y: auto; }
        .token-display { background: #e7f3ff; border: 1px solid #b3d7ff; padding: 10px; border-radius: 5px; margin-top: 10px; word-break: break-all; font-family: 'Courier New', monospace; font-size: 12px; }
        .status-indicator { display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-right: 8px; }
        .status-success { background: #28a745; }
        .status-error { background: #dc3545; }
        .status-warning { background: #ffc107; }
        @media (max-width: 768px) { .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 SMS API Tester</h1>
            <p>Test all SMS School Management System APIs from this interface</p>
        </div>
        
        <div class="content">
            <!-- Authentication Section -->
            <div class="auth-section">
                <h3>🔐 Authentication</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" id="loginEmail" value="superadmin@example.com" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label>Password:</label>
                            <input type="password" id="loginPassword" value="superpassword" placeholder="Enter password">
                        </div>
                        <button class="btn btn-success" onclick="login()">Login</button>
                        <button class="btn btn-danger" onclick="logout()">Logout</button>
                    </div>
                    <div>
                        <label>Current Token:</label>
                        <div id="tokenDisplay" class="token-display">No token - Please login first</div>
                    </div>
                </div>
            </div>

            <!-- API Testing Interface -->
            <div class="form-group">
                <label>API Endpoint:</label>
                <input type="text" id="apiUrl" value="/health" placeholder="Enter endpoint (e.g., /health)">
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="form-group">
                    <label>Method:</label>
                    <select id="apiMethod">
                        <option value="GET">GET</option>
                        <option value="POST">POST</option>
                        <option value="PUT">PUT</option>
                        <option value="DELETE">DELETE</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Content-Type:</label>
                    <select id="contentType">
                        <option value="application/json">application/json</option>
                        <option value="application/x-www-form-urlencoded">form-urlencoded</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Authorization:</label>
                    <select id="authType">
                        <option value="none">No Auth</option>
                        <option value="bearer">Bearer Token</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Request Body (JSON):</label>
                <textarea id="requestBody" placeholder='{"key": "value"}'></textarea>
            </div>

            <button class="btn btn-success" onclick="sendRequest()">Send Request</button>
            <button class="btn btn-warning" onclick="clearResponse()">Clear Response</button>

            <!-- Quick Endpoints -->
            <div class="grid">
                <div class="section">
                    <h3>🏥 Health & Status</h3>
                    <button class="endpoint-btn get" onclick="quickRequest('GET', '/health')">
                        <span class="method-badge get">GET</span>/health - API Health Check
                    </button>
                    <button class="endpoint-btn get" onclick="quickRequest('GET', '/ping')">
                        <span class="method-badge get">GET</span>/ping - Simple Ping
                    </button>
                    <button class="endpoint-btn get" onclick="quickRequest('GET', '/test-academic-health')">
                        <span class="method-badge get">GET</span>/test-academic-health - Academic Service Test
                    </button>
                    <button class="endpoint-btn get" onclick="quickRequest('GET', '/test-teacher-health')">
                        <span class="method-badge get">GET</span>/test-teacher-health - Teacher Service Test
                    </button>
                </div>

                <div class="section">
                    <h3>🔑 Authentication</h3>
                    <button class="endpoint-btn post" onclick="quickRequest('POST', '/login', '{\"email\":\"superadmin@example.com\",\"password\":\"superpassword\"}')">
                        <span class="method-badge post">POST</span>/login - User Login
                    </button>
                    <button class="endpoint-btn post" onclick="quickRequest('POST', '/register', '{\"name\":\"Test User\",\"email\":\"test@example.com\",\"password\":\"password\"}')">
                        <span class="method-badge post">POST</span>/register - User Registration
                    </button>
                    <button class="endpoint-btn post" onclick="quickRequest('POST', '/me', '{}', true)">
                        <span class="method-badge post">POST</span>/me - Get Current User (Auth)
                    </button>
                    <button class="endpoint-btn post" onclick="quickRequest('POST', '/logout', '{}', true)">
                        <span class="method-badge post">POST</span>/logout - User Logout (Auth)
                    </button>
                </div>

                <div class="section">
                    <h3>👥 User Management</h3>
                    <button class="endpoint-btn post" onclick="quickRequest('POST', '/users/', '{}', true)">
                        <span class="method-badge post">POST</span>/users/ - List Users (Auth)
                    </button>
                    <button class="endpoint-btn post" onclick="quickRequest('POST', '/users/store', '{\"name\":\"New User\",\"email\":\"newuser@example.com\",\"password\":\"password123\"}', true)">
                        <span class="method-badge post">POST</span>/users/store - Create User (Auth)
                    </button>
                    <button class="endpoint-btn post" onclick="quickRequest('POST', '/users/show', '{\"id\":1}', true)">
                        <span class="method-badge post">POST</span>/users/show - Show User (Auth)
                    </button>
                </div>

                <div class="section">
                    <h3>🎯 Roles & Permissions</h3>
                    <button class="endpoint-btn post" onclick="quickRequest('POST', '/roles/', '{}', true)">
                        <span class="method-badge post">POST</span>/roles/ - List Roles (Auth)
                    </button>
                    <button class="endpoint-btn post" onclick="quickRequest('POST', '/permissions/', '{}', true)">
                        <span class="method-badge post">POST</span>/permissions/ - List Permissions (Auth)
                    </button>
                    <button class="endpoint-btn post" onclick="quickRequest('POST', '/roles/store', '{\"name\":\"Test Role\",\"description\":\"Test role description\"}', true)">
                        <span class="method-badge post">POST</span>/roles/store - Create Role (Auth)
                    </button>
                </div>

                <div class="section">
                    <h3>🎓 Academic Gateway</h3>
                    <button class="endpoint-btn post" onclick="quickRequest('POST', '/academic/health', '{}', true)">
                        <span class="method-badge post">POST</span>/academic/health - Academic Health (Auth)
                    </button>
                    <button class="endpoint-btn post" onclick="quickRequest('POST', '/academic/students', '{}', true)">
                        <span class="method-badge post">POST</span>/academic/students - Students List (Auth)
                    </button>
                </div>

                <div class="section">
                    <h3>👨‍🏫 Teacher Gateway</h3>
                    <button class="endpoint-btn post" onclick="quickRequest('POST', '/teacher/health', '{}', true)">
                        <span class="method-badge post">POST</span>/teacher/health - Teacher Health (Auth)
                    </button>
                    <button class="endpoint-btn post" onclick="quickRequest('POST', '/teacher/classes', '{}', true)">
                        <span class="method-badge post">POST</span>/teacher/classes - Classes List (Auth)
                    </button>
                </div>
            </div>

            <!-- Response Area -->
            <div style="margin-top: 30px;">
                <h3>📋 Response</h3>
                <div id="responseArea" class="response-area">Click "Send Request" or use Quick Endpoints to see response here...</div>
            </div>
        </div>
    </div>

    <script>
        let authToken = localStorage.getItem('sms_auth_token') || '';
        
        // Update token display on page load
        updateTokenDisplay();
        
        function updateTokenDisplay() {
            const tokenDiv = document.getElementById('tokenDisplay');
            if (authToken) {
                tokenDiv.innerHTML = `<span class="status-indicator status-success"></span>${authToken.substring(0, 50)}...`;
            } else {
                tokenDiv.innerHTML = `<span class="status-indicator status-error"></span>No token - Please login first`;
            }
        }
        
        async function login() {
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            
            if (!email || !password) {
                alert('Please enter email and password');
                return;
            }
            
            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });
                
                const data = await response.json();
                
                if (response.ok && data.token) {
                    authToken = data.token;
                    localStorage.setItem('sms_auth_token', authToken);
                    updateTokenDisplay();
                    document.getElementById('responseArea').textContent = JSON.stringify(data, null, 2);
                    alert('Login successful!');
                } else {
                    document.getElementById('responseArea').textContent = 'Login failed: ' + JSON.stringify(data, null, 2);
                    alert('Login failed!');
                }
            } catch (error) {
                document.getElementById('responseArea').textContent = 'Login error: ' + error.message;
                alert('Login error: ' + error.message);
            }
        }
        
        function logout() {
            authToken = '';
            localStorage.removeItem('sms_auth_token');
            updateTokenDisplay();
            document.getElementById('responseArea').textContent = 'Logged out successfully';
            alert('Logged out!');
        }
        
        function quickRequest(method, url, body = '', requiresAuth = false) {
            document.getElementById('apiMethod').value = method;
            document.getElementById('apiUrl').value = url;
            document.getElementById('requestBody').value = body;
            document.getElementById('authType').value = requiresAuth ? 'bearer' : 'none';
            sendRequest();
        }
        
        async function sendRequest() {
            const method = document.getElementById('apiMethod').value;
            const url = document.getElementById('apiUrl').value;
            const body = document.getElementById('requestBody').value;
            const contentType = document.getElementById('contentType').value;
            const authType = document.getElementById('authType').value;
            const responseArea = document.getElementById('responseArea');
            
            if (!url) {
                alert('Please enter an API endpoint');
                return;
            }
            
            // Prepare headers
            const headers = {
                'Content-Type': contentType,
                'Accept': 'application/json'
            };
            
            // Add authorization header if needed
            if (authType === 'bearer') {
                if (!authToken) {
                    alert('Please login first to get authentication token');
                    return;
                }
                headers['Authorization'] = `Bearer ${authToken}`;
            }
            
            // Prepare request options
            const requestOptions = {
                method: method,
                headers: headers
            };
            
            // Add body for POST/PUT requests
            if ((method === 'POST' || method === 'PUT') && body) {
                requestOptions.body = body;
            }
            
            responseArea.textContent = 'Loading...';
            
            try {
                const startTime = Date.now();
                const response = await fetch(url, requestOptions);
                const endTime = Date.now();
                const responseTime = endTime - startTime;
                
                let responseText = await response.text();
                let responseData;
                
                try {
                    responseData = JSON.parse(responseText);
                    responseText = JSON.stringify(responseData, null, 2);
                } catch (e) {
                    // Response is not JSON
                    responseData = responseText;
                }
                
                const statusClass = response.ok ? 'status-success' : 'status-error';
                
                responseArea.innerHTML = `<div style="border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 15px;">
                    <strong>Status:</strong> <span class="status-indicator ${statusClass}"></span>${response.status} ${response.statusText}
                    <strong style="margin-left: 20px;">Time:</strong> ${responseTime}ms
                    <strong style="margin-left: 20px;">URL:</strong> ${method} ${url}
                </div>${responseText}`;
                
            } catch (error) {
                responseArea.innerHTML = `<div style="color: #dc3545;">
                    <strong>Error:</strong> ${error.message}
                    <br><strong>URL:</strong> ${method} ${url}
                </div>`;
            }
        }
        
        function clearResponse() {
            document.getElementById('responseArea').textContent = 'Response cleared. Click "Send Request" to test APIs...';
        }
    </script>
</body>
</html>