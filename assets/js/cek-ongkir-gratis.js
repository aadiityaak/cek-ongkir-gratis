// Check if data is already in local storage
if (
  !localStorage.getItem("data_city") ||
  !localStorage.getItem("data_country") ||
  !localStorage.getItem("data_state")
) {
  // Fetch the JSON data from files
  Promise.all([
    fetch(cek_ongkir_gratis_data.data_city_url).then((response) =>
      response.json()
    ),
    fetch(cek_ongkir_gratis_data.data_country_url).then((response) =>
      response.json()
    ),
    fetch(cek_ongkir_gratis_data.data_state_url).then((response) =>
      response.json()
    ),
  ])
    .then(([dataCity, dataCountry, dataState]) => {
      // Save the data to local storage
      localStorage.setItem("data_city", JSON.stringify(dataCity));
      localStorage.setItem("data_country", JSON.stringify(dataCountry));
      localStorage.setItem("data_state", JSON.stringify(dataState));

      // Inisialisasi elemen input dengan autocomplete setelah data tersedia
      initializeAutocomplete(dataCity);
    })
    .catch((error) => {
      console.error("Failed to fetch and save data:", error);
    });
} else {
  // Data is available in local storage, initialize autocomplete directly
  const dataCity = JSON.parse(localStorage.getItem("data_city"));
  initializeAutocomplete(dataCity);
}
function initializeAutocomplete(dataCity) {
  // Inisialisasi elemen input dengan autocomplete
  jQuery(document).ready(function ($) {
    initializeAutocomplete(dataCity);
    // Inisialisasi autocomplete untuk input origin
    $("#origin").autocomplete({
      source: dataCity,
      select: function (event, ui) {
        const originId = ui.item.id; // Ganti 'id' dengan properti yang sesuai di dataCity
        // Simpan ID origin ke cookie
        setOriginDestinationIds(
          originId,
          getOriginDestinationIds().destinationId
        );
      },
    });

    // Inisialisasi autocomplete untuk input destination
    $("#destination").autocomplete({
      source: dataCity,
      select: function (event, ui) {
        const destinationId = ui.item.id; // Ganti 'id' dengan properti yang sesuai di dataCity
        // Simpan ID destination ke cookie
        setOriginDestinationIds(
          getOriginDestinationIds().originId,
          destinationId
        );
      },
    });
  });
}

// Ambil data dari local storage dan simpan dalam variabel
var dataState = JSON.parse(localStorage.getItem("data_state"));
var dataCity = JSON.parse(localStorage.getItem("data_city"));

// Fungsi untuk menyimpan ID origin dan destination di cookie
function setOriginDestinationIds(originId, destinationId) {
  document.cookie = `originId=${originId}; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/`;
  document.cookie = `destinationId=${destinationId}; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/`;
}

// Fungsi untuk mendapatkan ID origin dan destination dari cookie
function getOriginDestinationIds() {
  const cookies = document.cookie.split("; ");
  let originId = null;
  let destinationId = null;

  cookies.forEach((cookie) => {
    const [name, value] = cookie.split("=");
    if (name === "originId") {
      originId = value;
    } else if (name === "destinationId") {
      destinationId = value;
    }
  });

  return { originId, destinationId };
}

// Inisialisasi elemen input dengan autocomplete
jQuery(document).ready(function ($) {
  // Inisialisasi autocomplete untuk input origin
  initializeAutocomplete(dataCity);

  // Event handler saat tombol "Cek Ongkir" diklik
  $("#cek-ongkir-button").on("click", function (event) {
    event.preventDefault(); // Mencegah aksi default dari tombol submit
    $(".loading-animation").show();

    // Mendapatkan ID origin dan destination dari cookie
    const originId = getOriginDestinationIds().originId;
    const destinationId = getOriginDestinationIds().destinationId;

    // Cek apakah ID origin dan destination telah disimpan di cookie
    if (originId && destinationId) {
      // Data yang akan dikirimkan dalam permintaan AJAX
      const data = {
        action: "cek_ongkir", // Nama action yang akan diproses oleh fungsi PHP
        origin: originId,
        destination: destinationId,
        weight: $("#weight").val(), // Ambil nilai weight dari elemen input
      };

      // Kirim permintaan AJAX menggunakan jQuery.post()
      jQuery
        .post(cek_ongkir_gratis_data.ajaxurl, data, function (response) {
          // Callback saat permintaan berhasil
          // Lakukan sesuatu dengan data yang diterima dari server
          $(".loading-animation").hide();
          $("#return-ongkir").html(response);
        })
        .fail(function (xhr, status, error) {
          // Callback saat permintaan gagal
          console.error(error);
        });
    } else {
      // Jika ID origin atau destination tidak ditemukan di cookie, beri tahu pengguna atau lakukan tindakan lain
      console.error("ID origin atau destination tidak ditemukan di cookie.");
    }
  });
});
