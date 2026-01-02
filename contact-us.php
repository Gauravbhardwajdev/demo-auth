<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact Us</title>
  <link rel="stylesheet" href="css/login.css" />
</head>

<body>
  <main class="wrap">
    <section class="card" aria-label="Contact form">
      <header class="card__header">
        <h1>Contact Us</h1>
        <p>Send a message and the details will be saved in our database.</p>
      </header>

      <form class="form" method="POST" action="contact_submit.php">
        <div class="grid">
          <label class="field">
            <span class="field__label">Full name</span>
            <input class="field__input" type="text" name="full_name" placeholder="Enter your name" required />
          </label>

          <label class="field">
            <span class="field__label">Email</span>
            <input class="field__input" type="email" name="email" placeholder="you@example.com" required />
          </label>
        </div>

        <label class="field">
          <span class="field__label">Message</span>
          <textarea class="field__input field__textarea" name="message" placeholder="Write your message..." required></textarea>
        </label>

        <button class="btn" type="submit">Send message</button>

        <p class="note">
          Tip: Open this page using localhost (not file path) so PHP runs correctly.
        </p>
      </form>
    </section>

    <section class="side">
      <div class="side__card">
        <h2>Reach us</h2>
        <ul class="meta">
          <li><span>Support:</span> support@yourdomain.com</li>
          <li><span>Hours:</span> Mon–Sat, 10:00–19:00</li>
          <li><span>Location:</span> Chandigarh, India</li>
        </ul>
      </div>
    </section>
  </main>
</body>
</html>
