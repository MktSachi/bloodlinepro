<div class="cont container">
        <div class="text-section">
            <h1>Every healthcare provider is embracing technology.<br> <span class="highlight">We’re here to help them all.</span></h1>
            <p>Over 500 hospitals and blood banks, spanning the globe, rely on BloodBankPro to streamline blood donations and manage vital supplies.</p>
            <p>That includes renowned medical centers and community blood centers, from established institutions to innovative startups revolutionizing healthcare delivery.</p>
        </div>
        <div class="testimonial-section">
            <div class="testimonial">
               <!-- <img src="../Image/reg.png" alt="Doordash Logo"> -->
                <p>We significantly reduced our donor registartion time, a critical improvement for our operations.</p>
            </div>
            <div class="testimonial">
              <!--  <img src="Image/testimonial2.jpg" alt="Testimonial 2">-->
                <p>BloodBankPro facilitated quick identification of qualified donors, ensuring swift and efficient matching for blood needs.</p>
            </div>
            <div class="testimonial">
             <!--   <img src="Image/testimonial3.jpg" alt="Testimonial 3"> -->
                <p>Thanks to BloodBankPro, we successfully streamlined blood request process, ensuring quicker and more efficient blood supply management.</p>
            </div>
            <div class="dots">
                <span class="dot" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
                <span class="dot" onclick="currentSlide(3)"></span>
            </div>
        </div>
    </div>
    <script>
        let slideIndex = 1;
        showSlides(slideIndex);

        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("testimonial");
            let dots = document.getElementsByClassName("dot");

            if (n > slides.length) { slideIndex = 1 }
            if (n < 1) { slideIndex = slides.length }

            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }

            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }

            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        // Automatically change slides every 5 seconds
        setInterval(() => {
            slideIndex++;
            showSlides(slideIndex);
        }, 5000);
    </script>