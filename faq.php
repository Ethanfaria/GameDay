<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frequently Asked Questions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS\main.css">
    <link rel="stylesheet" href="CSS\faq.css">
</head>
<body>
<?php include 'header.php'; ?>

    <section>
        <h2 class="title">FAQ</h2>

        <div class="faq">
            <div class="question">
                <h3>What is GameDay?</h3>

                <svg width="15" height="10" viewbox="0 0 42 25" >
                    <path d="M3 3L21 21L39 3" stroke="white" stroke-width="7" stroke-linecap="round"></path>
                </svg>
            </div>
            <div class="answer">
                <p>
                    GameDay is a football and futsal ground booking platform that allows players to easily book slots, find nearby grounds, join tournaments, hire referees/coaches, and stay updated with football-related events.
                </p>
            </div>
        </div>
        <div class="faq">
            <div class="question">
                <h3>How do I book a ground on GameDay?</h3>

                <svg width="15" height="10" viewbox="0 0 42 25" >
                    <path d="M3 3L21 21L39 3" stroke="white" stroke-width="7" stroke-linecap="round"></path>
                </svg>
            </div>
            <div class="answer">
                <p>
                    Simply search for available grounds, select your preferred time slot, and confirm your booking through our secure payment system.
                </p>
            </div>
        </div>
        <div class="faq">
            <div class="question">
                <h3>Do I need to pay in advance?</h3>

                <svg width="15" height="10" viewbox="0 0 42 25" >
                    <path d="M3 3L21 21L39 3" stroke="white" stroke-width="7" stroke-linecap="round"></path>
                </svg>
            </div>
            <div class="answer">
                <p>
                    Yes, all bookings require advance payment. This guarantees your slot and prevents last-minute no-shows, ensuring that ground owners don’t face losses due to unclaimed slots.
                </p>
            </div>
        </div>
        <div class="faq">
            <div class="question">
                <h3>Can I list my futsal/football ground on GameDay?</h3>

                <svg width="15" height="10" viewbox="0 0 42 25" >
                    <path d="M3 3L21 21L39 3" stroke="white" stroke-width="7" stroke-linecap="round"></path>
                </svg>
            </div>
            <div class="answer">
                <p>
                    Absolutely! Ground owners can partner with us to increase visibility and manage bookings efficiently. Contact us for details.
                </p>
            </div>
        </div>
        <div class="faq">
            <div class="question">
                <h3>How does the tournament booking feature work?</h3>

                <svg width="15" height="10" viewbox="0 0 42 25" >
                    <path d="M3 3L21 21L39 3" stroke="white" stroke-width="7" stroke-linecap="round"></path>
                </svg>
            </div>
            <div class="answer">
                <p>
                    Organizers can list their tournaments, manage registrations, and get access to referees, venues, and event promotions through GameDay.
                </p>
            </div>
        </div>
        <div class="faq">
            <div class="question">
                <h3> Is there a way to leave reviews for a ground?</h3>

                <svg width="15" height="10" viewbox="0 0 42 25" >
                    <path d="M3 3L21 21L39 3" stroke="white" stroke-width="7" stroke-linecap="round"></path>
                </svg>
            </div>
            <div class="answer">
                <p>
                    Yes! Players can leave reviews and ratings based on their experience to help others choose the best grounds.  
                </p>
            </div>
        </div>
        <div class="faq">
            <div class="question">
                <h3>How do I contact GameDay for support?</h3>

                <svg width="15" height="10" viewbox="0 0 42 25" >
                    <path d="M3 3L21 21L39 3" stroke="white" stroke-width="7" stroke-linecap="round"></path>
                </svg>
            </div>
            <div class="answer">
                <p>
                    You can reach out via our support page, email, or helpline for any assistance.
                </p>
            </div>
        </div>
    </section>
    <script>
        const faqs = document.querySelectorAll('.faq');

        faqs.forEach(faq => {
            faq.addEventListener('click', () => {
                faq.classList.toggle('active');
            });
        });
    </script>
</body>
</html>