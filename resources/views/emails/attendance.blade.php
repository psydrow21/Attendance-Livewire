


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tailwind CSS Simple Email Template Example </title>
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">

<style>
    .text-gold {
  background-image: repeating-linear-gradient(
    to right,
    #a2682a 0%,
    #be8c3c 8%,
    #be8c3c 18%,
    #d3b15f 27%,
    #faf0a0 35%,
    #ffffc2 40%,
    #faf0a0 50%,
    #d3b15f 58%,
    #be8c3c 67%,
    #b17b32 77%,
    #bb8332 83%,
    #d4a245 88%,
    #e1b453 93%,
    #a4692a 100%
  );
  background-size: 150%;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-family: "Yantramanav";
  filter: drop-shadow(0 0 1px rgba(255, 200, 0, 0.3));
  animation: MoveBackgroundPosition 6s ease-in-out infinite;
}

.bg-dark {
  background-color: rgb(29, 28, 28);
}

.bg-card {
  background-color: rgb(34, 32, 32);
}
.bg-gold {
  background-image: repeating-linear-gradient(
    to right,
    #a2682a 0%,
    #be8c3c 8%,
    #be8c3c 18%,
    #d3b15f 27%,
    #faf0a0 35%,
    #ffffc2 40%,
    #faf0a0 50%,
    #d3b15f 58%,
    #be8c3c 67%,
    #b17b32 77%,
    #bb8332 83%,
    #d4a245 88%,
    #e1b453 93%,
    #a4692a 100%
  );
  background-size: 150%;
}

</style>
</head>



<body>
  <div class="flex items-center justify-center min-h-screen p-5 bg-dark min-w-screen">
    <div class="max-w-xl p-8 text-center text-gray-800 bg-card shadow-xl  shadow-slate-300 lg:max-w-3xl rounded-3xl lg:p-12">
      <h3 class="text-2xl text-gold">Thanks for signing up for VetoGaming!</h3>
      <div class="flex justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24" fill="url(#grad)" viewBox="0 0 24 24" stroke="currentColor">
          <defs>
            <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="0%">
              <stop offset="0%" style="stop-color: #a2682a" />
              <stop offset="8%" style="stop-color: #be8c3c" />
              <stop offset="18%" style="stop-color: #be8c3c" />
              <stop offset="27%" style="stop-color: #d3b15f" />
              <stop offset="35%" style="stop-color: #faf0a0" />
              <stop offset="40%" style="stop-color: #ffffc2" />
              <stop offset="50%" style="stop-color: #faf0a0" />
              <stop offset="58%" style="stop-color: #d3b15f" />
              <stop offset="67%" style="stop-color: #be8c3c" />
              <stop offset="77%" style="stop-color: #b17b32" />
              <stop offset="83%" style="stop-color: #bb8332" />
              <stop offset="88%" style="stop-color: #d4a245" />
              <stop offset="93%" style="stop-color: #e1b453" />
              <stop offset="100%" style="stop-color: #a4692a" />
            </linearGradient>
          </defs>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76" />
        </svg>

      </div>

      <p class="text-gold">We're happy you're here. Let's get your email address verified:</p>
      <div class="mt-4">
        <button class="px-2 py-2 text-black bg-gold rounded">Click to Verify Email</button>
        <p class="mt-4 text-sm text-gold">If you’re having trouble clicking the "Verify Email Address" button, copy
          and
          paste
          the URL below
          into your web browser:


          <a href="#" class="text-gold underline">http://localhost:8000/email/verify/3/1ab7a09a3</a>
        </p>
      </div>
    </div>
  </div>


    <div>
        <!-- Order your soul. Reduce your wants. - Augustine -->

        <p>Hello {{ $adminmail['name'] ?? 'admin' }}</p>
        <p>Your attendance email is working.</p>
    </div>
</body>

</html>
