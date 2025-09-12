<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Choose Your Collection</title>
  <style>
    body, html {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: #f5f5f5;
    }

    .container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: center;
      height: 100vh;
      gap: 40px;
      padding: 20px;
    }

    .card {
      position: relative;
      width: 300px;
      height: 400px;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 20px rgba(0,0,0,0.2);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .card:hover {
      transform: scale(1.05);
      box-shadow: 0 15px 30px rgba(0,0,0,0.3);
    }

    .card .btn {
      position: absolute;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      background: #FFD700;
      color: black;
      border: none;
      padding: 12px 25px;
      font-size: 1.2rem;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s, transform 0.2s;
    }

    .card .btn:hover {
      background: #D4AF37;
      transform: translateX(-50%) translateY(-3px);
    }

    @media(max-width: 650px){
      .container {
        flex-direction: column;
        height: auto;
        gap: 20px;
      }
      .card {
        width: 90%;
        height: 300px;
      }
      .card .btn {
        font-size: 1rem;
        padding: 10px 20px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <!-- Premium Card -->
  <div class="card">
    <img src="images/premium.jpg" alt="Premium Watches">
    <button class="btn" onclick="chooseVersion('premium')">Premium</button>
  </div>

  <!-- Essentials Card -->
  <div class="card">
    <img src="images/essentials.jpg" alt="Essentials Watches">
    <button class="btn" onclick="chooseVersion('essentials')">Essentials</button>
  </div>
</div>

<script>
  // Redirect based on choice
  function chooseVersion(version) {
    localStorage.setItem('siteVersion', version);
    if(version === 'premium') window.location.href = 'premium/index.php';
    else window.location.href = 'essentials/index.php';
  }

  // Auto-redirect if user already chose
  const savedVersion = localStorage.getItem('siteVersion');
  if(savedVersion){
    if(savedVersion === 'premium') window.location.href = 'premium/index.php';
    if(savedVersion === 'essentials') window.location.href = 'essentials/index.php';
  }
</script>

</body>
</html>
