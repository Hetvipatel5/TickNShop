<?php
function showMessage($type, $message, $btnText, $btnLink) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>TickNShop | Message</title>
        <style>
            /* 🎨 Theme Colors */
            :root {
                --primary: #000000;       /* Rich Black */
                --accent: #D4AF37;        /* Luxury Gold */
                --text: #FFFFFF;          /* White */
                --secondary: #1A1A1A;     /* Charcoal Black */
                --highlight: #FFD700;     /* Bright Gold */
            }

            body {
                margin: 0;
                padding: 0;
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                background: url('images/message2.jpg') no-repeat center center/cover;
                font-family: 'Poppins', sans-serif;
                position: relative;
                color: var(--text);
            }

            /* Dark overlay for background image */
            body::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.6);
                z-index: 0;
            }

            .message-box {
                position: relative;
                z-index: 1;
                background: var(--secondary);
                padding: 40px 50px;
                border-radius: 15px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.7);
                text-align: center;
                border: 2px solid var(--accent);
                max-width: 500px;
            }

            .message-box h2 {
                font-size: 26px;
                font-weight: bold;
                margin-bottom: 25px;
                color: var(--accent);
                text-shadow: 1px 1px 4px black;
            }

            .message-box a {
                display: inline-block;
                padding: 12px 25px;
                border-radius: 30px;
                font-weight: bold;
                font-size: 16px;
                background: linear-gradient(135deg, var(--highlight), var(--accent));
                color: var(--primary);
                text-decoration: none;
                transition: 0.3s;
                box-shadow: 0 4px 15px rgba(0,0,0,0.5);
            }

            .message-box a:hover {
                background: linear-gradient(135deg, var(--accent), var(--highlight));
                transform: scale(1.05);
            }

            /* Success & error text */
            .success h2 { color: var(--highlight); }
            .error h2 { color: #FF4C4C; }
        </style>
    </head>
    <body>
        <div class="message-box <?php echo $type; ?>">
            <h2><?php echo $message; ?></h2>
            <a href="<?php echo $btnLink; ?>"><?php echo $btnText; ?></a>
        </div>
    </body>
    </html>
    <?php
}
?>
