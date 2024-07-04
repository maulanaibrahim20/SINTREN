<section id="contact" class="contact">
    <div class="container">

        <div class="section-title">
            <h2>Kontak Kami</h2>
            <p>Anda Dapat Menghubungi kami dari melalui kontak dibawah ini dan dapat menemui kami sesuai peta yang
                tersedia </p>
        </div>
        <iframe style="border:0; width: 100%; height: 350px;"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.8881322506227!2d108.27887677580723!3d-6.408409362676324!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6eb87d1fcaf97d%3A0x4fc15b3c8407ada4!2sPoliteknik%20Negeri%20Indramayu!5e0!3m2!1sid!2sid!4v1719040978145!5m2!1sid!2sid"
            frameborder="0" allowfullscreen></iframe>
    </div>

    <div class="container">

        <div class="row mt-5">

            <div class="col-lg-4">
                <div class="info">
                    <div class="address">
                        <i class="ri-map-pin-line"></i>
                        <h4>Lokasi:</h4>
                        <p>Politeknik Negeri Indramayu</p>
                    </div>

                    <div class="email">
                        <i class="ri-mail-line"></i>
                        <h4>Email:</h4>
                        <p>contact@sintrenayu.com</p>
                    </div>

                    <div class="phone">
                        <i class="ri-phone-line"></i>
                        <h4>Call:</h4>
                        <p>+6285942842400</p>
                    </div>

                </div>

            </div>

            <div class="col-lg-8 mt-5 mt-lg-0">
                <form action="{{ url('/kotakSaran') }}" method="POST" role="form" class="php-email-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <input type="text" name="nama" class="form-control" id="name"
                                placeholder="Nama Anda" required>
                        </div>
                        <div class="col-md-6 form-group mt-3 mt-md-0">
                            <input type="email" class="form-control" name="email" id="email"
                                placeholder="Email Anda" required>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <input type="text" class="form-control" name="subjek" id="subject" placeholder="Subjek"
                            required>
                    </div>
                    <div class="form-group mt-3">
                        <textarea class="form-control" name="pesan" rows="5" placeholder="Pesan" required></textarea>
                    </div>
                    <div class="my-3">
                        <div class="loading">Loading</div>
                        <div class="error-message"></div>
                        <div class="sent-message">Your message has been sent. Thank you!</div>
                    </div>
                    <div class="text-center"><button type="submit">Kirim Pesan</button></div>
                </form>
            </div>
        </div>
    </div>
</section>
