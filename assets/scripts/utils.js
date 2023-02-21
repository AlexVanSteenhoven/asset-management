import "../styles/loader.css";

$(".alert")
  .fadeTo(2000, 500)
  .slideUp(500, function () {
    $(".alert").slideUp(500);
  });

export function showLoader() {
    $('#loader-wrapper').css("display", 'block').fadeOut('slow', () => {
        $('#loader-wrapper').css("display", 'none');
    });
}

export function hideLoader() {
    $('#loader-wrapper').css("display", 'none');
}

switch (document.readyState) {
    case "loading":
        showLoader(1000);
        break;
    case "complete":
    case "interactive":
        hideLoader();
        break;
}