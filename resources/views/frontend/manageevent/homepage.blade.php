<!doctype html>

<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Coming Soon</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

        <style>
            * {
                box-sizing: border-box;
            }

            html,
            body {
                margin: 0;
                padding: 0;
                min-height: 100%;
            }

            body {
                min-height: 100vh;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
                background: #f5f8fc;
                color: #263238;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .coming-page {
                width: 100%;
                max-width: 520px;
                min-height: 100vh;
                padding: 20px 16px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .coming-card {
                width: 100%;
                background: #ffffff;
                border-radius: 24px;
                padding: 42px 25px 30px;
                text-align: center;
                box-shadow: 0 12px 40px rgba(30, 55, 90, 0.08);
                border: 1px solid #edf1f6;
            }

            .icon-wrapper {
                width: 92px;
                height: 92px;
                margin: 0 auto 25px;
                border-radius: 50%;
                background: #eaf3ff;
                color: #2878d4;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 38px;
            }

            .coming-label {
                display: inline-block;
                padding: 7px 15px;
                border-radius: 30px;
                background: #eef5ff;
                color: #2878d4;
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 1px;
                text-transform: uppercase;
                margin-bottom: 15px;
            }

            .coming-title {
                margin: 0;
                font-size: 32px;
                line-height: 1.2;
                font-weight: 700;
                color: #17212b;
            }

            .coming-text {
                margin: 16px auto 0;
                max-width: 390px;
                color: #7b8794;
                font-size: 15px;
                line-height: 1.7;
            }

            .success-message {
                margin-top: 25px;
                padding: 15px;
                border-radius: 14px;
                background: #f1fbf5;
                border: 1px solid #d9f1e2;
                color: #28734a;
                font-size: 13px;
                line-height: 1.5;
            }

            .success-message i {
                margin-right: 6px;
            }

            .event-icon {
                margin-top: 28px;
                display: flex;
                justify-content: center;
                gap: 10px;
            }

            .event-icon span {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: #2878d4;
                display: block;
                animation: pulse 1.5s infinite ease-in-out;
            }

            .event-icon span:nth-child(2) {
                animation-delay: 0.2s;
            }

            .event-icon span:nth-child(3) {
                animation-delay: 0.4s;
            }

            @keyframes pulse {
                0%,
                100% {
                    opacity: 0.25;
                    transform: scale(0.8);
                }

                50% {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            .footer-text {
                margin: 30px 0 0;
                color: #a0aab4;
                font-size: 11px;
            }

            @media (max-width: 575.98px) {
                body {
                    align-items: stretch;
                }

                .coming-page {
                    min-height: 100vh;
                    padding: 12px;
                }

                .coming-card {
                    border-radius: 20px;
                    padding: 38px 20px 25px;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                }

                .icon-wrapper {
                    width: 82px;
                    height: 82px;
                    font-size: 33px;
                    margin-bottom: 22px;
                }

                .coming-title {
                    font-size: 28px;
                }

                .coming-text {
                    font-size: 14px;
                    line-height: 1.65;
                }

                .success-message {
                    font-size: 12px;
                }
            }

            .footer-text {
                text-align: center;
                color: #6c757d;
                font-size: 14px;
                line-height: 1.6;
                margin-bottom: 15px;
            }

            .footer-contact {
                text-align: center;
                padding: 14px 18px;
                margin: 0 auto;
                max-width: 320px;
                background: #f8f9fa;
                border-radius: 12px;
                border: 1px solid #e9ecef;
            }

            .contact-title {
                font-size: 14px;
                font-weight: 600;
                color: #343a40;
                margin-bottom: 10px;
            }

            .contact-title i {
                margin-right: 5px;
            }

            .contact-item {
                margin: 7px 0;
                font-size: 14px;
            }

            .contact-item i {
                width: 20px;
                margin-right: 5px;
            }

            .contact-item a {
                color: #007bff;
                text-decoration: none;
            }

            .contact-item a:hover {
                text-decoration: underline;
            }
        </style>
    </head>

    <body>
        <div class="coming-page">
            <div class="coming-card">
                <div class="icon-wrapper">
                    <i class="fas fa-calendar-alt"></i>
                </div>

                <span class="coming-label"> Stay Tuned </span>

                <h1 class="coming-title">Coming Soon!</h1>

                <p class="coming-text">
                    Thank you for registering with us. We are preparing something exciting for you. Please stay tuned
                    for more updates.
                </p>

                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    Your registration has been completed successfully.
                </div>

                <div class="event-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <p class="footer-text">We look forward to seeing you at the event. 💙</p>

                <div class="footer-contact">
                    <div class="contact-title">
                        <i class="fas fa-headset"></i>
                        Need Assistance?
                    </div>

                    <div class="contact-item">
                        <i class="fas fa-phone-alt"></i>
                        <a href="tel:9999999990">9999999990</a>
                    </div>

                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:info@dev.infotasks.com">info@dev.infotasks.com</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
