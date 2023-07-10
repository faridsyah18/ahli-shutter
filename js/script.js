// Mengambil elemen home dan galeri
const halamanHome = document.querySelector('.home');
const halamanGaleri = document.querySelector('.galeri');
const lihatSelengkapnya = document.querySelector('.check-it-more p');

// Mengambil elemen navigasi halaman galeri
const navigasiGaleri = document.querySelectorAll('.nav-item .nav-link');

// Mengambil semua elemen nav link
const linkNavPertama = document.querySelectorAll('.navbar-nav .nav-link');

// Mengambil data JSON 
function menampilkanSemuaBarang() {
    $.getJSON('../data/barang.json', function (data) {
        let barang = data.barang;
        $.each(barang, function (i, data) {
            $('#daftar-barang').append('<div class="col-md-3"><div class="card mb-3"><img src="../img/stock/' + data.gambar +'" alt="" class="card-img-top"><div class="card-body"><h5 class="card-title">' + data.nama + '</h5><p class="card-text">' + data.deskripsi +'</p><h5 class="card-title">Rp. ' + data.harga + '</h5><a href="https://shorturl.at/nrJ07" class="btn btn-primary mt-2">Pesan</a></div></div></div>');
        });
    });
};

menampilkanSemuaBarang();

$('.nav-item .nav-link').on('click', function () {
    let kategori = $(this).html();
    console.log(kategori);

    if (kategori == 'All') {
        menampilkanSemuaBarang();
        return;
    }

    $.getJSON('../data/barang.json', function (data) {
        let barang = data.barang;
        console.log(barang);
        let isiBarang = '';

    $.each(barang, function (i, item) {
        if (item.kategori == kategori) {
            isiBarang += '<div class="col-md-3"><div class="card mb-3"><img src="../img/stock/' + item.gambar +'" alt="" class="card-img-top"><div class="card-body"><h5 class="card-title">' + item.nama + '</h5><p class="card-text">' + item.deskripsi +'</p><h5 class="card-title">Rp. ' + item.harga + '</h5><a href="https://shorturl.at/nrJ07" class="btn btn-primary mt-2">Pesan</a></div></div></div>';
        }
    });

        $('#daftar-barang').html(isiBarang);
    });
});

// Menambahkan event listener pada setiap nav link
linkNavPertama.forEach(navLinkPertama => {
    navLinkPertama.addEventListener('click', function() {
        // Menghapus kelas "active" dari semua nav link
        linkNavPertama.forEach(link => link.classList.remove('active'));

        // Menghapus kelas "tidak-aktif" dari halaman yang sedang aktif
        halamanHome.classList.remove('tidak-aktif');
        halamanGaleri.classList.remove('tidak-aktif');

        // Menambahkan kelas "active" pada nav link yang diklik
        this.classList.add('active');

        // Menambahkan kelas "tidak-aktif" pada halaman yang sesuai
        if (this.textContent === 'Home') {
            halamanGaleri.classList.add('tidak-aktif');
        } else if (this.textContent === 'Gallery' || this.textContent === 'Lihat Selengkapnya' ) {
            halamanHome.classList.add('tidak-aktif');
        }
    });
});

navigasiGaleri.forEach(navGallery => {
    navGallery.addEventListener('click', function () {
        navigasiGaleri.forEach(link => link.classList.remove('active'));

        this.classList.add('active');
    }); 
});

// Menambahkan event listener pada "Lihat Selengkapnya"
lihatSelengkapnya.addEventListener('click', function() {
    // Menghapus kelas "active" dari semua nav link
    linkNavPertama.forEach(link => link.classList.remove('active'));

    // Menambahkan kelas "active" pada link "Gallery"
    linkNavPertama.forEach(link => {
        if (link.textContent === 'Gallery') {
            link.classList.add('active');
        }
    });

    // Menghapus kelas "tidak-aktif" dari halaman yang sedang aktif
    halamanHome.classList.remove('tidak-aktif');
    halamanGaleri.classList.remove('tidak-aktif');

    // Menambahkan kelas "tidak-aktif" pada halaman Home
    halamanHome.classList.add('tidak-aktif');
});