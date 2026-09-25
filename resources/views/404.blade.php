
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>404 - Page Not Found</title>

    <link rel="preload"
          href="{{ asset('css/adminlte.css') }}"
          as="style">

    <link rel="stylesheet"
          href="{{ asset('css/adminlte.css') }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Inter", -apple-system, BlinkMacSystemFont,
                "Segoe UI", Roboto, Arial, sans-serif;
            background:
                radial-gradient(circle at 10% 20%, rgba(13, 110, 253, 0.10), transparent 30%),
                radial-gradient(circle at 90% 80%, rgba(111, 66, 193, 0.10), transparent 30%),
                #f5f7fb;
            overflow-x: hidden;
        }

        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
            position: relative;
            overflow: hidden;
        }

        /* Background circles */
        .circle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(1px);
        }

        .circle-one {
            width: 220px;
            height: 220px;
            background: rgba(13, 110, 253, 0.08);
            top: -70px;
            left: -70px;
        }

        .circle-two {
            width: 300px;
            height: 300px;
            background: rgba(111, 66, 193, 0.07);
            right: -100px;
            bottom: -100px;
        }

        .circle-three {
            width: 80px;
            height: 80px;
            border: 15px solid rgba(13, 110, 253, 0.06);
            left: 12%;
            bottom: 15%;
        }

        .error-card {
            width: 100%;
            max-width: 760px;
            background: rgba(255, 255, 255, 0.92);
            border-radius: 24px;
            padding: 55px 45px;
            text-align: center;
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.08),
                0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.8);
            position: relative;
            z-index: 2;
            backdrop-filter: blur(10px);
        }

        .error-icon {
            width: 90px;
            height: 90px;
            margin: 0 auto 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef4ff;
            color: #0d6efd;
            font-size: 42px;
            font-weight: 700;
            box-shadow: 0 10px 25px rgba(13, 110, 253, 0.12);
        }

        .error-code {
            font-size: clamp(90px, 16vw, 150px);
            line-height: 0.9;
            font-weight: 800;
            letter-spacing: -7px;
            margin: 0;
            background: linear-gradient(
                135deg,
                #0d6efd,
                #6610f2
            );
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .error-title {
            font-size: clamp(24px, 4vw, 34px);
            font-weight: 700;
            color: #212529;
            margin: 22px 0 12px;
        }

        .error-text {
            max-width: 540px;
            margin: 0 auto;
            color: #6c757d;
            font-size: 16px;
            line-height: 1.7;
        }

        .error-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .error-btn {
            min-width: 145px;
            padding: 12px 22px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-home {
            background: #0d6efd;
            color: #fff;
            border: 1px solid #0d6efd;
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.20);
        }

        .btn-home:hover {
            background: #0b5ed7;
            border-color: #0b5ed7;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(13, 110, 253, 0.28);
        }

        .btn-back {
            background: #fff;
            color: #495057;
            border: 1px solid #dee2e6;
        }

        .btn-back:hover {
            background: #f8f9fa;
            color: #212529;
            transform: translateY(-2px);
            border-color: #ced4da;
        }

        .help-text {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #edf0f3;
            color: #adb5bd;
            font-size: 13px;
        }

        .help-text span {
            color: #6c757d;
            font-weight: 500;
        }

        /* Small floating dots */
        .dot {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #0d6efd;
            border-radius: 50%;
            opacity: 0.15;
        }

        .dot-one {
            top: 18%;
            right: 15%;
        }

        .dot-two {
            bottom: 22%;
            right: 25%;
            width: 12px;
            height: 12px;
        }

        .dot-three {
            top: 30%;
            left: 18%;
            width: 6px;
            height: 6px;
        }

        @media (max-width: 576px) {

            .error-card {
                padding: 40px 22px;
                border-radius: 18px;
            }

            .error-icon {
                width: 72px;
                height: 72px;
                font-size: 34px;
            }

            .error-code {
                letter-spacing: -4px;
            }

            .error-title {
                margin-top: 18px;
            }

            .error-text {
                font-size: 14px;
            }

            .error-btn {
                width: 100%;
            }

            .error-actions {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <main class="error-page">

        <!-- Background decoration -->
        <div class="circle circle-one"></div>
        <div class="circle circle-two"></div>
        <div class="circle circle-three"></div>

        <div class="dot dot-one"></div>
        <div class="dot dot-two"></div>
        <div class="dot dot-three"></div>

        <!-- Error Card -->
        <div class="error-card">

            <div class="error-icon">
                !
            </div>

            <h1 class="error-code">
                404
            </h1>

            <h2 class="error-title">
                Oops! Page Not Found
            </h2>

            <p class="error-text">
                The page you are looking for may have been moved,
                deleted, or the URL may be incorrect.
                Please check the address and try again.
            </p>

          

            <div class="help-text">
                If you believe this is an error,
                <span>please contact your administrator.</span>
            </div>

        </div>

    </main>

    <script src="{{ asset('js/adminlte.js') }}"></script>

</body>
</html>

