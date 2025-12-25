<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Alamanda Houseboat - Login Staff</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet" />
  <style>
  * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Inter", sans-serif;
    }

  body {
  background: url("background.jpg") center/cover no-repeat;
  min-height: 100vh;
  overflow-y: auto;

  display: flex;
  justify-content: center;
  align-items: center;
}


    /* Overlay gelap */
    body::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.45);
      z-index: -1;
    }

    /* Navbar */
    nav {
      position: absolute;
      top: 25px;
      right: 40px;
      display: flex;
      gap: 45px;
      font-size: 18px;
      font-weight: 500;
    }

    nav a {
      text-decoration: none;
      color: rgba(255, 255, 255, 0.9);
      transition: 0.2s;
    }

    nav a:hover {
      color: #ff8c32;
    }

    nav a.active {
      color: #ff8c32;
      font-weight: 700;
    }

    /* Logo */
    .logo {
      position: absolute;
      top: 20px;
      left: 50px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .logo img {
      width: 55px;
    }

    .logo-text {
      color: white;
      font-size: 21px;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    /* Login Card */
    .password-wrapper { position: relative; width: 100%; }
    .password-wrapper input { width: 100%; padding-right: 40px; }
    .toggle-eye { position: absolute; right: 5px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 18px; }

    /* Login Card */
    .card {
      width: 380px;
      background: white;
      padding: 40px;
      border-radius: 18px;
	  align-items:center;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .sun-icon {
      text-align: center;
      font-size: 35px;
      margin-bottom: 20px;
      color: #ff8c32;
    }

    h2 {
      text-align: center;
      font-size: 26px;
      font-weight: 700;
      line-height: 1.3;
      margin-bottom: 10px;
    }

    .subtitle {
      text-align: center;
      font-size: 14px;
      color: #555;
      margin-bottom: 25px;
    }

    label {
      display: block;
      margin: 10px 0 5px;
      font-weight: 600;
    }

    input {
      width: 100%;
      padding: 10px;
      border: none;
      border-bottom: 2px solid #aaa;
      outline: none;
      font-size: 15px;
      transition: 0.2s;
    }

    input:focus {
      border-bottom: 2px solid #000;
    }

    .forgot {
      text-align: right;
      font-size: 12px;
      margin-top: 5px;
      color: #555;
      cursor: pointer;
    }

    .btn {
      width: 100%;
      padding: 13px;
      border: none;
      border-radius: 30px;
      background: #222;
      color: white;
      font-size: 16px;
      font-weight: 600;
      margin-top: 25px;
      cursor: pointer;
      transition: 0.2s;
    }

    .btn:hover {
      opacity: 0.9;
    }

    .gmail-btn {
      width: 100%;
      padding: 13px;
      border-radius: 30px;
      margin-top: 15px;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
      background: #eee;
      border: none;
      cursor: pointer;
      font-weight: 600;
    }

    .gmail-btn img {
      width: 20px;
    }
  </style>
</head>
<body>
  <div class="logo">
    <img src="{{ asset('storage/pic/logo_alamanda.png') }}" alt="Logo" />
    <span class="logo-text">Alamanda Houseboat</span>
  </div>

 

  <div class="card">
    <div class="sun-icon">✺</div>
    <h2>Staff Login</h2>
    <p class="subtitle">Please enter your details</p>

    <label>Email</label>
    <input type="email" />

    <label>Password</label>
    <div class="password-wrapper">
      <input id="password" type="password" />
      <span class="toggle-eye" onclick="togglePassword()">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"></path>
          <circle cx="12" cy="12" r="3"></circle>
        </svg>
      </span>
    </div>
   

    <button class="btn">Log In</button>

    <button class="gmail-btn">
      <img src="https://upload.wikimedia.org/wikipedia/commons/4/4e/Gmail_Icon.png" />
      Log in with Gmail
    </button>
  </div>
<script>
function togglePassword(){ const p=document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password'; }
</script>
</body>
</html>
