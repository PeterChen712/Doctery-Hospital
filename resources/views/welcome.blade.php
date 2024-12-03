<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Manajemen Rumah Sakit</title>

    <script src="https://kit.fontawesome.com/c1df782baf.js"></script>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-thin-rounded/css/uicons-thin-rounded.css'>
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/2.1.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel="stylesheet" href="assets/css/home.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html {
            scroll-behavior: smooth;
        }

        .navbar a {
            transition: color 0.3s;
        }

        .navbar a:hover {
            color: #4B5563;
        }

        section {
            padding: 80px 0;
            scroll-margin-top: 80px;
            /* Accounts for fixed navbar */
        }
    </style>
</head>

<body>

    <header>

        <div class="logo"><img src="assets/images/home/logo3.png" alt=""></div>

        <nav class="navbar">
            <a href="#home">Beranda</a>
            <a href="#about">Tentang</a>
            <a href="#doctor">Dokter</a>
            <a href="#services">Layanan</a>
            <a href="#review">Testimoni</a>
        </nav>

        <div class="right-icons">
            <div id="menu-bars" class="fas fa-bars"></div>

            @auth
                <a href="{{ route('dashboard') }}" class="btn">Dashboard</a>
            @else
                <div class="auth-buttons">
                    <a href="{{ route('login') }}" class="btn login-btn">Masuk</a>
                    <a href="{{ route('register') }}" class="btn register-btn">Daftar</a>
                </div>
            @endauth
        </div>

    </header>

    <!-- header section ended -->

    <!-- Home section started -->
    <section id="home" class="min-h-screen">
        <!-- Home content -->
        <div class="main-home">

            <div class="home">
                <div class="home-left-content">
                    <span>welcome to hospital management</span>
                    <h2>Kami Merawat<br>Kesehatan Pasien</h2>
                    @include('components.main-text')
                    <p class="lorem">Kami berkomitmen untuk memberikan pelayanan kesehatan terbaik untuk Anda dan keluarga.</p>

                    <div class="home-btn">
                        <a href="#about">Baca Selengkapnya</a>
                        <a class="homebtnsec" href="{{ route('dashboard') }}">Masuk</a>
                    </div>

                </div>

                <div class="home-right-content">
                    <img src="assets/images/home/hero2.png" alt="">
                </div>
            </div>
        </div>


        <div class="technology">
            <div class="main-technology">

                <div class="inner-technology">
                    <span></span>
                    <i class="fi fi-tr-hands-heart"></i>
                    <h2>Kualitas &amp; Keselamatan</h2>
                    <p>Rumah sakit kami menggunakan teknologi mutakhir dan mempekerjakan tim ahli.</p>
                </div>

                <div class="inner-technology">
                    <span></span>
                    <i class="fi fi-rr-doctor"></i>
                    <h2>Kualitas &amp; Keselamatan</h2>
                    <p>Rumah sakit kami menggunakan teknologi mutakhir dan mempekerjakan tim ahli.</p>
                </div>

                <div class="inner-technology">
                    <span></span>
                    <i class="fi fi-tr-user-md"></i>
                    <h2>Kualitas &amp; Keselamatan</h2>
                    <p>Rumah sakit kami menggunakan teknologi mutakhir dan mempekerjakan tim ahli.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- home section ends -->

    <!-- About us section started -->

    <section id="about" class="min-h-screen">
        <!-- About content -->
        <div class="main-about">

            <div class="about-heading">Tentang Kami</div>

            <div class="inner-main-about">
                <div class="about-inner-content-left">
                    <img src="assets/images/home/about1.png" alt="">
                </div>

                <div class="about-inner-content">
                    <div class="about-right-content">
                        <h2>Kami Menetapkan Standar dalam Penelitian<br>dan Perawatan Klinis</h2>
                        <p>Kami menyediakan layanan medis terlengkap, sehingga setiap orang memiliki kesempatan untuk
                            menerima bantuan medis berkualitas.</p>
                        <p class="aboutsec-content">
                            Klinik kami telah berkembang menjadi fasilitas kelas dunia untuk perawatan gigi, kosmetik
                            gigi,
                            dan kedokteran restoratif lanjutan. Kami termasuk penyedia implan paling berkualifikasi
                            dengan
                            lebih dari 30 tahun pelatihan dan pengalaman berkualitas.
                        </p>
                        <button class="aboutbtn">Baca Selengkapnya</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About us section ends -->

    <!-- our doctors -->

    <section id="doctor" class="min-h-screen">
        <!-- Services content -->
        <div class="main-doctors">
            <div class="doctors-heading">
                <h2>Dokter Kami</h2>
            </div>

            <div class="main-inner-doctor">
                <div class="doc-poster">
                    <div class="doc-icons">
                        <i class="fa-solid fa-share"></i>
                        <i class="fa-solid fa-eye"></i>
                        <i class="fa-solid fa-heart"></i>
                    </div>
                    <img src="assets/images/home/team1.jpg" alt="">

                    <div class="doc-details">
                        <h2>Mr Joe</h2>

                        <i class="fa-brands fa-linkedin"></i>
                        <i class="fa-brands fa-instagram"></i>
                    </div>

                </div>

                <div class="doc-poster">
                    <div class="doc-icons">
                        <i class="fa-solid fa-share"></i>
                        <i class="fa-solid fa-eye"></i>
                        <i class="fa-solid fa-heart"></i>
                    </div>
                    <img src="assets/images/home/team2.jpg" alt="">
                    <div class="doc-details">
                        <h2>Mr Joe</h2>

                        <i class="fa-brands fa-linkedin"></i>
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                </div>

                <div class="doc-poster">
                    <div class="doc-icons">
                        <i class="fa-solid fa-share"></i>
                        <i class="fa-solid fa-eye"></i>
                        <i class="fa-solid fa-heart"></i>
                    </div>
                    <img src="assets/images/home/team3.jpg" alt="">
                    <div class="doc-details">
                        <h2>Mr Joe</h2>

                        <i class="fa-brands fa-linkedin"></i>
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                </div>

                <div class="doc-poster">
                    <div class="doc-icons">
                        <i class="fa-solid fa-share"></i>
                        <i class="fa-solid fa-eye"></i>
                        <i class="fa-solid fa-heart"></i>
                    </div>
                    <img src="assets/images/home/team4.jpg" alt="">
                    <div class="doc-details">
                        <h2>Mr Joe</h2>

                        <i class="fa-brands fa-linkedin"></i>
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                </div>

                <div class="doc-poster">
                    <div class="doc-icons">
                        <i class="fa-solid fa-share"></i>
                        <i class="fa-solid fa-eye"></i>
                        <i class="fa-solid fa-heart"></i>
                    </div>
                    <img src="assets/images/home/team5.jpg" alt="">
                    <div class="doc-details">
                        <h2>Mr Joe</h2>

                        <i class="fa-brands fa-linkedin"></i>
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                </div>

                <div class="doc-poster">
                    <div class="doc-icons">
                        <i class="fa-solid fa-share"></i>
                        <i class="fa-solid fa-eye"></i>
                        <i class="fa-solid fa-heart"></i>
                    </div>
                    <img src="assets/images/home/team6.jpg" alt="">

                    <div class="doc-details">
                        <h2>Mr Joe</h2>

                        <i class="fa-brands fa-linkedin"></i>
                        <i class="fa-brands fa-instagram"></i>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- our doctors ended -->

    <!-- our services -->

    <section id="services" class="min-h-screen">
        <!-- Services content -->

        <div class="our-service">
            <div class="service-heading">
                <h2>Layanan Kami</h2>
            </div>

            <div class="main-services">
                <div class="inner-services">
                    <div class="service-icon">
                        <i class="fa-solid fa-truck-medical"></i>
                    </div>
                    <h3>Pemeriksaan Kesehatan</h3>
                    <p>Kami menawarkan prosedur medis luas untuk pasien rawat jalan dan rawat inap, dan kami sangat
                        bangga
                        dengan pencapaian staf kami.</p>
                </div>

                <div class="inner-services">
                    <div class="service-icon">
                        <i class="fa-regular fa-hospital"></i>
                    </div>
                    <h3>Pemeriksaan Kesehatan</h3>
                    <p>Kami menawarkan prosedur medis luas untuk pasien rawat jalan dan rawat inap, dan kami sangat
                        bangga
                        dengan pencapaian staf kami.</p>
                </div>

                <div class="inner-services">
                    <div class="service-icon">
                        <i class="fa-regular fa-heart"></i>
                    </div>
                    <h3>Pemeriksaan Kesehatan</h3>
                    <p>Kami menawarkan prosedur medis luas untuk pasien rawat jalan dan rawat inap, dan kami sangat
                        bangga
                        dengan pencapaian staf kami.</p>
                </div>

                <div class="inner-services">
                    <div class="service-icon">
                        <i class="fa-solid fa-notes-medical"></i>
                    </div>
                    <h3>Pemeriksaan Kesehatan</h3>
                    <p>Kami menawarkan prosedur medis luas untuk pasien rawat jalan dan rawat inap, dan kami sangat
                        bangga
                        dengan pencapaian staf kami.</p>
                </div>

                <div class="inner-services">
                    <div class="service-icon">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <h3>Pemeriksaan Kesehatan</h3>
                    <p>Kami menawarkan prosedur medis luas untuk pasien rawat jalan dan rawat inap, dan kami sangat
                        bangga
                        dengan pencapaian staf kami.</p>
                </div>

                <div class="inner-services">
                    <div class="service-icon">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <h3>Pemeriksaan Kesehatan</h3>
                    <p>Kami menawarkan prosedur medis luas untuk pasien rawat jalan dan rawat inap, dan kami sangat
                        bangga
                        dengan pencapaian staf kami.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- our services ended -->

    <!-- customer review -->

    <section id="review" class="min-h-screen">
        <div class="main-review">
            <section>
                <div class="review-heading">
                    <h1>Ulasan Pelanggan Kami</h1>
                </div>

                <div class="main-inner-review">

                    <div class="review-inner-content">

                        <div class="review-box">
                            <img src="assets/images/home/pic1.png" alt="">

                            <h2>Lara John</h2>
                            <div class="review-stars">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                            </div>

                            <div class="review-text">
                                <p>Saya sangat puas dengan layanan yang diberikan, tim medis sangat profesional dan
                                    berpengalaman.</p>
                            </div>

                        </div>

                        <div class="review-box">
                            <img src="assets/images/home/pic2.png" alt="">

                            <h2>Lara John</h2>
                            <div class="review-stars">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                            </div>

                            <div class="review-text">
                                <p>Saya sangat puas dengan layanan yang diberikan, tim medis sangat profesional dan
                                    berpengalaman.</p>
                            </div>

                        </div>

                        <div class="review-box">
                            <img src="assets/images/home/pic3.png" alt="">

                            <h2>Lara John</h2>
                            <div class="review-stars">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                            </div>

                            <div class="review-text">
                                <p>Saya sangat puas dengan layanan yang diberikan, tim medis sangat profesional dan
                                    berpengalaman.</p>
                            </div>

                        </div>

                    </div>

                </div>
            </section>
        </div>
    </section>


    <!-- customer review -->


    <!-- footer -->

    <footer class="bg-[#1a1a1a] text-white py-24">
        <div class="container mx-auto px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Logo Column -->
                <div class="footer-brand space-y-6">
                    <img src="{{ asset('assets/images/home/logo3.png') }}" alt="Hospital Logo"
                        class="w-[150px] h-auto transition-transform hover:scale-105">
                    <p class="text-gray-400 text-[1.4rem] leading-relaxed max-w-[300px]">
                        Menyediakan layanan kesehatan berkualitas dengan kasih sayang dan keunggulan.
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="space-y-6">
                    <h3
                        class="text-[1.8rem] font-semibold relative pb-4 after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-[50px] after:h-[2px] after:bg-[#2ec8a6]">
                        Link Cepat
                    </h3>
                    <ul class="space-y-4">
                        <li>
                            <a href="{{ route('dashboard') }}"
                                class="text-gray-400 text-[1.4rem] hover:text-[#2ec8a6] hover:pl-2 transition-all duration-300">
                                Dasbor
                            </a>
                        </li>
                        <li>
                            <a href="#services"
                                class="text-gray-400 text-[1.4rem] hover:text-[#2ec8a6] hover:pl-2 transition-all duration-300">
                                Layanan Kami
                            </a>
                        </li>
                        <li>
                            <a href="#doctors"
                                class="text-gray-400 text-[1.4rem] hover:text-[#2ec8a6] hover:pl-2 transition-all duration-300">
                                Dokter Kami
                            </a>
                        </li>
                        <li>
                            <a href="#about"
                                class="text-gray-400 text-[1.4rem] hover:text-[#2ec8a6] hover:pl-2 transition-all duration-300">
                                Tentang Kami
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="space-y-6">
                    <h3
                        class="text-[1.8rem] font-semibold relative pb-4 after:content-[''] after:absolute after:left-0 after:bottom-0 after:w-[50px] after:h-[2px] after:bg-[#2ec8a6]">
                        Hubungi Kami
                    </h3>
                    <ul class="space-y-4">
                        <li class="flex items-center space-x-4">
                            <i class="fas fa-map-marker-alt text-[#2ec8a6] text-[1.6rem]"></i>
                            <span class="text-gray-400 text-[1.4rem]">123 Hospital Street, City</span>
                        </li>
                        <li class="flex items-center space-x-4">
                            <i class="fas fa-phone text-[#2ec8a6] text-[1.6rem]"></i>
                            <span class="text-gray-400 text-[1.4rem]">+1 234 567 890</span>
                        </li>
                        <li class="flex items-center space-x-4">
                            <i class="fas fa-envelope text-[#2ec8a6] text-[1.6rem]"></i>
                            <span class="text-gray-400 text-[1.4rem]">info@hospital.com</span>
                        </li>
                    </ul>

                    <!-- Social Links -->
                    <div class="flex space-x-4 mt-8">
                        <a href="#"
                            class="w-[35px] h-[35px] flex items-center justify-center bg-white/10 rounded-full hover:bg-[#2ec8a6] hover:-translate-y-1 transition-all duration-300">
                            <i class="fab fa-facebook-f text-white text-[1.4rem]"></i>
                        </a>
                        <a href="#"
                            class="w-[35px] h-[35px] flex items-center justify-center bg-white/10 rounded-full hover:bg-[#2ec8a6] hover:-translate-y-1 transition-all duration-300">
                            <i class="fab fa-twitter text-white text-[1.4rem]"></i>
                        </a>
                        <a href="#"
                            class="w-[35px] h-[35px] flex items-center justify-center bg-white/10 rounded-full hover:bg-[#2ec8a6] hover:-translate-y-1 transition-all duration-300">
                            <i class="fab fa-instagram text-white text-[1.4rem]"></i>
                        </a>
                        <a href="#"
                            class="w-[35px] h-[35px] flex items-center justify-center bg-white/10 rounded-full hover:bg-[#2ec8a6] hover:-translate-y-1 transition-all duration-300">
                            <i class="fab fa-linkedin-in text-white text-[1.4rem]"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-white/10 mt-12 pt-8 text-center text-gray-400 text-[1.3rem]">
                <p>&copy; {{ date('Y') }} Doctery Hospital.</p>
            </div>
        </div>
    </footer>


    <script>
        let menubar = document.querySelector('#menu-bars');
        let navbar = document.querySelector('.navbar');

        menubar.onclick = () => {
            menubar.classList.toggle('fa-times');
            navbar.classList.toggle('active')
        }
    </script>

</body>

</html>
